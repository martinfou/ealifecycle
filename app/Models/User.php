<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The groups that belong to the user.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'user_groups')
                    ->withPivot('permission')
                    ->withTimestamps();
    }

    /**
     * Get groups where user has read permission.
     */
    public function readGroups(): BelongsToMany
    {
        return $this->groups()->wherePivot('permission', 'read');
    }

    /**
     * Get groups where user has write permission.
     */
    public function writeGroups(): BelongsToMany
    {
        return $this->groups()->wherePivot('permission', 'write');
    }

    /**
     * Check if user has any permission in a specific group.
     */
    public function hasPermissionInGroup($groupId): bool
    {
        return $this->groups()->where('groups.id', $groupId)->exists();
    }

    /**
     * Check if user has write permission in a specific group.
     */
    public function hasWritePermissionInGroup($groupId): bool
    {
        return $this->groups()
                    ->where('groups.id', $groupId)
                    ->wherePivot('permission', 'write')
                    ->exists();
    }

    /**
     * Get user's permission level in a specific group.
     */
    public function getPermissionInGroup($groupId): ?string
    {
        $group = $this->groups()->where('groups.id', $groupId)->first();
        return $group ? $group->pivot->permission : null;
    }

    /**
     * Get all group IDs that the user has access to.
     */
    public function getAccessibleGroupIds(): array
    {
        return $this->groups()->pluck('groups.id')->toArray();
    }

    /**
     * Get the strategies for the user.
     */
    public function strategies(): HasMany
    {
        return $this->hasMany(Strategy::class);
    }

    /**
     * Get the portfolios for the user.
     */
    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    /**
     * Get the trades imported by the user.
     */
    public function importedTrades(): HasMany
    {
        return $this->hasMany(Trade::class, 'imported_by_user_id');
    }

    /**
     * Get the status history changes made by the user.
     */
    public function statusChanges(): HasMany
    {
        return $this->hasMany(StatusHistory::class, 'changed_by_user_id');
    }
}
