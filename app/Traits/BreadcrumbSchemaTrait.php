<?php

namespace App\Traits;

trait BreadcrumbSchemaTrait
{
    protected function getBreadcrumbSchema($items)
    {
        return [
            '@context' => 'http://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($items)->map(function ($item, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => [
                        '@id' => $item['url'],
                        'name' => $item['name']
                    ]
                ];
            })->values()->all()
        ];
    }

    protected function getDefaultBreadcrumbs()
    {
        return [
            [
                'url' => config('app.url'),
                'name' => 'Car Rental Dubai'
            ]
        ];
    }
}
