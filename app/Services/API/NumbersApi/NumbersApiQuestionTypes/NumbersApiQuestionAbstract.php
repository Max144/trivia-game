<?php

declare(strict_types=1);

namespace App\Services\API\NumbersApi\NumbersApiQuestionTypes;

use App\Exceptions\TriviaGameNotCurrentQuestionException;
use App\Services\TriviaGameGeneration\WrongAnswersGenerators\NumberTypeWrongAnswerGenerator;
use App\Services\TriviaGameGeneration\WrongAnswersGenerators\WrongAnswerGeneratorAbstract;

abstract class NumbersApiQuestionAbstract
{
    protected array $wrongAnswers;
    protected const WRONG_ANSWER_GENERATOR_CLASS = NumberTypeWrongAnswerGenerator::class;
    protected const DEFAULT_WRONG_ANSWERS_COUNT = 3;
    protected WrongAnswerGeneratorAbstract $wrongAnswerGenerator;

    protected string $question;
    /**
     * @var mixed $answer
     */
    protected $answer;

    /**
     * @param object $response
     * @throws TriviaGameNotCurrentQuestionException
     */
    public function __construct(object $response)
    {
        if (!$response->found) {
            throw new TriviaGameNotCurrentQuestionException();
        }
        $this->setQuestionAndAnswerFromResponse($response);
    }

    protected function setQuestionAndAnswerFromResponse(object $response)
    {
        $this->answer = $response->number;
        $this->question = $response->text;
    }

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

    public function getAnswerString(): string
    {
        return (string) $this->answer;
    }

    public function getWrongAnswers(): array
    {
        return $this->wrongAnswers;
    }

    public function getWrongAnswersGeneratorClassInstance(): WrongAnswerGeneratorAbstract
    {
        if (!isset($this->wrongAnswerGenerator)) {
            $this->wrongAnswerGenerator = resolve(static::WRONG_ANSWER_GENERATOR_CLASS);
        }

        return $this->wrongAnswerGenerator;
    }

    public function generateWrongAnswers(?int $count = null)
    {
        if (!isset($count)) {
            $count = self::DEFAULT_WRONG_ANSWERS_COUNT;
        }
        $wrongAnswerGenerator = $this->getWrongAnswersGeneratorClassInstance();

        $this->wrongAnswers = $wrongAnswerGenerator->generateWrongAnswersArray($this->getAnswer(), $count);
    }
}
