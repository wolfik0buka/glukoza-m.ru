<?php namespace App\Http\Controllers\Pub;

use App\Http\Controllers\Controller;
use App\Models\Tovar;
use Illuminate\Http\Request;

class Search extends Controller
{


	public function index(Request $request)
	{
        $query = $request->get('query');

        if ($query) {
            $products = $this->searchProducts($query);
            $count_products = count($products);

            return response()->json(compact('count_products', 'query'), 200);
        } else {
            return 'Слишком короткий запрос';
        }
	}


    public function page($query)
    {
        $products = $this->searchProducts($query);

        return view('public.search.searchPage', compact('products', 'query'));
	}
	

    public function searchProducts($query)
    {
        return Tovar::like(['name'], $query)
            ->whereHas('linksToCats', function($query) { return $query; })
            ->with('linksToCats', 'tovar_1c_att')
            ->where('del', 0)
            ->where('usluga', 0)
            ->select('id', 'name', 'podzakaz', 'price', 'symbol_code')
            ->get()
            ->filter(function($product) {
                return $product->tovar_1c_att || $product->podzakaz;
            })
            ->map(function($product) {
                $product->link = link_product($product->linksToCats->first()->id_cat, $product->id);

                if (isset($product->tovar_1c_att->pres) && $product->tovar_1c_att->pres == 1) {
                    $product->available = 1;
                } elseif ($product->podzakaz == 1) {
                    $product->available = 1;
                } else {
                    $product->available = 0;
                }

                return $product;
            })
            ->sortByDesc('available');

	}

}
