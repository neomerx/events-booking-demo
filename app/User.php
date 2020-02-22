<?php

namespace App;

use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int               id
 * @property string            name
 * @property string            email
 * @property string            password
 * @property string            api_token
 * @property DateTimeInterface email_verified_at
 * @property string            remember_token
 */
final class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /** @inheritdoc */
    protected $table = 'users';

    /** @inheritdoc */
    protected $primaryKey = 'id';

    /** @inheritdoc */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /** @inheritdoc */
    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    /** @inheritdoc */
    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /** @inheritdoc */
    protected $casts = [
        'id'                => 'integer',
        'email_verified_at' => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];
}
