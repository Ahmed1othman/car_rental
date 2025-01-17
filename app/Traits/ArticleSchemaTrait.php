<?php

namespace App\Traits;

trait ArticleSchemaTrait
{
    protected function getArticleSchema($data = [])
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            '@id' => $data['url'] . '#article',
            'headline' => $data['title'],
            'description' => $data['description'],
            'articleBody' => $data['content'],
            'wordCount' => str_word_count(strip_tags($data['content'])),
            'image' => [
                '@type' => 'ImageObject',
                'url' => $data['image'],
                'height' => 675,
                'width' => 1200
            ],
            'datePublished' => $data['date_published'],
            'dateModified' => $data['date_modified'],
            'author' => [
                '@type' => 'Organization',
                'name' => 'Afandina Car Rental LLC',
                '@id' => config('app.url') . '/#organization',
                'url' => config('app.url'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => 'https://admin.afandinacarrental.com/admin/dist/logo/website_logos/black_logo.svg',
                    'width' => 300,
                    'height' => 300
                ],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => '+971 50 551 2025',
                    'contactType' => 'customer service',
                    'areaServed' => 'AE',
                    'availableLanguage' => ['English', 'Arabic', 'French']
                ]
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Afandina Car Rental LLC',
                '@id' => config('app.url') . '/#organization',
                'url' => config('app.url'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => 'https://admin.afandinacarrental.com/admin/dist/logo/website_logos/black_logo.svg',
                    'width' => 300,
                    'height' => 300
                ],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => '+971 50 551 2025',
                    'contactType' => 'customer service',
                    'areaServed' => 'AE',
                    'availableLanguage' => ['English', 'Arabic', 'French']
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $data['url']
            ],
            'inLanguage' => app()->getLocale() . '-AE',
            'keywords' => $data['keywords'] ?? '',
            'articleSection' => $data['section'] ?? 'Car Rental',
            'about' => [
                '@type' => 'Thing',
                'name' => 'Luxury Car Rental Dubai',
                'sameAs' => config('app.url')
            ]
        ];
    }
}
