<?php

namespace App\Enums;


enum AttachableModels: string
{
    case AgriculturalGuidance = \Modules\Extension\Models\AgriculturalGuidance::class;
    case Question = \Modules\Extension\Models\Question::class;
    case Answer = \Modules\Extension\Models\Answer::class;

    /**
     * gives array of classes and thier slugs
     * 
     * @return array
     */
    public static function list(): array
    {
        return [
            'agricultural-guidances' => self::AgriculturalGuidance->value,
            'questions' => self::Question->value,
            'answers'=> self::Answer->value,
        ];
    }

    /**
     * gives array of classes slugs
     * 
     * @return array
     */
    public static function slugs(): array
    {
        return [
            'agricultural-guidances',
            'questions',
            'answers',
        ];
    }
}