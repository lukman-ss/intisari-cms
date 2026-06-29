<?php

declare(strict_types=1);

namespace App\Services;

class MediaUploadService
{
    private string $uploadPath;

    public function __construct()
    {
        // Store in public/storage/uploads to be accessible without complex router handling, 
        // while respecting the 'storage/uploads' convention requirement for URLs
        $this->uploadPath = rtrim(app()->basePath('public/storage/uploads'), '/\\');
    }

    public function upload(array $file): array
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload failed with error code: ' . $file['error']);
        }

        $year = date('Y');
        $month = date('m');
        $targetDir = $this->uploadPath . '/' . $year . '/' . $month;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        // Never trust original filename
        $newFilename = uniqid('img_') . '.' . $ext;
        $targetFile = $targetDir . '/' . $newFilename;

        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            throw new \RuntimeException('Failed to move uploaded file.');
        }

        return [
            'filename' => $year . '/' . $month . '/' . $newFilename,
            'mime_type' => mime_content_type($targetFile) ?: $file['type'],
            'size' => filesize($targetFile),
        ];
    }

    public function deleteFile(string $filename): bool
    {
        $filepath = $this->uploadPath . '/' . $filename;
        if (is_file($filepath)) {
            return unlink($filepath);
        }
        return false;
    }
}
