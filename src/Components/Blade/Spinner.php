<?php

namespace Zyna\Components\Blade;

use Zyna\Components\Base\SpinnerComponent;

/**
 * Spinner component for standard Blade implementation.
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
        return 'zyna::components.blade.spinner';
    }
}