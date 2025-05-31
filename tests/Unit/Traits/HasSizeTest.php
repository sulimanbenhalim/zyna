<?php

namespace Zyna\Tests\Unit\Traits;

use PHPUnit\Framework\TestCase;
use Zyna\Traits\HasSize;

class HasSizeTest extends TestCase
{
    private $component;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create an anonymous class that uses the trait for testing
        $this->component = new class {
            use HasSize;
            
            // Expose protected method for testing
            public function publicInitializeSize(?string $size = null): void
            {
                $this->initializeSize($size);
            }
        };
    }

    public function test_default_size_is_md()
    {
        $this->assertEquals('md', $this->component->getSize());
    }

    public function test_can_set_valid_size()
    {
        $this->component->setSize('lg');
        $this->assertEquals('lg', $this->component->getSize());
    }

    public function test_invalid_size_is_ignored()
    {
        $this->component->setSize('invalid-size');
        $this->assertEquals('md', $this->component->getSize());
    }

    public function test_is_valid_size_returns_correct_result()
    {
        $this->assertTrue($this->component->isValidSize('xs'));
        $this->assertTrue($this->component->isValidSize('sm'));
        $this->assertTrue($this->component->isValidSize('md'));
        $this->assertTrue($this->component->isValidSize('lg'));
        $this->assertTrue($this->component->isValidSize('xl'));
        $this->assertFalse($this->component->isValidSize('invalid'));
    }

    public function test_get_available_sizes_returns_all_sizes()
    {
        $sizes = $this->component->getAvailableSizes();
        
        $this->assertIsArray($sizes);
        $this->assertContains('xs', $sizes);
        $this->assertContains('sm', $sizes);
        $this->assertContains('md', $sizes);
        $this->assertContains('lg', $sizes);
        $this->assertContains('xl', $sizes);
        $this->assertCount(5, $sizes);
    }

    public function test_initialize_size_sets_size()
    {
        $this->component->publicInitializeSize('xl');
        $this->assertEquals('xl', $this->component->getSize());
    }

    public function test_initialize_size_with_null_keeps_default()
    {
        $this->component->publicInitializeSize(null);
        $this->assertEquals('md', $this->component->getSize());
    }

    public function test_set_size_returns_self_for_chaining()
    {
        $result = $this->component->setSize('lg');
        $this->assertSame($this->component, $result);
    }
}