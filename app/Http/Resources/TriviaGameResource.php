<?php

namespace App\Http\Resources;

use App\Models\TriviaGame;
use App\Services\TriviaGameGeneration\TriviaGameGeneratorService;
use Illuminate\Http\Resources\Json\JsonResource;

class TriviaGameResource extends JsonResource
{
    public function toArray($request)
    {
        $triviaService = new TriviaGameGeneratorService($this->resource);

        return [
            'id' => $this->id,
            'is_finished' => $this->is_finished,
            'is_won' => $this->is_won,
            'created_at' => $this->created_at,
            'currentQuestion' => $this->when(
                !$this->is_finished,
                new QuestionResource($triviaService->getNextQuestion())
            ),
            'wrongAnsweredQuestion' => new QuestionResource($this->whenLoaded('wrongAnsweredQuestion')),
            'answeredQuestions' => QuestionResource::collection($this->whenLoaded('answeredQuestions')),
            'questions_count' => TriviaGame::QUESTIONS_COUNT,
            'answered_questions_count' => $this->when(
                isset($this->answered_questions_count),
                $this->answered_questions_count
            ),
        ];
    }
}
