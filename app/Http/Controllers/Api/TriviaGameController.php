<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TriviaGameIsFinishedException;
use App\Exceptions\TriviaGameNotCurrentQuestionException;
use App\Exceptions\TriviaGameApiWrongTypeException;
use App\Http\Controllers\Controller;
use App\Http\Resources\TriviaGameResource;
use App\Models\Answer;
use App\Models\TriviaGame;
use App\Services\TriviaGameGeneration\TriviaGameGeneratorService;
use App\Services\TriviaGameService;

class TriviaGameController extends Controller
{
    private TriviaGameGeneratorService $triviaGameGeneratorService;
    private TriviaGameService $gameService;

    public function __construct(TriviaGameGeneratorService $triviaGameGeneratorService, TriviaGameService $gameService)
    {
        $this->triviaGameGeneratorService = $triviaGameGeneratorService;
        $this->gameService = $gameService;
    }

    /**
     * Create trivia game instance
     *
     * @return TriviaGameResource
     * @throws TriviaGameNotCurrentQuestionException
     * @throws TriviaGameApiWrongTypeException
     * @OA\Get(
     *     path="/api/trivia-game/create",
     *     operationId="trivia-game.create",
     *     tags={"TriviaGameController"},
     *     description="creating instance of trivia game with questions",
     *     @OA\Response(
     *          response=200,
     *          description="trivia game instance is returned",
     *          @OA\JsonContent(),
     *       ),
     * )
     */
    public function create(): TriviaGameResource
    {
        return new TriviaGameResource($this->triviaGameGeneratorService->generate());
    }

    /**
     * Show trivia game instance
     *
     * @param $id
     * @return TriviaGameResource
     * @OA\Get(
     *     path="/api/trivia-game/{id}",
     *     operationId="trivia-game.show",
     *     tags={"TriviaGameController"},
     *     description="get instance of trivia game",
     *     @OA\Parameter(
     *          name="id",
     *          description="Trivia game id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="trivia game instance is returned",
     *          @OA\JsonContent(),
     *       ),
     * )
     */
    public function get($id): TriviaGameResource
    {
        $game = TriviaGame::query()
            ->with(
                [
                    'currentQuestion',
                    'wrongAnsweredQuestion.userAnswer',
                    'wrongAnsweredQuestion.rightAnswer',
                    'answeredQuestions.userAnswer',
                    'answeredQuestions.rightAnswer',
                ]
            )
            ->withCount(['questions', 'answeredQuestions'])
            ->find($id);
        return new TriviaGameResource($game);
    }

    /**
     * Show trivia game instance
     *
     * @param Answer $answer
     * @return array
     * @throws TriviaGameNotCurrentQuestionException
     * @throws TriviaGameIsFinishedException
     * @OA\Post(
     *     path="/api/trivia-game/submit-answer/{id}",
     *     operationId="trivia-game.submit-answer",
     *     tags={"TriviaGameController"},
     *     description="submit trivia answer",
     *     @OA\Parameter(
     *          name="id",
     *          description="answer id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="true/false is returned if answer right/wrong",
     *          @OA\JsonContent(),
     *       ),
     * )
     */
    public function submitAnswer(Answer $answer): array
    {
        return [
            'result' => $this->gameService->submitAnswer($answer)
        ];
    }
}
