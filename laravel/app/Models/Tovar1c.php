<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tovar1c extends Model
{
    protected $table = '1c_tovar';

    protected $guarded = [];

    protected $casts = [
        'id' => 'String',
        'pres' => 'Integer',
        'id_tovar' => 'Integer',
        'price' => 'Double',
        'arc' => 'Integer'
    ];

}
