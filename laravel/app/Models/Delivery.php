<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $table = 'delivery';

    protected $hidden = [
        'created_at',
        'updated_at',
        'dop_fld',
        'route'
    ];

    protected $casts = [
        'id' => 'Integer'
    ];


    public function product()
    {
        return $this
            ->hasOne('App\Models\Tovar', 'id', 'id_tovar');
    }


    public function type_att()
    {
        return $this
            ->hasOne('App\Models\DeliveryType', 'id', 'type');
    }


}