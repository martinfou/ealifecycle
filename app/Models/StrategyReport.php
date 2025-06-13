<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StrategyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'strategy_id',
        'file_path',
        'original_filename',
        'uploaded_by'
    ];

    /**
     * Get the strategy that owns the report.
     */
    public function strategy(): BelongsTo
    {
        return $this->belongsTo(Strategy::class);
    }

    /**
     * Get the user who uploaded the report.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
} 