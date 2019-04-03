<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $connection = "principal";

    protected $fillable = [
        'name','email','cpf','birth','password','sex','level_id'
    ];

    protected $hidden = [];
    protected $dates = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];
}
