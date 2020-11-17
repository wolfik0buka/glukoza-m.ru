<?php namespace App\Http\Controllers\Pub;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Tovar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Carbon\Carbon;

class Products extends Controller
{

    public static function single()
    {
        try {
            $product = Tovar::with([
                'linksToCats',
                'tovar_1c_att',
                'relatedProducts.tovar_1c_att'
            ])
                ->where('del', 0)
                ->findOrFail(Input::get('idTov'));
        } catch (ModelNotFoundException $ex) {
            app()->abort(404);
        }
        if($product->symbol_code){
            return redirect('https://glukoza-med.ru/product/' . $product->symbol_code, 301); 
        }

        $cat = self::getParentCatForProduct($product);

        $canonical = $product->canonical;

        $related = self::getRelated($cat, $product);

        $seo = self::seo()
            ->setTitle($product->name.' купить в Санкт-Петербурге')
            ->setH1($product->name)
            ->all();

        return view('public.product.page_product', compact('product', 'cat', 'related', 'canonical', 'seo'));
    }

    public static function singleSymbol(Request $request, $symbol_code)
    {
        try {
            $product = Tovar::with([
                'linksToCats',
                'tovar_1c_att',
                'relatedProducts.tovar_1c_att'
            ])
                ->where('del', 0)
                ->where('symbol_code','=',$symbol_code)
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {
            app()->abort(404);
        }

        $cat = self::getParentCatForProduct($product);

        $canonical = '/product/' . $product->symbol_code;

        $related = self::getRelated($cat, $product);

        $seo = self::seo()
            ->setTitle($product->name.' купить в Санкт-Петербурге')
            ->setH1($product->name)
            ->all();

        if(isset($product->updated_at) and $product->updated_at->greaterThan(Carbon::create(2012, 9, 5, 23, 26, 11))){
            $last_modified = $product->updated_at->timezone('GMT')->format('D, d M Y H:i:s T');
            
            if($request->headers->has('If-Modified-Since') and 
                Carbon::createFromFormat(
                    'D, d M Y H:i:s T',
                    $request->headers->get('If-Modified-Since')
                )->greaterThanOrEqualTo($product->updated_at)
            ){
                
                header("HTTP/1.1 304 Not Modified", false);
                header('Last-Modified: ' .  $last_modified, true , 304); 
                header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
                exit;

            }else{
                header('Last-Modified: ' .  $last_modified);            

            }
        }

        return view('public.product.page_product', compact('product', 'cat', 'related', 'canonical', 'seo'));
    }


    /**
     * @param $product
     * @return Category
     */
    public static function getParentCatForProduct($product)
    {
        $catId = (count($product->linksToCats) > 0)
            ? $product->linksToCats->first()->id_cat
            : Input::get('cat');

        try {
            $cat = Category::with('parentCat')
                ->findOrFail($catId);
        } catch (ModelNotFoundException $ex) {
            $cat = Category::with('parentCat')
                ->where('id', 16)
                ->first();
        }

        return $cat;
    }


    public static function getRelated($cat, $product)
    {
        return Tovar::whereHas('linksToCats', function($q) use($cat) {
            $q->where('id_cat', $cat->id);
        })
            ->where('id', '!=', $product->id)
            ->whereNotIn('id', $product->relatedProducts->pluck('id'))
            ->with('tovar_1c_att')
            ->get()
            ->filter(function($product) {
                return $product->is_available && !$product->del;
            })
            ->take(9);
    }


}