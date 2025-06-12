<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Group;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'initial_capital',
        'status',
        'user_id',
        'group_id',
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
     * Get the group that owns the portfolio.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * The strategies that belong to the portfolio.
     */
    public function strategies(): BelongsToMany
    {
        return $this->belongsToMany(Strategy::class, 'portfolio_strategies')
                    ->withPivot(['allocation_amount', 'allocation_percent', 'status', 'last_rebalanced_at'])
                    ->withTimestamps();
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
     * Get the history entries for this portfolio.
     */
    public function history(): HasMany
    {
        return $this->hasMany(PortfolioHistory::class)->orderBy('created_at', 'desc');
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
