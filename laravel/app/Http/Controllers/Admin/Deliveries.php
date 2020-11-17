<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class Deliveries extends Controller
{

    public function getAll()
    {
        return Delivery::with('product.tovar_1c_att')
            ->whereIn('id', [1, 2, 3, 4, 5, 11, 13, 14])
            ->orderBy('sort', 'asc')
            ->get();
    }



}