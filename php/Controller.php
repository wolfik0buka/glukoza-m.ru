<?php namespace App;

require_once __DIR__ . '/../vendor/autoload.php';
//use Illuminate\Database\Capsule\Manager as DB;
use duncan3dc\Laravel\BladeInstance;

class Controller
{
    public function __construct()
    {
//        $this->db = $this->db_init();
        $this->blade = $this->blade_init();
    }



    // инициализация шаблонизатора
    public static function blade_init()
    {
        $blade = new BladeInstance(__DIR__ . '/../views', __DIR__ . '/../views/cache');
        return $blade;
    }

}