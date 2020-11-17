<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCapture extends Model
{
    protected $table = 'order_capture';
    protected $guarded = ['id'];
    protected $casts = [
        'content' => 'Array'
    ];
}
