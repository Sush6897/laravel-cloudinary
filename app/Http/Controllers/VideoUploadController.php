<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessVideoUpload;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoUploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        $chunkNumber = $request->input('resumableChunkNumber');
        $identifier = $request->input('resumableIdentifier');
        $fileName = $request->input('resumableFilename');
        $totalChunks = $request->input('resumableTotalChunks');

        $tempPath = 'chunks/' . $identifier;
        $chunkName = $chunkNumber . '.part';

        // Handle Resumable.js "test" request (GET)
        if ($request->isMethod('GET')) {
            if (Storage::disk('local')->exists($tempPath . '/' . $chunkName)) {
                return response()->json(['message' => 'Chunk exists'], 200);
            }
            return response()->json(['message' => 'Chunk not found'], 204);
        }

        // Handle Upload request (POST)
        $file = $request->file('file');
        $file->storeAs($tempPath, $chunkName, 'local');

        // Check if all chunks are uploaded
        $chunksUploaded = count(Storage::disk('local')->files($tempPath));

        if ($chunksUploaded == $totalChunks) {
            $video = Video::create([
                'original_filename' => $fileName,
                'status' => 'processing',
                'filename' => $identifier . '.' . pathinfo($fileName, PATHINFO_EXTENSION),
            ]);

            ProcessVideoUpload::dispatch($video, $tempPath);

            return response()->json(['message' => 'Upload complete, processing in background', 'video_id' => $video->id]);
        }

        return response()->json(['message' => 'Chunk uploaded successfully']);
    }
}
