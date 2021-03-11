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
        return Response::with('linked_tovar')
          ->get();
    }
    public function update(Request $request)
    {
        $response = Response::where('id', $request->id)->first();
	    $response->response = $request->response;
	    $response->answer = $request->answer;
	    $response->fio = $request->fio;
	    $response->created_at = $request->created_at;
	    $response->rating = $request->rating;
	    $response->confirmed = $request->confirmed;
	    $response->deleted = $request->deleted;
	    $response->user_id = $request->user_id;
	    $response->tovar_id = $request->tovar_id;

	    if ($response->save()) {
	        return 'Сохранено';
        }
    }

}
