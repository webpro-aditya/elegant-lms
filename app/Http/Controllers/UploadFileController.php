<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadFileController extends Controller
{

    public function upload_image(Request $request)
    {

        $request->validate([
            'files.*' => [
                'required',
                'image',
                'mimes:jpeg,jpg,bmp,png,svg,gif'
            ],
        ], [], [
            'files.*' => 'File'
        ]);
        $path =config('app.has_public_folder') ? 'public/uploads/editor-image' : 'uploads/editor-image';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $files = $request->files;
        $image_url = [];
        foreach ($files as $file) {
            foreach ($file as $k => $f) {
                $fileName = $f->getClientOriginalName() . time() . "." . $f->getClientOriginalExtension();
                $f->move($path.'/', $fileName);
                $image_url[$k] = asset($path . '/' . $fileName);
            }
        }

        return response()->json($image_url);
    }
}
