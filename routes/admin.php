<?php


use App\Http\Controllers\admin\CarController;
use App\Http\Controllers\admin\ContactController;
use App\Http\Controllers\admin\CurrencyController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\GenericController;
use App\Http\Controllers\admin\MainSettingController;
use App\Http\Controllers\admin\SitemapController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StaticTranslationController;
use App\Models\Brand;
use App\Models\Car_model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

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


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    /**
     * Define routes protected by 'auth' middleware for authenticated users.
     * Includes dashboard, contacts, settings, cars, image management, models, and other resources.
     */
Route::group(['middleware' => 'auth'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/contacts', [ContactController::class, 'edit'])->name('contacts.create');
    Route::get('/contacts', [ContactController::class, 'edit'])->name('contacts');

    Route::get('/main-settings/about-us', [MainSettingController::class, 'getAboutUs'])->name('get-about-us');

    Route::resource('cars', 'App\Http\Controllers\admin\old\CarController');

    Route::resource('languages', 'App\Http\Controllers\admin\LanguageController');

    Route::post('toggleStatus', [HelperController::class, 'toggleStatus'])->name('toggleStatus');

    Route::resource('brands', 'App\Http\Controllers\admin\BrandController');

    Route::resource('car_models', 'App\Http\Controllers\admin\Car_modelController');

//Route::resource('body_styles', 'App\Http\Controllers\admin\Body_styleController');

    Route::resource('makers', 'App\Http\Controllers\admin\MakerController');

    Route::resource('features', 'App\Http\Controllers\admin\FeatureController');


    Route::delete('cars/images/delete/{id}', [CarController::class, 'deleteImage'])->name('cars.delete_image');

    // Route::get('/cars/{car}/edit_images', [CarController::class, 'editImages'])->name('cars.edit_images');
    Route::post('/cars/{car}/update_images', [CarController::class, 'updateImages'])->name('cars.update_images');

    Route::get('cars/edit_images/{id}', [CarController::class, 'edit_images'])->name('cars.edit_images');
    Route::post('cars/images/update-default-image', [CarController::class, 'updateDefaultImage'])->name('cars.updateDefaultImage');
    Route::post('cars/images', [CarController::class, 'storeImages'])->name('cars.storeImages');
    Route::post('cars/youtube', [CarController::class, 'storeYoutubeUrls'])->name('cars.storeYouTube');
    Route::resource('cars', 'App\Http\Controllers\admin\CarController');
    Route::get('cars/models/{brand}', ['App\Http\Controllers\admin\CarController', 'getModelsByBrand'])->name('cars.models');

    Route::resource('categories', 'App\Http\Controllers\admin\CategoryController');

    Route::resource('gear_types', 'App\Http\Controllers\admin\Gear_typeController');

    Route::resource('colors', 'App\Http\Controllers\admin\ColorController');

    Route::resource('locations', 'App\Http\Controllers\admin\LocationController');

    Route::resource('blogs', 'App\Http\Controllers\admin\BlogController');

    Route::resource('faqs', 'App\Http\Controllers\admin\FaqController');

    Route::resource('services', 'App\Http\Controllers\admin\ServiceController');

    Route::resource('documents', 'App\Http\Controllers\admin\DocumentController');

    Route::resource('currencies', CurrencyController::class);


    Route::get('contacts/edit', [ContactController::class, 'edit'])->name('contacts.edit');
    Route::put('contacts/update', [ContactController::class, 'update'])->name('contacts.update');

    Route::resource('abouts', 'App\Http\Controllers\admin\AboutController');

    Route::resource('homes', 'App\Http\Controllers\admin\HomeController');

    Route::resource('instagrams', 'App\Http\Controllers\admin\InstagramController');

    Route::resource('advertisements', 'App\Http\Controllers\admin\AdvertisementController');


    Route::get('static-translations', [StaticTranslationController::class, 'index'])->name('static-translations.index');

    Route::post('static-translations/save', [StaticTranslationController::class, 'saveTranslations'])->name('static-translations.save'); // Accept POST requests

    Route::resource('short_videos', 'App\Http\Controllers\admin\ShortVideoController');

//sitemap

    Route::get('sitemap', function () {
        return view('pages.admin.sitemap.index');
    })->name('sitemap');

    Route::post('/sitemap/generate', [SitemapController::class, 'generate'])->name('sitemap.generate');
    Route::get('/sitemap/download', [SitemapController::class, 'download'])->name('sitemap.download');
    Route::get('/sitemap/download/{lang}/{type}', [SitemapController::class, 'downloadSpecific'])->name('sitemap.download.specific');
    Route::get('/sitemap/list', [SitemapController::class, 'list'])->name('sitemap.list');
    Route::post('/sitemap/notify', [SitemapController::class, 'notify'])->name('sitemap.notify');




    Route::get('/get-models/{brand}', function (Brand $brand) {
        $locale = App::getLocale();
        $carModels = Car_model::select('car_models.id', 'car_model_translations.name')
            ->where('brand_id', $brand->id)
            ->leftJoin('car_model_translations', function($join) use ($locale) {
                $join->on('car_model_translations.car_model_id', '=', 'car_models.id')
                    ->where('car_model_translations.locale', '=', $locale);
            })
            ->get();

        return response()->json($carModels);
    })->name('get.models');

});









Route::resource('currencies', 'App\Http\Controllers\admin\CurrencyController');
