<?php

namespace Zyna\Traits;

/**
 * Trait for components that support icons.
 */
trait HasIcon
{
    /**
     * The icon name or path.
     *
     * @var string|null
     */
    public ?string $icon = null;

    /**
     * The icon position relative to content.
     *
     * @var string
     */
    public string $iconPosition = 'left';

    /**
     * The available icon positions.
     *
     * @var array<string>
     */
    protected array $availableIconPositions = [
        'left',
        'right',
    ];

    /**
     * Initialize the icon trait.
     *
     * @param string|null $icon
     * @param string|null $iconPosition
     * @return void
     */
    protected function initializeIcon(?string $icon = null, ?string $iconPosition = null): void
    {
        if ($icon !== null) {
            $this->setIcon($icon);
        }

        if ($iconPosition !== null) {
            $this->setIconPosition($iconPosition);
        }
    }

    /**
     * Set the icon.
     *
     * @param string|null $icon
     * @return $this
     */
    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Get the icon.
     *
     * @return string|null
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Check if the component has an icon.
     *
     * @return bool
     */
    public function hasIcon(): bool
    {
        return $this->icon !== null && $this->icon !== '';
    }

    /**
     * Set the icon position.
     *
     * @param string $position
     * @return $this
     */
    public function setIconPosition(string $position): self
    {
        if ($this->isValidIconPosition($position)) {
            $this->iconPosition = $position;
        }

        return $this;
    }

    /**
     * Get the icon position.
     *
     * @return string
     */
    public function getIconPosition(): string
    {
        return $this->iconPosition;
    }

    /**
     * Check if an icon position is valid.
     *
     * @param string $position
     * @return bool
     */
    public function isValidIconPosition(string $position): bool
    {
        return in_array($position, $this->availableIconPositions, true);
    }

    /**
     * Get all available icon positions.
     *
     * @return array<string>
     */
    public function getAvailableIconPositions(): array
    {
        return $this->availableIconPositions;
    }

    /**
     * Check if the icon should be displayed on the left.
     *
     * @return bool
     */
    public function isIconLeft(): bool
    {
        return $this->iconPosition === 'left';
    }

    /**
     * Check if the icon should be displayed on the right.
     *
     * @return bool
     */
    public function isIconRight(): bool
    {
        return $this->iconPosition === 'right';
    }
}