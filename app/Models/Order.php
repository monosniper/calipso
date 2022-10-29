<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use voku\helper\ASCII;

class Order extends Model implements HasMedia
{
    use HasFactory, HasTags, InteractsWithMedia;

    const ACTIVE_STATUS = 'active';
    const WORK_STATUS = 'in_work';
    const REVIEWS_STATUS = 'reviews';
    const COMPLETED_STATUS = 'completed';

    const STATUSES = [
        self::ACTIVE_STATUS,
        self::WORK_STATUS,
        self::REVIEWS_STATUS,
        self::COMPLETED_STATUS,
    ];

    protected $fillable = [
        'title',
        'description',
        'price',
        'days',
        'isSafe',
        'isUrgent',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'isSafe' => 'boolean',
        'isUrgent' => 'boolean',
    ];

    /**
     * Scope a query to only include urgent orders.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeUrgent($query) {
        return $query->where('isUrgent', true);
    }

    /**
     * Scope a query to only include completed orders.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeCompleted($query) {
        return $query->where('status', self::COMPLETED_STATUS);
    }

    /**
     * Scope a query to only include active orders.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::ACTIVE_STATUS);
    }

    /**
     * Scope a query to only include orders in work.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeInWork($query) {
        return $query->where('status', self::WORK_STATUS);
    }

    /**
     * Scope a query to only include orders in work.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeNotCompleted($query) {
        return $query->whereNotIn('status', [self::COMPLETED_STATUS]);
    }

    /**
     * Get offers associated with this lot.
     *
     * @return HasMany
     */
    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Get author of this lot.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get safe of this lot.
     *
     * @return HasOne
     */
    public function safe(): HasOne
    {
        return $this->hasOne(Safe::class);
    }

    /**
     * Get category associated with this lot.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getStatusAttribute($status) {
        return $this->isSafe ? $this->safe ? $this->safe->status : $status : $status;
    }

    public function getMimeTypeIcon($mime_type) {
        // List of official MIME Types: http://www.iana.org/assignments/media-types/media-types.xhtml
        $icon_classes = array(
            // Media
            'image' => 'fa-file-image',
            'audio' => 'fa-file-audio',
            'video' => 'fa-file-video',
            // Documents
            'application/pdf' => 'fa-file-pdf',
            'application/msword' => 'fa-file-word',
            'application/vnd.ms-word' => 'fa-file-word',
            'application/vnd.oasis.opendocument.text' => 'fa-file-word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml' => 'fa-file-word',
            'application/vnd.ms-excel' => 'fa-file-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml' => 'fa-file-excel',
            'application/vnd.oasis.opendocument.spreadsheet' => 'fa-file-excel',
            'application/vnd.ms-powerpoint' => 'fa-file-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml' => 'fa-file-powerpoint',
            'application/vnd.oasis.opendocument.presentation' => 'fa-file-powerpoint',
            'text/plain' => 'fa-file-alt',
            'text/html' => 'fa-file-code',
            'application/json' => 'fa-file-code',
            // Archives
            'application/gzip' => 'fa-file-archive',
            'application/zip' => 'fa-file-archive',
        );
        foreach ($icon_classes as $text => $icon) {
            if (strpos($mime_type, $text) === 0) {
                return $icon;
            }
        }
        return 'fa-file';
    }

    public function getDescription($symbols=200) {
        return mb_strimwidth(mb_strimwidth(strip_tags($this->description), 0, strlen(strip_tags($this->description))), 0, $symbols);
    }

    /**
     * Get user that works with this lot.
     *
     * @return BelongsTo
     */
    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    /**
     * Get winner offer this lot.
     *
     * @return Model|HasMany|false
     */
    public function offer(): Model|HasMany|false
    {
        return $this->freelancer ? $this->offers()->where('user_id', $this->freelancer_id)->first()->load([
            'user' => function(BelongsTo $user) {
                $user->withCount([
                    'reviews as positive_reviews_count' => function(Builder $query) {
                        $query->positive();
                    }, 'reviews as negative_reviews_count' => function(Builder $query) {
                        $query->negative();
                    }
                ]);
            }
        ]) : false;
    }

    public function setStatus($status) {
        $this->status = $status;
        $this->save();
    }
}
