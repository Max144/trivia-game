<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $text
 * @property int $trivia_game_id
 * @property int $order_number
 * @property int|null $right_answer_id
 * @property int|null $user_answer_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read TriviaGame $triviaGame
 * @property-read Collection|Answer[] $answers
 * @property-read Answer $rightAnswer
 * @property-read Answer $userAnswer
 */
class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'trivia_game_id',
        'order_number',
        'right_answer_id',
        'user_answer_id',
    ];

    public function triviaGame(): BelongsTo
    {
        return $this->belongsTo(TriviaGame::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function rightAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function userAnswer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }
}
