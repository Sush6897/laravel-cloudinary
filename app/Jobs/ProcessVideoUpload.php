<?php

namespace App\Jobs;

use App\Mail\VideoUploadedNotification;
use App\Models\Video;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProcessVideoUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    public $tempPath;

    public $tries = 3;
    public $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(Video $video, string $tempPath)
    {
        $this->video = $video;
        $this->tempPath = $tempPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $chunksDir = storage_path('app/' . $this->tempPath);
            $finalPath = storage_path('app/temp_videos/' . $this->video->filename);

            if (!File::exists(dirname($finalPath))) {
                File::makeDirectory(dirname($finalPath), 0755, true);
            }

            // Merge chunks
            $fileNames = File::files($chunksDir);
            sort($fileNames, SORT_NATURAL);

            $out = fopen($finalPath, "wb");
            foreach ($fileNames as $file) {
                $in = fopen($file->getPathname(), "rb");
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
                fclose($in);
            }
            fclose($out);

            // Upload to Cloudinary
            $cloudinaryFile = Cloudinary::uploadApi()->upload($finalPath, [
                'folder' => 'videos',
                'resource_type' => 'video',
                'chunk_size' => 6000000 // 6MB chunks for large file uploads
            ]);

            // Update video record
            $this->video->update([
                'status' => 'completed',
                'cloudinary_public_id' => $cloudinaryFile['public_id'],
                'cloudinary_url' => $cloudinaryFile['secure_url'],
                'size' => File::size($finalPath),
            ]);

            // Cleanup
            File::deleteDirectory($chunksDir);
            File::delete($finalPath);

            // Notify Admin
            Mail::to(config('mail.from.address'))->send(new VideoUploadedNotification($this->video));

        } catch (\Exception $e) {
            $this->video->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
