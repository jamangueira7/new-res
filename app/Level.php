<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Level extends Model
{
    use Notifiable;
    use SoftDeletes;

    protected $connection = "principal";

    protected $fillable = [
        'description'
    ];

    protected $hidden = [];
    protected $dates = [
        'deleted_at',
        'updated_at',
        'created_at'
    ];
}
