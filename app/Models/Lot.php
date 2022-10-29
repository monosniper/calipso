<?php

namespace App\Models;

use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\Product;
use Bavix\Wallet\Traits\HasWallet;
use Couchbase\SearchResult;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Kyslik\ColumnSortable\Sortable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use willvincent\Rateable\Rateable;
use Illuminate\Database\Eloquent\Builder;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Cart;

class Lot extends Model implements HasMedia, Buyable, Product {

    use HasFactory, HasTags, InteractsWithMedia, Sortable, HasSlug, Rateable, HasWallet;

    const ACTIVE_STATUS = 'active';
    const MODERATION_STATUS = 'moderation';
    const REJECTED_STATUS = 'rejected';

    const STATUSES = [
        self::ACTIVE_STATUS,
        self::MODERATION_STATUS,
        self::REJECTED_STATUS,
    ];

    protected $with = [
        'media',
    ];

    protected $fillable = [
        'title',
        'description',
        'price',
        'discount_price',
        'properties',
        'status',
        'isPremium',
        'category_id',
        'user_id',
    ];

//    protected $casts = [
//        'properties' => 'array',
//    ];

    public $sortable = [
        'price',
        'views',
        'created_at',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('archive')
            ->singleFile();

        $this->addMediaCollection('images');
    }

    /**
     * Scope a query to only include active lots.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::ACTIVE_STATUS);
    }

    /**
    * Scope a query to only include premium lots.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopePremium($query)
    {
        return $query->where('isPremium', true);
    }

    /**
     * Scope a query to only include lots without premium.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotPremium($query)
    {
        return $query->where('isPremium', false);
    }

    /**
     * Scope a query to order lots in shop.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderForShop($query) {
        $orders = [
            [
                'name' => 'isPremium',
                'direction' => 'desc',
            ],
            [
                'name' => 'views',
                'direction' => 'desc',
            ],
            [
                'name' => 'created_at',
                'direction' => 'desc',
            ],
        ];

        foreach($orders as $order) {
            $query->orderBy($order['name'], $order['direction']);
        }

        return $query;
    }

    // Хз че там происходит, но это работает
    // Dont know what is it, but it works
    public function getPropertiesAttribute($properties) {
        if($properties) {
            if(is_object($properties)) {
                return $properties;
            } else {
                if(is_array(json_decode($properties))) {
                    return json_decode($properties);
                } else {
                    if(is_object(json_decode($properties))) {
                        return json_decode($properties, true);
                    } else {
                        return json_decode(json_decode($properties),true);
                    }
                }
            }
        } else {
            return [];
        }
    }

    /**
     * Check if in current category lots avg ratings are bigger
     * than this lot avg rating, and this avg rating isn't null.
     *
     * @return bool
     */
    public function isTop(): bool
    {
        // Other lots in current lot category with avg ratings
        $lots_in_category = $this->category->lots->except($this->id);
        $lots_with_bigger_rating = $lots_in_category->where('ratings_avg_rating', '>', $this->avg_rating);

        return !$lots_with_bigger_rating->count() && $this->avg_rating;
    }

    /**
     * Get the preview image of lot.
     *
     *
     * @return string
     */
    public function getPreview() {
        return optional($this->media->where('collection_name', 'images')->first())->original_url;
    }

    /**
     * Get short description of lot.
     *
     *
     * @param int $symbols
     * @return string
     */
    public function getShortDescription($symbols=200) {
        return mb_strimwidth(mb_strimwidth(strip_tags($this->description), 0, strlen(strip_tags($this->description)) - 24), 0, $symbols);
    }

    public function getDescriptionLenAttribute() {
        return strlen(mb_strimwidth(strip_tags($this->description), 0, strlen(strip_tags($this->description)) - 24));
    }

    /**
     * Get short title of lot.
     *
     *
     * @param int $symbols
     * @return string
     */
    public function getShortTitle($symbols=50) {
        return strlen($this->title) > $symbols ? mb_strimwidth($this->title, 0, $symbols) . '...' : $this->title;
    }

    /**
     * Support for cheaper sorting lots.
     *
     *
     * @param $query
     * @return string
     */
    public function priceCheapSortable($query) {
        return $query->orderBy('price', 'desc');
    }

    /**
     * Support for richer sorting lots.
     *
     *
     * @param $query
     * @return string
     */
    public function priceRichSortable($query) {
        return $query->orderBy('price', 'asc');
    }

    /**
     * Support for searching lots.
     *
     *
     * @return SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        $url = route('lots.show', $this->slug);

        return new \Spatie\Searchable\SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the count of all reviews for this lot.
     *
     * @return string
     */
    public function getReviewsCount()
    {
        return 10;
    }

    /**
     * Get all reviews for this lot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reviews() {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Get all reviews for this lot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all reviews for this lot.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Check position of this lot in shop.
     *
     * @param $ordered_lots
     * @return int
     */
    public function getPosition($ordered_lots): int {
        $lots = $ordered_lots->get();

        return $lots->search($this) + 1;
    }

    /**
     * Check position of this lot in shop if it has premium.
     *
     * @param $ordered_lots
     * @return int
     */
    public function getPositionIfPremium($ordered_lots): int {
        $premium_lots = $ordered_lots->premium();
        $prev_lot = $premium_lots
            ->where('views', '>', $this->views)
            ->whereDate('created_at', '>', $this->created_at)
            ->get()
            ->last();

        return $prev_lot ? $prev_lot->getPosition($ordered_lots) + 1 : $premium_lots->count();
    }

    public function getBuyableIdentifier($options = null)
    {
        return $this->id;
    }

    public function getBuyableDescription($options = null)
    {
        return $this->title;
    }

    public function getBuyablePrice($options = null)
    {
        return $this->price;
    }

    public function purchases() {
        return $this->hasMany(LotPurchase::class);
    }

    public function hasReviewFrom($user_id) {
        return Review::forLots()->where([
            ['user_id', $user_id],
            ['reviewable_id', $this->id],
        ])->exists();
    }

    public function getReviewFrom($user_id) {
        return Review::forLots()->where([
            ['user_id', $user_id],
            ['reviewable_id', $this->id],
        ])->first();
    }

    public function hasAddedTo($instance) {
        Cart::instance($instance)->restore(auth()->id());
        return !Cart::instance($instance)->search(function ($cartItem, $rowId) {
            return $cartItem->id === $this->slug;
        })->isEmpty();
    }

    public function canBuy(Customer $customer, int $quantity = 1, bool $force = false): bool
    {
        /**
         * If the service can be purchased once, then
         *  return !$customer->paid($this);
         */
        return !$customer->paid($this);
    }

    public function getAmountProduct(Customer $customer)
    {
        return $this->price * 100;
    }

    public function getMetaProduct(): ?array
    {
        return [
            'title' => $this->title,
            'description' => __('main.purchase_desc', ['id' => $this->id]),
        ];
    }
}
