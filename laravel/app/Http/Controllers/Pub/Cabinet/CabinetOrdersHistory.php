<?php namespace App\Http\Controllers\Pub\Cabinet;

use App\Http\Controllers\Controller;
use App\User;
use Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CabinetOrdersHistory extends Controller
{

    public function index()
    {
        try {
            $user = CabinetUser::get();

            $seo = self::seo()
                ->setTitle('История заказов')
                ->setH1('Мои заказы')
                ->all();

            $bonuses = CabinetBonuses::getByUserId($user->id);

            return view('public.cabinet.cabinetOrdersHistory', compact('user', 'seo', 'bonuses'));

        } catch (ModelNotFoundException $ex) {
            // Error handling code
            //dd($ex);
        }
    }

}