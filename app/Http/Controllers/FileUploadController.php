<?php

namespace App\Http\Controllers;

use App\Models\File;

class FileUploadController extends Controller
{
    public function index()
    {
        $files = File::all();
        return view('file-upload', compact('files'));
    }
}
