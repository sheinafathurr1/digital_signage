<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Playlist extends Model
{
    use HasFactory;

    /**
     * @return BelongsToMany<Content, $this>
     */
    public function contents(): BelongsToMany
    {
        return $this->belongsToMany(Content::class, 'playlist_content')
            ->withPivot('order')
            ->withTimestamps()
            ->orderBy('playlist_content.order');
    }

    /**
     * @return HasMany<Display, $this>
     */
    public function displays(): HasMany
    {
        return $this->hasMany(Display::class);
    }
}
