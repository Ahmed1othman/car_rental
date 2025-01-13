<?php

namespace App\Traits;

trait FAQSchemaTrait
{
    protected function getFAQSchema($seoQuestions)
    {
        $questions = $seoQuestions->map(function($question) {
            return [
                '@type' => 'Question',
                'name' => $question->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $question->answer
                ]
            ];
        })->all();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $questions
        ];
    }
}
