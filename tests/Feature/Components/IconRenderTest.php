<?php

namespace Zyna\Tests\Feature\Components;

use Illuminate\Support\Facades\Blade;
use Zyna\Tests\TestCase;

class IconRenderTest extends TestCase
{
    public function test_icon_component_can_render()
    {
        // Create a simple test icon file
        $iconPath = __DIR__ . '/../../../resources/icons/outline';
        if (!is_dir($iconPath)) {
            mkdir($iconPath, 0755, true);
        }
        
        // Create a test icon SVG
        file_put_contents(
            $iconPath . '/test-icon.svg',
            '<svg viewBox="0 0 24 24"><path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/></svg>'
        );
        
        // Test rendering the icon component
        $rendered = Blade::render('<x-zyna:icon name="test-icon" />');
        
        $this->assertStringContainsString('<svg', $rendered);
        $this->assertStringContainsString('viewBox="0 0 24 24"', $rendered);
        $this->assertStringContainsString('<path', $rendered);
        
        // Test with custom properties
        $rendered = Blade::render('<x-zyna:icon name="test-icon" size="lg" color="primary" class="custom-class" />');
        
        $this->assertStringContainsString('w-6 h-6', $rendered); // lg size
        $this->assertStringContainsString('text-blue-600', $rendered); // primary color
        $this->assertStringContainsString('custom-class', $rendered);
        
        // Clean up
        unlink($iconPath . '/test-icon.svg');
    }
    
    public function test_spinner_component_can_render()
    {
        // Test basic spinner
        $rendered = Blade::render('<x-zyna:spinner />');
        
        $this->assertStringContainsString('<div role="status">', $rendered);
        $this->assertStringContainsString('<svg', $rendered);
        $this->assertStringContainsString('animate-spin', $rendered);
        $this->assertStringContainsString('Loading...', $rendered);
        
        // Test with custom properties
        $rendered = Blade::render('<x-zyna:spinner color="danger" size="xl" />');
        
        $this->assertStringContainsString('w-10 h-10', $rendered); // xl size
        $this->assertStringContainsString('fill-red-600', $rendered); // danger color
    }
}