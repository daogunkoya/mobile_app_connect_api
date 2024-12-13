<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Storage;

class ProcessDocumentJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public $document;

    public function __construct(UserDocument $document)
    {
        $this->document = $document;
    }

    public function handle()
    {
        $path = Storage::disk('public')->path($this->document->file_path);
        $directory = dirname($this->document->file_path);

        // Optimize and resize images if not PDF
        if (in_array($this->document->mime_type, ['image/jpeg', 'image/png'])) {
            $this->processImage($path, $directory);
        }

        // Update the file size after processing
        $this->document->update([
            'file_size' => filesize($path) / 1024, // Convert to KB
            'status' => 'processed',
        ]);

        // Optionally, pass the file to a third-party service for verification
        $verificationResult = $this->verifyWithThirdParty($path);

        $this->document->update(['verification_result' => $verificationResult]);
    }

    protected function processImage(string $filePath, string $directory): void
{
    $mimeType = mime_content_type($filePath);
    $baseFilename = basename($filePath); // Extract the filename

    // Load the image
    switch ($mimeType) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($filePath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($filePath);
            imagealphablending($image, false);
            imagesavealpha($image, true);
            break;
        default:
            throw new \Exception("Unsupported image format: $mimeType");
    }

    // Generate resized images
    $this->saveResizedImage($image, $directory . '/small/', 300, 300, $baseFilename);
    $this->saveResizedImage($image, $directory . '/medium/', 600, 600, $baseFilename);
    $this->saveResizedImage($image, $directory . '/regular/', 1200, 1200, $baseFilename);

    // Save the original image as JPEG with compression quality of 75
    imagejpeg($image, $filePath, 75);

    // Free up memory
    imagedestroy($image);
}

protected function saveResizedImage($image, string $directory, int $maxWidth, int $maxHeight, string $baseFilename): void
{
    $width = imagesx($image);
    $height = imagesy($image);

    $scale = min($maxWidth / $width, $maxHeight / $height);
    $newWidth = (int)($width * $scale);
    $newHeight = (int)($height * $scale);

    $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

    // Preserve transparency for PNG
    imagealphablending($resizedImage, false);
    imagesavealpha($resizedImage, true);

    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Ensure the save directory exists
    $savePath = Storage::disk('public')->path($directory);
    if (!is_dir($savePath)) {
        mkdir($savePath, 0755, true);
    }

    // Save the resized image
    imagejpeg($resizedImage, $savePath . $baseFilename, 75);

    imagedestroy($resizedImage);
}


    protected function verifyWithThirdParty(string $filePath): string
    {
        // Integration logic with a third-party service
        return json_encode(['status' => 'verified', 'details' => 'Sample verification result']);
    }
}