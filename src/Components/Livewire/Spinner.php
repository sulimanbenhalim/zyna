<?php

namespace Zyna\Components\Livewire;

use Zyna\Components\Base\SpinnerComponent;

/**
 * Spinner component for Livewire-enabled Blade implementation.
 */
class Spinner extends SpinnerComponent
{
    /**
     * Get the view name for the component.
     *
     * @return string
     */
    protected function getViewName(): string
    {
        return 'zyna::components.livewire.spinner';
    }
}