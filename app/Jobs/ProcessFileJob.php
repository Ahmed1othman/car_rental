<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Traits\FileProcessingTrait;
use Intervention\Image\Facades\Image;
use App\Models\CarImage;

class ProcessFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FileProcessingTrait;

    protected $model;
    protected $modelId;
    protected $field;
    protected $tempPath;
    protected $originalName;
    protected $options;
    protected $isMultiple;

    /**
     * Create a new job instance.
     *
     * @param string $model Model class name
     * @param int $modelId Model ID
     * @param string $field Field name in the model
     * @param string $tempPath Temporary path of the uploaded file
     * @param string $originalName Original file name
     * @param array $options Processing options
     * @param bool $isMultiple Whether this is a multiple file upload
     */
    public function __construct($model, $modelId, $field, $tempPath, $originalName, $options = [], $isMultiple = false)
    {
        $this->model = $model;
        $this->modelId = $modelId;
        $this->field = $field;
        $this->tempPath = $tempPath;
        $this->originalName = $originalName;
        $this->options = $options;
        $this->isMultiple = $isMultiple;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $model = $this->model::findOrFail($this->modelId);
            
            // Process the file
            $file = Storage::get($this->tempPath);
            
            // Generate final path
            $filename = pathinfo($this->originalName, PATHINFO_FILENAME);
            $originalExtension = strtolower(pathinfo($this->originalName, PATHINFO_EXTENSION));
            
            // Check if the file is a video
            $videoExtensions = ['mp4', 'webm', 'ogg'];
            $isVideo = in_array($originalExtension, $videoExtensions);
            
            if ($isVideo) {
                // For videos, keep the original extension
                $extension = '.' . $originalExtension;
                $finalPath = 'media/' . $filename . '_' . uniqid() . $extension;
                
                // Store the video file directly without processing
                Storage::disk('public')->put($finalPath, $file);
            } else {
                // For images, process with Intervention Image
                $extension = '.webp';
                $finalPath = 'media/' . $filename . '_' . uniqid() . $extension;
                $thumbnailPath = 'media/thumbnails/' . $filename . '_' . uniqid() . $extension;
                
                // Process original image with Intervention Image
                $image = Image::make($file);
                
                // Resize if needed
                if (isset($this->options['maxWidth']) && isset($this->options['maxHeight'])) {
                    $image->resize($this->options['maxWidth'], $this->options['maxHeight'], function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                }
                
                // Create thumbnail version (330 × 240 px)
                $thumbnail = Image::make($file);
                $thumbnail->fit(330, 240, function ($constraint) {
                    $constraint->aspectRatio();
                });
                
                // Convert to WebP and save both versions
                $image->encode('webp', $this->options['quality'] ?? 90);
                $thumbnail->encode('webp', $this->options['quality'] ?? 90);
                
                Storage::disk('public')->put($finalPath, $image->stream());
                Storage::disk('public')->put($thumbnailPath, $thumbnail->stream());
            }
            
            // Update model or create media record
            if ($this->isMultiple) {
                // Create new CarImage record
                $media = new CarImage();
                $media->car_id = $this->modelId;
                $media->file_path = $finalPath;
                if (!$isVideo) {
                    $media->thumbnail_path = $thumbnailPath;
                }
                $media->alt = $this->options['alt'] ?? null;
                $media->type = $isVideo ? 'video' : 'image';
                $media->save();
            } else {
                // Update model's image field directly
                $model->{$this->field} = $finalPath;
                $model->save();
            }
            
            // Clean up temp file
            Storage::delete($this->tempPath);
            
            Log::info('File processed successfully', [
                'model' => $this->model,
                'model_id' => $this->modelId,
                'field' => $this->field,
                'path' => $finalPath
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to process file', [
                'model' => $this->model,
                'model_id' => $this->modelId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get the destination path for the file based on model type
     */
    protected function getDestinationPath($model)
    {
        $modelName = Str::plural(Str::snake(class_basename($model)));
        return $modelName;
    }

    /**
     * Get the file type based on extension
     */
    protected function getFileType($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, $this->imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $this->videoExtensions)) {
            return 'video';
        } else {
            return 'document';
        }
    }
}
