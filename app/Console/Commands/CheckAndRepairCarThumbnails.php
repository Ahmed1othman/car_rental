<?php

namespace App\Console\Commands;

use App\Models\Car;
use App\Models\CarImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class CheckAndRepairCarThumbnails extends Command
{
    protected $signature = 'cars:repair-thumbnails';
    protected $description = 'Check all car thumbnails and repair missing ones';

    public function handle()
    {
        $this->info('Starting car thumbnails check and repair...');

        // Get all cars
        $cars = Car::all();
        $this->info('Found ' . $cars->count() . ' cars to check');

        $processedImages = 0;
        $processedCarImages = 0;
        $processedDefaultImages = 0;
        $fixedImages = 0;
        $fixedCarImages = 0;
        $fixedDefaultImages = 0;
        $errorImages = 0;

        $bar = $this->output->createProgressBar($cars->count());

        foreach ($cars as $car) {
            // Process the car's images collection
            foreach ($car->images as $image) {
                $processedImages++;
                $processedCarImages++;
                
                // Skip videos (they don't have thumbnails)
                if ($image->type === 'video') {
                    continue;
                }

                // Check if thumbnail exists in filesystem
                if ($image->thumbnail_path && !Storage::disk('public')->exists($image->thumbnail_path)) {
                    $this->line('');
                    $this->warn("Car ID {$car->id}: Missing thumbnail file for image ID {$image->id}: {$image->thumbnail_path}");

                    try {
                        // Check if the original image exists
                        if (!Storage::disk('public')->exists($image->file_path)) {
                            $this->error("Original image missing too: {$image->file_path}. Removing image record from database.");
                            $image->delete();
                            $errorImages++;
                            continue;
                        }

                        // Reset thumbnail_path in database
                        $image->update(['thumbnail_path' => null]);

                        // Get the original file path
                        $file = Storage::disk('public')->path($image->file_path);

                        // Generate new thumbnail path in media/thumbnails directory
                        $filename = pathinfo($image->file_path, PATHINFO_FILENAME);
                        $thumbnailPath = 'media/thumbnails/' . $filename . '_' . uniqid() . '.webp';

                        // Create thumbnail using Intervention Image
                        $thumbnail = Image::make($file);
                        $thumbnail->resize(737, 536, function ($constraint) {
                            $constraint->aspectRatio();
                        });

                        // Convert to WebP and save
                        $thumbnail->encode('webp', 100);
                        Storage::disk('public')->put($thumbnailPath, $thumbnail->stream());

                        // Update database with new thumbnail path
                        $image->update([
                            'thumbnail_path' => $thumbnailPath
                        ]);

                        // If this is the default image for the car, update the car's default_thumbnail_path
                        if ($car->default_image_path === $image->file_path) {
                            $car->update([
                                'default_thumbnail_path' => $thumbnailPath
                            ]);
                        }

                        $fixedImages++;
                        $fixedCarImages++;
                        $this->info("Fixed thumbnail for car image ID {$image->id}");
                    } catch (\Exception $e) {
                        $this->error("Error repairing image {$image->id}: {$e->getMessage()}");
                        $errorImages++;
                    }
                }
            }

            // Check car's default thumbnail if it exists
            if ($car->default_image_path && $car->default_thumbnail_path) {
                $processedImages++;
                $processedDefaultImages++;
                
                if (!Storage::disk('public')->exists($car->default_thumbnail_path)) {
                    $this->line('');
                    $this->warn("Car ID {$car->id}: Missing default thumbnail: {$car->default_thumbnail_path}");

                    try {
                        // Check if original default image exists
                        if (!Storage::disk('public')->exists($car->default_image_path)) {
                            $this->error("Default image also missing: {$car->default_image_path}. Clearing default image references.");
                            $car->update([
                                'default_image_path' => null,
                                'default_thumbnail_path' => null
                            ]);
                            $errorImages++;
                            continue;
                        }

                        // Get the original file path
                        $file = Storage::disk('public')->path($car->default_image_path);

                        // Generate new thumbnail path
                        $filename = pathinfo($car->default_image_path, PATHINFO_FILENAME);
                        $thumbnailPath = 'media/thumbnails/' . $filename . '_' . uniqid() . '.webp';

                        // Create thumbnail using Intervention Image
                        $thumbnail = Image::make($file);
                        $thumbnail->resize(737, 536, function ($constraint) {
                            $constraint->aspectRatio();
                        });

                        // Convert to WebP and save
                        $thumbnail->encode('webp', 100);
                        Storage::disk('public')->put($thumbnailPath, $thumbnail->stream());

                        // Update database
                        $car->update([
                            'default_thumbnail_path' => $thumbnailPath
                        ]);

                        $fixedImages++;
                        $fixedDefaultImages++;
                        $this->info("Fixed default thumbnail for car ID {$car->id}");
                    } catch (\Exception $e) {
                        $this->error("Error repairing default image for car {$car->id}: {$e->getMessage()}");
                        $errorImages++;
                    }
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info('Thumbnail check and repair completed!');
        $this->info("Processed {$processedImages} total images");
        $this->info("  - {$processedCarImages} images from car_images table");
        $this->info("  - {$processedDefaultImages} default images from cars table");
        $this->info("Fixed {$fixedImages} total thumbnails");
        $this->info("  - {$fixedCarImages} thumbnails for car images");
        $this->info("  - {$fixedDefaultImages} thumbnails for default images");
        $this->error("Encountered {$errorImages} errors");

        return Command::SUCCESS;
    }
}
