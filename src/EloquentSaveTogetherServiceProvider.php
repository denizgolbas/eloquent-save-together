<?php

namespace Denizgolbas\EloquentSaveTogether;

use Illuminate\Support\ServiceProvider;
use Denizgolbas\EloquentSaveTogether\Console\Commands\PublishConfig;

class EloquentSaveTogetherServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Register config for publishing
        $this->publishes([
            __DIR__.'/../config/eloquent-save-together.php' => config_path('eloquent-save-together.php'),
        ], 'eloquent-save-together-config');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishConfig::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/eloquent-save-together.php', 'eloquent-save-together'
        );
    }
}