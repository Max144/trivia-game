<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property bool $is_finished
 * @property bool|null $is_won
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Collection|Question[] $questions
 * @property-read Question|null $currentQuestion
 * @property-read Question|null $wrongAnsweredQuestion
 */
class TriviaGame extends Model
{
    use HasFactory;

    public const QUESTIONS_COUNT = 20;

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order_number');
    }

    public function answeredQuestions(): HasMany
    {
        return $this->hasMany(Question::class)
            ->whereNotNull('user_answer_id')
            ->orderBy('order_number');
    }

    public function currentQuestion(): HasOne
    {
        return $this->hasOne(Question::class)
            ->whereNull('user_answer_id')
            ->orderBy('order_number');
    }

    public function wrongAnsweredQuestion(): HasOne
    {
        return $this->hasOne(Question::class)
            ->havingRaw('user_answer_id != right_answer_id')
            ->orderBy('order_number')
            ->groupBy('questions.id');
    }
}
