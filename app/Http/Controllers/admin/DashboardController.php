<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // إحصائيات عامة
        $carCount = Car::count();
        $categoryCount = Category::count();
        $brandCount = Brand::count();
        
        // السيارات حسب الفئات
        $carsByCategory = Category::withCount('cars')
            ->with('translations')
            ->orderBy('cars_count', 'desc')
            ->take(5)
            ->get();

        // أحدث السيارات المضافة
        $latestCars = Car::with(['brand', 'category', 'translations'])
            ->latest()
            ->take(5)
            ->get();

        // أكثر العلامات التجارية التي لديها سيارات
        $topBrands = Brand::withCount('cars')
            ->with('translations')
            ->orderBy('cars_count', 'desc')
            ->take(5)
            ->get();

        return view('pages.admin.dashboard', compact(
            'carCount',
            'categoryCount',
            'brandCount',
            'latestCars',
            'topBrands',
            'carsByCategory'
        ));
    }
}
