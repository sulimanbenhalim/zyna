<?php

namespace Zyna\Support;

use Illuminate\Support\Facades\File;

class AssetManager
{
    protected array $config;
    protected ?array $manifest = null;

    public function __construct(array $config = [])
    {
        $defaults = [
            'base_path' => '/vendor/zyna',
            'development_server' => 'http://localhost:5173',
            'use_manifest' => true,
            'version' => null,
        ];

        // Only set default manifest path if not provided
        if (!isset($config['manifest_path'])) {
            $defaults['manifest_path'] = $this->getDefaultManifestPath();
        }

        $this->config = array_merge($defaults, $config);
    }

    /**
     * Get the JavaScript asset URL
     */
    public function getJavaScriptUrl(): string
    {
        if ($this->isDevelopmentMode()) {
            return $this->getDevelopmentAssetUrl('resources/js/core.js');
        }

        $manifestEntry = $this->getManifestEntry('resources/js/core.js');
        if ($manifestEntry && isset($manifestEntry['file'])) {
            return $this->getAssetUrl($manifestEntry['file']);
        }

        // Fallback to built file
        return $this->getAssetUrl('core.es.js');
    }

    /**
     * Get the CSS asset URL
     */
    public function getCssUrl(): ?string
    {
        if ($this->isDevelopmentMode()) {
            return null; // CSS is injected by Vite in development
        }

        $manifestEntry = $this->getManifestEntry('resources/js/core.js');
        if ($manifestEntry && isset($manifestEntry['css']) && !empty($manifestEntry['css'])) {
            return $this->getAssetUrl($manifestEntry['css'][0]);
        }

        return null;
    }

    /**
     * Get all required asset URLs
     */
    public function getAssets(): array
    {
        return [
            'js' => $this->getJavaScriptUrl(),
            'css' => $this->getCssUrl(),
        ];
    }

    /**
     * Generate script tag for JavaScript asset
     */
    public function getScriptTag(): string
    {
        $url = $this->getJavaScriptUrl();
        $attributes = $this->isDevelopmentMode() ? 'type="module"' : '';
        
        return sprintf('<script src="%s" %s></script>', $url, $attributes);
    }

    /**
     * Generate link tag for CSS asset
     */
    public function getStylesheetTag(): ?string
    {
        $url = $this->getCssUrl();
        
        if (!$url) {
            return null;
        }

        return sprintf('<link rel="stylesheet" href="%s">', $url);
    }

    /**
     * Generate all asset tags
     */
    public function getAssetTags(): array
    {
        $tags = [];

        // Add CSS tag if available
        $cssTag = $this->getStylesheetTag();
        if ($cssTag) {
            $tags[] = $cssTag;
        }

        // Add JavaScript tag
        $tags[] = $this->getScriptTag();

        return $tags;
    }

    /**
     * Get asset tags as HTML string
     */
    public function renderAssetTags(): string
    {
        return implode("\n", $this->getAssetTags());
    }

    /**
     * Get Livewire-specific asset configuration
     */
    public function getLivewireAssets(): array
    {
        $assets = $this->getAssets();
        
        return [
            'scripts' => [$assets['js']],
            'styles' => array_filter([$assets['css']]),
            'preload' => true, // Ensure assets load before Livewire
        ];
    }

    /**
     * Check if running in development mode
     */
    protected function isDevelopmentMode(): bool
    {
        // Allow tests to force production mode
        if ($this->config['force_production'] ?? false) {
            return false;
        }
        
        try {
            $isLocal = function_exists('app') ? app()->environment('local') : false;
        } catch (\Exception $e) {
            $isLocal = false;
        }
        
        return $isLocal && 
               $this->isViteServerRunning() && 
               !$this->config['use_manifest'];
    }

    /**
     * Check if Vite development server is running
     */
    protected function isViteServerRunning(): bool
    {
        if (!$this->config['development_server']) {
            return false;
        }

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 1,
                    'method' => 'HEAD'
                ]
            ]);
            
            $result = @file_get_contents($this->config['development_server'], false, $context);
            return $result !== false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get development asset URL
     */
    protected function getDevelopmentAssetUrl(string $path): string
    {
        return rtrim($this->config['development_server'], '/') . '/' . ltrim($path, '/');
    }

    /**
     * Get production asset URL
     */
    protected function getAssetUrl(string $path): string
    {
        $basePath = rtrim($this->config['base_path'], '/');
        $url = $basePath . '/' . ltrim($path, '/');

        // Add version parameter for cache busting
        if ($this->config['version']) {
            $separator = strpos($url, '?') !== false ? '&' : '?';
            $url .= $separator . 'v=' . $this->config['version'];
        }

        return $url;
    }

    /**
     * Get manifest entry for a given path
     */
    protected function getManifestEntry(string $path): ?array
    {
        $manifest = $this->getManifest();
        return $manifest[$path] ?? null;
    }

    /**
     * Load and cache the Vite manifest
     */
    protected function getManifest(): array
    {
        if ($this->manifest !== null) {
            return $this->manifest;
        }

        if (!$this->fileExists($this->config['manifest_path'])) {
            $this->manifest = [];
            return $this->manifest;
        }

        try {
            $content = $this->fileGetContents($this->config['manifest_path']);
            $this->manifest = json_decode($content, true) ?: [];
        } catch (\Exception $e) {
            $this->manifest = [];
        }

        return $this->manifest;
    }

    /**
     * Set configuration
     */
    public function setConfig(array $config): self
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    /**
     * Get configuration value
     */
    public function getConfig(string $key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Clear manifest cache
     */
    public function clearCache(): self
    {
        $this->manifest = null;
        return $this;
    }

    /**
     * Get default manifest path
     */
    protected function getDefaultManifestPath(): ?string
    {
        try {
            if (function_exists('public_path')) {
                return public_path('vendor/zyna/.vite/manifest.json');
            }
        } catch (\Exception $e) {
            // Ignore errors
        }
        
        return null;
    }

    /**
     * Check if file exists - wrapper for testing
     */
    protected function fileExists(?string $path): bool
    {
        if (!$path) {
            return false;
        }
        
        try {
            if (class_exists('Illuminate\Support\Facades\File')) {
                return \Illuminate\Support\Facades\File::exists($path);
            }
            return file_exists($path);
        } catch (\Exception $e) {
            return file_exists($path);
        }
    }

    /**
     * Get file contents - wrapper for testing
     */
    protected function fileGetContents(string $path): string
    {
        try {
            if (class_exists('Illuminate\Support\Facades\File')) {
                return \Illuminate\Support\Facades\File::get($path);
            }
            return file_get_contents($path);
        } catch (\Exception $e) {
            return file_get_contents($path);
        }
    }
}