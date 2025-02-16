<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class SitemapController extends Controller
{
    /**
     * Generate the sitemap.
     */
    public function generate()
    {
        Artisan::call('sitemap:generate');
        return response()->json(['message' => 'Sitemap generated successfully!']);
    }

    /**
     * Download the sitemap.
     */
    public function download()
    {
        $filePath = public_path('sitemap.xml');

        if (!File::exists($filePath)) {
            return response()->json(['error' => 'Sitemap not found'], 404);
        }

        return Response::download($filePath, 'sitemap.xml');
    }

    /**
     * Notify search engines (Google & Bing).
     */
    public function notify()
    {
        $sitemapUrl = url('/sitemap.xml');

        Http::get("https://www.google.com/ping?sitemap=$sitemapUrl");
        Http::get("https://www.bing.com/ping?sitemap=$sitemapUrl");

        return response()->json(['message' => 'Search engines notified successfully!']);
    }
}
