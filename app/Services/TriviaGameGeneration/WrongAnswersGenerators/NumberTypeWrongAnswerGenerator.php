<?php

declare(strict_types=1);

namespace App\Services\TriviaGameGeneration\WrongAnswersGenerators;

class NumberTypeWrongAnswerGenerator extends WrongAnswerGeneratorAbstract
{
    /**
     * Replace all digits in number to random
     * @param float $correctAnswer
     */
    public function generateWrongAnswer($correctAnswer): string
    {
        $correctAnswerString = strval($correctAnswer);

        do {
            $result = floatval(
                preg_replace_callback(
                    '|\d|',
                    fn() => $this->faker->randomDigit(),
                    $correctAnswerString
                )
            );
        } while ($result === $correctAnswerString);

        return (string)$result;
    }
}