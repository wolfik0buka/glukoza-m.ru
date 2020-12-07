<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyLinkCat extends Model
{
    protected $table = 'property_link_cat';

    public function name_property()
    {
        return $this
            ->hasOne('App\Models\Property', 'id', 'id_property');
    }
    

}
