<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,webp,gif,pdf',
        ]);

        $path = $request->file('file')->store('uploads', 's3');
        $url  = Storage::disk('s3')->url($path);

        return response()->json(['url' => $url], 201);
    }
}