<?php

namespace App\Traits;

trait FAQSchemaTrait
{
    protected function getFAQSchema($seoQuestions)
    {
        \Log::info('FAQ Schema Input:', ['questions' => $seoQuestions]);
        
        if (!$seoQuestions || ($seoQuestions instanceof \Illuminate\Support\Collection && $seoQuestions->isEmpty())) {
            \Log::info('No SEO questions found');
            return null;
        }

        // Ensure we're working with a collection
        if (!($seoQuestions instanceof \Illuminate\Support\Collection)) {
            $seoQuestions = collect($seoQuestions);
        }

        // Filter valid questions (must have both question and answer)
        $validQuestions = $seoQuestions->filter(function($question) {
            $isValid = !empty(trim($question->question_text)) && !empty(trim($question->answer_text));
            if (!$isValid) {
                \Log::info('Invalid question found:', ['question' => $question]);
            }
            return $isValid;
        })->map(function($question) {
            return [
                '@type' => 'Question',
                'name' => trim($question->question_text),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => trim($question->answer_text)
                ]
            ];
        })->values()->all();

        \Log::info('Valid Questions:', ['count' => count($validQuestions)]);

        // Only return schema if there's at least one valid question
        if (empty($validQuestions)) {
            \Log::info('No valid questions found after filtering');
            return null;
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $validQuestions
        ];

        \Log::info('Generated FAQ Schema:', ['schema' => $schema]);
        return $schema;
    }
}