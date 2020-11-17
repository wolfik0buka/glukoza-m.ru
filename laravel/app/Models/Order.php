<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'order_shop';

    protected $guarded = [
        'id',
        'glukoza_number_formatted'
    ];

    protected $casts = [
        'id' => 'Integer',
        'user_id' => 'Integer',
        'number' => 'Integer',
        'delivery' => 'Integer',
        'status' => 'Integer',
        'status_agreed' => 'Integer',
        'status_pay' => 'Integer',
        'bonus' => 'Integer',
        'manager_id' => 'Integer',
        'onmedi_order_id' => 'Integer',
        'payment_type_id' => 'Integer',
        'bitrix_task_id' => 'Integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'number_formatted',
        'is_done',
        'is_success',
    ];


    public function body_att()
    {
        return $this
          ->hasMany('App\Models\BodyOrder', 'parent', 'id');
    }


    public function productLinks()
    {
        return $this
          ->hasMany('App\Models\BodyOrder', 'parent', 'id');
    }


    public function deliveryPickupPoint()
    {
        return $this
            ->hasOne('App\Models\Point', 'parent', 'id');
    }

    public function deliveryModel()
    {
        return $this
            ->hasOne('App\Models\Delivery', 'id', 'delivery');
    }


    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }


    public static function makeHash(Order $order)
    {
        $salt = implode('-', [
            'glukoza',
            $order->id,
            $order->number
        ]);

        $hash = hash('md5', $salt, false);

        $order->payment_hash = $hash;
        $order->save();

        return $order;
    }


    public function getDeliveryPrice()
    {
        $productLinks = $this->getRelation('productLinks');

        $deliveryProductLinks = collect($productLinks)
            ->filter(function($link) {
                return $link->product->usluga === 1;
            });

        if (count($deliveryProductLinks) > 0) {
            return $deliveryProductLinks->first()->price;
        } else {
            return 0;
        }
    }


    public function getProductsSum()
    {
        $productLinks = $this->getRelation('productLinks');

        return collect($productLinks)->sum(function($link) {
            if ($link->product->usluga === 0) {
                return $link->price * $link->amount;
            } else {
                return 0;
            }

        });
    }


    public function getPaymentSum()
    {
        return ($this->getProductsSum() + $this->getDeliveryPrice()) - (int) $this->attributes['bonus'];
    }


    public function getNumberFormattedAttribute()
    {
        if ($this->attributes['onmedi_order_id'] > 0) {
            return $this->attributes['onmedi_order_id'];
        } else {
            return str_repeat('0', 5 - strlen($this->attributes['number'])).$this->attributes['number'].'\\' . strftime('%y', strtotime($this->attributes['date_order']));
        }
    }


    public function glukozaNumberFormatted()
    {
        return str_repeat('0', 5 - strlen($this->attributes['number'])).$this->attributes['number'].'\\' . strftime('%y', strtotime($this->attributes['date_order']));
    }

    
    public function getIsDoneAttribute()
    {
        if ($this->attributes['status'] == 3 && $this->attributes['status_agreed'] == 1 && $this->attributes['status_pay'] == 1) {
            return 1;
        }
        if ($this->attributes['status'] == 4) {
            return 1;
        }
        return 0;
    }


    public function hasEmail()
    {
        return strlen($this->attributes['email']) > 4;
    }


    public function hasPhone()
    {
        return strlen($this->attributes['phone']) >= 7;
    }

    
    public function getIsSuccessAttribute()
    {
        return $this->attributes['status'] === 3
            && $this->attributes['status_agreed'] === 1
            && $this->attributes['status_pay'] === 1;
    }


}