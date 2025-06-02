<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The users that belong to the group.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_groups')
                    ->withPivot('permission')
                    ->withTimestamps();
    }

    /**
     * Get users with read permission in this group.
     */
    public function readUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('permission', 'read');
    }

    /**
     * Get users with write permission in this group.
     */
    public function writeUsers(): BelongsToMany
    {
        return $this->users()->wherePivot('permission', 'write');
    }

    /**
     * Get the strategies that belong to this group.
     */
    public function strategies(): HasMany
    {
        return $this->hasMany(Strategy::class);
    }

    /**
     * Check if a user has any permission in this group.
     */
    public function hasUser($userId): bool
    {
        return $this->users()->where('users.id', $userId)->exists();
    }

    /**
     * Check if a user has write permission in this group.
     */
    public function hasUserWithWritePermission($userId): bool
    {
        return $this->users()
                    ->where('users.id', $userId)
                    ->wherePivot('permission', 'write')
                    ->exists();
    }

    /**
     * Get the permission level for a specific user in this group.
     */
    public function getUserPermission($userId): ?string
    {
        $user = $this->users()->where('users.id', $userId)->first();
        return $user ? $user->pivot->permission : null;
    }
}
