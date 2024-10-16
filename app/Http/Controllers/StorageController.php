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
            'category' => 'none',
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

    public function categorize()
    {
        $files = File::orderBy('category')
                     ->orderBy('stage')
                     ->orderBy('name')
                     ->get();
        $categories = ['Hitting', 'Base Running', 'Fielding', 'Baseball IQ'];
        return view('categorize', compact('files', 'categories'));
    }

    public function updateCategory(Request $request, File $file)
    {
        $request->validate([
            'category' => 'required|in:Hitting,Base Running,Fielding,Baseball IQ',
            'stage' => 'required|integer|min:1|max:10',
        ]);

        $file->update([
            'category' => $request->category,
            'stage' => $request->stage,
        ]);

        return response()->json(['success' => true, 'message' => 'File updated successfully']);
    }
}
