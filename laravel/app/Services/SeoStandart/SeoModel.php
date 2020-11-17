<?php namespace App\Services\SeoStandart;

use Illuminate\Database\Eloquent\Model;

class SeoModel extends Model
{
    protected $table = 'seo';

    protected $guarded = [
        'id'
    ];

    protected $casts = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


}
