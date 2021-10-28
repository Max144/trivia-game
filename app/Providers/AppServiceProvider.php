<?php

namespace App\Providers;

use App\Services\TriviaGameGeneration\WrongAnswersGenerators\DateTypeWrongAnswerGenerator;
use App\Services\TriviaGameGeneration\WrongAnswersGenerators\NumberTypeWrongAnswerGenerator;
use App\Services\TriviaGameGeneration\WrongAnswersGenerators\YearTypeWrongAnswerGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(DateTypeWrongAnswerGenerator::class, fn() => new DateTypeWrongAnswerGenerator());
        $this->app->singleton(NumberTypeWrongAnswerGenerator::class, fn() => new NumberTypeWrongAnswerGenerator());
        $this->app->singleton(YearTypeWrongAnswerGenerator::class, fn() => new YearTypeWrongAnswerGenerator());
    }
}
