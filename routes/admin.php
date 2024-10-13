<?php


use App\Http\Controllers\admin\CarController;
use App\Http\Controllers\admin\ContactController;
use App\Http\Controllers\admin\CurrencyController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\GenericController;
use App\Http\Controllers\admin\MainSettingController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\LoginController;
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

Route::group(['middleware' => 'auth'], function () {

    Route::resource('templates', App\Http\Controllers\admin\TemplateController::class);
    Route::resource('admin.settings', App\Http\Controllers\admin\TemplateController::class);


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

    Route::get('cars/edit_images/{id}', [CarController::class, 'edit_images'])->name('cars.edit_images');
    Route::post('cars/images/update-default-image', [CarController::class, 'updateDefaultImage'])->name('cars.updateDefaultImage');
    Route::post('cars/images', [CarController::class, 'storeImages'])->name('cars.storeImages');
    Route::post('cars/youtube', [CarController::class, 'storeYoutubeUrls'])->name('cars.storeYouTube');
    Route::resource('cars', 'App\Http\Controllers\admin\CarController');

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

});

