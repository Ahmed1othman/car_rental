<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\CarController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::fallback(function () {
    return redirect('/admin/dashboard');
});

Route::get('/cars/images/check-status/{carId}', [CarController::class, 'checkImageProcessingStatus']);

Route::post('/cars/{id}/upload-image', [CarController::class, 'uploadImage'])->name('cars.upload-image');
Route::post('/cars/{id}/upload-default-image', [CarController::class, 'uploadDefaultImage'])->name('cars.upload-default-image');

// Test queue route
Route::get('/test-queue', function () {
    \App\Jobs\TestQueueJob::dispatch();
    return 'Job dispatched! Check your logs.';
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::delete('/cars/delete-image/{id}', [CarController::class, 'deleteImage'])->name('cars.delete-image');
    // Car image management routes
    Route::post('/cars/delete-selected-images', [CarController::class, 'deleteMultipleImages'])->name('cars.delete-selected-images');
    Route::delete('/cars/delete-all-images/{carId}', [CarController::class, 'deleteAllImages'])->name('cars.delete-all-images');
    Route::post('/cars/delete-selected-images', [CarController::class, 'deleteSelectedImages'])
        ->name('admin.cars.delete-selected-images');

    Route::post('/cars/delete-all-images/{car}', [CarController::class, 'deleteAllImages'])
        ->name('admin.cars.delete-all-images');
});
