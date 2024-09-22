<?php


use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\GenericController;
use App\Http\Controllers\admin\MainSettingController;
use App\Http\Controllers\HelperController;
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

//Route::get('/dashboard', function () {
//    return view('pages/admin/dashboard');
//})->name('dashboard');

Route::resource('templates',App\Http\Controllers\admin\TemplateController::class);


Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

Route::get('/main-settings/about-us', [MainSettingController::class,'getAboutUs'])->name('get-about-us');

Route::resource('brands', \App\Http\Controllers\admin\old\BrandController::class);

Route::resource('categories', \App\Http\Controllers\admin\old\CategoryController::class);




Route::resource('cars', 'App\Http\Controllers\admin\old\CarController');

Route::resource('languages', 'App\Http\Controllers\admin\LanguageController');



Route::post('toggleStatus', [HelperController::class, 'toggleStatus'])->name('toggleStatus');

Route::resource('brands', 'App\Http\Controllers\admin\BrandController');

Route::resource('car_models', 'App\Http\Controllers\admin\Car_modelController');

Route::resource('body_styles', 'App\Http\Controllers\admin\Body_styleController');

Route::resource('makers', 'App\Http\Controllers\admin\MakerController');

Route::resource('features', 'App\Http\Controllers\admin\FeatureController');

Route::resource('cars', 'App\Http\Controllers\admin\CarController');

Route::resource('categories', 'App\Http\Controllers\admin\CategoryController');

Route::resource('gear_types', 'App\Http\Controllers\admin\Gear_typeController');

Route::resource('colors', 'App\Http\Controllers\admin\ColorController');

Route::resource('locations', 'App\Http\Controllers\admin\LocationController');
