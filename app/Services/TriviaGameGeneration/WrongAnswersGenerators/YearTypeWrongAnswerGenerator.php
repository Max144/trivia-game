<?php

declare(strict_types=1);

namespace App\Services\TriviaGameGeneration\WrongAnswersGenerators;

class YearTypeWrongAnswerGenerator extends WrongAnswerGeneratorAbstract
{
    /**
     * @param int $correctAnswer
     */
    public function generateWrongAnswer($correctAnswer): string
    {
        do {
            $year = $this->faker->numberBetween($correctAnswer - 10, $correctAnswer + 10);
        } while ($year === $correctAnswer);

        return (string)$year;
    }
}
