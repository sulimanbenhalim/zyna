<?php

namespace Zyna\Tests\Unit\Components;

use Zyna\Tests\TestCase;
use Zyna\Components\Blade\Icon;

class IconComponentTest extends TestCase
{
    public function test_icon_component_initialization()
    {
        $icon = new Icon('home');
        
        $this->assertEquals('home', $icon->name);
        $this->assertEquals('outline', $icon->style);
        $this->assertNull($icon->color);
        $this->assertEquals('md', $icon->size);
    }

    public function test_icon_component_with_custom_properties()
    {
        $icon = new Icon(
            name: 'user',
            size: 'lg',
            style: 'solid',
            color: 'success',
            class: 'custom-class'
        );
        
        $this->assertEquals('user', $icon->name);
        $this->assertEquals('solid', $icon->style);
        $this->assertEquals('success', $icon->color);
        $this->assertEquals('lg', $icon->size);
        $this->assertEquals('custom-class', $icon->class);
    }

    public function test_icon_component_render_returns_view()
    {
        $icon = new Icon('home');
        
        $view = $icon->render();
        
        $this->assertEquals('zyna::components.blade.icon', $view->name());
    }

    public function test_icon_component_has_icon_manager_methods()
    {
        $icon = new Icon('home');
        
        // Test that IconManager methods are accessible
        $this->assertTrue(method_exists($icon, 'getSvgContent'));
        $this->assertTrue(method_exists($icon, 'getClasses'));
        $this->assertTrue(method_exists($icon, 'processedSvg'));
    }
}