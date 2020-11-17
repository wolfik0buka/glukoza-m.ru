<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LinkProductRelated;
use Illuminate\Http\Request;

class ProductRelateds extends Controller
{


    public function add(Request $request)
    {
        return LinkProductRelated::create([
          'product_id' => $request->get('product_id'),
          'related_id' => $request->get('related_id'),
        ]);
	}


    public function remove(Request $request)
    {
        return LinkProductRelated::where('product_id', $request->get('product_id'))
          ->where('related_id', $request->get('related_id'))
          ->delete();
    }

}