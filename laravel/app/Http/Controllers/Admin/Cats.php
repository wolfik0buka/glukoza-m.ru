<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

class Cats extends Controller
{

    public function all()
    {
        return Category::where('del', 0)
            ->orderBy('order_fld', 'asc')
            ->get();
    }

}