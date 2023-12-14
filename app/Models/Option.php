<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'option_text',
        'votes'
    ];

    protected $withCount = ['votes'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
