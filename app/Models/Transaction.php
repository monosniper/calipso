<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const PENDING_STATUS = 'pending';
    const SUCCESS_STATUS = 'success';
    const ERROR_STATUS = 'error';

    const STATUSES = [
        self::PENDING_STATUS,
        self::SUCCESS_STATUS,
        self::ERROR_STATUS,
    ];

    protected $fillable = [
        'type',
        'description',
        'amount',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function setStatus($status) {
        $this->status = $status;
        $this->save();
    }
}
