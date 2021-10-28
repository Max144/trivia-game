<?php

declare(strict_types=1);

namespace App\Services\TriviaGameGeneration\WrongAnswersGenerators;

use Carbon\Carbon;

class DateTypeWrongAnswerGenerator extends WrongAnswerGeneratorAbstract
{
    /**
     * @param Carbon $correctAnswer
     */
    public function generateWrongAnswer($correctAnswer): string
    {

        do {
            // get date time +- 10 years from correct
            $newDate = Carbon::parse(
                $this->faker->dateTimeBetween(
                    $correctAnswer->copy()->subYears(10),
                    $correctAnswer->copy()->addYears(10)
                )
            )->startOfDay();
        } while ($newDate->equalTo($correctAnswer));

        return $newDate->toDateString();
    }
}