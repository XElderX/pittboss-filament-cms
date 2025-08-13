<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Users extends Authenticatable implements FilamentUser, HasName
{
    use SoftDeletes;

    protected $connection = 'external';
    protected $table = 'users';

    public $timestamps = false;

    // Add primary key if it's not 'id'
    protected $primaryKey = 'id'; // Adjust if your primary key is different

    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'enabled',
        'role',
        'sub_user_merchant_id',
        'partner_id',
        'support_group_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
    ];

    protected $attributes = [
        'role'                 => 'merchant',
        'enabled'              => true,
        'reset_password'       => true,
        'two_fa_enable'        => false
    ];

    protected $casts = [
        'email_verified_at'    => 'datetime',
        'enabled'              => 'boolean',
        'reset_password'       => 'boolean',
        'password' => 'hashed', // Add this to ensure proper password hashing
    ];

    public const ROLE_ADMIN = 'admin';
    public const ROLE_MERCHANT = 'merchant';
    public const ROLE_SUB_USER = 'sub_user';
    public const ROLE_PARTNER = 'partner';
    public const ROLE_SUPPORT = 'support';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_MERCHANT,
        self::ROLE_SUB_USER,
        self::ROLE_PARTNER,
        self::ROLE_SUPPORT
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName(): string
    {
        return 'email';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword(): string
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     */
    public function getRememberToken(): ?string
    {
        return $this->remember_token;
    }

    /**
     * Set the token value for the "remember me" session.
     */
    public function setRememberToken($value): void
    {
        $this->remember_token = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     */
    public function getRememberTokenName(): string
    {
        return 'remember_token';
    }

    public function getFilamentName(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''))
            ?: ($this->email ?? 'Unknown User');
    }

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        if ($panel->getId() === 'adminPanel') {
            return $this->role === 'admin';
        }

        if ($panel->getId() === 'merchant') {
            return $this->role === 'merchant';
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isEnabled(): bool
    {
        return $this->enabled === true;
    }
}
