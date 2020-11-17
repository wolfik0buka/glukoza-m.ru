<?php namespace App\Http\Controllers\Pub;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class Layout extends Controller
{

    public function render_layout(Request $request)
    {
        $data = collect($request->all());
        return view('public.layout', compact('data'))->render();
    }

}
