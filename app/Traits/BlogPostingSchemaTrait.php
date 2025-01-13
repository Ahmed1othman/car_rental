<?php

namespace App\Traits;

trait BlogPostingSchemaTrait
{
    protected function getBlogPostingSchema($data = [])
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            '@id' => $data['url'] . '#article',
            'headline' => $data['title'],
            'description' => $data['description'],
            'articleBody' => $data['content'],
            'image' => [
                '@type' => 'ImageObject',
                'url' => $data['image'],
                'contentUrl' => $data['image']
            ],
            'datePublished' => $data['date_published'],
            'dateModified' => $data['date_modified'],
            'author' => [
                '@type' => 'Organization',
                'name' => 'Afandina Car Rental LLC',
                '@id' => config('app.url') . '/#organization'
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Afandina Car Rental LLC',
                '@id' => config('app.url') . '/#organization',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => 'https://admin.afandinacarrental.com/admin/dist/logo/website_logos/black_logo.svg'
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $data['url']
            ],
            'inLanguage' => app()->getLocale() . '-AE',
            'keywords' => $data['keywords'] ?? ''
        ];
    }
}
