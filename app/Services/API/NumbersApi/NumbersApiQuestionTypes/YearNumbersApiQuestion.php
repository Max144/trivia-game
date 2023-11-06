<?php

declare(strict_types=1);

namespace App\Services\API\NumbersApi\NumbersApiQuestionTypes;

use App\Services\TriviaGameGeneration\WrongAnswersGenerators\YearTypeWrongAnswerGenerator;

class YearNumbersApiQuestion extends NumbersApiQuestionAbstract
{
    /**
     * @var int $answer
     */
    protected const WRONG_ANSWER_GENERATOR_CLASS = YearTypeWrongAnswerGenerator::class;
}
