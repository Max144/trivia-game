<?php

declare(strict_types=1);

namespace App\Services\TriviaGameGeneration;

use App\Exceptions\TriviaGameNotCurrentQuestionException;
use App\Exceptions\TriviaGameApiWrongTypeException;
use App\Models\Question;
use App\Models\TriviaGame;
use App\Services\API\NumbersApi\NumbersApiClient;
use App\Services\API\NumbersApi\NumbersApiQuestionTypes\NumbersApiQuestionAbstract;

class TriviaGameGeneratorService
{
    private NumbersApiClient $numbersApiClient;

    public function __construct(NumbersApiClient $numbersApiClient)
    {
        $this->numbersApiClient = $numbersApiClient;
    }

    /**
     * @param int $questionsCount
     * @param array|null $questionTypes
     * @param null $wrongResultsCount
     * @return TriviaGame
     * @throws TriviaGameNotCurrentQuestionException
     * @throws TriviaGameApiWrongTypeException
     */
    public function generate(
        int $questionsCount = 20,
        ?array $questionTypes = null,
        $wrongResultsCount = null
    ): TriviaGame {
        /**
         * @var TriviaGame $triviaGame
         */
        $triviaGame = TriviaGame::create();

        $questionObjects = $this->getUniqueQuestionsForTrivia($questionsCount, $questionTypes, $wrongResultsCount);
        $this->generateQuestionsForTrivia($triviaGame, $questionObjects);

        return $triviaGame;
    }

    /**
     * @param int $questionsCount
     * @param array|null $questionTypes
     * @param null $wrongResultsCount
     * @return NumbersApiQuestionAbstract[]
     * @throws TriviaGameNotCurrentQuestionException
     * @throws TriviaGameApiWrongTypeException
     */
    public function getUniqueQuestionsForTrivia(
        int $questionsCount = 20,
        ?array $questionTypes = null,
        $wrongResultsCount = null
    ): array {
        /**
         * @var NumbersApiQuestionAbstract[] $questionObjects
         */
        $questionObjects = [];

        do {
            $newQuestion = $this->numbersApiClient->getNewRandomTypeQuestion($questionTypes);

            foreach ($questionObjects as $storedQuestion) {
                // if the same question exists - take next
                if ($newQuestion->getQuestion() === $storedQuestion->getQuestion()) {
                    continue 2;
                }
            }

            $questionObjects[] = $newQuestion;
            $newQuestion->generateWrongAnswers($wrongResultsCount);
        } while (count($questionObjects) < $questionsCount);

        return $questionObjects;
    }

    /**
     * @param TriviaGame $triviaGame
     * @param NumbersApiQuestionAbstract[] $questionObjects
     */
    protected function generateQuestionsForTrivia(TriviaGame $triviaGame, array $questionObjects)
    {
        foreach ($questionObjects as $key => $questionObject) {
            $this->generateQuestionFromQuestionObject($triviaGame, $questionObject, $key + 1);
        }
    }

    protected function generateQuestionFromQuestionObject(
        TriviaGame $triviaGame,
        NumbersApiQuestionAbstract $questionObject,
        int $questionOrderNumber
    ) {
        $question = $triviaGame->questions()->create(
            [
                'text' => $questionObject->getQuestion(),
                'order_number' => $questionOrderNumber,
            ]
        );

        $answers = $questionObject->getWrongAnswers();
        $answers[] = $questionObject->getAnswerString();
        shuffle($answers);

        $answersModels = $question->answers()->createMany(array_map(fn($answer) => ['text' => $answer], $answers));
        $question->update(
            ['right_answer_id' => $answersModels->where('text', $questionObject->getAnswerString())->first()->id]
        );
    }
}