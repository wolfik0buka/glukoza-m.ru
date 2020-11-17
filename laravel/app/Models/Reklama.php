<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reklama extends Model
{
    protected $table = 'reklama';
    protected $casts = [
        'win' => 'integer'
    ];
}