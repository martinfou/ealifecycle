<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Portfolio extends Model
{
    protected $fillable = [
        'name',
        'description',
        'initial_capital',
        'status',
        'user_id',
    ];

    protected $casts = [
        'initial_capital' => 'decimal:2',
    ];

    /**
     * Get the user that owns the portfolio.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the strategies in this portfolio.
     */
    public function strategies(): BelongsToMany
    {
        return $this->belongsToMany(Strategy::class, 'portfolio_strategies')
                    ->withPivot('allocation_amount', 'allocation_percent', 'status', 'date_added', 'date_removed', 'notes')
                    ->withTimestamps()
                    ->orderBy('date_added');
    }

    /**
     * Get only active strategies in this portfolio.
     */
    public function activeStrategies(): BelongsToMany
    {
        return $this->strategies()->wherePivot('status', 'active');
    }

    /**
     * Get only paused strategies in this portfolio.
     */
    public function pausedStrategies(): BelongsToMany
    {
        return $this->strategies()->wherePivot('status', 'paused');
    }

    /**
     * Scope a query to only include active portfolios.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include portfolios for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get total allocated amount across all active strategies.
     */
    public function getTotalAllocatedAmountAttribute()
    {
        return $this->activeStrategies->sum('pivot.allocation_amount');
    }

    /**
     * Get total allocated percentage across all active strategies.
     */
    public function getTotalAllocatedPercentAttribute()
    {
        return $this->activeStrategies->sum('pivot.allocation_percent');
    }

    /**
     * Get count of active strategies.
     */
    public function getActiveStrategiesCountAttribute()
    {
        return $this->activeStrategies()->count();
    }

    /**
     * Get count of all strategies (including paused).
     */
    public function getTotalStrategiesCountAttribute()
    {
        return $this->strategies()->whereIn('portfolio_strategies.status', ['active', 'paused'])->count();
    }

    /**
     * Check if user can view this portfolio.
     */
    public function canUserView($user)
    {
        return $this->user_id === $user->id;
    }

    /**
     * Check if user can edit this portfolio.
     */
    public function canUserEdit($user)
    {
        return $this->user_id === $user->id;
    }
}
