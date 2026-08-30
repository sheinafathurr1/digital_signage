<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

#[Fillable(['name', 'location', 'unique_code', 'orientation', 'playlist_id', 'last_seen_at'])]
class Display extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'last_seen_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Display $display) {
            if (empty($display->unique_code)) {
                $display->unique_code = static::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (static::where('unique_code', $code)->exists());

        return $code;
    }

    /**
     * @return BelongsTo<Playlist, $this>
     */
    public function playlist(): BelongsTo
    {
        return $this->belongsTo(Playlist::class);
    }

    public function touchLastSeen(): void
    {
        $this->forceFill(['last_seen_at' => Carbon::now()])->save();
    }

    public function getIsOnlineAttribute(): bool
    {
        if (! $this->last_seen_at) {
            return false;
        }

        return $this->last_seen_at->greaterThanOrEqualTo(Carbon::now()->subMinutes(2));
    }
}
