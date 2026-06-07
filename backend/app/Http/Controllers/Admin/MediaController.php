<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file'   => 'required|file|max:10240|mimes:jpg,jpeg,png,webp,gif,pdf,mp3,mp4',
            'folder' => 'nullable|string|max:50|alpha_dash',
        ]);

        $file   = $request->file('file');
        $folder = $request->input('folder', 'uploads');
        $ext    = $file->getClientOriginalExtension();
        $name   = Str::uuid() . '.' . $ext;
        $path   = "{$folder}/{$name}";

        Storage::disk('s3')->put($path, file_get_contents($file), 'public');

        $url = Storage::disk('s3')->url($path);

        return response()->json([
            'success' => true,
            'data'    => [
                'url'  => $url,
                'path' => $path,
                'name' => $name,
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ],
        ], 201);
    }
}
