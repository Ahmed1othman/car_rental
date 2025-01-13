<?php
namespace App\Http\Resources;

use App\Models\Car;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Currency;
use App\Traits\OrganizationSchemaTrait;
use App\Traits\BreadcrumbSchemaTrait;
use App\Traits\WebPageSchemaTrait;
use App\Traits\FAQSchemaTrait;

class DetailedCarResource extends JsonResource
{
    use OrganizationSchemaTrait, WebPageSchemaTrait, BreadcrumbSchemaTrait, FAQSchemaTrait;

    public function toArray($request)
    {
        $base_url = asset('storage/');
        // Cache currency exchange rate and locale
        $currencyExchangeRate = Currency::find(app('currency_id'))->exchange_rate ?? 1;
        $locale = app()->getLocale() ?? 'en';
        $currency = \App\Models\Currency::find(app('currency_id'));
        $currencyLanguage = $currency->translations->where('locale', $locale)->first();

        $carCategory = Car::where('category_id', $this->category_id)
            ->whereNot('id', $this->id)
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Retrieve translation data for the current locale
        $translation = $this->translations->where('locale', $locale)->first();
        $gearTypeName = $this->gearType->translations->where('locale', $locale)->first()->name ?? null;
        $colorTranslation = $this->color->translations->where('locale', $locale)->first();
        $brandName = $this->brand->translations->where('locale', $locale)->first()->name ?? null;
        $carModel = $this->carModel ? $this->carModel->translations->where('locale', $locale)->first(): null;
        $categoryName = $this->category->translations->where('locale', $locale)->first()->name ?? null;


        // Calculate and round prices
        $prices = [
            'daily_main_price' => ceil($this->daily_main_price * $currencyExchangeRate),
            'daily_discount_price' => ceil($this->daily_discount_price * $currencyExchangeRate),
            'weekly_main_price' => ceil($this->weekly_main_price * $currencyExchangeRate),
            'weekly_discount_price' => ceil($this->weekly_discount_price * $currencyExchangeRate),
            'monthly_main_price' => ceil($this->monthly_main_price * $currencyExchangeRate),
            'monthly_discount_price' => ceil($this->monthly_discount_price * $currencyExchangeRate),
        ];

        // Decode and format meta keywords if they exist
        $metaKeywordsArray = $translation && $translation->meta_keywords ? json_decode($translation->meta_keywords, true) : null;
        $metaKeywords = $metaKeywordsArray ? implode(', ', array_column($metaKeywordsArray, 'value')) : null;

        $seoQuestions = $this->seoQuestions->where('locale',$locale);
        $seoQuestionSchema = $this->getFAQSchema($seoQuestions);
        return array_merge($prices, [
            'id' => $this->id,
            'door_count' => $this->door_count,
            'luggage_capacity' => $this->luggage_capacity,
            'passenger_capacity' => $this->passenger_capacity,
            'insurance_included' => $this->insurance_included,
            'free_delivery' => $this->free_delivery,

            'crypto_payment_accepted' => $this->crypto_payment_accepted,
            'daily_mileage_included'=> $this->daily_mileage_included,
            'monthly_mileage_included'=> $this->monthly_mileage_included,
            'weakly_mileage_included'=> $this->weakly_mileage_included,
            'currency'=>[
                'name' => $currencyLanguage->name,
                'code' => $currency->code,
                'symbol' => $currency->symbol??null
            ],
            'year' => $this->year->year??null,
            'is_featured' => $this->is_featured,
            'is_flash_sale' => $this->is_flash_sale,
            'status' => $this->status,
            'gear_type' => $gearTypeName,
            'color' => [
                'name' => $colorTranslation->name ?? null,
                'code' => $this->color->color_code ?? null,
            ],
            'brand' => $brandName,
            'car_model' => $carModel ? $carModel->name : null,
            'category' => $categoryName,
            'default_image_path' => $this->default_image_path,
            'slug' => $this->slug ?? null,
            'name' => $translation->name ?? null,
            "description"=> $translation->description?? null,
            "long_description"=> $translation->long_description?? null,
            'images' => collect([
                [
                    'file_path' => $this->default_image_path,
                    'alt' => 'Default Image',  // You can customize this
                    'type' => 'image',         // Default type
                ],
            ])->concat(
                $this->images->map(fn($image) => [
                    'file_path' => $image->file_path,
                    'alt' => $image->alt,
                    'type' => $image->type,
                ])
            ),
            "car_features"=> FeatureResource::collection($this->features),
            'related_cars'=> CarResource::collection($carCategory),
            'seo_data' => [
                'seo_title' => $translation->meta_title ?? null,
                'seo_description' => $translation->meta_description ?? null,
                'seo_keywords' => $metaKeywords,
                'seo_robots' => [
                    'index'=>$translation->robots_index?? 'noindex',
                    'follow'=>$translation->robots_follow?? 'nofollow',
                ],
                'seo_image' => $base_url.$this->default_image_path?? null,
                'seo_image_alt' => $translation->meta_title?? null,
                'schemas'=>array_filter([
                    'faq_schema'=> $this->getFAQSchema($seoQuestions),
                    'product_schema'=>$this->productSchema($translation, $brandName, $prices, $currency),
                    'organization_schema' => $this->getOrganizationSchema(),
                    'local_business_schema' => $this->getLocalBusinessSchema(
                        asset('storage/' . $this->image_path),
                        'AED ' . $prices['daily_discount_price'] . ' per day'
                    ),
                    'breadcrumb_schema' => $this->getBreadcrumbSchema([
                        [
                            'url' => config('app.url') . "/{$locale}/home",
                            'name' => __('messages.home')
                        ],
                        [
                            'url' => config('app.url') . "/{$locale}/product/filter",
                            'name' => __('messages.cars')
                        ],
                        [
                            'url' => config('app.url') . "/{$locale}/product/car/{$this->slug}",
                            'name' => $translation->name
                        ]
                    ]),
                    'webpage_schema' => $this->getWebPageSchema([
                        'url' => config('app.url') . "/{$locale}/product/car/{$this->slug}",
                        'name' => $translation->name,
                        'description' => $translation->meta_description,
                        'image' => asset('storage/' . $this->image_path),
                        'date_modified' => $this->updated_at->toIso8601String(),
                        'date_published' => $this->created_at->toIso8601String(),
                    ]),
                ]),
            ],
            'no_deposit' => $this->no_debosite??1,
            'discount_rate' => ceil(($this->daily_main_price - $this->daily_discount_price) * 100 / $this->daily_main_price),
        ]);
    }

    public function productSchema($translation, $brandName, array $prices, $currency): array
    {
        return [
            "@context" => "https://schema.org",
            "@type" => "Product",
            "name" => $translation->name ?? null,
            "image" => $this->default_image_path,
            "description" => $translation->description ?? null,
            "brand" => [
                "@type" => "Brand",
                "name" => $brandName
            ],
            "offers" => [
                [
                    "@type" => "Offer",
                    "price" => $prices['daily_discount_price'] ?? $prices['daily_main_price'],
                    "priceCurrency" => $currency->code,
                    "availability" => 'available',
                ],
            ]
        ];
    }
}
