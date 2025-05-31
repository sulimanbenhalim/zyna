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
        'default' => [
            /*
            |--------------------------------------------------------------------------
            | Colors
            |--------------------------------------------------------------------------
            |
            | Define the color palette for components. These map to Flowbite's
            | CSS variables like --color-primary-500.
            |
            */
            'colors' => [
                'primary' => [
                    '50' => '#eff6ff',
                    '100' => '#dbeafe',
                    '200' => '#bfdbfe',
                    '300' => '#93c5fd',
                    '400' => '#60a5fa',
                    '500' => '#3b82f6',
                    '600' => '#2563eb',
                    '700' => '#1d4ed8',
                    '800' => '#1e40af',
                    '900' => '#1e3a8a',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | Typography
            |--------------------------------------------------------------------------
            |
            | Define font families for different text types.
            |
            */
            'fonts' => [
                'sans' => "'Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'",
                'body' => "'Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'system-ui', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'Noto Sans', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'",
                'mono' => "'ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace'",
            ],

            /*
            |--------------------------------------------------------------------------
            | Spacing
            |--------------------------------------------------------------------------
            |
            | Define custom spacing values.
            |
            */
            'spacing' => [],

            /*
            |--------------------------------------------------------------------------
            | Breakpoints
            |--------------------------------------------------------------------------
            |
            | Define responsive breakpoint values.
            |
            */
            'breakpoints' => [],
        ],

        /*
        |--------------------------------------------------------------------------
        | Active Theme
        |--------------------------------------------------------------------------
        |
        | Specify which theme is currently active.
        |
        */
        'active' => 'default',
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Management
    |--------------------------------------------------------------------------
    |
    | Configure how assets (CSS/JS) are managed and included.
    |
    */
    'assets' => [
        'auto_include' => true, // Automatically include assets in views
        'base_path' => '/vendor/zyna',
        'manifest_path' => null, // Auto-detected if null
        'development_server' => 'http://localhost:5173',
        'use_manifest' => true,
        'livewire' => [
            'preload' => true, // Preload assets before Livewire components
            'defer' => false, // Whether to defer asset loading
        ],
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
    | Configure the icon system settings for Flowbite icons.
    |
    */
    'icons' => [
        'provider' => 'flowbite',
        'default_style' => 'outline', // Available: 'outline', 'solid'
        'default_size' => 'md', // Available: 'xs', 'sm', 'md', 'lg', 'xl', '2xl'
        'cache_ttl' => 86400, // 24 hours in seconds
        'sizes' => [
            'xs' => 'w-3 h-3',
            'sm' => 'w-4 h-4',
            'md' => 'w-5 h-5',
            'lg' => 'w-6 h-6',
            'xl' => 'w-8 h-8',
            '2xl' => 'w-10 h-10',
        ],
        'colors' => [
            'primary' => 'text-blue-600',
            'secondary' => 'text-gray-600',
            'success' => 'text-green-600',
            'danger' => 'text-red-600',
            'warning' => 'text-yellow-600',
            'info' => 'text-cyan-600',
            'light' => 'text-gray-400',
            'dark' => 'text-gray-900',
            'white' => 'text-white',
        ],
    ],
];