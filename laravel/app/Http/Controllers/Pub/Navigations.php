<?php namespace App\Http\Controllers\Pub;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;

class Navigations extends Controller
{

    public static function expand_catalog()
    {
        $cats = Category::with('childs')->where('del', 0)->where('parent', 1)->select('id', 'name', 'parent')->get();
        return view('public.expand_menu', compact('cats'))->render();
    }

}
