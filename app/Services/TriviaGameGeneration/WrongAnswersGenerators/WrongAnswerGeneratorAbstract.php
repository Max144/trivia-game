<?php

declare(strict_types=1);

namespace App\Services\TriviaGameGeneration\WrongAnswersGenerators;

use Illuminate\Foundation\Testing\WithFaker;

abstract class WrongAnswerGeneratorAbstract
{
    use WithFaker;

    public function __construct()
    {
        $this->setUpFaker();
    }

    public function generateWrongAnswersArray($correctAnswer, int $answersCount): array
    {
        $answers = [];

        do {
            $newAnswer = $this->generateWrongAnswer($correctAnswer);

            if (!in_array($newAnswer, $answers)) {
                $answers[] = $newAnswer;
            }
        } while (count($answers) < $answersCount);

        return $answers;
    }

    abstract public function generateWrongAnswer($correctAnswer): string;
}
