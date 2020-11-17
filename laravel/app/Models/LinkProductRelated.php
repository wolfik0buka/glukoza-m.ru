<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkProductRelated extends Model
{

    protected $table = 'link_product_related';

    protected $guarded = [];


    public function product()
    {
        return $this
          ->hasOne('App\Models\Tovar', 'id', 'product_id');
    }


}