<?php

namespace App\Http\Filters\Order;

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

        if(in_array('min_offers', $filters)) {
            $builder->has('offers', '<=', 2);
        }

        if(in_array('nice_reviews', $filters)) {
            $builder->whereHas('user.reviews', function (Builder $query) {
                $query->where('rating', '>', 3);
            })->orHas('user.reviews', '=', 0);
        }

        if(in_array('reviews', $filters)) {
            $builder->has('user.reviews');
        }

        if(in_array('urgent', $filters)) {
            $builder->urgent();
        }

        return $builder;
    }
}
