<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

trait FileProcessingTrait
{
    protected $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    protected $videoExtensions = ['mp4', 'webm', 'ogg', 'mov'];
    
    /**
     * Process any type of file (image, video, or document)
     */
    protected function processFile($file, $destinationPath, $options = [])
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $uniqueFilename = $filename . '_' . uniqid();

        // Process based on file type
        if (in_array($extension, $this->imageExtensions)) {
            return $this->processImage($file, $destinationPath, $uniqueFilename, $options);
        } elseif (in_array($extension, $this->videoExtensions)) {
            return $this->processVideo($file, $destinationPath, $uniqueFilename, $extension, $options);
        } else {
            return $this->processDocument($file, $destinationPath, $uniqueFilename, $extension, $options);
        }
    }

    /**
     * Process image files
     */
    protected function processImage($file, $destinationPath, $uniqueFilename, $options = [])
    {
        $webpFilename = $uniqueFilename . '.webp';
        $fullPath = Storage::disk('public')->path($destinationPath . '/' . $webpFilename);
        
        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0777, true);
        }

        // Process and optimize image
        $image = Image::make($file->getRealPath());
        
        // Get original dimensions
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        
        // Set maximum dimensions while maintaining aspect ratio
        $maxWidth = $options['maxWidth'] ?? 1920;
        $maxHeight = $options['maxHeight'] ?? 1080;
        
        // Calculate new dimensions while maintaining aspect ratio
        if ($originalWidth > $maxWidth || $originalHeight > $maxHeight) {
            $widthRatio = $maxWidth / $originalWidth;
            $heightRatio = $maxHeight / $originalHeight;
            $ratio = min($widthRatio, $heightRatio);
            
            $newWidth = round($originalWidth * $ratio);
            $newHeight = round($originalHeight * $ratio);
            
            $image->resize($newWidth, $newHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Save with high quality
        $image->encode('webp', $options['quality'] ?? 95)
              ->save($fullPath);

        return $destinationPath . '/' . $webpFilename;
    }

    /**
     * Process video files
     */
    protected function processVideo($file, $destinationPath, $uniqueFilename, $extension, $options = [])
    {
        $filename = $uniqueFilename . '.' . $extension;
        
        // Store video file
        $path = $file->storeAs(
            $destinationPath,
            $filename,
            'public'
        );

        return $path;
    }

    /**
     * Process document files
     */
    protected function processDocument($file, $destinationPath, $uniqueFilename, $extension, $options = [])
    {
        $filename = $uniqueFilename . '.' . $extension;
        
        // Store document file
        $path = $file->storeAs(
            $destinationPath,
            $filename,
            'public'
        );

        return $path;
    }
}
