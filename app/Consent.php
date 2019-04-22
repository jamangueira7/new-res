<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Consent extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $connection = "principal";

    protected $fillable = [
        'code','archive','status','recipient','unimed', 'user_id'
    ];

    protected $hidden = [];
    protected $dates = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];
}
