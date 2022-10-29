<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Safe extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const ACTIVE_STATUS = 'active';
    const AGREEMENT_STATUS = 'agreement';
    const RESERVATION_STATUS = 'reservation';
    const WORK_STATUS = 'in_work';
    const REVIEWS_STATUS = 'reviews';
    const COMPLETE_STATUS = 'complete';

    const STATUSES = [
        self::ACTIVE_STATUS,
        self::AGREEMENT_STATUS,
        self::RESERVATION_STATUS,
        self::WORK_STATUS,
        self::REVIEWS_STATUS,
        self::COMPLETE_STATUS,
    ];

    protected $fillable = [
        'order_id',
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function offer() {
        return $this->hasOneThrough(Offer::class, Order::class);
    }

    public function freelancer() {
        return $this->order->freelancer;
    }

    public function customer() {
        return $this->belongsTo(Order::class);
    }

    public function completedStatus($status) {
        $status_position = array_search($status, self::STATUSES);
        $current_status_position = array_search($this->status, self::STATUSES);

        return $current_status_position > $status_position;
    }

    public function setStatus($status) {
        $this->status = $status;
        $this->save();
    }
}
