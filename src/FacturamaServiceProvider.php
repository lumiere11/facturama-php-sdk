<?php

namespace MyPackage;

use Illuminate\Support\ServiceProvider;

class MyPackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        // ...
    }

    public function boot()
    {
        // Publish configuration file
        $this->publishes([
            __DIR__.'/Config/facturama.php' => config_path('facturama.php'),
        ], 'config');
    }
}
