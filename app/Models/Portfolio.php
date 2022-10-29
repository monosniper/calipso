<?php

namespace App\Models;

use Conner\Likeable\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Portfolio extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Likeable;

    protected $fillable = [
        'title',
        'link',
        'description',
        'tag',
    ];

    protected $with = [
        'media',
    ];

    public function getPreview() {
        return $this->getFirstMediaUrl('preview');
    }

    /**
     * Get short title of portfolio.
     *
     *
     * @param int $symbols
     * @return string
     */
    public function getShortTitle($symbols=20) {
        return strlen($this->title) > $symbols ? mb_strimwidth($this->title, 0, $symbols) . '...' : $this->title;
    }
}
