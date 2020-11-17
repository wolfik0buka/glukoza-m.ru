<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCancelReason extends Model
{
    protected $table = 'order_cancel_reasons';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
