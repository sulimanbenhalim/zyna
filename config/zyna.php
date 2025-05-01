<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Component Implementation
    |--------------------------------------------------------------------------
    |
    | This value determines which implementation of the components will be used.
    | Options: 'blade' or 'livewire'
    |
    */
    'components' => [
        'implementation' => 'blade', // Available options: 'blade', 'livewire'
        'prefix' => 'zyna', // The prefix used for component registration
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the default theme settings for all components.
    | These values can be overridden by the user.
    |
    */
    'themes' => [
        // Theme configuration will be expanded in v0.0.4
    ],

    /*
    |--------------------------------------------------------------------------
    | JavaScript Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the JavaScript integration settings.
    |
    */
    'javascript' => [
        'publicPath' => 'vendor/zyna',
        'autoload' => true,
        'deferLoading' => false,
        'useCdn' => false,
        'cdnVersion' => '3.0.0',
    ],

    /*
    |--------------------------------------------------------------------------
    | Icon Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the default icon provider.
    |
    */
    'icons' => [
        'provider' => 'heroicons',
        'defaultSet' => 'outline',
    ],
];