<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    /**
     * Get the role label for display.
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'superadministrator' => 'Super Administrator',
            'administrator'      => 'Administrator',
            'user'               => 'User',
            default              => ucfirst($this->role),
        };
    }

    /**
     * Check if the user is a superadministrator.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadministrator';
    }

    /**
     * Check if the user is an administrator or higher.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['administrator', 'superadministrator']);
    }
}
