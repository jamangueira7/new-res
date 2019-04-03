<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UserPermission extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $connection = "principal";

    protected $fillable = [
        'cpf','name','phone','birth','gender','notes','email','password','status','permission',
    ];

    protected $hidden = [
        'id'
    ];
    protected $dates = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];
}
