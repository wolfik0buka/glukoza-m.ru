<?php namespace App\Http\Controllers\Pub;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PropertyLinkCat;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Cats extends Controller
{
    public static function single($request)
    {
        try {
            $cat = Category::with('parentCat', 'childs.products.linksToCats', 'products.tovar_1c_att')
                ->where('id', $request->cat)
                ->firstOrFail();
            $canonical = '/index.php?page=cat&cat='.$cat->id;
        } catch (ModelNotFoundException $ex) {
            return response()
                ->view('errors.404')
                ->setStatusCode(404);
        }
        if ($cat->slug) {
            return redirect('https://glukoza-med.ru/category/' . $cat->slug, 301);
        }

        // Сортируем по имени
        $cat->products = $cat->products
            ->map(function ($product) {
                $product->sort = implode('-', [
                    $product->is_available ? 0 : 1,
                    $product->name
                ]);
                return $product;
            })
            ->sortBy('sort');

        $seo = self::seo()
            ->setTitle((mb_strlen($cat->tit, 'utf8') > 5 ? $cat->tit : $cat->name))
            ->setDescription($cat->description)
            ->setKeywords($cat->keywords)
            ->all();

        return view('public.cats.list_products', compact('cat', 'canonical', 'seo'));
    }

    public static function singleSlug($slug)
    {
        try {
            $cat = Category::with('parentCat', 'childs.products.linksToCats', 'products.tovar_1c_att', 'products.productProperties.name_property')
                ->where('slug', $slug)
                ->firstOrFail();
            $canonical = '/category/'.$cat->slug;
        } catch (ModelNotFoundException $ex) {
            return response()
                ->view('errors.404')
                ->setStatusCode(404);
        }

        // Сортируем по имени
        $cat->products = $cat->products
            ->map(function ($product) {
                $product->sort = implode('-', [
                    $product->is_available ? 0 : 1,
                    $product->name
                ]);
                return $product;
            })
            ->sortBy('sort');

        // Сео костыли:
        // Для определенной категории выводим дополнительный список ссылок
        // Перед товарами.
        $cat->childs = $cat->childs->filter(function ($cat) {
            return $cat->products->count()  > 0 ? true : false;
        });
        if ($cat->id === 2) {
            $cat->childs = $cat->childs->map(function ($cat) {
                $linksAnchors = [
                    'Глюкометры Акку-Чек' => 'Акку-чек',
                    'Глюкометры Ван Тач' => 'Ван Тач',
                    'Глюкометры Сателлит' => 'Сателлит',
                    'Глюкометры Диаконт' => 'Диаконт',
                    'Глюкометры Контур' => 'Контур',
                    'Глюкометры Gmate Life' => 'Gmate Life',
                    'Глюкометры Ebsensor' => 'Ebsensor',
                    'Глюкометры Caresense' => 'CareSense',
                    'Глюкометры Freestyle' => 'Freestyle',
                    'Глюкометры для детей' => 'Для детей',
                    'Глюкометры для пожилых' => 'Для пожилых людей'
                ];
                $cat->name = in_array($cat->name, array_keys($linksAnchors)) ?
                    $linksAnchors[$cat->name] :
                    $cat->name;
                return $cat;
            });
        }
        $filter = array(
            'is_filter' => false,
            'props' => array(),
        );
        if($cat->products->count() > 0){
            $filter_properties = array();
            $filter_properties['price'] = array('min' => 99999, 'max' => 0);
            foreach ($cat->products as $product) {
                
                if (isset($product->tovar_1c_att->price)) {
                    $temp_price = intval($product->tovar_1c_att->price);
                    $filter_properties['price']['min'] = min($temp_price, $filter_properties['price']['min']);
                    $filter_properties['price']['max'] = max($temp_price, $filter_properties['price']['max']);
                }
                
                foreach ($product->productProperties as $product_property) {
                    $prop_slug = $product_property->name_property->slug;

                    if(!in_array($prop_slug, array_keys($filter_properties))){
                        $filter_properties[$prop_slug] = array(
                            'name' => $product_property->name_property->name,
                            'items' => array(),
                        );
                    }

                    if (in_array($product_property->slug, array_keys($filter_properties[$prop_slug]['items']))) {
                        $filter_properties[$prop_slug]['items'][$product_property->slug]['count'] += 1;
                    } else {
                        $filter_properties[$prop_slug]['items'][$product_property->slug] = array(
                            'count' => 1,
                            'value' => $product_property->value
                        );
                    }
                }
            }
            if(count(array_keys($filter_properties)) > 1){
                $filter = array(
                    'is_filter' => true, 
                    'props' => $filter_properties
                );
            }
        }


        

        $seo = self::seo()
            ->setTitle((mb_strlen($cat->tit, 'utf8') > 5 ? $cat->tit : $cat->name))
            ->setDescription($cat->description)
            ->setKeywords($cat->keywords)
            ->all();

        return view('public.cats.list_products', compact('cat', 'canonical', 'seo', 'filter'));
    }
}
