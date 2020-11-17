<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{

	protected $table = 'collections';

	protected $guarded = [
	    'id'
    ];

	protected $hidden = [
	    'created_at',
	    'updated_at',
	    'deleted_at',
    ];


    public function products()
    {
        return $this
            ->belongsToMany('App\Models\Tovar', 'collection_products', 'collection_id', 'product_id', 'id')
            ->where('tovar.del', 0);
    }

}
