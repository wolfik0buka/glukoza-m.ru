<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'category';

    protected $casts = [
        'parent' => 'Integer',
        'id' => 'Integer',
        'del' => 'Integer'
    ];

    public function scopeForSharing()
    {
        return $this
            ->where('id', '>', 0)
            ->where('del', 0)
            ->orderby('order_fld', 'asc')
            ->select('id', 'name', 'parent', 'order_fld','slug');
    }

    public function childs()
    {
        return $this->hasMany($this, 'parent', 'id');
    }

    public function parentCat()
    {
        return $this->hasOne('App\Models\Category', 'id', 'parent');
    }

    public function products()
    {
        return $this
            ->belongsToMany('App\Models\Tovar', 'link_tovar', 'id_cat', 'id_tovar', 'id')
            ->where('tovar.del', 0);
    }
}
