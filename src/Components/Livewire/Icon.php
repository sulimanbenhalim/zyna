<?php

namespace Zyna\Components\Livewire;

use Livewire\Component;
use Zyna\Support\IconManager;

class Icon extends Component
{
    public string $name;
    public ?string $size = null;
    public ?string $style = null;
    public ?string $color = null;
    public ?string $class = null;

    protected IconManager $iconManager;

    public function mount()
    {
        $this->iconManager = app(IconManager::class);
        
        // Use defaults from config if not provided
        $this->size = $this->size ?? config('zyna.icons.default_size', 'md');
        $this->style = $this->style ?? config('zyna.icons.default_style', 'outline');
    }

    public function render()
    {
        return view('zyna::components.livewire.icon', [
            'processedSvg' => $this->processedSvg(),
            'classes' => $this->getClasses(),
        ]);
    }

    /**
     * Get the SVG content for the icon
     */
    protected function getSvgContent(): ?string
    {
        return $this->iconManager->getIcon($this->name, $this->style);
    }

    /**
     * Get the computed classes for the icon
     */
    protected function getClasses(): string
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
    protected function processedSvg(): ?string
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
        
        // Add wire:loading attributes for loading states
        $attributes['wire:loading.class'] = 'opacity-50';
        
        return $this->iconManager->processSvg($svg, $attributes);
    }
}