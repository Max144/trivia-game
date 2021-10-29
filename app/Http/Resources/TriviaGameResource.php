<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TriviaGameResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'is_finished' => $this->is_finished,
            'is_won' => $this->is_won,
            'created_at' => $this->created_at,
            'currentQuestion' => $this->when(
                !$this->is_finished,
                new QuestionResource($this->whenLoaded('currentQuestion'))
            ),
            'wrongAnsweredQuestion' => new QuestionResource($this->whenLoaded('wrongAnsweredQuestion')),
            'answeredQuestions' => QuestionResource::collection($this->whenLoaded('answeredQuestions')),
            'questions_count' => $this->when(
                isset($this->questions_count),
                $this->questions_count
            ),
            'answered_questions_count' => $this->when(
                isset($this->answered_questions_count),
                $this->answered_questions_count
            ),
        ];
    }
}
