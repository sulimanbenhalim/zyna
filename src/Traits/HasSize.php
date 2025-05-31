<?php

namespace Zyna\Traits;

/**
 * Trait for components that support size variants.
 */
trait HasSize
{
    /**
     * The size variant for the component.
     *
     * @var string
     */
    public string $size = 'md';

    /**
     * The available size variants.
     *
     * @var array<string>
     */
    protected array $availableSizes = [
        'xs',
        'sm',
        'md',
        'lg',
        'xl',
    ];

    /**
     * Initialize the size trait.
     *
     * @param string|null $size
     * @return void
     */
    protected function initializeSize(?string $size = null): void
    {
        if ($size !== null) {
            $this->setSize($size);
        }
    }

    /**
     * Set the size variant.
     *
     * @param string $size
     * @return $this
     */
    public function setSize(string $size): self
    {
        if ($this->isValidSize($size)) {
            $this->size = $size;
        }

        return $this;
    }

    /**
     * Get the size variant.
     *
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * Check if a size is valid.
     *
     * @param string $size
     * @return bool
     */
    public function isValidSize(string $size): bool
    {
        return in_array($size, $this->availableSizes, true);
    }

    /**
     * Get all available sizes.
     *
     * @return array<string>
     */
    public function getAvailableSizes(): array
    {
        return $this->availableSizes;
    }
}