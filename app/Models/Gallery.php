<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'user_id',
        'layout',
    ];

    public const LAYOUTS = [
        'grid' => 'Grid Layout',
        'masonry' => 'Masonry Layout',
        'list' => 'List Layout',
        'carousel' => 'Carousel Layout',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('position');
    }
}
