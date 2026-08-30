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
    'background_color',
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

    /**
     * Background colours offered for text slides.
     *
     * Deliberately a fixed list rather than a free colour picker: every entry
     * is checked to clear WCAG AA (>= 4.5:1) against the white slide text, so
     * an announcement cannot be made unreadable from across a lobby. The
     * ratio beside each colour is its measured contrast against #FFFFFF.
     *
     * @var array<string, array{label: string, hex: string}>
     */
    public const BACKGROUND_COLORS = [
        'oranye' => ['label' => 'Oranye', 'hex' => '#BE5520'],    // 4.66:1
        'hijau' => ['label' => 'Hijau', 'hex' => '#15803D'],      // 5.02:1
        'teal' => ['label' => 'Teal', 'hex' => '#0F766E'],        // 5.47:1
        'biru' => ['label' => 'Biru', 'hex' => '#2563EB'],        // 5.17:1
        'biru-tua' => ['label' => 'Biru Tua', 'hex' => '#1E40AF'], // 8.72:1
        'ungu' => ['label' => 'Ungu', 'hex' => '#7C3AED'],        // 5.70:1
        'marun' => ['label' => 'Marun', 'hex' => '#9D174D'],      // 7.88:1
        'gelap' => ['label' => 'Gelap', 'hex' => '#2C2420'],      // 15.21:1
    ];

    public const DEFAULT_BACKGROUND_COLOR = 'oranye';

    /** Emergency slides always use this, whatever colour was picked. */
    public const PRIORITY_BACKGROUND_HEX = '#E74C3C';

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

    /**
     * Resolved slide background. Priority content always overrides the chosen
     * colour with red — the emergency signal has to stay recognisable — and an
     * unknown or missing key falls back to the default rather than breaking.
     */
    public function getBackgroundHexAttribute(): string
    {
        if ($this->is_priority) {
            return self::PRIORITY_BACKGROUND_HEX;
        }

        $palette = self::BACKGROUND_COLORS;
        $key = $this->background_color ?? self::DEFAULT_BACKGROUND_COLOR;

        return ($palette[$key] ?? $palette[self::DEFAULT_BACKGROUND_COLOR])['hex'];
    }
}
