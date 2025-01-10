<?php

namespace App\Jobs;

use App\Models\CarImage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImageProcessingTrait;
use Illuminate\Support\Facades\Log;

class ProcessSingleCarImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ImageProcessingTrait;

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
        try {
            // Get the full paths
            $originalFullPath = Storage::disk('public')->path($this->originalPath);
            $destinationFullPath = Storage::disk('public')->path($this->finalPath);
            
            // Process and optimize the image using our trait
            $this->processAndOptimizeImage($originalFullPath, $destinationFullPath);

            // Create car image record
            CarImage::create([
                'car_id' => $this->car->id,
                'file_path' => $this->finalPath
            ]);

            // Delete the original temporary file
            Storage::disk('public')->delete($this->originalPath);

            Log::info('Car image processed successfully', [
                'car_id' => $this->car->id,
                'path' => $this->finalPath,
                'size' => filesize($destinationFullPath)
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to process car image', [
                'car_id' => $this->car->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }
}
