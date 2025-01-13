<?php

namespace App\Traits;

trait WebPageSchemaTrait
{
    protected function getWebPageSchema($data = [])
    {
        $url = $data['url'] ?? config('app.url');
        $name = $data['name'] ?? 'Car Rental Dubai';
        $description = $data['description'] ?? 'Luxury Car Rental in Dubai - Rent the latest models of luxury cars with Afandina Car Rental LLC';
        $image = $data['image'] ?? 'https://admin.afandinacarrental.com/admin/dist/logo/website_logos/black_logo.svg';
        $dateModified = $data['date_modified'] ?? now()->toIso8601String();
        $datePublished = $data['date_published'] ?? now()->toIso8601String();

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebPage',
                    '@id' => $url,
                    'url' => $url,
                    'name' => $name,
                    'isPartOf' => [
                        '@id' => config('app.url') . '/#website'
                    ],
                    'primaryImageOfPage' => [
                        '@id' => '#primaryimage'
                    ],
                    'image' => [
                        '@id' => '#primaryimage'
                    ],
                    'thumbnailUrl' => $image,
                    'datePublished' => $datePublished,
                    'dateModified' => $dateModified,
                    'description' => $description,
                    'inLanguage' => app()->getLocale() . '-AE'
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => config('app.url') . '/#website',
                    'url' => config('app.url'),
                    'name' => 'Afandina Car Rental',
                    'description' => 'Luxury Car Rental in Dubai - Premium and Sports Cars',
                    'publisher' => [
                        '@id' => config('app.url') . '/#organization'
                    ],
                    'inLanguage' => app()->getLocale() . '-AE'
                ],
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'Organization',
                    'name' => 'Afandina Car Rental LLC',
                    'url' => config('app.url'),
                    'logo' => 'https://admin.afandinacarrental.com/admin/dist/logo/website_logos/black_logo.svg',
                    'sameAs' => [
                        'https://www.facebook.com/afandinacars',
                        'https://www.instagram.com/carrentaln/',
                        'https://maps.app.goo.gl/UzNNvHCWiDtGew6b9'
                    ]
                ]
            ]
        ];
    }
}
