<?php

namespace Zyna\Tests\Unit\Traits;

use PHPUnit\Framework\TestCase;
use Zyna\Traits\HasColor;

class HasColorTest extends TestCase
{
    private $component;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an anonymous class that uses the trait for testing
        $this->component = new class {
            use HasColor;
            
            // Expose protected method for testing
            public function publicInitializeColor(?string $color = null): void
            {
                $this->initializeColor($color);
            }
        };
    }

    public function test_default_color_is_primary()
    {
        $this->assertEquals('primary', $this->component->getColor());
    }

    public function test_can_set_valid_color()
    {
        $this->component->setColor('success');
        $this->assertEquals('success', $this->component->getColor());
    }

    public function test_invalid_color_is_ignored()
    {
        $this->component->setColor('invalid-color');
        $this->assertEquals('primary', $this->component->getColor());
    }

    public function test_is_valid_color_returns_correct_result()
    {
        $this->assertTrue($this->component->isValidColor('primary'));
        $this->assertTrue($this->component->isValidColor('success'));
        $this->assertFalse($this->component->isValidColor('invalid'));
    }

    public function test_get_available_colors_returns_all_colors()
    {
        $colors = $this->component->getAvailableColors();
        
        $this->assertIsArray($colors);
        $this->assertContains('primary', $colors);
        $this->assertContains('secondary', $colors);
        $this->assertContains('success', $colors);
        $this->assertContains('danger', $colors);
        $this->assertContains('warning', $colors);
        $this->assertContains('info', $colors);
        $this->assertContains('light', $colors);
        $this->assertContains('dark', $colors);
        $this->assertContains('gray', $colors);
    }

    public function test_initialize_color_sets_color()
    {
        $this->component->publicInitializeColor('danger');
        $this->assertEquals('danger', $this->component->getColor());
    }

    public function test_initialize_color_with_null_keeps_default()
    {
        $this->component->publicInitializeColor(null);
        $this->assertEquals('primary', $this->component->getColor());
    }

    public function test_set_color_returns_self_for_chaining()
    {
        $result = $this->component->setColor('success');
        $this->assertSame($this->component, $result);
    }
}