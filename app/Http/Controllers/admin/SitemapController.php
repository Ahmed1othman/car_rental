<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Models\Language;

class SitemapController extends Controller
{
    /**
     * Generate the sitemap.
     */
    public function generate()
    {
        // Create language directories if they don't exist
        $languages = Language::where('is_active', 1)->pluck('code');
        foreach ($languages as $lang) {
            $langDir = public_path($lang);
            if (!File::exists($langDir)) {
                File::makeDirectory($langDir, 0755, true);
            }
        }

        Artisan::call('sitemap:generate');
        return response()->json(['message' => 'Sitemap index and all sitemaps generated successfully!']);
    }

    /**
     * Download the sitemap index.
     */
    public function download()
    {
        $filePath = public_path('sitemap.xml');

        if (!File::exists($filePath)) {
            return response()->json(['error' => 'Sitemap index not found'], 404);
        }

        return Response::download($filePath, 'sitemap.xml');
    }

    /**
     * Download a specific sitemap file.
     */
    public function downloadSpecific($lang, $type)
    {
        $filePath = public_path("$lang/sitemap-$type.xml");

        if (!File::exists($filePath)) {
            return response()->json(['error' => "Sitemap file not found: $lang/sitemap-$type.xml"], 404);
        }

        return Response::download($filePath, "sitemap-$lang-$type.xml");
    }

    /**
     * List all available sitemaps.
     */
    public function list()
    {
        $sitemaps = ['sitemap.xml' => url('/sitemap.xml')];
        $languages = Language::where('is_active', 1)->pluck('code');
        
        foreach ($languages as $lang) {
            $langDir = public_path($lang);
            if (File::exists($langDir)) {
                $files = File::files($langDir);
                foreach ($files as $file) {
                    $filename = $file->getFilename();
                    if (strpos($filename, 'sitemap-') === 0 && strpos($filename, '.xml') !== false) {
                        $sitemaps["$lang/$filename"] = url("/$lang/$filename");
                    }
                }
            }
        }
        
        return response()->json(['sitemaps' => $sitemaps]);
    }

    /**
     * Notify search engines (Google & Bing) about the sitemap index.
     */
    public function notify()
    {
        $sitemapUrl = url('/sitemap.xml');

        $googleResponse = Http::get("https://www.google.com/ping?sitemap=$sitemapUrl");
        $bingResponse = Http::get("https://www.bing.com/ping?sitemap=$sitemapUrl");

        return response()->json([
            'message' => 'Search engines notified successfully!',
            'google_status' => $googleResponse->status(),
            'bing_status' => $bingResponse->status()
        ]);
    }
}
