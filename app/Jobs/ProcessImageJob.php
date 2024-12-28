<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tempPath;
    protected $originalName;
    protected $model;
    protected $modelId;
    protected $field;
    protected $isMultiple;

    public function __construct($tempPath, $originalName, $model, $modelId, $field, $isMultiple = false)
    {
        $this->tempPath = $tempPath;
        $this->originalName = $originalName;
        $this->model = $model;
        $this->modelId = $modelId;
        $this->field = $field;
        $this->isMultiple = $isMultiple;
    }

    public function handle()
    {
        try {
            Log::info('Starting image processing job', [
                'model' => $this->model,
                'field' => $this->field,
                'is_multiple' => $this->isMultiple
            ]);

            // Get the temp file path
            $filePath = storage_path('app/' . $this->tempPath);

            if (!file_exists($filePath)) {
                Log::error("Temp file not found: " . $filePath);
                return;
            }

            // Generate unique filename
            $filename = pathinfo($this->originalName, PATHINFO_FILENAME);
            $webpFilename = $filename . '_' . uniqid() . '.webp';
            
            // Define the correct storage paths
            $publicPath = 'public/images/' . $webpFilename;
            $fullPath = storage_path('app/' . $publicPath);
            $relativePath = 'images/' . $webpFilename;

            // Ensure directory exists
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0777, true);
            }

            // Process and optimize image
            $image = Image::make($filePath);
            
            // Get original aspect ratio
            $originalWidth = $image->width();
            $originalHeight = $image->height();
            
            // Calculate new dimensions maintaining 200px height
            $newHeight = 513;
            $newWidth = ($originalWidth / $originalHeight) * $newHeight;
            
            $image->resize($newWidth, $newHeight, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode('webp', 85)
            ->save($fullPath);

            // Clean up temp file
            Storage::delete($this->tempPath);

            // Get the model instance
            $model = $this->model::find($this->modelId);
            
            if ($model) {
                if ($this->isMultiple) {
                    // For many-to-many relationships (like car_images)
                    $relation = Str::plural($this->field);
                    $model->$relation()->create([
                        'file_path' => $relativePath,
                        'alt' => null,
                        'type' => 'image'
                    ]);

                    Log::info('Created image relation', [
                        'model' => get_class($model),
                        'id' => $model->id,
                        'relation' => $relation,
                        'path' => $relativePath
                    ]);
                } else {
                    // For single image fields in the same table
                    if ($model->{$this->field}) {
                        Storage::disk('public')->delete($model->{$this->field});
                    }
                    
                    $model->{$this->field} = $relativePath;
                    $model->save();
                }

                Log::info('Image processed and saved successfully', [
                    'path' => $relativePath,
                    'full_path' => $fullPath,
                    'public_path' => $publicPath
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error processing image: ' . $e->getMessage(), [
                'exception' => $e,
                'file' => $this->originalName
            ]);
            
            if (isset($filePath) && file_exists($filePath)) {
                @unlink($filePath);
            }
            
            throw $e;
        }
    }
}
