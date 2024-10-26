<?php

use App\Http\Controllers\apis\BLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('get-main-settings', [\App\Http\Controllers\apis\GeneralController::class, 'getMainSetting']);
Route::middleware(['language','currency'])->group(function () {
    Route::get('get-footer', [\App\Http\Controllers\apis\GeneralController::class, 'getFooter']);
    Route::get('home', [\App\Http\Controllers\apis\HomePageController::class, 'index']);
    Route::post('search', [\App\Http\Controllers\apis\HomePageController::class, 'search']);
    Route::get('brands', [\App\Http\Controllers\apis\BrandController::class, 'index']);
    Route::get('categories', [\App\Http\Controllers\apis\CategoryController::class, 'index']);
    Route::get('locations', [\App\Http\Controllers\apis\ServiceController::class, 'index']);
    Route::get('blogs', [\App\Http\Controllers\apis\BlogController::class, 'index']);
    Route::get('blogs/{slug}', [\App\Http\Controllers\apis\BlogController::class, 'show']);
    Route::get('faqs', [\App\Http\Controllers\apis\FAQController::class, 'index']);
    Route::get('services', [\App\Http\Controllers\apis\ServiceController::class, 'index']);

    Route::get('cars', [\App\Http\Controllers\apis\CarController::class, 'index']);
    Route::get('cars/{slug}', [\App\Http\Controllers\apis\CarController::class, 'show']);
    Route::post('advanced-search', [\App\Http\Controllers\apis\CarController::class, 'advancedSearch']);

});
