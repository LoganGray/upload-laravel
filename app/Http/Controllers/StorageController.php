<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

class StorageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max file size 10MB
        ]);

        $uploadedFile = $request->file('file');
        $filename = time() . '_' . $uploadedFile->getClientOriginalName();
        $path = $uploadedFile->storeAs('uploads', $filename, 'public');

        $file = File::create([
            'name' => $filename,
            'path' => $path,
        ]);

        return response()->json(['success' => true, 'file' => $file]);
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
