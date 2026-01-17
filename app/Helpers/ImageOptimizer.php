<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageOptimizer
{
    /**
     * Optimize, resize, and save an uploaded image.
     * 
     * @param UploadedFile $file The uploaded file.
     * @param string $path The storage path (folder).
     * @param int $maxWidth Maximum width in pixels.
     * @param int $quality Quality (0-100).
     * @return string The relative path of the saved file.
     */
    public static function save(UploadedFile $file, string $path = 'assets', int $maxWidth = 1000, int $quality = 80): string
    {
        // Ensure directory exists in storage/app/public
        $fullPath = storage_path("app/public/{$path}");
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0775, true);
        }

        // Create a unique filename with .webp extension for better compression
        $filename = Str::uuid() . '.webp';
        $destination = $fullPath . '/' . $filename;

        // Get image info
        list($width, $height, $type) = getimagesize($file->getRealPath());
        
        // Load image based on type
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($file->getRealPath());
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($file->getRealPath());
                // Handle transparency for PNG
                imagepalettetotruecolor($source);
                imagealphablending($source, true);
                imagesavealpha($source, true);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($file->getRealPath());
                break;
            case IMAGETYPE_WEBP:
                $source = imagecreatefromwebp($file->getRealPath());
                break;
            default:
                // If format not supported, just save original file
                return $file->store($path, 'public');
        }

        // Resize if width is larger than max
        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = floor($height * ($maxWidth / $width));
            
            $virtualImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for resizing
            if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_WEBP) {
                imagealphablending($virtualImage, false);
                imagesavealpha($virtualImage, true);
                $transparent = imagecolorallocatealpha($virtualImage, 255, 255, 255, 127);
                imagefilledrectangle($virtualImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled($virtualImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $virtualImage;
        }

        // Save as WebP
        if (function_exists('imagewebp')) {
            imagewebp($source, $destination, $quality);
        } else {
            // Fallback to JPEG if WebP not enabled
            $filename = Str::uuid() . '.jpg';
            $destination = $fullPath . '/' . $filename;
            imagejpeg($source, $destination, $quality);
        }

        imagedestroy($source);

        return $path . '/' . $filename;
    }
}
