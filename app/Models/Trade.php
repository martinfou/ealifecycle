<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trade extends Model
{
    protected $fillable = [
        'strategy_id',
        'magic_number',
        'symbol',
        'type',
        'lot_size',
        'open_price',
        'close_price',
        'open_time',
        'close_time',
        'profit',
        'commission',
        'swap',
        'comment',
        'original_file',
        'raw_data',
        'imported_by_user_id',
    ];

    protected $casts = [
        'open_time' => 'datetime',
        'close_time' => 'datetime',
        'raw_data' => 'array',
        'lot_size' => 'decimal:2',
        'open_price' => 'decimal:5',
        'close_price' => 'decimal:5',
        'profit' => 'decimal:2',
        'commission' => 'decimal:2',
        'swap' => 'decimal:2',
    ];

    /**
     * Get the strategy that owns the trade.
     */
    public function strategy(): BelongsTo
    {
        return $this->belongsTo(Strategy::class);
    }

    /**
     * Get the user who imported the trade.
     */
    public function importedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'imported_by_user_id');
    }

    /**
     * Scope a query to only include trades for a specific strategy.
     */
    public function scopeForStrategy($query, $strategyId)
    {
        return $query->where('strategy_id', $strategyId);
    }

    /**
     * Scope a query to only include trades with a specific magic number.
     */
    public function scopeWithMagicNumber($query, $magicNumber)
    {
        return $query->where('magic_number', $magicNumber);
    }

    /**
     * Scope a query to only include trades for a specific symbol.
     */
    public function scopeForSymbol($query, $symbol)
    {
        return $query->where('symbol', $symbol);
    }
}
