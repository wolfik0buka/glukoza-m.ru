<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    protected $table = 'property';

    public function values_property()
    {
        return $this
            ->hasMany('App\Models\PropertyLinkValue', 'id_property', 'id');
    }
}
