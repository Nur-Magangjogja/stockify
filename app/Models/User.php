<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //Role Constants
    const ROLE_ADMIN = 'Admin';
    const ROLE_MANAGER = 'Manajer Gudang';
    const ROLE_Staff = 'Staff Gudang';

    //Role Helpers
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isManager()
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isStaff()
    {
        return $this->role === self::ROLE_Staff;
    }

    public function getRoleLabelAttribute()
    {
            return match ($this->role) {
                self::ROLE_ADMIN => 'Admin',
                self::ROLE_MANAGER => 'Manager',
                self::ROLE_Staff => 'Staff',
                default => 'Unknown',
            };
    }

    /**
     * Get the user's avatar URL based on their name.
     */
    public function getAvatarUrlAttribute()
    {
        // Gunakan ui-avatars.com untuk men-generate gambar inisial
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=3b82f6&color=ffffff&bold=true&size=128";
    }

    public function stockTransactions()
    {
        return $this->hasMany(Stock_transaction::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    //Scopes
    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function getIsActiveAttribute()
    {
        return !is_null($this->email_verified_at);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }
}
