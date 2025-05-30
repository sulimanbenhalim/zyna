<?php

namespace Zyna\Components\Base;

use Illuminate\View\Component;

/**
 * Base component class for all Zyna components.
 * 
 * Both Blade and Livewire component implementations extend this class
 * since they both render Blade templates. The difference is in their
 * usage context and the attributes they support (standard vs wire:).
 */
abstract class BaseComponent extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view($this->getViewName());
    }

    /**
     * Get the view name for the component.
     * Child classes should implement this to return their specific view.
     *
     * @return string
     */
    abstract protected function getViewName(): string;

    /**
     * Get the component name.
     * 
     * @return string
     */
    public function getComponentName(): string
    {
        $className = class_basename(static::class);
        return strtolower($className);
    }

    /**
     * Determine if the component should render.
     *
     * @return bool
     */
    public function shouldRender()
    {
        return true;
    }
}