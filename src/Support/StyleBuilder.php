<?php

namespace Zyna\Support;

/**
 * StyleBuilder class for dynamically generating component CSS classes.
 * 
 * This class provides the foundation for building Tailwind CSS classes
 * based on component properties and theme configuration.
 */
class StyleBuilder
{
    /**
     * The theme instance.
     *
     * @var Theme
     */
    protected Theme $theme;

    /**
     * Create a new StyleBuilder instance.
     *
     * @param Theme $theme
     */
    public function __construct(Theme $theme)
    {
        $this->theme = $theme;
    }

    /**
     * Build CSS classes for a component.
     *
     * @param string $component The component type
     * @param array $props The component properties
     * @return string
     */
    public function build(string $component, array $props = []): string
    {
        $method = lcfirst($component) . 'Classes';
        
        // Check if a specific method exists for this component
        if (method_exists($this, $method)) {
            return $this->$method($props);
        }
        
        // Return empty string if no specific builder exists yet
        return '';
    }

    /**
     * Merge multiple class strings, removing duplicates.
     *
     * @param array $classes
     * @return string
     */
    protected function mergeClasses(array $classes): string
    {
        $merged = [];
        
        foreach ($classes as $classString) {
            if (is_string($classString) && $classString !== '') {
                $classList = explode(' ', $classString);
                foreach ($classList as $class) {
                    $class = trim($class);
                    if ($class !== '') {
                        $merged[$class] = true;
                    }
                }
            }
        }
        
        return implode(' ', array_keys($merged));
    }

    /**
     * Filter out null or empty values from an array.
     *
     * @param array $values
     * @return array
     */
    protected function filterEmpty(array $values): array
    {
        return array_filter($values, function ($value) {
            return $value !== null && $value !== '';
        });
    }

    /**
     * Get a theme value for a specific component.
     *
     * @param string $component
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getThemeValue(string $component, string $key, $default = null)
    {
        return $this->theme->get("{$component}.{$key}", $default);
    }

    /**
     * Apply conditional classes based on a condition.
     *
     * @param bool $condition
     * @param string $trueClasses
     * @param string $falseClasses
     * @return string
     */
    protected function when(bool $condition, string $trueClasses, string $falseClasses = ''): string
    {
        return $condition ? $trueClasses : $falseClasses;
    }

    /**
     * Build data attributes from an array.
     *
     * @param array $data
     * @return array
     */
    protected function buildDataAttributes(array $data): array
    {
        $attributes = [];
        
        foreach ($data as $key => $value) {
            if ($value !== null) {
                $attributes["data-{$key}"] = $value;
            }
        }
        
        return $attributes;
    }
}