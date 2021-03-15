<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Pub\Search;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\LinkTovar;
use App\Models\Tovar;
use Illuminate\Http\Request;
use App\Services\Image\Facades\Image;
use App\Services\Translater;


class Products extends Controller
{

    public function searchByName(Request $request)
    {
        return Tovar::like(['name'], $request->get('query'))
          ->with('tovar_1c_att')
          ->where('del', 0)
          ->where('usluga', 0)
          ->get();
    }


	public function update(Request $request)
	{
	    $product = Tovar::where('id', $request->id)->first();
	    $product->desc_full = $request->desc_full;
	    $product->description = $request->description;
	    $product->name = $request->name;
	    $product->price = $request->price;
	    $product->price_old = $request->price_old;
	    $product->podzakaz = $request->podzakaz;

        if (!$product->symbol_code) {
            $code = Translater::translateCHPU($request->name);
            while (Tovar::where('symbol_code', $code)->first()) {
                $code .= rand(0, 9);
            }
            $product->symbol_code = $code;
        }


	    if ($product->save()) {
	        return 'Сохранено';
        }
	}


    public function add(Request $request)
    {
        $product = Tovar::create([
            'name' => $request->name
        ]);
        return response()
            ->json(['product_id' => $product->id], 200);
	}


    public function uploadPic($id, Request $request)
    {
        if ($request->file('file')) {
            $product = Tovar::with('tovar_1c_att')
                ->where('id', $id)
                ->first();

            $product->upload($request->file('file'));
            $product->createThumbs();

            return response()->json(['error' => false ], 200);

        } else {
            return response()->json(['error' => true ], 200);
        }
    }

    public function uploadPicDescription(Request $request)
    {
        if ($request->file('file')) {

            $link = Tovar::uploadPictureDescription($request->file('file'));

            return $link;

        } else {
            return response()->json(['error' => true ], 200);
        }
    }


    public function getAll()
    {
        return Tovar::with('tovar_1c_att', 'linksRelatedProducts')
          ->where('del', 0)
          ->get();
    }

    public function getAll_limit($skip,$take)
    {
        return Tovar::with('tovar_1c_att', 'linksRelatedProducts')
          ->where('del', 0)
          ->skip($skip)->take($take)
          ->get();
    }


    public function getSingle($id)
    {
        $product = Tovar::with('linksToCats',
                'tovar_1c_att',
                'relatedProducts.tovar_1c_att')
            ->findOrFail($id);
        return $product;
    }


    public function updateCatLinks(Request $request)
    {
        $links = collect($request->get('links'));
        $product_id = $request->get('product_id');

        LinkTovar::where('id_tovar', $product_id)->delete();

        if (count($links) > 0) {
            LinkTovar::insert($links->toArray());
        }
        return LinkTovar::where('id', $product_id)->get();
    }


    public function clone($product_id)
    {
        $source_product = $this->getSingle($product_id);

        $product = Tovar::create([
            "name" => $source_product->name,
            "canonical" => $source_product->canonical,
            "description" => $source_product->description,
            "desc_full" => $source_product->desc_full,
            "price" => $source_product->price,
            "price_old" => $source_product->price_old,
            "podzakaz" => $source_product->podzakaz,
        ]);

        $product->upload($source_product->src);
        $product->createThumbs();

        // клонируем привязки к разделам каталога
        foreach($source_product->linksToCats as $link_to_cat) {
            LinkTovar::create([
                'id_tovar' => $product->id,
                'id_cat' => $link_to_cat->id_cat
            ]);
        }

        return redirect()->to("/admin_new/index.php?page=nom&id=".$product->id);
    }

}