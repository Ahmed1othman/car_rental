<?php

use App\Http\Controllers\apis\AboutUsPageController;
use App\Http\Controllers\apis\BlogController;
use App\Http\Controllers\apis\BrandController;
use App\Http\Controllers\apis\CarController;
use App\Http\Controllers\apis\CategoryController;
use App\Http\Controllers\apis\ContactUsPageController;
use App\Http\Controllers\apis\FAQController;
use App\Http\Controllers\apis\GeneralController;
use App\Http\Controllers\apis\HomePageController;
use App\Http\Controllers\apis\ServiceController;
use App\Http\Controllers\apis\ShortVideoController;
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


Route::get('get-main-settings', [GeneralController::class, 'getMainSetting']);
Route::middleware(['language','currency','cta'])->group(function () {
    Route::get('get-footer', [GeneralController::class, 'getFooter']);
    Route::get('home', [HomePageController::class, 'index']);
    Route::get('about-us', [AboutUsPageController::class, 'index']);
    Route::get('contact-us', [ContactUsPageController::class, 'index']);
    Route::get('contact-us/send-message', [ContactUsPageController::class, 'storeContactMessage']);
    Route::post('search', [HomePageController::class, 'search']);
    Route::get('brands', [BrandController::class, 'index']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('short-videos', [ShortVideoController::class, 'index']);
    Route::get('locations', [ServiceController::class, 'index']);
    Route::get('blogs', [BlogController::class, 'index']);
    Route::get('blogs/{slug}', [BlogController::class, 'show']);
    Route::get('faqs', [FAQController::class, 'index']);
    Route::get('services', [ServiceController::class, 'index']);

    Route::get('cars', [CarController::class, 'index']);
    Route::get('cars/{slug}', [CarController::class, 'show']);
    Route::post('advanced-search', [CarController::class, 'advancedSearch']);
    Route::post('brand-cars', [CarController::class, 'getBrandCars']);
    Route::post('category-cars', [CarController::class, 'getCategoryCars']);
    Route::get('advanced-search-setting', [GeneralController::class, 'advancedSearchSetting']);
});
