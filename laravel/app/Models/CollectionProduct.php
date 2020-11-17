<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionProduct extends Model
{

    protected $table = 'collection_products';

    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

}
