<?php

declare(strict_types=1);

namespace App\Validation;

class MediaValidator
{
    private array $allowedMimes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
        'application/pdf', 'text/plain'
    ];
    private array $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf', 'txt'];
    private int $maxSize = 10485760; // 10MB

    public function validateUpload(array $file): array
    {
        $errors = [];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Upload error code: " . $file['error'];
            return $errors;
        }

        if ($file['size'] > $this->maxSize) {
            $errors[] = "File is too large. Max size is 10MB.";
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedExts, true)) {
            $errors[] = "File extension not allowed.";
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $this->allowedMimes, true)) {
            $errors[] = "MIME type not allowed.";
        }

        return $errors;
    }

    public function validateUpdate(array $data): array
    {
        return [];
    }
}
