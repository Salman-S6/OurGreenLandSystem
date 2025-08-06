<?php

namespace Modules\Extension\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\Extension\Models\AgriculturalGuidance;
use Modules\Extension\Models\Answer;
use Modules\Extension\Models\Question;
use Modules\Extension\Policies\AgriculturalGuidancePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        AgriculturalGuidance::class => AgriculturalGuidancePolicy::class,
        Question::class => QuestionPolicy::class,
        Answer::class => AnswerPolicy::class
    ];

    /**
     * Register the service provider.
     */
    public function register(): void {
        parent::register();
    }

    public function boot(): void
    {
        $this->registerPolicies();
    }


}
