<?php

namespace SeBuDesign\PoEditor;

use Illuminate\Support\ServiceProvider;

class PoEditorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!preg_match('/lumen/i', app()->version())) {
            $this->publishes([
                __DIR__ . '/../config/poeditor.php' => config_path('poeditor.php'),
            ], 'config');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\SynchroniseTranslations::class,
            ]);
        }
    }

    public function register()
    {
        if (!preg_match('/lumen/i', app()->version())) {
            $this->mergeConfigFrom(
                __DIR__ . '/../config/poeditor.php',
                'poeditor'
            );
        }
    }

}
