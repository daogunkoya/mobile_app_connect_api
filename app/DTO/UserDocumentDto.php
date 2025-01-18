<?php

namespace App\DTO;


use App\Models\Currency;
use App\DTO\CurrencyDto;
use App\Models\UserDocument;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;


class UserDocumentDto extends BaseDto
{
    public function __construct(
        public string $originalName,
        public string $documentType,
        public string $mimeType,
        public null | string $verificationResult

    )
    {
    }

  
    public static function fromEloquentModel(UserDocument $userDocument): UserDocumentDto
    {
        return new self(
            $userDocument->original_name,
            $userDocument->document_type,
            $userDocument->mime_type,
            $userDocument->verification_result  
        );
    }

    
    }

   
