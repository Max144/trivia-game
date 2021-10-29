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
            'currentQuestion' => new QuestionResource($this->whenLoaded('currentQuestion')),
            'wrongAnsweredQuestion' => new QuestionResource($this->whenLoaded('wrongAnsweredQuestion')),
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
}
