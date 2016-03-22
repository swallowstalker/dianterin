<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

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
class User extends Authenticatable
{

    const ROLE_ADMIN = 498;
    const ROLE_USER = 1;

    protected $table = "user_customer";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'twitter', 'phone',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function transactions() {
        return $this->hasMany('App\GeneralTransaction', 'user_id');
    }


    public function getBalanceAttribute() {

        $balance = $this->transactions()->byOwner($this->id)->sum("movement");
        return $balance;
    }
}
