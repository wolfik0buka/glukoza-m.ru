<?php namespace App\Http\Controllers\Pub;

use App\Http\Controllers\Controller;
use App\Models\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Responses extends Controller
{
    public static function getAll()
    {
        try {
            $responses = Response::orderBy('id', 'desc')->get();
        } catch (ModelNotFoundException $ex) {
            return response()
                ->view('errors.404')
                ->setStatusCode(404);
        }
    
        

        
        $seo = self::seo()
            ->setTitle("Отзывы")
            ->setDescription("Отзывы о наше компании")
            ->all();
        
        return view('public.responses.list_responses', compact( 'responses', 'seo'));
    }
    
}
