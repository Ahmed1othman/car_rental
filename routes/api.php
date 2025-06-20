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
use App\Http\Controllers\apis\LocationController;
use App\Http\Controllers\apis\ServiceController;
use App\Http\Controllers\apis\ShortVideoController;
use Illuminate\Support\Facades\Route;
use Spatie\ResponseCache\Middleware\CacheResponse;

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

Route::middleware(['language','currency','cta', 'cache.response'])->group(function () {
    Route::get('get-footer', [GeneralController::class, 'getFooter']);
    Route::get('home', [HomePageController::class, 'index'])->middleware('cache.response');
    Route::get('about-us', [AboutUsPageController::class, 'index']);
    Route::get('contact-us', [ContactUsPageController::class, 'index'])->middleware('cache.response');
    Route::post('contact-us/send-message', [ContactUsPageController::class, 'storeContactMessage']);
    Route::post('search', [HomePageController::class, 'search']);
    Route::get('brands', [BrandController::class, 'index']);
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('short-videos', [ShortVideoController::class, 'index']);
    Route::get('locations', [LocationController::class, 'index']);
    Route::get('blogs', [BlogController::class, 'index']);
    Route::get('blogs/{slug}', [BlogController::class, 'show'])->middleware('cache.response');
    Route::get('faqs', [FAQController::class, 'index']);
    Route::get('services', [ServiceController::class, 'index']);

    Route::get('/cars/brand/{brand}', [CarController::class, 'getBrandCars']);
    Route::get('cars/category/{category}', [CarController::class, 'getCategoryCars']);
    Route::get('cars/location/{location}', [CarController::class, 'getLocationCars']);
    Route::get('cars', [CarController::class, 'index']);
    Route::get('cars/{slug}', [CarController::class, 'show']);
    Route::post('advanced-search', [CarController::class, 'advancedSearch']);
    Route::get('advanced-search-setting', [GeneralController::class, 'advancedSearchSetting']);
    Route::get('seo-pages',[HomePageController::class,'SEO']);
    Route::get('currencies',[GeneralController::class,'getCurrencies']);
});

// Debug route for SEO questions
Route::get('/debug/brand-seo/{brand}', function($brand) {
    $brandModel = \App\Models\Brand::where('slug', $brand)->first();
    if (!$brandModel) {
        return response()->json(['error' => 'Brand not found'], 404);
    }
    
    return response()->json([
        'brand_id' => $brandModel->id,
        'total_questions' => $brandModel->seoQuestions()->count(),
        'questions_by_locale' => $brandModel->seoQuestions()
            ->select('locale')
            ->selectRaw('count(*) as count')
            ->groupBy('locale')
            ->get(),
        'all_questions' => $brandModel->seoQuestions()->get()
    ]);
});
