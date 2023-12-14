<?php

namespace App\Models\Scopes;

use App\Models\Question;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AvailableScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (! $model instanceof  Question) {
            return;
        }

        $now = now();

        $builder->where(function ($query) use ($now) {
            $query->where(function ($q) use ($now) {
                $q->whereNull('start_date')
                    ->whereNull('end_date');
            })->orWhere(function ($q) use ($now) {
                $q->where('start_date', '<=', $now)
                    ->whereNull('end_date');
            })->orWhere(function ($q) use ($now) {
                $q->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
            });
        });
    }

}
