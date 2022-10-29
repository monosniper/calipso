<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Category extends Model
{
    use HasFactory;
    use HasSlug;
    use HasRecursiveRelationships;

    const SHOP_NAME = 'shop';
    const FREELANCE_NAME = 'freelance';

    const FOR_NAMES = [
        self::SHOP_NAME,
        self::FREELANCE_NAME,
    ];

    protected $fillable = ['name', 'parent_id', 'for'];

    /**
     * Scope a query to only include category for lots.
     *
     * @param  Builder  $query
     * @return Builder
     */
     public function scopeForShop($query)
     {
         return $query->where('for', self::SHOP_NAME);
     }

    /**
     * Scope a query to only include category for orders.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeForFreelance(Builder $query): Builder
    {
        return $query->where('for', self::FREELANCE_NAME);
    }

    public function getTotalCount($for): int {
        $total_count = (int) $this[$for."_count"];
        $children = $this->descendants;

        if($children->count()) {
            foreach($children as $child) {
                $total_count += (int) $child[$for."_count"];
            }
        }

        return $total_count;
    }

     /**
      * Get the options for generating the slug.
      */
     public function getSlugOptions() : SlugOptions
     {
         return SlugOptions::create()
             ->generateSlugsFrom('name')
             ->saveSlugsTo('slug');
     }

     public function getItemsCount() {
         $types = [
             self::SHOP_NAME => 'lots',
             self::FREELANCE_NAME => 'orders',
         ];

         return $this[$types[$this->for].'_count'];
     }

    public function getSalesSum() {
        $lots = Lot::whereHas('purchases')->withCount('purchases')->where('category_id', $this->id)->pluck('price', 'purchases_count');
        $total = 0;
        foreach ($lots as $purchases_count => $price) {
            $total += (int) $price * (int) $purchases_count;
        }
        return $total;
    }

    /**
     * Get the count of orders for this category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lots() {
        return $this->hasMany(Lot::class);
    }

    /**
     * Get the count of orders for this category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders() {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the count of freelancers for this category.
     *
     * @return BelongsToMany
     */
    public function freelancers(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
