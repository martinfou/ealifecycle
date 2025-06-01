<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusHistory extends Model
{
    protected $table = 'status_history';

    protected $fillable = [
        'strategy_id',
        'previous_status_id',
        'new_status_id',
        'changed_by_user_id',
        'notes',
    ];

    /**
     * Get the strategy that owns the status history.
     */
    public function strategy(): BelongsTo
    {
        return $this->belongsTo(Strategy::class);
    }

    /**
     * Get the previous status.
     */
    public function previousStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'previous_status_id');
    }

    /**
     * Get the new status.
     */
    public function newStatus(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'new_status_id');
    }

    /**
     * Get the user who made the status change.
     */
    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
