<?php

namespace Zyna\Components\Base;

use Zyna\Traits\HasColor;
use Zyna\Traits\HasSize;

/**
 * Base Spinner component for loading indicators.
 */
abstract class SpinnerComponent extends BaseComponent
{
    use HasColor, HasSize;

    /**
     * Additional CSS classes.
     *
     * @var string
     */
    public string $class;

    /**
     * Create a new component instance.
     *
     * @param string|null $color
     * @param string|null $size
     * @param string $class
     * @return void
     */
    public function __construct(
        ?string $color = null,
        ?string $size = null,
        string $class = ''
    ) {
        $this->class = $class;
        
        $this->initializeColor($color);
        $this->initializeSize($size);
    }

    /**
     * Get the spinner SVG classes.
     *
     * @return string
     */
    public function spinnerClasses(): string
    {
        $classes = ['inline', 'animate-spin'];
        
        // Add size classes
        switch ($this->size) {
            case 'xs':
                $classes[] = 'w-3 h-3';
                break;
            case 'sm':
                $classes[] = 'w-4 h-4';
                break;
            case 'md':
                $classes[] = 'w-6 h-6';
                break;
            case 'lg':
                $classes[] = 'w-8 h-8';
                break;
            case 'xl':
                $classes[] = 'w-10 h-10';
                break;
        }
        
        // Add background color classes (the secondary color in Flowbite spinners)
        $classes[] = 'text-gray-200 dark:text-gray-600';
        
        // Add fill color classes based on color prop
        switch ($this->color) {
            case 'primary':
                $classes[] = 'fill-blue-600';
                break;
            case 'secondary':
                $classes[] = 'fill-gray-600 dark:fill-gray-300';
                break;
            case 'success':
                $classes[] = 'fill-green-500';
                break;
            case 'danger':
                $classes[] = 'fill-red-600';
                break;
            case 'warning':
                $classes[] = 'fill-yellow-400';
                break;
            case 'info':
                $classes[] = 'fill-cyan-600';
                break;
            case 'light':
                $classes[] = 'fill-gray-300';
                break;
            case 'dark':
                $classes[] = 'fill-gray-800';
                break;
            case 'gray':
                $classes[] = 'fill-gray-500';
                break;
        }
        
        // Add custom classes
        if ($this->class) {
            $classes[] = $this->class;
        }
        
        return implode(' ', $classes);
    }
}