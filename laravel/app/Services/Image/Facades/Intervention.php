<?php namespace App\Services\Image\Facades;

use Illuminate\Support\Facades\Facade;

class Intervention extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'intervention';
    }
}
