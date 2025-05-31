<?php

namespace Zyna\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class IconManager
{
    /**
     * Icon cache duration in seconds
     */
    protected int $cacheTtl;

    /**
     * Base path for icons
     */
    protected string $basePath;

    /**
     * Available icon styles
     */
    protected array $styles = ['outline', 'solid'];

    /**
     * Icon size mappings
     */
    protected array $sizes;

    /**
     * Color mappings for icons
     */
    protected array $colors;

    public function __construct()
    {
        // For package development, use the package's own resources directory
        $this->basePath = __DIR__ . '/../../resources/icons';
        
        $this->cacheTtl = config('zyna.icons.cache_ttl', 86400);
        $this->sizes = config('zyna.icons.sizes', [
            'xs' => 'w-3 h-3',
            'sm' => 'w-4 h-4',
            'md' => 'w-5 h-5',
            'lg' => 'w-6 h-6',
            'xl' => 'w-8 h-8',
            '2xl' => 'w-10 h-10',
        ]);
        $this->colors = array_merge(config('zyna.icons.colors', [
            'primary' => 'text-blue-600',
            'secondary' => 'text-gray-600',
            'success' => 'text-green-600',
            'danger' => 'text-red-600',
            'warning' => 'text-yellow-600',
            'info' => 'text-cyan-600',
            'light' => 'text-gray-400',
            'dark' => 'text-gray-900',
            'white' => 'text-white',
        ]), ['current' => 'currentColor']);
    }

    /**
     * Get an icon SVG content
     */
    public function getIcon(string $name, string $style = 'outline'): ?string
    {
        $cacheKey = "zyna_icon_{$style}_{$name}";
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($name, $style) {
            $path = $this->getIconPath($name, $style);
            
            if (!File::exists($path)) {
                // Try to find the icon in any subdirectory
                $foundPath = $this->findIconInSubdirectories($name, $style);
                if ($foundPath) {
                    return File::get($foundPath);
                }
                return null;
            }
            
            return File::get($path);
        });
    }

    /**
     * Get the full path to an icon
     */
    protected function getIconPath(string $name, string $style): string
    {
        return "{$this->basePath}/{$style}/{$name}.svg";
    }

    /**
     * Find icon in subdirectories
     */
    protected function findIconInSubdirectories(string $name, string $style): ?string
    {
        $styleDir = "{$this->basePath}/{$style}";
        
        if (!File::isDirectory($styleDir)) {
            return null;
        }

        $directories = File::directories($styleDir);
        
        foreach ($directories as $dir) {
            $iconPath = "{$dir}/{$name}.svg";
            if (File::exists($iconPath)) {
                return $iconPath;
            }
        }
        
        return null;
    }

    /**
     * Get size classes for an icon
     */
    public function getSizeClasses(string $size): string
    {
        return $this->sizes[$size] ?? $this->sizes['md'];
    }

    /**
     * Get color classes for an icon
     */
    public function getColorClasses(string $color): string
    {
        // If it starts with text-, use it as is (custom Tailwind classes)
        if (Str::startsWith($color, 'text-')) {
            return $color;
        }
        
        return $this->colors[$color] ?? $color;
    }

    /**
     * Process SVG content with attributes
     */
    public function processSvg(string $svg, array $attributes = []): string
    {
        // Remove any existing width/height attributes
        $svg = preg_replace('/(width|height)=["\']\d+["\']/', '', $svg);
        
        // Add custom attributes
        $attributeString = '';
        foreach ($attributes as $key => $value) {
            $attributeString .= " {$key}=\"{$value}\"";
        }
        
        // Insert attributes into the SVG tag
        $svg = preg_replace('/<svg/', '<svg' . $attributeString, $svg, 1);
        
        return $svg;
    }

    /**
     * Check if an icon exists
     */
    public function iconExists(string $name, string $style = 'outline'): bool
    {
        $path = $this->getIconPath($name, $style);
        
        if (File::exists($path)) {
            return true;
        }
        
        // Check subdirectories
        return $this->findIconInSubdirectories($name, $style) !== null;
    }

    /**
     * Get all available icons
     */
    public function getAllIcons(string $style = 'outline'): array
    {
        $styleDir = "{$this->basePath}/{$style}";
        $icons = [];
        
        if (!File::isDirectory($styleDir)) {
            return $icons;
        }

        // Get icons from root directory
        $files = File::files($styleDir);
        foreach ($files as $file) {
            if ($file->getExtension() === 'svg') {
                $icons[] = $file->getFilenameWithoutExtension();
            }
        }

        // Get icons from subdirectories
        $directories = File::directories($styleDir);
        foreach ($directories as $dir) {
            $category = basename($dir);
            $files = File::files($dir);
            
            foreach ($files as $file) {
                if ($file->getExtension() === 'svg') {
                    $iconName = $file->getFilenameWithoutExtension();
                    $icons[] = [
                        'name' => $iconName,
                        'category' => $category,
                        'full_name' => "{$category}/{$iconName}"
                    ];
                }
            }
        }
        
        return $icons;
    }

    /**
     * Clear icon cache
     */
    public function clearCache(): void
    {
        $patterns = ['zyna_icon_outline_*', 'zyna_icon_solid_*'];
        
        foreach ($patterns as $pattern) {
            $keys = Cache::get($pattern);
            if (is_array($keys)) {
                foreach ($keys as $key) {
                    Cache::forget($key);
                }
            }
        }
    }
}