<?php

namespace Zyna\Tests\Unit\Components;

use Zyna\Tests\TestCase;
use Zyna\Components\Blade\Spinner;

class SpinnerComponentTest extends TestCase
{
    public function test_spinner_component_initialization()
    {
        $spinner = new Spinner();
        
        $this->assertEquals('primary', $spinner->color);
        $this->assertEquals('md', $spinner->size);
        $this->assertEquals('', $spinner->class);
    }

    public function test_spinner_component_with_custom_properties()
    {
        $spinner = new Spinner('danger', 'xl', 'custom-spinner');
        
        $this->assertEquals('danger', $spinner->color);
        $this->assertEquals('xl', $spinner->size);
        $this->assertEquals('custom-spinner', $spinner->class);
    }

    public function test_spinner_classes_method()
    {
        $spinner = new Spinner('primary', 'lg');
        $classes = $spinner->spinnerClasses();
        
        $this->assertStringContainsString('inline', $classes);
        $this->assertStringContainsString('animate-spin', $classes);
        $this->assertStringContainsString('w-8 h-8', $classes); // lg size
        $this->assertStringContainsString('text-gray-200', $classes);
        $this->assertStringContainsString('fill-blue-600', $classes); // primary color
    }

    public function test_spinner_classes_with_different_colors()
    {
        // Test success color
        $spinner = new Spinner('success');
        $classes = $spinner->spinnerClasses();
        $this->assertStringContainsString('fill-green-500', $classes);
        
        // Test danger color
        $spinner = new Spinner('danger');
        $classes = $spinner->spinnerClasses();
        $this->assertStringContainsString('fill-red-600', $classes);
        
        // Test secondary color
        $spinner = new Spinner('secondary');
        $classes = $spinner->spinnerClasses();
        $this->assertStringContainsString('fill-gray-600', $classes);
    }

    public function test_spinner_classes_with_different_sizes()
    {
        // Test xs size
        $spinner = new Spinner(null, 'xs');
        $classes = $spinner->spinnerClasses();
        $this->assertStringContainsString('w-3 h-3', $classes);
        
        // Test xl size
        $spinner = new Spinner(null, 'xl');
        $classes = $spinner->spinnerClasses();
        $this->assertStringContainsString('w-10 h-10', $classes);
    }

    public function test_spinner_component_view_name()
    {
        $spinner = new Spinner();
        
        $reflection = new \ReflectionClass($spinner);
        $method = $reflection->getMethod('getViewName');
        $method->setAccessible(true);
        
        $this->assertEquals('zyna::components.blade.spinner', $method->invoke($spinner));
    }

    public function test_spinner_inherits_traits()
    {
        $spinner = new Spinner();
        
        // Test HasColor trait
        $this->assertTrue(method_exists($spinner, 'getColor'));
        $this->assertTrue(method_exists($spinner, 'setColor'));
        
        // Test HasSize trait
        $this->assertTrue(method_exists($spinner, 'getSize'));
        $this->assertTrue(method_exists($spinner, 'setSize'));
    }
}