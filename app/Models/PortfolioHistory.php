<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioHistory extends Model
{
    protected $fillable = [
        'portfolio_id',
        'strategy_id',
        'action_type',
        'old_values',
        'new_values',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the portfolio that this history belongs to.
     */
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    /**
     * Get the strategy that this history relates to (if any).
     */
    public function strategy(): BelongsTo
    {
        return $this->belongsTo(Strategy::class);
    }

    /**
     * Get the user who made this change.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get human-readable action description.
     */
    public function getActionDescriptionAttribute(): string
    {
        return match($this->action_type) {
            'created' => 'Portfolio created',
            'updated' => 'Portfolio updated',
            'status_changed' => 'Portfolio status changed',
            'strategy_added' => 'Strategy added to portfolio',
            'strategy_updated' => 'Strategy allocation updated',
            'strategy_activated' => 'Strategy activated in portfolio',
            'strategy_paused' => 'Strategy paused in portfolio',
            'strategy_removed' => 'Strategy removed from portfolio',
            default => 'Unknown action',
        };
    }

    /**
     * Get detailed description with values.
     */
    public function getDetailedDescriptionAttribute(): string
    {
        $description = $this->action_description;
        
        if ($this->strategy) {
            $description .= " ({$this->strategy->name})";
        }

        // Add specific details based on action type
        switch ($this->action_type) {
            case 'status_changed':
                if ($this->old_values && $this->new_values) {
                    $oldStatus = $this->old_values['status'] ?? 'unknown';
                    $newStatus = $this->new_values['status'] ?? 'unknown';
                    $description .= " from {$oldStatus} to {$newStatus}";
                }
                break;
                
            case 'strategy_added':
            case 'strategy_updated':
                $values = $this->new_values ?? [];
                $details = [];
                
                if (isset($values['allocation_amount']) && $values['allocation_amount'] > 0) {
                    $details[] = '$' . number_format($values['allocation_amount'], 2);
                }
                
                if (isset($values['allocation_percent']) && $values['allocation_percent'] > 0) {
                    $details[] = number_format($values['allocation_percent'], 1) . '%';
                }
                
                if (!empty($details)) {
                    $description .= ' with allocation: ' . implode(', ', $details);
                }
                break;
        }

        return $description;
    }

    /**
     * Create a new portfolio history entry.
     */
    public static function logActivity(
        int $portfolioId,
        string $actionType,
        int $userId,
        ?int $strategyId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $notes = null
    ): self {
        return self::create([
            'portfolio_id' => $portfolioId,
            'strategy_id' => $strategyId,
            'action_type' => $actionType,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'notes' => $notes,
            'user_id' => $userId,
        ]);
    }
}
