<?php namespace App\Providers;

use App\Models\Category;
use App\Models\Settings;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $settings = Settings::select('id', 'value', 'type')->get();
        view()->share('settings', $settings);
        config([ 'settings' => $settings ]);

        view()->share('cats', Category::ForSharing()->get());
        view()->share('cat_url', '/category/');
        view()->share('static_url', '/index.php?page=stat&alias=');
        view()->share('cdn', "https://cdn.glukoza-med.ru");
    }

    public function register()
    {
        require_once __DIR__ . '/../Http/helpers.php';

        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );
    }

}
