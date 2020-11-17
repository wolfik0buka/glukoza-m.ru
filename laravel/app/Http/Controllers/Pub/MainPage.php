<?php namespace App\Http\Controllers\Pub;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Reklama;
use App\Models\StaticPage;
use App\Models\Tovar;
use Illuminate\Http\Request;
use App\Models\Collection;

class MainPage extends Controller
{
    public static function index(Request $request)
    {
        $page = StaticPage::find(5);


        // Выбираем товары из раздела акции для "Лучшие предложения дня"
        $catOffers = Category::with('products.tovar_1c_att', 'products.linksToCats')->find(16);

        $bestOffers = $catOffers->products
            ->filter(function($product) {
                return ((($product->tovar_1c_att['pres'] == 1) || ($product->podzakaz == 1)) && ($product->del == 0));
            })
            ->sortBy(function ($product) {
                return $product->name;
            });

        $main_slider_products = Collection::with('products.tovar_1c_att', 'products.linksToCats')
            ->find(1)
            ->products
            ->map(function($product) {
                $product->link = count($product->linksToCats) > 0
                    ? link_product($product->linksToCats->first()->id_cat, $product->id)
                    : null;
                return $product;
            });

        $seo = self::seo()->all();

        return view('public.page_main.layout', compact('page', 'bestOffers', 'main_slider_products', 'seo'));
    }
}