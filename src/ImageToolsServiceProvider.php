<?php

namespace ReesMcIvor\ImageTools;

use Illuminate\Support\ServiceProvider;
use ReesMcIvor\ImageTools\Classes\ImageResizer;

class ImageToolsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('imagetools', function ($app) {
            return new ImageResizer();
        });

        $this->mergeConfigFrom(
            __DIR__.'/../config/imagetools.php', 'imagetools'
        );
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Publish configuration file for customization
        $this->publishes([
            __DIR__.'/../config/imagetools.php' => config_path('imagetools.php'),
        ]);

        // Optionally, load views, migrations, etc.
    }
}
