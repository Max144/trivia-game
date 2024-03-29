<?php

declare(strict_types=1);

namespace App\Services\API\NumbersApi;

use App\Exceptions\TriviaGameNotCurrentQuestionException;
use App\Exceptions\TriviaGameApiWrongTypeException;
use App\Services\API\NumbersApi\NumbersApiQuestionTypes\DateNumbersApiQuestion;
use App\Services\API\NumbersApi\NumbersApiQuestionTypes\MathNumbersApiQuestion;
use App\Services\API\NumbersApi\NumbersApiQuestionTypes\NumbersApiQuestionAbstract;
use App\Services\API\NumbersApi\NumbersApiQuestionTypes\TriviaNumbersApiQuestion;
use App\Services\API\NumbersApi\NumbersApiQuestionTypes\YearNumbersApiQuestion;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class NumbersApiClient
{
    public const NUMBERS_API_GET_RANDOM_QUESTION_URL = 'http://numbersapi.com/random/';

    public const QUESTION_TYPES = [
        'trivia' => 'trivia',
        'math' => 'math',
        'date' => 'date',
        'year' => 'year',
    ];

    public const QUESTION_TYPES_QUESTION_CLASSES_MAPPER = [
        self::QUESTION_TYPES['trivia'] => TriviaNumbersApiQuestion::class,
        self::QUESTION_TYPES['math'] => MathNumbersApiQuestion::class,
        self::QUESTION_TYPES['date'] => DateNumbersApiQuestion::class,
        self::QUESTION_TYPES['year'] => YearNumbersApiQuestion::class,
    ];

    /**
     * @throws TriviaGameApiWrongTypeException
     */
    protected function getNewQuestionData(string $type)
    {
        if (!in_array($type, self::QUESTION_TYPES)) {
            throw new TriviaGameApiWrongTypeException();
        }

        $url = self::NUMBERS_API_GET_RANDOM_QUESTION_URL . $type . '?json=1&fragment=1';
        $response = Http::get($url);

        return json_decode($response->body());
    }

    /**
     * @throws TriviaGameApiWrongTypeException
     */
    public function getNewQuestion(string $type)
    {
        $questionData = $this->getNewQuestionData($type);

        $questionClassName = self::QUESTION_TYPES_QUESTION_CLASSES_MAPPER[$type];
        return new $questionClassName($questionData);
    }

    /**
     * @throws TriviaGameApiWrongTypeException
     */
    public function getNewTriviaQuestion(): NumbersApiQuestionAbstract
    {
        return $this->getNewQuestion(self::QUESTION_TYPES['trivia']);
    }

    /**
     * @throws TriviaGameApiWrongTypeException
     */
    public function getNewMathQuestion(): NumbersApiQuestionAbstract
    {
        return $this->getNewQuestion(self::QUESTION_TYPES['math']);
    }

    /**
     * @throws TriviaGameApiWrongTypeException
     */
    public function getNewDateQuestion(): NumbersApiQuestionAbstract
    {
        return $this->getNewQuestion(self::QUESTION_TYPES['date']);
    }

    /**
     * @throws TriviaGameApiWrongTypeException
     */
    public function getNewYearQuestion(): NumbersApiQuestionAbstract
    {
        return $this->getNewQuestion(self::QUESTION_TYPES['year']);
    }

    /**
     * @param array|null $allowedTypes
     * @return NumbersApiQuestionAbstract
     * @throws TriviaGameApiWrongTypeException
     */
    public function getNewRandomTypeQuestion(?array $allowedTypes = null): NumbersApiQuestionAbstract
    {
        if (!isset($allowedTypes)) {
            $allowedTypes = self::QUESTION_TYPES;
        }

        return $this->getNewQuestion(Arr::random($allowedTypes));
    }
}
