<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Services\SeoStandart\Seo;

abstract class Controller extends BaseController
{

	use DispatchesCommands, ValidatesRequests;


    public static function seo()
    {
        return (new Seo);
	}


}