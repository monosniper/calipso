<?php

namespace App\Http\Filters\User;

use Fouladgar\EloquentBuilder\Support\Foundation\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class FiltersFilter extends Filter
{
    /**
     * Apply the condition to the query.
     *
     * @param Builder $builder
     * @param mixed $value
     *
     * @return Builder
     */
    public function apply(Builder $builder, $value): Builder
    {
        $filters = array_filter(explode(',', $value));

        if(in_array('nice_reviews', $filters)) {
            $builder->where(function (Builder $query) {
                $query->whereHas('reviews', function (Builder $query) {
                    $query->where('rating', '>', 3);
                })->orHas('reviews', '=', 0);
            });
        }

        if(in_array('reviews', $filters)) {
            $builder->has('reviews');
        }

        return $builder;
    }
}
