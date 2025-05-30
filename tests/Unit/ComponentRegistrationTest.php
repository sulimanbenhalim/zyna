<?php

namespace Zyna\Tests\Unit;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use PHPUnit\Framework\TestCase;
use Zyna\ZynaServiceProvider;

class ComponentRegistrationTest extends TestCase
{
    protected ZynaServiceProvider $serviceProvider;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a mock application
        $app = $this->createMock(\Illuminate\Contracts\Foundation\Application::class);
        
        $this->serviceProvider = new ZynaServiceProvider($app);
    }

    public function test_discover_components_returns_empty_array_for_non_existent_directory()
    {
        $reflection = new \ReflectionClass($this->serviceProvider);
        $method = $reflection->getMethod('discoverComponents');
        $method->setAccessible(true);
        
        $result = $method->invoke(
            $this->serviceProvider,
            '/non/existent/path',
            'NonExistent\\Namespace\\'
        );
        
        $this->assertEquals([], $result);
    }

    public function test_discover_components_handles_empty_directory()
    {
        // Create a temporary directory
        $tempDir = sys_get_temp_dir() . '/zyna_test_' . uniqid();
        mkdir($tempDir);
        
        $reflection = new \ReflectionClass($this->serviceProvider);
        $method = $reflection->getMethod('discoverComponents');
        $method->setAccessible(true);
        
        $result = $method->invoke(
            $this->serviceProvider,
            $tempDir,
            'Test\\Namespace\\'
        );
        
        $this->assertEquals([], $result);
        
        // Clean up
        rmdir($tempDir);
    }

    public function test_discover_components_ignores_non_php_files()
    {
        // Create a temporary directory with non-PHP files
        $tempDir = sys_get_temp_dir() . '/zyna_test_' . uniqid();
        mkdir($tempDir);
        
        // Create some non-PHP files
        file_put_contents($tempDir . '/test.txt', 'test');
        file_put_contents($tempDir . '/test.md', '# Test');
        file_put_contents($tempDir . '/test.js', 'console.log("test");');
        
        $reflection = new \ReflectionClass($this->serviceProvider);
        $method = $reflection->getMethod('discoverComponents');
        $method->setAccessible(true);
        
        $result = $method->invoke(
            $this->serviceProvider,
            $tempDir,
            'Test\\Namespace\\'
        );
        
        $this->assertEquals([], $result);
        
        // Clean up
        unlink($tempDir . '/test.txt');
        unlink($tempDir . '/test.md');
        unlink($tempDir . '/test.js');
        rmdir($tempDir);
    }

    public function test_discover_components_handles_php_files_without_existing_classes()
    {
        // Create a temporary directory with PHP files
        $tempDir = sys_get_temp_dir() . '/zyna_test_' . uniqid();
        mkdir($tempDir);
        
        // Create PHP files that don't contain the expected classes
        file_put_contents($tempDir . '/TestComponent.php', '<?php // Empty file');
        file_put_contents($tempDir . '/AnotherComponent.php', '<?php // Another empty file');
        
        $reflection = new \ReflectionClass($this->serviceProvider);
        $method = $reflection->getMethod('discoverComponents');
        $method->setAccessible(true);
        
        $result = $method->invoke(
            $this->serviceProvider,
            $tempDir,
            'NonExistent\\Namespace\\'
        );
        
        $this->assertEquals([], $result);
        
        // Clean up
        unlink($tempDir . '/TestComponent.php');
        unlink($tempDir . '/AnotherComponent.php');
        rmdir($tempDir);
    }

    public function test_register_components_selects_correct_directory_for_blade()
    {
        // Create a partial mock to test the method selection
        $serviceProvider = $this->getMockBuilder(ZynaServiceProvider::class)
            ->setConstructorArgs([$this->createMock(\Illuminate\Contracts\Foundation\Application::class)])
            ->onlyMethods(['discoverComponents'])
            ->getMock();
        
        // Test that the correct directory is passed for blade implementation
        $serviceProvider->expects($this->once())
            ->method('discoverComponents')
            ->with(
                $this->stringContains('/Components/Blade'),
                'Zyna\\Components\\Blade\\'
            )
            ->willReturn([]);
        
        $reflection = new \ReflectionClass($serviceProvider);
        $method = $reflection->getMethod('registerComponents');
        $method->setAccessible(true);
        
        $method->invoke($serviceProvider, 'blade');
    }

    public function test_register_components_selects_correct_directory_for_livewire()
    {
        // Create a partial mock to test the method selection
        $serviceProvider = $this->getMockBuilder(ZynaServiceProvider::class)
            ->setConstructorArgs([$this->createMock(\Illuminate\Contracts\Foundation\Application::class)])
            ->onlyMethods(['discoverComponents'])
            ->getMock();
        
        // Test that the correct directory is passed for livewire implementation
        $serviceProvider->expects($this->once())
            ->method('discoverComponents')
            ->with(
                $this->stringContains('/Components/Livewire'),
                'Zyna\\Components\\Livewire\\'
            )
            ->willReturn([]);
        
        $reflection = new \ReflectionClass($serviceProvider);
        $method = $reflection->getMethod('registerComponents');
        $method->setAccessible(true);
        
        $method->invoke($serviceProvider, 'livewire');
    }

    public function test_component_name_conversion()
    {
        // Test that PascalCase component names are converted to kebab-case
        
        // Create a temporary directory with a PHP file
        $tempDir = sys_get_temp_dir() . '/zyna_test_' . uniqid();
        mkdir($tempDir);
        
        // Create a PHP file
        file_put_contents($tempDir . '/TestButtonComponent.php', '<?php 
            namespace Test\\Namespace;
            class TestButtonComponent {}
        ');
        
        // Include the file to make the class exist
        require_once $tempDir . '/TestButtonComponent.php';
        
        $reflection = new \ReflectionClass($this->serviceProvider);
        $method = $reflection->getMethod('discoverComponents');
        $method->setAccessible(true);
        
        $result = $method->invoke(
            $this->serviceProvider,
            $tempDir,
            'Test\\Namespace\\'
        );
        
        // Should convert TestButtonComponent to test-button-component
        $this->assertArrayHasKey('Test\\Namespace\\TestButtonComponent', $result);
        $this->assertEquals('test-button-component', $result['Test\\Namespace\\TestButtonComponent']);
        
        // Clean up
        unlink($tempDir . '/TestButtonComponent.php');
        rmdir($tempDir);
    }
}