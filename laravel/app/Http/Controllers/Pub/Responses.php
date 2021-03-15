<?php namespace App\Http\Controllers\Pub;

use App\Http\Controllers\Controller;
use App\Models\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Mail;

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
    
    public static function add(Request $request){
        $answer = [];
        $answer['status'] = 'test';
        $data = $request->input();

        $response = Response::create([
            'fio' => $data['fio'],
            'response' => $data['response'],
            'tovar_id' => $data['tovar_id'],
            'user_id' => $data['user_id'],
            'confirmed' => false,
            'deleted' => false,
        ]);
        $answer['status'] = 'added';
        $answer['response'] = $response;
        Responses::sendNotification();
        return $answer;
    }

    static public function sendNotification()
    {
        
        try {
            $email = 'shop@glukoza-med.ru';
            $email = 'molodoi_padavan@mail.ru';

            Mail::send(
                'public.mail.response',
                [],
                function ($message) {
                    $message
                        ->to('shop@glukoza-med.ru')
                        ->subject('Добавлен новый отзыв на сайте glukoza-med.ru');
                }
            );
        } catch (\Exception $ex) {
            //
        }
    }
}
