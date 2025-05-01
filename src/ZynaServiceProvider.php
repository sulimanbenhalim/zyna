<?php

namespace Zyna;

use Illuminate\Support\ServiceProvider;

class ZynaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge package configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/zyna.php', 'zyna'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/zyna.php' => config_path('zyna.php'),
        ], 'zyna-config');

        // Publish assets
        $this->publishes([
            __DIR__.'/../dist' => public_path('vendor/zyna'),
        ], 'zyna-assets');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/zyna'),
        ], 'zyna-views');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'zyna');

        // Check component implementation setting
        // This logic will be expanded in v0.0.6 when implementing the component registration system
        $implementation = config('zyna.components.implementation', 'blade');
        if ($implementation === 'livewire' && !class_exists(\Livewire\Livewire::class)) {
            // Fallback to blade if Livewire is selected but not installed
            config(['zyna.components.implementation' => 'blade']);
        }
    }
}