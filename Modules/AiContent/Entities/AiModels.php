<?php

namespace Modules\AiContent\Entities;

final class AiModels
{
    const OPEN_AI_MODELS = [
        'gpt-3.5-turbo-instruct' => 'Turbo Instruct'
    ];

    const AI_TONES = [
        'funny' => 'Funny',
        'casual' => 'Casual',
        'excited' => 'Excited',
        'professional' => 'Professional',
        'witty' => 'Witty',
        'sarcastic' => 'Sarcastic',
        'feminine' => 'Feminine',
        'masculine' => 'Masculine',
        'bold' => 'Bold',
        'dramatic' => 'Dramatic',
        'gumpy' => 'Gumpy',
        'secretive' => 'Secretive',
    ];
    const AI_CREATIVITY = [
        '1' => 'High',
        '0.5' => 'Medium',
        '0' => 'Low',
    ];
    const SUPPORTED_LANGUAGES = [
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        // 'de' => 'German',
        'ar' => 'Arabic',
        'bn' => 'Bengali',
    ];
}
