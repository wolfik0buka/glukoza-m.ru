<?php namespace App\Services;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BonusSystem
{
    protected $user;

    public $current_balance = false;
    public $used_bonuses = false;
    public $all_bonuses = false;


    public function __construct($user_id)
    {
        $this->getUserById($user_id);

        if ($this->user) {
            $this->calculateAllBonuses();
            $this->calculateUsedBonuses();
            $this->calculateBalance();
        }
    }


    protected function calculateBalance()
    {
        $this->current_balance = intval($this->all_bonuses) - intval($this->used_bonuses);
        if ($this->current_balance < 0) {
            $this->current_balance = 0;
        }
    }


    protected function calculateAllBonuses()
    {
        $sum_by_all_orders = collect($this->user->orders)
            ->where('status_pay', 1)
            ->sum(function ($order) {
                return $order->body_att->sum(function ($item) {
                    return intval($item->price) * intval($item->amount);
                });
            });

        $this->all_bonuses = intval(floor($sum_by_all_orders / 100));
    }


    protected function calculateUsedBonuses()
    {
        $this->used_bonuses = collect($this->user->orders)
            ->sum(function ($order) {
                return $order->bonus;
            });

        if ($this->used_bonuses > $this->all_bonuses) {
            $this->used_bonuses = $this->all_bonuses;
        }
    }


    protected function getUserById($id)
    {
        if ($id instanceof User) {
            $this->user = $id;
        } else {
            try {
                $this->user = User::with([
                    'orders' => function ($q) { return $q->where('status_pay', 1); },
                    'orders.body_att'
                ])->findOrFail(+$id);
            } catch (ModelNotFoundException $ex) {
                $this->user = false;
            }
        }
    }


}