<?php
// app/Console/Commands/FetchCarModels.php
namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Car_model;
use App\Models\Language;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class FetchCarModels extends Command
{
    protected $signature = 'fetch:car-models';
    protected $description = 'Fetch car models, translate, and store in the database';
    private $yearRange = [2015, 2025];
    private $carQueryApi = 'https://www.carqueryapi.com/api/0.3/';
    private $locales ;

    public function handle()
    {
        $this->locales = Language::all()->pluck('code')->toArray();

        $brandsss = [];
        $existingBrands = DB::table('brands')
            ->join('brand_translations', 'brands.id', '=', 'brand_translations.brand_id')
            ->where('brand_translations.locale', 'en')
            ->pluck('brand_translations.name')
            ->toArray();
        $brands = $this->fetchBrands();

        foreach ($brands as $brand) {
            if (in_array($brand['make_display'], $existingBrands)) {
                $currentBrand = Brand::wherehas('translations', function ($query) use ($brand) {
                    $query->where('locale', 'en')
                        ->where('name',$brand['make_display']);
                })->first();

                $models = $this->fetchModels($brand['make_id']);

                foreach ($models as $model) {
                    $existModel = Car_model::wherehas('translations', function ($query) use ($model) {
                        $query->where('locale', 'en')
                            ->where('name',$model['model_name']);
                    })->first();
                    if($existModel)
                        continue;
                    $carModelId = DB::table('car_models')->insertGetId([
                        'brand_id' => $currentBrand->id,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                ]);

                foreach ($this->locales as $locale) {
                    $translatedName = $this->translateText($model['model_name'] ?? 'undefined', $locale);

                    do{
                        $random_int = random_int(1, 99999);
                        $slug = Str::slug($translatedName . '-' . $locale . '-' . $random_int);
                        $exists = DB::table('car_model_translations')
                            ->where('slug', $slug)
                            ->first();
                    }while($exists);


                    DB::table('car_model_translations')->insert([
                        'car_model_id' => $carModelId,
                        'name' => $translatedName,
                        'locale' => $locale,
                        'slug' => $slug,
                        'meta_title' => "$translatedName - {$brand['make_display']}",
                        'meta_description' => "Information about $translatedName in $locale.",
                        'meta_keywords' => "$translatedName, {$brand['make_display']}, $locale",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            }


        }


        $this->info('Car models fetched and stored successfully.');
    }

    private function fetchBrands()
    {
        $response = file_get_contents("{$this->carQueryApi}?cmd=getMakes");
        return json_decode($response, true)['Makes'];
    }

    private function fetchModels($brand)
    {
        $models = [];
        for ($year = $this->yearRange[0]; $year <= $this->yearRange[1]; $year++) {


            $response = file_get_contents("{$this->carQueryApi}?cmd=getModels&make=$brand&year=$year");
            $yearModels = json_decode($response, true)['Models'] ?? [];
            $models = array_merge($models, $yearModels); // Combine models for all years
        }

        return $models;
    }

    private function translateText($text, $locale)
    {
        try {
            $translator = new GoogleTranslate($locale);
            return $translator->translate($text);
        } catch (\Exception $e) {
            $this->error("Translation failed for $text: " . $e->getMessage());
            return $text; // Fallback to the original text
        }
    }
}

