<?php

namespace App\Jobs;

use App\Models\CarImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessSingleCarImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $car;
    protected $originalPath;
    protected $finalPath;

    public function __construct($car, $originalPath, $finalPath)
    {
        $this->car = $car;
        $this->originalPath = $originalPath;
        $this->finalPath = $finalPath;
    }

    public function handle()
    {
        // Process the image (similar logic as before)
        $imageContent = Storage::disk('public')->get($this->originalPath);
        $sourceImage = imagecreatefromstring($imageContent);
        
        // Get original dimensions
        $width = imagesx($sourceImage);
        $height = imagesy($sourceImage);
        
        // Calculate new dimensions (max 800px width/height)
        $ratio = $width / $height;
        $newWidth = min($width, 800);
        $newHeight = intval($newWidth / $ratio);

        if ($newHeight > 800) {
            $newHeight = 800;
            $newWidth = intval($newHeight * $ratio);
        }

        // Create new image
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        
        // High-quality resampling
        imagecopyresampled(
            $resizedImage, 
            $sourceImage, 
            0, 0, 0, 0, 
            $newWidth, $newHeight, 
            $width, $height
        );

        // Create temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'img');
        
        // Save as WebP with high quality
        imagewebp($resizedImage, $tempFile, 85);
        
        // Clean up resources
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);

        // Get the optimized content
        $optimizedContent = file_get_contents($tempFile);
        unlink($tempFile);

        // Store the optimized image
        Storage::disk('public')->put($this->finalPath, $optimizedContent);

        // Create car image record
        CarImage::create([
            'car_id' => $this->car->id,
            'file_path' => $this->finalPath
        ]);

        // Delete the original temporary file
        Storage::disk('public')->delete($this->originalPath);
    }
}
