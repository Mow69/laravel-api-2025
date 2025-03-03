<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Profil extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ProfilFactory> */
    use HasFactory, InteractsWithMedia;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $fillable = [
        'firstname',
        'lastname',
        'status_id'
    ];

    protected $appends = [
        'image'
    ];

    protected $hidden = [
        'media'
    ];

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function getImageAttribute(): string
    {
        return $this->getFirstMediaUrl('avatar');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile();
    }
}
