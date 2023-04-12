<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class MediaController extends Controller
{


    public function show($directory, $filename)
    {
        $directories = ['images', 'audios', 'videos'];

        if (!in_array($directory, $directories)) {
            abort(404);
        }

        $path = Storage::disk('public')->path($directory . '/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($directory . '/' . $filename);
        $type = Storage::disk('public')->mimeType($directory . '/' . $filename);

        return response($file)->header('Content-Type', $type);
    }



}
