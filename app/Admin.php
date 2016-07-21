<?php

namespace App;
use Illuminate\Database\Schema\Builder;

/**
 * App\User
 *
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $twitter
 * @property string $phone
 * @property integer $role
 * @property integer $deposit
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $remember_token
 */
class Admin extends User
{

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('admin', function ($builder) {
            $builder->where("role", User::ROLE_ADMIN);
        });
    }
}
