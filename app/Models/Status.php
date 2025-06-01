<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the strategies for the status.
     */
    public function strategies(): HasMany
    {
        return $this->hasMany(Strategy::class);
    }

    /**
     * Get the status history records for this status as new status.
     */
    public function newStatusHistory(): HasMany
    {
        return $this->hasMany(StatusHistory::class, 'new_status_id');
    }

    /**
     * Get the status history records for this status as previous status.
     */
    public function previousStatusHistory(): HasMany
    {
        return $this->hasMany(StatusHistory::class, 'previous_status_id');
    }
}
