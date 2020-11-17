<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectionProduct;
use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Tovar;

class Collections extends Controller
{

	public function getAll()
	{
		return Collection::with('products.tovar_1c_att')
            ->get();
	}


    public function searchProducts(Request $request)
    {
        return Tovar::like(['name'], $request->get('query'))
            ->with('linksToCats', 'tovar_1c_att')
            ->where('usluga', 0)
            ->get()
            ->sortBy(function($product) {
                return $product->is_available ? 0 : 1;
            })
            ->values()
            ->toArray();
	}


    public function removeProductFromCollection(Request $request)
    {
        CollectionProduct::where('product_id', $request->get('product_id'))
            ->where('collection_id', $request->get('collection_id'))
            ->delete();
	}


    public function addProductToCollection(Request $request)
    {
        CollectionProduct::create($request->all());

        return Collection::with('products.tovar_1c_att')
            ->find($request->get('collection_id'));
    }

}
