<?php

declare(strict_types=1);

namespace App\Services\API\NumbersApi\NumbersApiQuestionTypes;

use App\Exceptions\TriviaApiParseException;

abstract class NumbersApiQuestionAbstract
{
    protected string $question;
    /**
     * @var mixed $answer
     */
    protected $answer;

    /**
     * @throws TriviaApiParseException
     */
    public function __construct(object $response)
    {
        if (!$response->found) {
            throw new TriviaApiParseException();
        }
        $this->setQuestionAndAnswerFromResponse($response);
    }

    protected function setQuestionAndAnswerFromResponse(object $response)
    {
        $this->answer = $response->number;
        $this->question = $response->text;
    }

    /**
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}