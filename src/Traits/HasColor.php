<?php

namespace Zyna\Traits;

/**
 * Trait for components that support color variants.
 */
trait HasColor
{
    /**
     * The color variant for the component.
     *
     * @var string
     */
    public string $color = 'primary';

    /**
     * The available color variants.
     *
     * @var array<string>
     */
    protected array $availableColors = [
        'primary',
        'secondary',
        'success',
        'danger',
        'warning',
        'info',
        'light',
        'dark',
        'gray',
    ];

    /**
     * Initialize the color trait.
     *
     * @param string|null $color
     * @return void
     */
    protected function initializeColor(?string $color = null): void
    {
        if ($color !== null) {
            $this->setColor($color);
        }
    }

    /**
     * Set the color variant.
     *
     * @param string $color
     * @return $this
     */
    public function setColor(string $color): self
    {
        if ($this->isValidColor($color)) {
            $this->color = $color;
        }

        return $this;
    }

    /**
     * Get the color variant.
     *
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Check if a color is valid.
     *
     * @param string $color
     * @return bool
     */
    public function isValidColor(string $color): bool
    {
        return in_array($color, $this->availableColors, true);
    }

    /**
     * Get all available colors.
     *
     * @return array<string>
     */
    public function getAvailableColors(): array
    {
        return $this->availableColors;
    }
}