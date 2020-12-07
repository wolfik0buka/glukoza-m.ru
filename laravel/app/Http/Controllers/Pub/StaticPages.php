<?php namespace App\Http\Controllers\Pub;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StaticPages extends Controller
{


    public static function index(Request $request)
    {
        $data = $request->all();

        try {
            $page = StaticPage::where('alias', $request->alias)->firstOrFail();

            if ($request->alias == 'contacts') {
                return self::contacts($page);
            } else {
                $seo = self::seo()
                    ->setTitle($page->title)
                    ->all();

                return view('public.static.layout', compact('page', 'data', 'seo'))
                    ->render();
            }
        } catch (ModelNotFoundException $ex) {
            return response()->view('errors.404')->setStatusCode(404);
        }
    }


    public static function contacts($page)
    {
        $seo = self::seo()
            ->setTitle($page->title)
            ->all();

        return view('public.static.contacts', compact('page', 'seo'));
    }


    public function pageCardPayment()
    {
        $seo = self::seo()
            ->setTitle('Оплата с помощью банковской карты')
            ->all();

        return view('public.static.payment_cards', compact( 'seo'));
    }


    public function pageRequisites()
    {
        $seo = self::seo()
            ->setTitle('Реквизиты')
            ->all();

        return view('public.static.requisites', compact( 'seo'));
    }



    public function pageConfirm()
    {
        $seo = self::seo()
        ->setTitle('Согласие на обработку персональных данных')
        ->all();

        return view('public.static.confirm', compact( 'seo'));
    }


    public function pageOferta()
    {
        $seo = self::seo()
        ->setTitle('Правила магазина - публичная оферта')
        ->all();

        return view('public.static.oferta', compact( 'seo'));
    }


    public function pageBeznal()
    {
        $seo = self::seo()
        ->setTitle('Для юридических лиц')
        ->all();

        return view('public.static.beznal', compact( 'seo'));
    }
    public function pageDostavka(Request $request)
    {
        $request->alias = 'delivery';
        return $this->index($request);
    }


    public function pageKontakty(Request $request)
    {
        $request->alias = 'contacts';
        return $this->index($request);
    }


    public function pageOplata(Request $request)
    {
        $request->alias = 'payment';
        return $this->index($request);
    }


    public function pageBonusnayaProgramma(Request $request)
    {
        $request->alias = 'bonus';
        return $this->index($request);
    }


    public function pageVozvrat(Request $request)
    {
        $request->alias = 'return';
        return $this->index($request);
    }


    public function pagePolitikaKonfidencialnosti(Request $request)
    {
        $request->alias = 'privacy';
        return $this->index($request);
    }
    
    public function pageOrderInstruction(Request $request)
    {
        $request->alias = 'kak-sdelat-zakaz';
        return $this->index($request);
    }
}
