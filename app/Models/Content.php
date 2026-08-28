<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'title',
    'type',
    'file_path',
    'text_body',
    'duration',
    'start_date',
    'end_date',
    'order',
    'is_active',
    'is_priority',
])]
class Content extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'duration' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
            'is_priority' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany<Playlist, $this>
     */
    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class, 'playlist_content')
            ->withPivot('order')
            ->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeScheduledToday(Builder $query): Builder
    {
        $today = Carbon::today()->toDateString();

        return $query
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('start_date')->orWhereDate('start_date', '<=', $today);
            })
            ->where(function (Builder $q) use ($today) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $today);
            });
    }

    public function isScheduledNow(): bool
    {
        $today = Carbon::today();

        if ($this->start_date && $this->start_date->gt($today)) {
            return false;
        }

        if ($this->end_date && $this->end_date->lt($today)) {
            return false;
        }

        return true;
    }

    public function getFileUrlAttribute(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        return Storage::disk('public')->url($this->file_path);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'image' => 'Gambar',
            'video' => 'Video',
            'text' => 'Teks',
            'html-embed' => 'HTML Embed',
            default => $this->type,
        };
    }
}
