<?php namespace App\Http\Controllers\Pub\Cabinet;

use App\Http\Controllers\Controller;
use App\User;
use Session;

class CabinetUser extends Controller
{

    public static function get()
    {
        // $user_id = app()->isLocal()
        //     ? 1662385
        //     : Session::get('user.id');
        $user_id = Session::get('user.id');
        return User::with('orders.productLinks.product')
            ->findOrFail($user_id);
    }
}
