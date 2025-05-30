<?php

namespace Zyna;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Zyna\Support\StyleBuilder;
use Zyna\Support\Theme;

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

        // Register Theme as singleton
        $this->app->singleton(Theme::class, function ($app) {
            $activeTheme = config('zyna.themes.active', 'default');
            $themeConfig = config("zyna.themes.{$activeTheme}", []);
            
            return new Theme($themeConfig);
        });

        // Register StyleBuilder as singleton
        $this->app->singleton(StyleBuilder::class, function ($app) {
            return new StyleBuilder($app->make(Theme::class));
        });
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

        // Register components based on implementation setting
        $implementation = config('zyna.components.implementation', 'blade');
        
        if ($implementation === 'livewire' && !class_exists(\Livewire\Livewire::class)) {
            // Fallback to blade if Livewire is selected but not installed
            config(['zyna.components.implementation' => 'blade']);
            $implementation = 'blade';
        }
        
        // Register components - both implementations use Blade components
        $this->registerComponents($implementation);
    }

    /**
     * Register component implementations.
     *
     * @param string $implementation
     * @return void
     */
    protected function registerComponents(string $implementation)
    {
        $directory = $implementation === 'livewire' 
            ? __DIR__ . '/Components/Livewire'
            : __DIR__ . '/Components/Blade';
            
        $namespace = $implementation === 'livewire'
            ? 'Zyna\\Components\\Livewire\\'
            : 'Zyna\\Components\\Blade\\';
        
        $components = $this->discoverComponents($directory, $namespace);
        
        // Register all components as Blade components with zyna: prefix
        foreach ($components as $class => $componentName) {
            Blade::component($class, "zyna:{$componentName}");
        }
    }

    /**
     * Discover component classes in a directory.
     *
     * @param string $directory
     * @param string $namespace
     * @return array
     */
    protected function discoverComponents(string $directory, string $namespace): array
    {
        $filesystem = new Filesystem();
        $components = [];
        
        if (!$filesystem->isDirectory($directory)) {
            return $components;
        }
        
        foreach ($filesystem->files($directory) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            
            // Get component name from filename (without .php)
            $componentName = str_replace('_', '-', Str::snake($file->getFilenameWithoutExtension()));
            
            $className = $namespace . $file->getFilenameWithoutExtension();
            
            // Only add if class exists
            if (class_exists($className)) {
                $components[$className] = $componentName;
            }
        }
        
        // Scan subdirectories recursively
        foreach ($filesystem->directories($directory) as $dir) {
            $dirName = basename($dir);
            $components = array_merge(
                $components,
                $this->discoverComponents(
                    $dir,
                    $namespace . $dirName . '\\'
                )
            );
        }
        
        return $components;
    }
}