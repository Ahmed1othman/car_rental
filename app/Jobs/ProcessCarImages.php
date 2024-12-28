<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProcessCarImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $car;
    protected $imagePaths;
    protected $originalPaths;

    public function __construct(Car $car, array $imagePaths, array $originalPaths)
    {
        $this->car = $car;
        $this->imagePaths = $imagePaths;
        $this->originalPaths = $originalPaths;
    }

    public function handle()
    {
        // Increase memory limit
        ini_set('memory_limit', '512M'); // Increase memory limit to 512M

        foreach ($this->originalPaths as $index => $originalPath) {
            $finalPath = $this->imagePaths[$index];
            
            // Dispatch a new job for each image
            ProcessSingleCarImage::dispatch($this->car, $originalPath, $finalPath);
        }
    }
}
