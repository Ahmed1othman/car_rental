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
            // Get the model instance
            $model = $this->model::findOrFail($this->modelId);
            
            // Get the temp file
            $tempFile = Storage::disk('local')->get($this->tempPath);
            $tempFullPath = Storage::disk('local')->path($this->tempPath);
            
            // Create UploadedFile instance from temp file
            $file = new \Illuminate\Http\UploadedFile(
                $tempFullPath,
                $this->originalName,
                Storage::disk('local')->mimeType($this->tempPath),
                null,
                true
            );

            // Process the file
            $destinationPath = $this->getDestinationPath($model);
            $processedPath = $this->processFile($file, $destinationPath, $this->options);

            // Save the file path to the model
            if ($this->isMultiple) {
                $relation = Str::plural($this->field);
                $model->$relation()->create([
                    'file_path' => $processedPath,
                    'type' => $this->getFileType($file),
                    'alt' => $this->options['alt'] ?? null
                ]);
            } else {
                $model->{$this->field} = $processedPath;
                $model->save();
            }

            // Clean up temp file
            Storage::disk('local')->delete($this->tempPath);

            Log::info('File processed successfully', [
                'model' => get_class($model),
                'model_id' => $this->modelId,
                'field' => $this->field,
                'path' => $processedPath
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
