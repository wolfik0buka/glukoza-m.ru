<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyLinkValue extends Model
{
    protected $table = 'property_link_value';
    public function name_property()
    {
        return $this
            ->hasOne('App\Models\Property', 'id', 'id_property');
    }
}
