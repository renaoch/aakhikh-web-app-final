<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * POST /api/media/upload
     * Uploads a file to S3 and returns the public URL.
     * Protected by VerifySupabaseJwt + CheckRole:admin,editor
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file'   => 'required|file|max:20480|mimes:jpeg,jpg,png,gif,webp,mp4,mov,pdf,mp3',
            'folder' => 'nullable|string|in:sermons,events,announcements,products,leaders,general',
        ]);

        $file   = $request->file('file');
        $folder = $request->input('folder', 'general');
        $ext    = $file->getClientOriginalExtension();
        $name   = Str::uuid() . '.' . $ext;
        $path   = $folder . '/' . $name;

        Storage::disk('s3')->put($path, file_get_contents($file), 'public');

        $url = Storage::disk('s3')->url($path);

        return response()->json([
            'success' => true,
            'url'     => $url,
            'path'    => $path,
            'name'    => $name,
            'size'    => $file->getSize(),
            'mime'    => $file->getMimeType(),
        ], 201);
    }

    /**
     * DELETE /api/media
     * Deletes a file from S3 by path.
     * Protected by VerifySupabaseJwt + CheckRole:admin
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $path = $request->input('path');

        if (! Storage::disk('s3')->exists($path)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found.',
            ], 404);
        }

        Storage::disk('s3')->delete($path);

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully.',
        ]);
    }
}
