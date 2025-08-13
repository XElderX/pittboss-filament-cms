<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Users extends Model
{

    use SoftDeletes;

    protected $connection = 'external';
    protected $table = 'users';

    public $timestamps = false;

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
        'id'
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
}
