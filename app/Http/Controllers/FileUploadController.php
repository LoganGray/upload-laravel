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
        try {
            // Delete the file from storage
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }

            // Delete the file record from the database
            $file->delete();

            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting file: ' . $e->getMessage()], 500);
        }
    }
}

use Illuminate\Support\Facades\Storage;
