<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class FileUploadController extends Controller
{
    public function index()
    {
        $files = File::all();
        return view('file-upload', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max file size 10MB
        ]);

        $uploadedFile = $request->file('file');
        $filename = time() . '_' . $uploadedFile->getClientOriginalName();
        $path = $uploadedFile->storeAs('uploads', $filename, 'public');

        File::create([
            'name' => $filename,
            'path' => $path,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(File $file)
    {
        // Delete the file from storage
        Storage::disk('public')->delete($file->path);

        // Delete the file record from the database
        $file->delete();

        return response()->json(['success' => true]);
    }
}

use Illuminate\Support\Facades\Storage;
