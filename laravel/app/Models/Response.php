<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $table = 'responses';
    public $timestamps = false;
    
    protected $dates = [
        'created_at',
    ];
    
    public function created_date()
    {
        return $this->created_at->diffForHumans();
    }

    public function linked_tovar()
    {
        return $this->belongsTo('App\Models\Tovar', 'tovar_id');
    }
}
