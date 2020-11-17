<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'id' => 'String'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function get($id)
    {
        return $this
            ->where('id', $id)
            ->first();
    }


    public function getValueAttribute()
    {
        if ($this->attributes['type']==='integer') {
            return (int) $this->attributes['value'];
        }
        if ($this->attributes['type']==='text') {
            return (string) $this->attributes['value'];
        }

        return $this->attributes['value'];
    }


}
