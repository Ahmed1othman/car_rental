<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Car;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessCarImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $car;
    protected $imagePath;
    protected $originalPath;
    protected $isDefaultImage;

    public function __construct(Car $car, string $imagePath, string $originalPath, bool $isDefaultImage = false)
    {
        $this->car = $car;
        $this->imagePath = $imagePath;
        $this->originalPath = $originalPath;
        $this->isDefaultImage = $isDefaultImage;
    }

    public function handle()
    {
        try {
            // Get original image content
            $imageContent = Storage::disk('public')->get($this->originalPath);
            
            // Create image resource
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

            // Create new image with correct orientation
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
            
            // Clean up
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);

            // Get the optimized content
            $optimizedContent = file_get_contents($tempFile);
            unlink($tempFile);

            // Store the optimized image
            Storage::disk('public')->put($this->imagePath, $optimizedContent);

            // Delete the original temporary file
            Storage::disk('public')->delete($this->originalPath);

            // Update the car model
            if ($this->isDefaultImage) {
                $this->car->update(['default_image_path' => $this->imagePath]);
            }

            Log::info('Image processed successfully', [
                'car_id' => $this->car->id,
                'path' => $this->imagePath,
                'size' => strlen($optimizedContent)
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing image: ' . $e->getMessage(), [
                'car_id' => $this->car->id,
                'path' => $this->imagePath
            ]);
            
            throw $e;
        }
    }
}
