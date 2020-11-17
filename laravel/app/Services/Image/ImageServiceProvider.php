<?php namespace App\Services\Image;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('intervention', function () {
            return new ImageManager();
        });
        $this->app->alias('intervention', 'Intervention\Image\ImageManager');

        $this->app->singleton('image', function () {
            return new Image();
        });
        $this->app->alias('image', 'App\Services\Image\Image');
    }
}
