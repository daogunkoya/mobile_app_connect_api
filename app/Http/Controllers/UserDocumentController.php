<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\UserDocument;
use App\Jobs\ProcessDocumentJob;
use App\Http\Controllers\Controller;

class UserDocumentController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB
            'document_type' => 'required|string|in:profile,identity,files',
        ]);

        $file = $request->file('document');
        $user = auth()->user();

        // Define directory path based on user and document type
        $filePath = "images/users/{$user->id_user}/{$request->document_type}/original/";
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $storedPath = $file->storeAs($filePath, $fileName, 'public');

        // Create a database record
        $document = UserDocument::create([
            'user_id' => $user->id_user,
            'document_type' => $request->document_type,
            'file_path' => $storedPath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize() / 1024, // Convert to KB
        ]);

        // Dispatch background job
        ProcessDocumentJob::dispatch($document);

        return response()->json(['message' => 'Document uploaded successfully!', 'document' => $document]);
    }
}