<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\TriviaGameIsFinishedException;
use App\Exceptions\TriviaGameNotCurrentQuestionException;
use App\Models\Answer;
use App\Models\Question;

class TriviaGameService
{
    /**
     * @throws TriviaGameNotCurrentQuestionException
     * @throws TriviaGameIsFinishedException
     */
    public function submitAnswer(Answer $answer): bool
    {
        if ($answer->question->triviaGame->is_finished) {
            throw new TriviaGameIsFinishedException();
        }

        if (!$this->checkQuestionIsCurrent($answer->question)) {
            throw new TriviaGameNotCurrentQuestionException();
        }

        $answer->question->update(['user_answer_id' => $answer->id]);
        $this->changeGameStatusAfterQuestion($answer->question);

        return $answer->question->right_answer_id === $answer->id;
    }

    protected function changeGameStatusAfterQuestion(Question $question)
    {
        if (isset($question->triviaGame->wrongAnsweredQuestion)) {
            $question->triviaGame->is_finished = true;
            $question->triviaGame->is_won = false;
            $question->triviaGame->save();
        }

        if (!isset($question->triviaGame->currentQuestion)) {
            $question->triviaGame->is_finished = true;
            $question->triviaGame->is_won = true;
            $question->triviaGame->save();
        }
    }

    protected function checkQuestionIsCurrent(Question $question): bool
    {
        return $question->triviaGame->currentQuestion->id === $question->id;
    }
}