<?php

namespace App\Console\Commands;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GenerateThumbnails extends Command
{
    protected $signature = 'images:generate-thumbnails {--force : Force regeneration of all thumbnails}';
    protected $description = 'Generate thumbnails for car images';

    public function handle()
    {
        $this->info('Starting thumbnail generation...');

        // Get all car images that need thumbnails
        $query = CarImage::query();
        if (!$this->option('force')) {
            $query->whereNull('thumbnail_path');
        }
        $images = $query->get();

        $this->info('Found ' . $images->count() . ' images that need thumbnails');

        $bar = $this->output->createProgressBar($images->count());

        foreach ($images as $image) {
            try {
                // Get the original file path
                $file = Storage::disk('public')->path($image->file_path);

                // Generate thumbnail path
                $pathInfo = pathinfo($image->file_path);
                $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['filename'] . '.webp';

                // Create thumbnail using Intervention Image
                $thumbnail = Image::make($file);
                $thumbnail->fit(330, 240, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // Convert to WebP and save
                $thumbnail->encode('webp', 100);
                Storage::disk('public')->put($thumbnailPath, $thumbnail->stream());

                // Update database
                $image->update([
                    'thumbnail_path' => $thumbnailPath
                ]);

                // If this is a default image, update the car's default_thumbnail_path
                $car = $image->car;
                if ($car && $car->default_image_path === $image->file_path) {
                    $car->update([
                        'default_thumbnail_path' => $thumbnailPath
                    ]);
                }

                $bar->advance();
            } catch (\Exception $e) {
                $this->error("Error processing image {$image->id}: {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine();

        // Now handle default images from cars table
        $this->info('Processing default images from cars...');
        
        $cars = Car::whereNotNull('default_image_path')
                  ->when(!$this->option('force'), function($query) {
                      $query->where(function($q) {
                          $q->whereNull('default_thumbnail_path')
                            ->orWhere('default_thumbnail_path', '');
                      });
                  })
                  ->get();

        $this->info('Found ' . $cars->count() . ' default images that need thumbnails');

        $bar = $this->output->createProgressBar($cars->count());

        foreach ($cars as $car) {
            try {
                if (!$car->default_image_path) continue;

                // Get the original file path
                $file = Storage::disk('public')->path($car->default_image_path);

                // Generate thumbnail path
                $pathInfo = pathinfo($car->default_image_path);
                $thumbnailPath = $pathInfo['dirname'] . '/thumb_' . $pathInfo['filename'] . '.webp';

                // Create thumbnail using Intervention Image
                $thumbnail = Image::make($file);
                $thumbnail->fit(330, 240, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // Convert to WebP and save
                $thumbnail->encode('webp', 100);
                Storage::disk('public')->put($thumbnailPath, $thumbnail->stream());

                // Update database
                $car->update([
                    'default_thumbnail_path' => $thumbnailPath
                ]);

                $bar->advance();
            } catch (\Exception $e) {
                $this->error("Error processing default image for car {$car->id}: {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->newLine();

        $this->info('Thumbnail generation completed!');
    }
}
