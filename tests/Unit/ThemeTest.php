<?php

namespace Zyna\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Zyna\Support\Theme;

class ThemeTest extends TestCase
{
    protected Theme $theme;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->theme = new Theme([
            'colors' => [
                'primary' => [
                    '500' => '#3b82f6',
                    '600' => '#2563eb',
                ],
                'secondary' => [
                    '500' => '#6b7280',
                ],
            ],
            'fonts' => [
                'sans' => 'Inter',
            ],
        ]);
    }

    public function test_it_can_get_values_using_dot_notation()
    {
        $this->assertEquals('#3b82f6', $this->theme->get('colors.primary.500'));
        $this->assertEquals('#2563eb', $this->theme->get('colors.primary.600'));
        $this->assertEquals('Inter', $this->theme->get('fonts.sans'));
    }

    public function test_it_returns_default_when_key_not_found()
    {
        $this->assertEquals('default', $this->theme->get('non.existent.key', 'default'));
        $this->assertNull($this->theme->get('non.existent.key'));
    }

    public function test_it_can_set_values_using_dot_notation()
    {
        $this->theme->set('colors.primary.700', '#1d4ed8');
        $this->assertEquals('#1d4ed8', $this->theme->get('colors.primary.700'));

        $this->theme->set('new.nested.value', 'test');
        $this->assertEquals('test', $this->theme->get('new.nested.value'));
    }

    public function test_it_can_merge_configurations()
    {
        $this->theme->merge([
            'colors' => [
                'primary' => [
                    '700' => '#1d4ed8',
                ],
                'tertiary' => [
                    '500' => '#10b981',
                ],
            ],
            'spacing' => [
                'custom' => '10px',
            ],
        ]);

        // Original values should remain
        $this->assertEquals('#3b82f6', $this->theme->get('colors.primary.500'));
        
        // New values should be added
        $this->assertEquals('#1d4ed8', $this->theme->get('colors.primary.700'));
        $this->assertEquals('#10b981', $this->theme->get('colors.tertiary.500'));
        $this->assertEquals('10px', $this->theme->get('spacing.custom'));
    }

    public function test_it_can_check_if_key_exists()
    {
        $this->assertTrue($this->theme->has('colors.primary.500'));
        $this->assertTrue($this->theme->has('fonts.sans'));
        $this->assertFalse($this->theme->has('non.existent.key'));
    }

    public function test_it_can_get_all_configuration()
    {
        $all = $this->theme->all();
        
        $this->assertIsArray($all);
        $this->assertArrayHasKey('colors', $all);
        $this->assertArrayHasKey('fonts', $all);
        $this->assertEquals('#3b82f6', $all['colors']['primary']['500']);
    }

    public function test_it_handles_empty_configuration()
    {
        $emptyTheme = new Theme();
        
        $this->assertEquals([], $emptyTheme->all());
        $this->assertNull($emptyTheme->get('any.key'));
        $this->assertFalse($emptyTheme->has('any.key'));
    }

    public function test_merge_returns_self_for_chaining()
    {
        $result = $this->theme->merge(['new' => 'value']);
        
        $this->assertSame($this->theme, $result);
        $this->assertEquals('value', $this->theme->get('new'));
    }

    public function test_set_returns_self_for_chaining()
    {
        $result = $this->theme->set('new.key', 'value');
        
        $this->assertSame($this->theme, $result);
        $this->assertEquals('value', $this->theme->get('new.key'));
    }
}