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
//        $carCount = Car::count();
        $carCount = 15;
        $categoryCount = Category::count();
        $brandCount = Brand::count();
//        $userCount = User::count();
        $userCount = 3;

        // Monthly revenue data example
        $months = ['January', 'February', 'March', 'April'];
        $revenueData = [1000, 2000, 1500, 3000];

        return view('pages.admin.dashboard', compact('carCount', 'categoryCount', 'brandCount', 'userCount', 'months', 'revenueData'));
    }
}
