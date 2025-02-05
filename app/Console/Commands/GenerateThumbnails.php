<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GenerateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:generate-thumbnails {--force : Force regeneration of all thumbnails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate thumbnails for all existing car images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting thumbnail generation...');

        // Get all car images
        $query = \App\Models\CarImage::where('type', 'image');
        
        if (!$this->option('force')) {
            $query->whereNull('thumbnail_path');
        }
        
        $images = $query->get();

        $totalImages = $images->count();
        $this->info("Found {$totalImages} images that need thumbnails");

        if ($totalImages === 0) {
            $this->info('No images need thumbnails. Exiting...');
            return;
        }

        $bar = $this->output->createProgressBar($totalImages);
        $bar->start();

        foreach ($images as $image) {
            try {
                // Skip if original file doesn't exist
                if (!Storage::disk('public')->exists($image->file_path)) {
                    $this->warn("\nSkipping {$image->file_path} - File not found");
                    continue;
                }

                // Get original file
                $file = Storage::disk('public')->get($image->file_path);

                // Generate thumbnail path
                $filename = pathinfo($image->file_path, PATHINFO_FILENAME);
                $thumbnailPath = 'media/thumbnails/' . $filename . '_thumb.webp';

                // Create thumbnail using Intervention Image
                $thumbnail = Image::make($file);
                $thumbnail->resize(338, 240, function ($constraint) {
                    $constraint->aspectRatio();
                })->resizeCanvas(338, 240, 'center', false, '#ffffff');

                // Convert to WebP and save
                $thumbnail->encode('webp', 90);
                Storage::disk('public')->put($thumbnailPath, $thumbnail->stream());

                // Update database
                $image->thumbnail_path = $thumbnailPath;
                $image->save();

                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\nError processing {$image->file_path}: {$e->getMessage()}");
            }
        }

        $bar->finish();
        $this->info("\nThumbnail generation completed!");
    }
}
