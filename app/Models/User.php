<?php

namespace App\Models;

use App\Models\UserAuth\Authenticatable;
use App\Models\UserAuth\Authorizable;
use App\Models\UserAuth\CanResetPassword;
use App\Models\UserAuth\HasDatabaseNotifications;
use App\Models\UserAuth\NotifiyContract;
use App\Models\UserAuth\RoutesNotifications;

use Ccq18\Notify\NotifyContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @package App\Models
 * @property $name
 * @property $email
 * @property $password
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @property string $role
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRole($value)
 */
class User  extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract,
    NotifyContract
{

    use Authenticatable, Authorizable, CanResetPassword;
    use HasDatabaseNotifications, RoutesNotifications;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'confirmation_token',
        'password',
        'api_token',
        'settings',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * @var array
     */
    protected $casts = [
        'settings' => 'array'
    ];


    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return $this->getKeyName();
    }


    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return $this->rememberTokenName;
    }
}
