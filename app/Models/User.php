<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * class User
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $email
 * @property boolean $is_blocked
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'two_factor_method',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_email_code',
        'two_factor_email_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_email_code',
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
            'two_factor_enabled' => 'boolean',
            'two_factor_recovery_codes' => 'encrypted:collection',
            'two_factor_secret' => 'encrypted',
            'is_blocked' => 'boolean',
        ];
    }


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    // Check if user has a specific role
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return $role->intersect($this->roles)->isNotEmpty();
    }

    // Check if user has any of the given roles
    public function hasAnyRole($roles): bool
    {
        return $this->roles()->whereIn('name', (array)$roles)->exists();
    }

    // Check if user has a specific permission
    public function hasPermission($permission): bool
    {
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('name', $permission);
        })->exists();
    }

    // Assign role to user
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        $this->roles()->syncWithoutDetaching($role);
    }

    // Remove role from user
    public function removeRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->firstOrFail();
        }

        $this->roles()->detach($role);
    }

    // Sync roles (replace all existing roles)
    public function syncRoles($roles): void
    {
        $roleIds = [];

        foreach ((array)$roles as $role) {
            if (is_string($role)) {
                $role = Role::query()->where('name', $role)->firstOrFail();
                $roleIds[] = $role->id;
            } else {
                $roleIds[] = $role;
            }
        }

        $this->roles()->sync($roleIds);
    }

    public function blockUser(): void
    {
        $this->is_blocked = true;
        $this->save();
    }

    public function isBlocked(): bool
    {
        return $this->is_blocked;
    }

    public function getAllPermissions()
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions->pluck('name');
        })->unique()->values();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmail);
    }
}
