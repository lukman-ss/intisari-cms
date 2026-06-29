<?php

declare(strict_types=1);

namespace App\Services;

use GdImage;

class ImageThumbnailService
{
    private array $sizes = [
        'thumbnail' => ['width' => 150, 'height' => 150, 'crop' => true],
        'medium'    => ['width' => 300, 'height' => 300, 'crop' => false],
        'large'     => ['width' => 1024, 'height' => 1024, 'crop' => false],
    ];

    public function generateThumbnails(string $filePath): array
    {
        if (!extension_loaded('gd')) {
            return [];
        }

        if (!is_file($filePath)) {
            return [];
        }

        $mime = mime_content_type($filePath);
        $supportedMimes = [
            'image/jpeg' => 'imagecreatefromjpeg',
            'image/png'  => 'imagecreatefrompng',
            'image/webp' => 'imagecreatefromwebp',
        ];

        if (!isset($supportedMimes[$mime])) {
            return [];
        }

        if ($mime === 'image/webp' && !function_exists('imagecreatefromwebp')) {
            return [];
        }

        $createFunc = $supportedMimes[$mime];
        $sourceImage = @$createFunc($filePath);

        if (!$sourceImage) {
            return [];
        }

        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);
        
        $generated = [];

        foreach ($this->sizes as $sizeName => $dimensions) {
            $targetWidth = $dimensions['width'];
            $targetHeight = $dimensions['height'];
            $crop = $dimensions['crop'];

            if ($crop) {
                $sourceAspect = $sourceWidth / $sourceHeight;
                $targetAspect = $targetWidth / $targetHeight;

                if ($sourceAspect > $targetAspect) {
                    $newHeight = $targetHeight;
                    $newWidth = (int)round($targetHeight * $sourceAspect);
                } else {
                    $newWidth = $targetWidth;
                    $newHeight = (int)round($targetWidth / $sourceAspect);
                }
                
                $tempImage = imagecreatetruecolor($newWidth, $newHeight);
                $this->preserveTransparency($tempImage, $mime);
                
                imagecopyresampled($tempImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
                
                $finalImage = imagecreatetruecolor($targetWidth, $targetHeight);
                $this->preserveTransparency($finalImage, $mime);
                
                $xOffset = (int)round(($newWidth - $targetWidth) / 2);
                $yOffset = (int)round(($newHeight - $targetHeight) / 2);
                
                imagecopy($finalImage, $tempImage, 0, 0, $xOffset, $yOffset, $targetWidth, $targetHeight);
                imagedestroy($tempImage);
            } else {
                if ($sourceWidth <= $targetWidth && $sourceHeight <= $targetHeight) {
                    continue;
                }

                $ratio = min($targetWidth / $sourceWidth, $targetHeight / $sourceHeight);
                $newWidth = (int)round($sourceWidth * $ratio);
                $newHeight = (int)round($sourceHeight * $ratio);

                $finalImage = imagecreatetruecolor($newWidth, $newHeight);
                $this->preserveTransparency($finalImage, $mime);

                imagecopyresampled($finalImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $sourceWidth, $sourceHeight);
            }

            $pathInfo = pathinfo($filePath);
            $thumbFileName = $pathInfo['filename'] . '-' . $sizeName . '.' . $pathInfo['extension'];
            $thumbFilePath = $pathInfo['dirname'] . DIRECTORY_SEPARATOR . $thumbFileName;

            $this->saveImage($finalImage, $thumbFilePath, $mime);
            imagedestroy($finalImage);
            
            $generated[$sizeName] = $thumbFileName;
        }

        imagedestroy($sourceImage);

        return $generated;
    }

    private function preserveTransparency(GdImage $image, string $mime): void
    {
        if ($mime === 'image/png' || $mime === 'image/webp') {
            imagealphablending($image, false);
            imagesavealpha($image, true);
            $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
            if ($transparent !== false) {
                imagefill($image, 0, 0, $transparent);
            }
        }
    }

    private function saveImage(GdImage $image, string $path, string $mime): bool
    {
        return match ($mime) {
            'image/jpeg' => imagejpeg($image, $path, 85),
            'image/png'  => imagepng($image, $path, 8),
            'image/webp' => imagewebp($image, $path, 85),
            default      => false,
        };
    }
}
