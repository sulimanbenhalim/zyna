<?php

namespace Zyna\Tests\Unit\Traits;

use PHPUnit\Framework\TestCase;
use Zyna\Traits\HasIcon;

class HasIconTest extends TestCase
{
    private $component;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an anonymous class that uses the trait for testing
        $this->component = new class {
            use HasIcon;
            
            // Expose protected method for testing
            public function publicInitializeIcon(?string $icon = null, ?string $iconPosition = null): void
            {
                $this->initializeIcon($icon, $iconPosition);
            }
        };
    }

    public function test_default_icon_is_null()
    {
        $this->assertNull($this->component->getIcon());
    }

    public function test_default_icon_position_is_left()
    {
        $this->assertEquals('left', $this->component->getIconPosition());
    }

    public function test_can_set_icon()
    {
        $this->component->setIcon('heroicon-o-check');
        $this->assertEquals('heroicon-o-check', $this->component->getIcon());
    }

    public function test_can_set_icon_to_null()
    {
        $this->component->setIcon('heroicon-o-check');
        $this->component->setIcon(null);
        $this->assertNull($this->component->getIcon());
    }

    public function test_has_icon_returns_correct_result()
    {
        $this->assertFalse($this->component->hasIcon());
        
        $this->component->setIcon('heroicon-o-check');
        $this->assertTrue($this->component->hasIcon());
        
        $this->component->setIcon('');
        $this->assertFalse($this->component->hasIcon());
        
        $this->component->setIcon(null);
        $this->assertFalse($this->component->hasIcon());
    }

    public function test_can_set_valid_icon_position()
    {
        $this->component->setIconPosition('right');
        $this->assertEquals('right', $this->component->getIconPosition());
    }

    public function test_invalid_icon_position_is_ignored()
    {
        $this->component->setIconPosition('invalid-position');
        $this->assertEquals('left', $this->component->getIconPosition());
    }

    public function test_is_valid_icon_position_returns_correct_result()
    {
        $this->assertTrue($this->component->isValidIconPosition('left'));
        $this->assertTrue($this->component->isValidIconPosition('right'));
        $this->assertFalse($this->component->isValidIconPosition('invalid'));
    }

    public function test_get_available_icon_positions_returns_all_positions()
    {
        $positions = $this->component->getAvailableIconPositions();
        
        $this->assertIsArray($positions);
        $this->assertContains('left', $positions);
        $this->assertContains('right', $positions);
        $this->assertCount(2, $positions);
    }

    public function test_is_icon_left_returns_correct_result()
    {
        $this->assertTrue($this->component->isIconLeft());
        
        $this->component->setIconPosition('right');
        $this->assertFalse($this->component->isIconLeft());
    }

    public function test_is_icon_right_returns_correct_result()
    {
        $this->assertFalse($this->component->isIconRight());
        
        $this->component->setIconPosition('right');
        $this->assertTrue($this->component->isIconRight());
    }

    public function test_initialize_icon_sets_icon_and_position()
    {
        $this->component->publicInitializeIcon('heroicon-o-check', 'right');
        $this->assertEquals('heroicon-o-check', $this->component->getIcon());
        $this->assertEquals('right', $this->component->getIconPosition());
    }

    public function test_initialize_icon_with_nulls_keeps_defaults()
    {
        $this->component->publicInitializeIcon(null, null);
        $this->assertNull($this->component->getIcon());
        $this->assertEquals('left', $this->component->getIconPosition());
    }

    public function test_set_icon_returns_self_for_chaining()
    {
        $result = $this->component->setIcon('heroicon-o-check');
        $this->assertSame($this->component, $result);
    }

    public function test_set_icon_position_returns_self_for_chaining()
    {
        $result = $this->component->setIconPosition('right');
        $this->assertSame($this->component, $result);
    }
}