<?php namespace App\Http\Controllers\Pub;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;


class Feedback extends Controller
{
    
    public static function index(Request $request)
    {
        $data = $request->all();
    
        $seo = self::seo()
            ->setTitle('Обратная связь')
            ->all();
    
        return view('public.static.feedback', compact( 'seo'));

    }
    
    public static function handler(Request $request)
    {
        $data = $request->all();
        $answer = array();
        if (
            isset($data['fio']) and
            $data['fio'] and
            isset($data['phone']) and
            $data['phone'] and
            isset($data['question']) and
            $data['question']
        ) {
          $answer['status'] = 'Ok';
          $answer['error'] = false;
        } else {
            $answer['status'] = 'failed';
            $answer['error'] = 'Произошла ошибка, попроубйте еще раз';
        }
        
        echo json_encode($answer);
    }
}
