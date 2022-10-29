<?php

namespace App\Http\Filters\Order;

use App\Models\Order;
use Fouladgar\EloquentBuilder\Support\Foundation\Contracts\Filter;
use Illuminate\Database\Eloquent\Builder;

class TagsFilter extends Filter
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
         return $builder->withAnyTagsOfAnyType(is_array($value) ? $value : explode(',',$value));
    }
}
