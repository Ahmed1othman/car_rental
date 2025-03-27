<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use App\Models\Car;
use App\Models\Brand;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Location;
use App\Models\Language;
use Carbon\Carbon;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap for the website';

    protected $baseUrl = "https://www.afandinacarrental.com/";

    public function handle()
    {
        $languages = Language::where('is_active', 1)->pluck('code'); // Fetch active languages
        $sitemapIndex = SitemapIndex::create();

        foreach ($languages as $lang) {
            // Generate sitemap for static pages
            $this->generateStaticPagesSitemap($lang, $sitemapIndex);
            
            // Generate sitemap for categories
            $this->generateCategoriesSitemap($lang, $sitemapIndex);
            
            // Generate sitemap for brands
            $this->generateBrandsSitemap($lang, $sitemapIndex);
            
            // Generate sitemap for cars
            $this->generateCarsSitemap($lang, $sitemapIndex);
            
            // Generate sitemap for locations
            $this->generateLocationsSitemap($lang, $sitemapIndex);
            
            // Generate sitemap for blogs
            $this->generateBlogsSitemap($lang, $sitemapIndex);
        }

        // Save the sitemap index
        $sitemapIndex->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap index and all sitemaps generated successfully!');
    }

    protected function generateStaticPagesSitemap($lang, $sitemapIndex)
    {
        $sitemap = Sitemap::create();
        $staticPages = [
            'home' => '',
            'about-us' => 'about-us',
            'contact-us' => 'contact-us',
            '404' => '404'
        ];

        foreach ($staticPages as $key => $path) {
            $sitemap->add(
                Url::create($this->baseUrl.$lang.'/'.$path)
                    ->setPriority(1.0)
                    ->setChangeFrequency('monthly')
            );
        }

        $filename = "{$lang}/sitemap-static.xml";
        $sitemap->writeToFile(public_path($filename));
        $sitemapIndex->add($this->baseUrl . $filename);
        
        $this->info("Static pages sitemap for {$lang} generated.");
    }

    protected function generateCategoriesSitemap($lang, $sitemapIndex)
    {
        $sitemap = Sitemap::create();
        $categories = Category::get();

        foreach ($categories as $category) {
            $sitemap->add(
                Url::create($this->baseUrl.$lang.'/cars-rental-dubai/categories/'.$category->slug)
                    ->setPriority(0.7)
                    ->setChangeFrequency('weekly')
                    ->setLastModificationDate($category->updated_at)
            );
        }

        $filename = "{$lang}/sitemap-category.xml";
        $sitemap->writeToFile(public_path($filename));
        $sitemapIndex->add($this->baseUrl . $filename);
        
        $this->info("Categories sitemap for {$lang} generated.");
    }

    protected function generateBrandsSitemap($lang, $sitemapIndex)
    {
        $sitemap = Sitemap::create();
        $brands = Brand::all();

        foreach ($brands as $brand) {
            $sitemap->add(
                Url::create($this->baseUrl.$lang.'/cars-rental-dubai/brands/'.$brand->slug)
                    ->setPriority(0.7)
                    ->setChangeFrequency('weekly')
                    ->setLastModificationDate($brand->updated_at)
            );
        }

        $filename = "{$lang}/sitemap-brand.xml";
        $sitemap->writeToFile(public_path($filename));
        $sitemapIndex->add($this->baseUrl . $filename);
        
        $this->info("Brands sitemap for {$lang} generated.");
    }

    protected function generateCarsSitemap($lang, $sitemapIndex)
    {
        $sitemap = Sitemap::create();
        $cars = Car::get();

        foreach ($cars as $car) {
            $sitemap->add(
                Url::create($this->baseUrl.$lang.'/'.$car->slug)
                    ->setPriority(0.9)
                    ->setChangeFrequency('weekly')
                    ->setLastModificationDate($car->updated_at)
            );
        }

        $filename = "{$lang}/sitemap-product.xml";
        $sitemap->writeToFile(public_path($filename));
        $sitemapIndex->add($this->baseUrl . $filename);
        
        $this->info("Cars sitemap for {$lang} generated.");
    }

    protected function generateLocationsSitemap($lang, $sitemapIndex)
    {
        $sitemap = Sitemap::create();
        $locations = Location::get();

        foreach ($locations as $location) {
            $sitemap->add(
                Url::create($this->baseUrl.$lang.'/cars-rental-dubai/locations/'.$location->slug)
                    ->setPriority(0.8)
                    ->setChangeFrequency('weekly')
                    ->setLastModificationDate($location->updated_at)
            );
        }

        $filename = "{$lang}/sitemap-location.xml";
        $sitemap->writeToFile(public_path($filename));
        $sitemapIndex->add($this->baseUrl . $filename);
        
        $this->info("Locations sitemap for {$lang} generated.");
    }

    protected function generateBlogsSitemap($lang, $sitemapIndex)
    {
        $sitemap = Sitemap::create();
        $blogs = Blog::get();

        foreach ($blogs as $blog) {
            $sitemap->add(
                Url::create($this->baseUrl.$lang.'/cars-rental-dubai/blogs/'.$blog->slug)
                    ->setPriority(0.8)
                    ->setChangeFrequency('weekly')
                    ->setLastModificationDate($blog->updated_at)
            );
        }

        $filename = "{$lang}/sitemap-blog.xml";
        $sitemap->writeToFile(public_path($filename));
        $sitemapIndex->add($this->baseUrl . $filename);
        
        $this->info("Blogs sitemap for {$lang} generated.");
    }
}
