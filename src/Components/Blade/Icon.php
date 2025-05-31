<?php

namespace Zyna\Components\Blade;

use Illuminate\View\Component;
use Zyna\Support\IconManager;

class Icon extends Component
{
    protected IconManager $iconManager;
    
    public function __construct(
        public string $name,
        public ?string $size = null,
        public ?string $style = null,
        public ?string $color = null,
        public ?string $class = null
    ) {
        $this->iconManager = app(IconManager::class);
        
        // Use defaults from config if not provided
        $this->size = $this->size ?? config('zyna.icons.default_size', 'md');
        $this->style = $this->style ?? config('zyna.icons.default_style', 'outline');
    }

    public function render()
    {
        return view('zyna::components.blade.icon');
    }

    /**
     * Get the SVG content for the icon
     */
    public function getSvgContent(): ?string
    {
        return $this->iconManager->getIcon($this->name, $this->style);
    }

    /**
     * Get the computed classes for the icon
     */
    public function getClasses(): string
    {
        $classes = [];
        
        // Add size classes
        $classes[] = $this->iconManager->getSizeClasses($this->size);
        
        // Add color classes if specified
        if ($this->color) {
            $classes[] = $this->iconManager->getColorClasses($this->color);
        }
        
        // Add custom classes
        if ($this->class) {
            $classes[] = $this->class;
        }
        
        return implode(' ', $classes);
    }

    /**
     * Process the SVG with attributes
     */
    public function processedSvg(): ?string
    {
        $svg = $this->getSvgContent();
        
        if (!$svg) {
            return null;
        }
        
        $attributes = [
            'class' => $this->getClasses(),
            'fill' => $this->style === 'solid' ? 'currentColor' : 'none',
            'stroke' => $this->style === 'outline' ? 'currentColor' : 'none',
            'stroke-width' => $this->style === 'outline' ? '2' : '0',
        ];
        
        // Add aria-hidden for accessibility
        $attributes['aria-hidden'] = 'true';
        
        return $this->iconManager->processSvg($svg, $attributes);
    }
}