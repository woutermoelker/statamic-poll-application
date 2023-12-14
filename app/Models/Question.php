<?php

namespace App\Models;

use Database\Factories\QuestionFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'type',
        'start_date',
        'end_date',
        'is_visible',
        'media_path',
    ];

    protected $withCount = [
        'votes'
    ];

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($question) {
            $question->options()->getQuery()->delete();
        });
    }

}
