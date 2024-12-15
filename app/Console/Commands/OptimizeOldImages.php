<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\CarImage; // Assuming you have this model for the images

class OptimizeOldImages extends Command
{
    protected $signature = 'images:optimize-old';
    protected $description = 'Optimize and convert old images to WebP format';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Define the target dimensions for resizing (if needed)
        $desiredWidth = 800;
        $desiredHeight = 520;

        // Fetch all image records from your CarImage model
        $images = CarImage::all();

        foreach ($images as $image) {
            // Ensure the file path is valid
            $filePath = storage_path('app/public/' . $image->file_path);
            // Check if the file path is not null and exists
            if (!empty($image->file_path) && Storage::exists("/public/".$image->file_path)) {
                $this->info('Optimizing: ' . $image->file_path);

                // Optimize, resize and convert the image to WebP
                try {
                    $interventionImage = Image::make($filePath)
                        ->resize($desiredWidth, $desiredHeight)  // Resize if necessary
                        ->encode('webp', 85)  // Convert to WebP with quality of 85
                        ->save($filePath);  // Save the image

                    // Optionally, update the database or perform other actions
                    $image->file_path = 'images/' . pathinfo($image->file_path, PATHINFO_FILENAME) . '.webp';
                    $image->save();

                    $this->info('Optimized and converted: ' . $image->file_path);
                } catch (\Exception $e) {
                    $this->error('Error optimizing: ' . $image->file_path);
                    $this->error($e->getMessage());
                }
            } else {
                // File doesn't exist or the path is invalid
                $this->error('File does not exist or invalid path: ' . $image->file_path);
            }
        }

        $this->info('Image optimization completed!');
    }
}
