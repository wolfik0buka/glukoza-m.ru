<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Pub\Search;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Response;
use Illuminate\Http\Request;
use App\Services\Image\Facades\Image;

class Responses extends Controller
{

    public function getAll()
    {
        return Response::where('deleted', false)
          ->get();
    }

}
