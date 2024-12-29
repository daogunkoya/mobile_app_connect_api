<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDocumentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'original_name' => $this->originalName,
            'document_type' => $this->documentType,
            'mime_type' => $this->mimeType,
            'verification_result' => $this->verificationResult
        ];
    }
}
