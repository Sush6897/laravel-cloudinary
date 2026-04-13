<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test-send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test video upload notification email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recipient = config('mail.from.address');
        $this->info("Sending test mail to: {$recipient}");

        try {
            // Create a dummy video object for the notification
            $video = new \App\Models\Video();
            $video->original_filename = 'test_video.mp4';
            $video->size = 15728640; // 15 MB
            $video->cloudinary_url = 'https://cloudinary.com/test-video';

            \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\VideoUploadedNotification($video));

            $this->info('Test email sent successfully!');
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
        }
    }
}
