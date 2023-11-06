<?php

declare(strict_types=1);

namespace App\Services\TriviaGameGeneration;

use App\Exceptions\TriviaGameApiWrongTypeException;
use App\Models\Question;
use App\Models\TriviaGame;
use App\Services\API\NumbersApi\NumbersApiClient;
use App\Services\API\NumbersApi\NumbersApiQuestionTypes\NumbersApiQuestionAbstract;

class TriviaGameGeneratorService
{
    private TriviaGame $triviaGame;

    /**
     * @return TriviaGame
     */
    public function getTriviaGame(): TriviaGame
    {
        return $this->triviaGame;
    }

    private NumbersApiClient $numbersApiClient;

    public function __construct(?TriviaGame $triviaGame = null)
    {
        $this->numbersApiClient = resolve(NumbersApiClient::class);

        if (empty($triviaGame)) {
            $triviaGame = TriviaGame::query()->create();
        }

        $this->triviaGame = $triviaGame;
    }

    /**
     * @return ?Question
     * @throws TriviaGameApiWrongTypeException
     */
    public function getNextQuestion(): ?Question
    {
        if ($this->triviaGame->is_finished) {
            return null;
        }

        $currentQuestion = $this->triviaGame->currentQuestion;
        if (empty($currentQuestion)) {
            $currentQuestion = $this->generateUniqueQuestionForTrivia();
        }

        return $currentQuestion;
    }

    /**
     * @param array|null $questionTypes
     * @param null $wrongResultsCount
     * @return Question
     * @throws TriviaGameApiWrongTypeException
     */
    public function generateUniqueQuestionForTrivia(?array $questionTypes = null, $wrongResultsCount = null): Question
    {
        do {
            $newQuestion = $this->numbersApiClient->getNewRandomTypeQuestion($questionTypes);
        } while (
            $this->triviaGame->questions()
                ->where('text', $newQuestion->getQuestion())
                ->exists()
        );

        $newQuestion->generateWrongAnswers($wrongResultsCount);
        return $this->generateQuestionFromQuestionObject($newQuestion);
    }

    protected function generateQuestionFromQuestionObject(
        NumbersApiQuestionAbstract $questionObject
    ): Question {
        $lastQuestionOrderNumber = $this->triviaGame->questions()->max('order_number') ?? 0;
        $question = $this->triviaGame->questions()->create(
            [
                'text' => $questionObject->getQuestion(),
                'order_number' => $lastQuestionOrderNumber + 1,
            ]
        );

        $answers = $questionObject->getWrongAnswers();
        $answers[] = $questionObject->getAnswerString();
        shuffle($answers);

        $answersModels = $question->answers()->createMany(array_map(fn($answer) => ['text' => $answer], $answers));
        $question->update(
            ['right_answer_id' => $answersModels->where('text', $questionObject->getAnswerString())->first()->id]
        );

        return $question;
    }
}
