<?php namespace App\Http\Controllers\Pub;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\StaticPage;
use App\Models\Tovar;

class Sitemap extends Controller
{
    const BASE_URL = 'https://glukoza-med.ru';



    public function index()
    {
        $items = array_merge($this->getProducts(), $this->getCategories(), $this->getStaticPages());

        return $this->xmlOutput($items);
    }



    public function getProducts()
    {
        return Tovar::with('linksToCats')
            ->where('del', 0)
            ->get()
            ->filter(function($product) {
                return isset($product->linksToCats->first()->id);
            })
            ->map(function($product) {
                return [
                    'url' => self::BASE_URL . link_product($product->linksToCats->first()->id, $product->id),
                    'priority' => '0.7',
                ];
            })
            ->toArray();
    }



    public function getCategories()
    {
        return Category::with('parentCat')
            ->where('id', '>', 0)
            ->where('del', 0)
            ->get()
            ->map(function($cat) {
                return [
                    'url' => self::BASE_URL . link_cat($cat->id),
                    'priority' => '0.9',
                ];
            })
            ->toArray();

    }



    public function getStaticPages()
    {
        return StaticPage::where('del', 0)
            ->get()
            ->map(function($page) {
                return [
                    'url' => self::BASE_URL . link_static($page->alias),
                    'priority' => '0.5',
                ];
            })
            ->toArray();
    }



    public function xmlOutput($items)
    {
        return response(view('public.sitemap.layout', compact('items')), 200, ['Content-Type' => 'text/xml']);
    }

}
