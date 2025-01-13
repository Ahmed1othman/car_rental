<?php

namespace App\Traits;

trait OrganizationSchemaTrait
{
    protected function getOrganizationSchema()
    {
        return [
            '@context' => 'http://www.schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    'name' => 'Afandina car rental LLC',
                    'url' => 'https://afandinacarrental.com/',
                    'sameAs' => [
                        'https://www.facebook.com/afandinacars',
                        'https://www.instagram.com/carrentaln/',
                        'https://maps.app.goo.gl/UzNNvHCWiDtGew6b9'
                    ],
                    'logo' => 'https://admin.afandinacarrental.com/admin/dist/logo/website_logos/black_logo.svg',
                    'image' => 'https://admin.afandinacarrental.com/admin/dist/logo/website_logos/black_logo.svg',
                    'description' => 'Afandina car rental LLC',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => '79H2+CQC - Deira - Dubai',
                        'addressLocality' => 'Dubai',
                        'addressRegion' => 'Dubai',
                        'addressCountry' => 'United Arab Emirates'
                    ],
                    'geo' => [
                        '@type' => 'GeoCoordinates',
                        'latitude' => '25.2788198',
                        'longitude' => '55.3516818'
                    ],
                    'hasMap' => 'https://maps.app.goo.gl/UzNNvHCWiDtGew6b9',
                    'telephone' => '+971 50 551 2025'
                ],
                [
                    '@type' => 'LocalBusiness',
                    'name' => 'Afandina car rental LLC',
                    'url' => 'https://afandinacarrental.com/',
                    'sameAs' => [
                        'https://www.facebook.com/afandinacars',
                        'https://www.instagram.com/carrentaln/',
                        'https://maps.app.goo.gl/UzNNvHCWiDtGew6b9'
                    ],
                    'logo' => 'https://admin.afandinacarrental.com/admin/dist/logo/website_logos/black_logo.svg',
                    'image' => 'https://admin.afandinacarrental.com/admin/dist/logo/website_logos/black_logo.svg',
                    'description' => 'Afandina car rental LLC',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => '79H2+CQC - Deira - Dubai',
                        'addressLocality' => 'Dubai',
                        'addressRegion' => 'Dubai',
                        'addressCountry' => 'United Arab Emirates'
                    ],
                    'geo' => [
                        '@type' => 'GeoCoordinates',
                        'latitude' => '25.2788198',
                        'longitude' => '55.3516818'
                    ],
                    'hasMap' => 'https://maps.app.goo.gl/UzNNvHCWiDtGew6b9',
                    'telephone' => '+971 50 551 2025'
                ],
                [
                    '@type' => 'ImageObject',
                    '@id' => 'https://afandinacarrental.com//#primaryimage',
                    'inLanguage' => 'en-AE',
                    'url' => 'https://admin.afandinacarrental.com/admin/dist/logo/website_logos/black_logo.svg',
                    'width' => 300,
                    'height' => 300,
                    'caption' => 'Afandina Luxury Car Rental Dubai'
                ]
            ]
        ];
    }

    protected function getLocalBusinessSchema($carImage = null, $priceRange = null)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => 'Afandina Car Rental LLC',
            'image' => $carImage ?? 'https://admin.afandinacarrental.com/admin/dist/logo/website_logos/black_logo.svg',
            '@id' => 'https://afandinacarrental.com/',
            'url' => 'https://afandinacarrental.com/',
            'telephone' => '+971 50 551 2025',
            'priceRange' => $priceRange ?? 'AED 150 - AED 5000 per day',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => '79H2+CQC - Deira - Dubai',
                'addressLocality' => 'Dubai',
                'addressRegion' => 'Dubai',
                'addressCountry' => 'United Arab Emirates'
            ]
        ];
    }
}
