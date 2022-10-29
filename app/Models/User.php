<?php

namespace App\Models;

use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\CanPay;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Musonza\Chat\Traits\Messageable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use willvincent\Rateable\Rateable;
use Bavix\Wallet\Traits\HasWalletFloat;
use Bavix\Wallet\Interfaces\WalletFloat;

class User extends Authenticatable implements HasMedia, Wallet, WalletFloat, Customer
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        InteractsWithMedia,
        Rateable,
        CanPay,
        Messageable,
        HasWalletFloat;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'location',
        'resume',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Scope a query to only include freelancers.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeFreelancers($query): Builder
    {
        return $query->whereHas('roles', function (Builder $query) {
            $query->where('name', 'freelancer');
        });
    }

    /**
     * Scope a query to only include employers.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeEmployers($query): Builder
    {
        return $query->whereHas('roles', function (Builder $query) {
            $query->where('name', 'employer');
        });
    }

    /**
     * Get all roles associated with user.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get all categories associated with user.
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get all roles associated with user.
     *
     * @return array
     */
    public function getRolesArray(): array
    {
        return $this->roles->pluck('name')->all();
    }

    public function getShortResume($symbols=200) {
        $end = $this->resumeLength > $symbols ? '...' : '';
        return mb_strimwidth(mb_strimwidth(strip_tags($this->resume), 0, strlen(strip_tags($this->resume))), 0, $symbols) . $end;
    }

    public function getResumeLenAttribute() {
        return strlen(mb_strimwidth(strip_tags($this->resume), 0, strlen(strip_tags($this->resume))));
    }

    /**
     * Get concatenated first and last name of user.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        $full_name = '';

        if($this->first_name) {
            if($this->last_name) {
                $full_name = "$this->first_name $this->last_name";
            } else {
                $full_name = $this->first_name;
            }
        } else {
            $full_name = $this->username;
        }

        return $full_name;
    }

    /**
     * Get all time from creating user to now.
     *
     * @return string
     */
    public function getTimeOnService(): string
    {
        return Carbon::now()->locale('ru_RU')->diffForHumans($this->created_at, true);
    }

    /**
     * Check if user has admin permissions.
     *
     * @return boolean
     */
    public function getIsAdminAttribute(): bool
    {
        return in_array('admin', $this->getRolesArray());
    }

    /**
     * Check if user has freelancer role.
     *
     * @return boolean
     */
    public function getIsFreelancerAttribute(): bool
    {
        return in_array('freelancer', $this->getRolesArray());
    }

    /**
     * Check if user wrote the offer for given order.
     *
     * @return boolean
     */
    public function hasOfferOf($order_id) {
        $order = Order::findOrFail($order_id);
        return $order->offers()->where('user_id', $this->id)->exists();
    }

    public function isOffersLimitSpent() {
        return $this->offers()->whereDay('created_at', Carbon::now())->get()->count() >= config('calipso.limits.offers');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Check if user has purchased the lot.
     *
     * @param $lot_id
     * @return boolean
     */
     public function hasPurchasedLot($lot_id): bool
     {
         return LotPurchase::where([
             ['user_id', $this->id],
             ['lot_id', $lot_id],
         ])->exists();
     }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile();
    }

    /**
     * Get avatar url of user.
     *
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->hasMedia('avatar') ? $this->getFirstMediaUrl('avatar') : asset('assets/img/avatar.png');
    }

    /**
     * Get lots that have purchased by this user.
     *
     * @return HasMany
     */
    public function purchasedLots(): HasMany
    {
        return $this->hasMany(LotPurchase::class)->with('lot');
    }

    /**
     * Get lots created by this user.
     *
     * @return HasMany
     */
    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }

    /**
     * Get lots created by this user.
     *
     * @return HasMany
     */
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    /**
     * Get orders created by this user.
     *
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get orders working with this user.
     *
     * @return BelongsToMany
     */
    public function ordersInWork(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }

    /**
     * Get country code from country name of user.
     *
     * @return string
     */
    public function getCountryCodeAttribute($country_code): string
    {
        return strtolower($country_code);
    }

    /**
     * Get all reviews for this user.
     *
     * @return MorphMany
     */
    public function reviews() {
        return $this->morphMany(Review::class, 'reviewable');
    }

    /**
     * Check user verification.
     *
     * @return bool
     */
    public function isVerified() {
        return true;
    }

    /**
     * Check if in current category users ratings are bigger
     * than this user rating, and this rating isn't null.
     *
     * @return bool
     */
    public function isTop($name = false): bool
    {
        if($name) {
            $category = Category::forFreelance()->where('slug', $name)->firstOrFail();

            // Other users in category with ratings
            $users_in_category = $category->freelancers->except($this->id);
        } else {
            $users_in_category = User::freelancers()->get()->except($this->id);
        }

        $users_with_bigger_rating = $users_in_category->where('rating', '>', $this->rating);

        return !$users_with_bigger_rating->count() && $this->rating;
    }

    public function addRating($points, $name=false) {
        if($name) {
            if(!FreelanceRating::where([
                ['name', $name],
                ['user_id', $this->id],
            ])->exists()) {
                FreelanceRating::create([
                    'user_id' => $this->id,
                    'points' => $points,
                    'name' => $name,
                ]);

                $this->rating += $points;
                $this->save();
            }
        } else {
            $this->rating += $points;
            $this->save();
        }
    }

    public function decreaseRating($points, $name=false) {
        $rating = $this->rating - $points;
        if($rating < 0) $rating = 0;

        if($name) {
            if(!FreelanceRating::where([
                ['name', $name],
                ['user_id', $this->id],
            ])->exists()) {
                FreelanceRating::create([
                    'user_id' => $this->id,
                    'points' => $points - ($points * 2),
                    'name' => $name,
                ]);

                $this->rating = $rating;
                $this->save();
            }
        } else {
            $this->rating = $rating;
            $this->save();
        }
    }

    /**
     * Last user visit date.
     *
     * @return bool
     */
    public function lastOnlineDate() {
        return $this->created_at->subDay()->diffForHumans();
    }

    public function getOrdersWorkCountAttribute() {
        return Order::where(function ($query) {
            $query
                ->where('user_id', $this->id)
                ->orWhere('freelancer_id', $this->id);
        })->inWork()->count();
    }
}
