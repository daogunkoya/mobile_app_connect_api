<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DTO\UserDocumentDto;
use Illuminate\Support\Facades\Storage;
use App\Models\UserDocument;
use App\Jobs\ProcessDocumentJob;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserDocumentResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UserDocumentRequest;

class UserDocumentController extends Controller
{
    public function upload(UserDocumentRequest $request)
{
    $request->validate([
        'id_image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        'selfie_image' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
    ]);

    $user = auth()->user();

    $idImage = $request->file('id_image');
    $selfieImage = $request->file('selfie_image');

    $idImagePath = $idImage->storeAs("images/users/{$user->id_user}/identity/original", uniqid() . '.' . $idImage->getClientOriginalExtension(), 'public');
    $selfieImagePath = $selfieImage->storeAs("images/users/{$user->id_user}/selfie/original", uniqid() . '.' . $selfieImage->getClientOriginalExtension(), 'public');

    $idDocument = UserDocument::create([
        'user_id' => $user->id_user,
        'document_type' => 'identity',
        'file_path' => $idImagePath,
        'original_name' => $idImage->getClientOriginalName(),
        'mime_type' => $idImage->getMimeType(),
        'file_size' => $idImage->getSize() / 1024, // Convert to KB
    ]);

    $selfieDocument = UserDocument::create([
        'user_id' => $user->id_user,
        'document_type' => 'selfie',
        'file_path' => $selfieImagePath,
        'original_name' => $selfieImage->getClientOriginalName(),
        'mime_type' => $selfieImage->getMimeType(),
        'file_size' => $selfieImage->getSize() / 1024, // Convert to KB
    ]);

    $idDocDto = UserDocumentDto::fromEloquentModel($idDocument);

    $selfieDocumentDto = UserDocumentDto::fromEloquentModel($selfieDocument);

    // Dispatch background jobs
    ProcessDocumentJob::dispatch($idDocument);
    ProcessDocumentJob::dispatch($selfieDocument);

    

    //return response()->json(['message' => 'Documents uploaded successfully!']);

    // Return response with UserDocumentResource::collection
    return UserDocumentResource::collection([$idDocDto, $selfieDocumentDto])
        ->response()
        ->setStatusCode(Response::HTTP_OK);
}

}