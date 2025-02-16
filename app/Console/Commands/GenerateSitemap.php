<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Car;
use App\Models\Brand;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Location;
use App\Models\Language;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap for the website';

    public function handle()
    {
        $sitemap = Sitemap::create();
        $languages = Language::where('is_active', 1)->pluck('code'); // Fetch active languages
        $url = "https://www.afandinacarrental.com/"; // Fetch the base url of the website
        foreach ($languages as $lang) {
            // Add Static Pages
            $staticPages = [
                'home' => '',
                'about-us' => 'about-us',
                'contact-us' => 'contact-us',
                '404' => '404'
            ];
            foreach ($staticPages as $key => $path) {
                $sitemap->add(
                    Url::create($url.$lang.'/'.$path)
                        ->setPriority(1.0)
                        ->setChangeFrequency('monthly')
                );
            }

            // Add Dynamic Blog Pages
            $blogs = Blog::get();
            foreach ($blogs as $blog) {
                $sitemap->add(
                    Url::create($url.$lang.'/cars-rental-dubai/blogs/'.$blog->slug)
                        ->setPriority(0.8)
                        ->setChangeFrequency('weekly')
                        ->setLastModificationDate($blog->updated_at)
                );
            }

            // Add Dynamic Category Pages
            $categories = Category::get();
            foreach ($categories as $category) {
                $sitemap->add(
//                    Url::create("http://localhost:4200/$lang/cars-rental-dubai/categories/{$category->slug}")
                    Url::create($url.$lang.'/cars-rental-dubai/categories/'.$category->slug)
                        ->setPriority(0.7)
                        ->setChangeFrequency('weekly')
                        ->setLastModificationDate($category->updated_at)
                );
            }

            // Add Dynamic Brand Pages
            $brands = Brand::all();
            foreach ($brands as $brand) {
                $sitemap->add(
//                    Url::create("http://localhost:4200/$lang/cars-rental-dubai/brands/{$brand->slug}")
                    Url::create($url.$lang.'/cars-rental-dubai/brands/'.$brand->slug)
                        ->setPriority(0.7)
                        ->setChangeFrequency('weekly')
                        ->setLastModificationDate($brand->updated_at)
                );
            }

            // Add Dynamic Car Pages
            $cars = Car::get();
            foreach ($cars as $car) {
                $sitemap->add(
//                    Url::create("http://localhost:4200/$lang/{$car->slug}")
                    Url::create($url.$lang.'/'.$car->slug)
                        ->setPriority(0.9)
                        ->setChangeFrequency('weekly')
                        ->setLastModificationDate($car->updated_at)
                );
            }

            // Add Dynamic Location Pages
            $locations = Location::get();
            foreach ($locations as $location) {
                $sitemap->add(
//                    Url::create("http://localhost:4200/$lang/cars-rental-dubai/locations/{$location->slug}")
                    Url::create($url.$lang.'/cars-rental-dubai/locations/'.$location->slug)
                        ->setPriority(0.8)
                        ->setChangeFrequency('weekly')
                        ->setLastModificationDate($location->updated_at)

                );
            }
        }

        // Save the sitemap
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
