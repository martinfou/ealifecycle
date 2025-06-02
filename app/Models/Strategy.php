<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Strategy extends Model
{
    protected $fillable = [
        'name',
        'symbols_traded',
        'timeframe_id',
        'magic_number',
        'status_id',
        'date_in_status',
        'user_id',
        'group_id',
        'description',
    ];

    protected $casts = [
        'date_in_status' => 'date',
    ];

    /**
     * Get the user that owns the strategy.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the group that the strategy belongs to.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Get the status of the strategy.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get the timeframe of the strategy.
     */
    public function timeframe(): BelongsTo
    {
        return $this->belongsTo(Timeframe::class);
    }

    /**
     * Get the status history for the strategy.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(StatusHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the trades for the strategy.
     */
    public function trades(): HasMany
    {
        return $this->hasMany(Trade::class);
    }

    /**
     * Scope a query to only include strategies for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include strategies with a specific status.
     */
    public function scopeWithStatus($query, $statusId)
    {
        return $query->where('status_id', $statusId);
    }

    /**
     * Scope a query to only include strategies accessible by a user based on group membership.
     */
    public function scopeAccessibleByUser($query, $user)
    {
        return $query->whereHas('group.users', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->orWhere('user_id', $user->id);
    }

    /**
     * Check if a user can edit this strategy (has write permission in the group).
     */
    public function canUserEdit($user)
    {
        // Strategy owner can always edit
        if ($this->user_id === $user->id) {
            return true;
        }

        // Check if user has write permission in the strategy's group
        if ($this->group) {
            return $user->hasWritePermissionInGroup($this->group_id);
        }

        return false;
    }

    /**
     * Check if a user can view this strategy (has read or write permission in the group).
     */
    public function canUserView($user)
    {
        // Strategy owner can always view
        if ($this->user_id === $user->id) {
            return true;
        }

        // Check if user has any permission in the strategy's group
        if ($this->group) {
            return $user->hasPermissionInGroup($this->group_id);
        }

        return false;
    }
}
