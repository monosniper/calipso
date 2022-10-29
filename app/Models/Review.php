<?php

namespace App\Models;

use Conner\Likeable\Likeable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory, Likeable;

    protected $fillable = [
        'title',
        'content',
        'rating',
    ];

    /**
     * Scope a query to only include positive reviews.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopePositive($query) {
        return $query->where('rating', '>', 3);
    }

    /**
     * Scope a query to only include negative reviews.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeNegative($query) {
        return $query->where('rating', '<=', 3);
    }

    /**
     * Scope a query to only include reviews for lots.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeForLots($query) {
        return $query->where('reviewable_type', Lot::class);
    }

    /**
     * Scope a query to only include reviews for users.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeForUsers($query) {
        return $query->where('reviewable_type', User::class);
    }

    public function isNegative() {
        return $this->rating <= 3;
    }

    public static function getFor($reviewable_type, $reviewable_id) {
        return Review::where([
            ['reviewable_type', $reviewable_type],
            ['reviewable_id', $reviewable_id],
        ]);
    }

    /**
     * Get the author of this review.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Get reviewable model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function reviewable() {
        return $this->morphTo();
    }
}
