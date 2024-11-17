<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\StaticTranslation;
use Illuminate\Http\Request;

class StaticTranslationController extends Controller
{
    public function index()
    {
        // Fetch active languages
        $activeLanguages = Language::where('is_active', true)->pluck('code')->toArray();

        // Fetch translations that match active languages
        $staticTranslations = StaticTranslation::whereIn('locale', $activeLanguages)->get();

        return view('pages.admin.static_translation.index', [
            'staticTranslations' => $staticTranslations,
            'activeLanguages' => Language::where('is_active', true)->get(),
        ]);
    }



    public function saveTranslations(Request $request)
    {
        $translations = $request->input('translations');
        foreach ($translations as $translation) {
            // Check if a translation with the same key and locale exists
            $existingTranslation = StaticTranslation::where('key', $translation['key'])
                ->where('locale', $translation['locale'])
                ->where('section', $translation['section'])
                ->first();

            if ($existingTranslation) {
                // Update the existing translation if found
                $existingTranslation->update([
                    'value' => $translation['value'],
                    'section' => $translation['section'],
                    'updated_at' => now(),
                ]);
            } else {
                // Create a new translation record if not found
                StaticTranslation::create([
                    'key' => $translation['key'],
                    'locale' => $translation['locale'],
                    'value' => $translation['value'],
                    'section' => $translation['section'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return response()->json(['success' => true]);
    }



}
