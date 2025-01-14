<?php

namespace App\Traits;

trait FAQSchemaTrait
{
    protected function getFAQSchema($seoQuestions)
    {
        if (!$seoQuestions) {
            return null;
        }

        // Filter valid questions (must have both question and answer)
        $validQuestions = $seoQuestions->filter(function($question) {
            return !empty(trim($question->question)) && !empty(trim($question->answer));
        })->map(function($question) {
            return [
                '@type' => 'Question',
                'name' => trim($question->question),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => trim($question->answer)
                ]
            ];
        })->values()->all();

        // Only return schema if there's at least one valid question
        if (empty($validQuestions)) {
            return null;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $validQuestions
        ];
    }
}
