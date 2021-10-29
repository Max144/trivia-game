<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $text
 * @property int $question_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Question $question
 */
class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'question_id',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
