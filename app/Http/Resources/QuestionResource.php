<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'trivia_game_id' => $this->trivia_game_id,
            'triviaGame' => new TriviaGameResource($this->whenLoaded('triviaGame')),
            'order_number' => $this->order_number,
            'answers' => AnswerResource::collection($this->answers),
            $this->mergeWhen(isset($this->user_answer_id), [
                'rightAnswer' => new AnswerResource($this->whenLoaded('rightAnswer'))
            ]),
            'userAnswer' => new AnswerResource($this->whenLoaded('userAnswer')),
        ];
    }
}
