<?php namespace App\Http\Controllers\Pub;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PropertyLinkCat;
use App\Models\PropertyLinkValue;
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
            $cat = Category::with(
                'parentCat',
                'childs.products.linksToCats',
                'products.tovar_1c_att'
            )
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

        $cat->childs = $cat->childs->filter(function ($cat) {
            return $cat->products->count()  > 0 ? true : false;
        });
        // Сео костыли:
        // Для определенной категории выводим дополнительный список ссылок
        // Перед товарами.
        
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
        
        $filter = Cats::get_filter_properties($cat->products);
        
        if (isset($_GET['is_filter']) and $_GET['is_filter'] == 'true') {
            $filter_props = Cats::get_from_request_filters();
            
            foreach ($filter_props as $slug => $values) {
                if ($slug === 'price') {
                    continue;
                }
                foreach ($values as $slug_value) {
                    $filter['props'][$slug]['items'][$slug_value]['checked'] = true;
                }
            }
            
            $cat->products = $cat->products
                ->filter(function($product) use ($filter_props, &$filter ) {
                    $props = array();
                    foreach ($product->productProperties as $property) {
                        $props[$property->name_property->slug][] = $property->slug;
                    }
                    foreach ($filter_props as $prop_slug => $prop_values) {
                        if ($prop_slug === 'price') {
                            $bool_max_price = false;
                            $bool_min_price = false;
                            if (isset($prop_values['to']) and intval($prop_values['to']) < $filter['props']['price']['items']['max'] ) {
                                $filter['props']['price']['items']['cur_max'] = $prop_values['to'];
                                $bool_max_price = true;
                            }
                            
                            if (isset($prop_values['from']) and intval($prop_values['from']) > $filter['props']['price']['items']['min'] ) {
                                $filter['props']['price']['items']['cur_min'] = $prop_values['from'];
                                $bool_min_price = true;
                            }
                            if ($bool_max_price or $bool_min_price) {
                                if (isset($product->tovar_1c_att->price)) {
                                    $cur_price = intval($product->tovar_1c_att->price);
                                    if (isset($prop_values['to']) and
                                        intval($prop_values['to']) < $cur_price) {
                                        return false;
                                    }
                                    if (isset($prop_values['from']) and
                                        intval($prop_values['from']) > $cur_price) {
                                        return false;
                                    }
        
                                    if(!$product->is_available or $product->is_available == null){
                                        return false;
                                    }
                                } else {
                                    return false;
                                }
                            }
                           
                            
                        } else {
                            if (!in_array($prop_slug, array_keys($props))) {
                                return false;
                            }
                            if (!array_intersect($props[$prop_slug], $prop_values)) {
                                return false;
                            }
                        }
                    }
                    return true;
                });   
        }

        
        $seo = self::seo()
            ->setTitle((mb_strlen($cat->tit, 'utf8') > 5 ? $cat->tit : $cat->name))
            ->setDescription($cat->description)
            ->setKeywords($cat->keywords)
            ->all();

        return view('public.cats.list_products', compact('cat', 'canonical', 'seo', 'filter'));
    }
    
    public static function get_from_request_filters()
    {
        $filter_props = $_GET;
        $filter_props = array_filter($filter_props, function ($value, $slug) {
            if (strpos($slug, 'f_') !== false or $slug ==='price') {
            
                return true;
            }
            return false;
        }, ARRAY_FILTER_USE_BOTH);
        /*
        * Выбираем только свойства, по которым сортируем товары
         */
        if ($filter_props) {
            $temp_props = array();
            foreach ($filter_props as $slug => $value) {
                if ($slug === 'price') {
                    $temp_props['price'] = $value;
                } else {
                    $new_slug = explode('f_', $slug)[1];
                    foreach ($value as $prop_slug => $status) {
                        if ($status === 'true') {
                            $temp_props[$new_slug][] = $prop_slug;
                        }
                    }
                }
            }
            $filter_props = $temp_props;
            unset($temp_props);
        }
        return $filter_props;
    }
    
    public static function get_filter_properties($products){
        if($products->count() > 0){
            $filter_properties = array();
            $filter_properties['price'] =array(
                'name' => 'Цена',
                'items' => array('min' => 99999, 'max' => 0)
            );
            foreach ($products as $product) {
                
                if (isset($product->tovar_1c_att->price) and $product->tovar_1c_att->price and $product->is_available) {
                    $temp_price = intval($product->tovar_1c_att->price);
   
                    $filter_properties['price']['items']['min'] = min($temp_price, $filter_properties['price']['items']['min']);
                    $filter_properties['price']['items']['max'] = max($temp_price, $filter_properties['price']['items']['max']);
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
                            'value' => $product_property->value,
                            'checked' => false
                        );
                    }
                }
            }
            
            $filter_properties['price']['items']['cur_max'] = $filter_properties['price']['items']['max'];
            $filter_properties['price']['items']['cur_min'] = $filter_properties['price']['items']['min'];
    
            if(count(array_keys($filter_properties)) > 1){
               return array(
                    'is_filter' => true,
                    'props' => $filter_properties
                );
            } else{
                return array(
                    'is_filter' => false,
                    'props' => array()
                );
            }
        }
        return array(
            'is_filter' => false,
            'props' => array()
        );
    }
    
}
