<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;

trait ImageProcessingTrait
{
    protected function processAndOptimizeImage($sourcePath, $destinationPath)
    {
        $image = Image::make($sourcePath);
        
        // Get original dimensions
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        
        // Set maximum dimensions while maintaining aspect ratio
        $maxWidth = 1920;  // Maximum width for high-quality display
        $maxHeight = 1080; // Maximum height for high-quality display
        
        // Calculate new dimensions while maintaining aspect ratio
        if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
            $widthRatio = $maxWidth / $originalWidth;
            $heightRatio = $maxHeight / $originalHeight;
            $ratio = min($widthRatio, $heightRatio);
            
            $newWidth = round($originalWidth * $ratio);
            $newHeight = round($originalHeight * $ratio);
            
            $image->resize($newWidth, $newHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // Prevent upsizing smaller images
            });
        }

        // Save with high quality
        return $image->encode('webp', 95) // High quality WebP
                    ->save($destinationPath);
    }
}
