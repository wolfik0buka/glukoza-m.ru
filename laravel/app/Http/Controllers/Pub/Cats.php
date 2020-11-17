<?php namespace App\Http\Controllers\Pub;

use App\Http\Controllers\Controller;
use App\Models\Category;
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
            $cat = Category::with('parentCat', 'childs.products.linksToCats', 'products.tovar_1c_att')
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

        $seo = self::seo()
            ->setTitle((mb_strlen($cat->tit, 'utf8') > 5 ? $cat->tit : $cat->name))
            ->setDescription($cat->description)
            ->setKeywords($cat->keywords)
            ->all();

        return view('public.cats.list_products', compact('cat', 'canonical', 'seo'));
    }
}
