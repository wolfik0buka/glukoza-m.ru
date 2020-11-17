<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderCancelReason;

class OrderCancelReasons extends Controller
{

    public function index()
    {
        return OrderCancelReason::orderBy('sort')->get();
    }

}