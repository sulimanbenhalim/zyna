<?php

namespace Zyna\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Zyna\Support\StyleBuilder;
use Zyna\Support\Theme;

class StyleBuilderTest extends TestCase
{
    protected StyleBuilder $styleBuilder;
    protected Theme $theme;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->theme = new Theme([
            'colors' => [
                'primary' => [
                    '500' => '#3b82f6',
                ],
            ],
        ]);
        
        $this->styleBuilder = new StyleBuilder($this->theme);
    }

    public function test_it_can_be_instantiated_with_theme()
    {
        $this->assertInstanceOf(StyleBuilder::class, $this->styleBuilder);
    }

    public function test_build_returns_empty_string_for_unknown_component()
    {
        $result = $this->styleBuilder->build('UnknownComponent');
        
        $this->assertEquals('', $result);
    }

    public function test_build_accepts_component_props()
    {
        $result = $this->styleBuilder->build('TestComponent', ['color' => 'primary']);
        
        $this->assertIsString($result);
    }

    public function test_merge_classes_removes_duplicates()
    {
        // Using reflection to test protected method
        $reflection = new \ReflectionClass($this->styleBuilder);
        $method = $reflection->getMethod('mergeClasses');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->styleBuilder, [
            'text-blue-500 bg-white',
            'text-blue-500 p-4',
            'bg-white m-2'
        ]);
        
        $this->assertEquals('text-blue-500 bg-white p-4 m-2', $result);
    }

    public function test_merge_classes_handles_empty_strings()
    {
        $reflection = new \ReflectionClass($this->styleBuilder);
        $method = $reflection->getMethod('mergeClasses');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->styleBuilder, ['', 'text-blue-500', '', 'bg-white']);
        
        $this->assertEquals('text-blue-500 bg-white', $result);
    }

    public function test_filter_empty_removes_null_and_empty_values()
    {
        $reflection = new \ReflectionClass($this->styleBuilder);
        $method = $reflection->getMethod('filterEmpty');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->styleBuilder, [
            'value1',
            null,
            '',
            'value2',
            0,
            false,
            'value3'
        ]);
        
        $this->assertEquals(['value1', 3 => 'value2', 4 => 0, 5 => false, 6 => 'value3'], $result);
    }

    public function test_get_theme_value_retrieves_theme_configuration()
    {
        $reflection = new \ReflectionClass($this->styleBuilder);
        $method = $reflection->getMethod('getThemeValue');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->styleBuilder, 'colors', 'primary.500');
        
        $this->assertEquals('#3b82f6', $result);
    }

    public function test_get_theme_value_returns_default_when_not_found()
    {
        $reflection = new \ReflectionClass($this->styleBuilder);
        $method = $reflection->getMethod('getThemeValue');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->styleBuilder, 'nonexistent', 'key', 'default');
        
        $this->assertEquals('default', $result);
    }

    public function test_when_returns_true_classes_when_condition_is_true()
    {
        $reflection = new \ReflectionClass($this->styleBuilder);
        $method = $reflection->getMethod('when');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->styleBuilder, true, 'true-classes', 'false-classes');
        
        $this->assertEquals('true-classes', $result);
    }

    public function test_when_returns_false_classes_when_condition_is_false()
    {
        $reflection = new \ReflectionClass($this->styleBuilder);
        $method = $reflection->getMethod('when');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->styleBuilder, false, 'true-classes', 'false-classes');
        
        $this->assertEquals('false-classes', $result);
    }

    public function test_when_returns_empty_string_when_condition_is_false_and_no_false_classes()
    {
        $reflection = new \ReflectionClass($this->styleBuilder);
        $method = $reflection->getMethod('when');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->styleBuilder, false, 'true-classes');
        
        $this->assertEquals('', $result);
    }

    public function test_build_data_attributes_creates_proper_attributes()
    {
        $reflection = new \ReflectionClass($this->styleBuilder);
        $method = $reflection->getMethod('buildDataAttributes');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->styleBuilder, [
            'toggle' => 'modal',
            'target' => 'modal-1',
            'empty' => null,
            'value' => 'test'
        ]);
        
        $expected = [
            'data-toggle' => 'modal',
            'data-target' => 'modal-1',
            'data-value' => 'test'
        ];
        
        $this->assertEquals($expected, $result);
    }

    public function test_build_data_attributes_handles_empty_array()
    {
        $reflection = new \ReflectionClass($this->styleBuilder);
        $method = $reflection->getMethod('buildDataAttributes');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->styleBuilder, []);
        
        $this->assertEquals([], $result);
    }
}