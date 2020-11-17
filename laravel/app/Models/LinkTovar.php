<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkTovar extends Model
{
    protected $table = 'link_tovar';

    protected $guarded = [];

    public function tovar_att()
    {
        return $this->hasOne('App\Models\Tovar', 'id', 'id_tovar');
    }
}
