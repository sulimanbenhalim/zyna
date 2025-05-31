<?php

namespace Zyna\Components\Base;

use Zyna\Support\IconManager;
use Zyna\Traits\HasColor;
use Zyna\Traits\HasSize;

/**
 * Base Icon component for rendering icons.
 */
abstract class IconComponent extends BaseComponent
{
    use HasColor, HasSize;

    /**
     * The icon name/identifier.
     *
     * @var string
     */
    public string $name;

    /**
     * The icon style (outline or solid).
     *
     * @var string
     */
    public string $style;

    /**
     * Additional CSS classes.
     *
     * @var string
     */
    public string $class;

    /**
     * The icon manager instance.
     *
     * @var IconManager
     */
    protected IconManager $iconManager;

    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param string|null $color
     * @param string|null $size
     * @param string $style
     * @param string $class
     * @return void
     */
    public function __construct(
        string $name,
        ?string $color = null,
        ?string $size = null,
        string $style = 'outline',
        string $class = ''
    ) {
        $this->name = $name;
        $this->style = $style;
        $this->class = $class;
        
        $this->initializeColor($color);
        $this->initializeSize($size);
        
        // Try to get IconManager from container, create new instance if not available
        try {
            $this->iconManager = app(IconManager::class);
        } catch (\Exception $e) {
            $this->iconManager = new IconManager();
        }
    }

    /**
     * Get the icon SVG content.
     *
     * @return string|null
     */
    public function getIconSvg(): ?string
    {
        $svg = $this->iconManager->getIcon($this->name, $this->style);
        
        if (!$svg) {
            return null;
        }

        $attributes = [
            'class' => $this->getIconClasses(),
            'aria-hidden' => 'true',
        ];

        return $this->iconManager->processSvg($svg, $attributes);
    }

    /**
     * Get the icon classes.
     *
     * @return string
     */
    protected function getIconClasses(): string
    {
        $classes = [];
        
        // Add size classes from IconManager
        $classes[] = $this->iconManager->getSizeClasses($this->size);
        
        // Add color classes from IconManager
        $classes[] = $this->iconManager->getColorClasses($this->color);
        
        // Add custom classes
        if ($this->class) {
            $classes[] = $this->class;
        }
        
        return implode(' ', $classes);
    }

    /**
     * Check if the icon exists.
     *
     * @return bool
     */
    public function iconExists(): bool
    {
        return $this->iconManager->iconExists($this->name, $this->style);
    }
}