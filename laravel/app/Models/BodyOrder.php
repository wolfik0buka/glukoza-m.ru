<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BodyOrder extends Model
{
    use SoftDeletes;

    protected $table = 'body_order';

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'parent' => 'Integer',
        'id_tovar' => 'Integer',
        'price' => 'Double',
        'amount' => 'Integer',
    ];

    protected $hidden = [
        'created_at',
        'del',
        'deleted_at',
        'updated_at',
        'parent'
    ];


    public function tovar_att()
    {
        return $this
            ->hasOne('App\Models\Tovar', 'id', 'id_tovar');
    }


    public function product()
    {
        return $this
            ->hasOne('App\Models\Tovar', 'id', 'id_tovar');
    }


}