<?php namespace App\Http\Controllers\Pub\Cabinet;

use App\Http\Controllers\Controller;
use App\Services\BonusSystem;

class CabinetBonuses extends Controller
{

    public static function getByUserId($user_id)
    {
        $loyalty = self::loyaltyInstanceByUserId($user_id);

        return (object) [
            'used' => $loyalty->used_bonuses,
            'balance' => $loyalty->current_balance,
            'all' => $loyalty->all_bonuses,
        ];
    }


    protected static function loyaltyInstanceByUserId($user_id)
    {
        return new BonusSystem($user_id);
    }

}