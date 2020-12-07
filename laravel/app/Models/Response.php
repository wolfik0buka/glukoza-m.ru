<?php namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Response extends Model
{
    protected $table = 'responses';
    
    protected $dates = [
        'created_at',
    ];
    
    public function created_date()
    {
        return $this->created_at->diffForHumans();
    }

}
