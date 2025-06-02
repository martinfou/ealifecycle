<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Timeframe extends Model
{
    protected $fillable = [
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the strategies that use this timeframe (many-to-many).
     */
    public function strategies(): BelongsToMany
    {
        return $this->belongsToMany(Strategy::class, 'strategy_timeframes')
                    ->withPivot('is_primary')
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include active timeframes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order timeframes by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
