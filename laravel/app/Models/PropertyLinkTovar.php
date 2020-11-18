<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyLinkTovar extends Model
{
    protected $table = 'property_link_tovar';

    public function property_values()
    {
        return $this
            ->hasOne('App\Models\Property', 'id', 'id_value');
    }
    

}
