<?php

namespace Zyna\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Zyna\Console\Commands\InstallCommand;

class InstallCommandTest extends TestCase
{
    public function test_command_has_correct_signature()
    {
        $command = new InstallCommand();
        
        $this->assertStringContainsString('zyna:install', $command->getName());
    }

    public function test_command_has_correct_description()
    {
        $command = new InstallCommand();
        
        $this->assertEquals('Install and configure Zyna components', $command->getDescription());
    }

    public function test_command_signature_includes_expected_options()
    {
        $command = new InstallCommand();
        $definition = $command->getDefinition();
        
        $this->assertTrue($definition->hasOption('type'));
        $this->assertTrue($definition->hasOption('components'));
        $this->assertTrue($definition->hasOption('skip-dependencies'));
        $this->assertTrue($definition->hasOption('force'));
    }

    public function test_command_type_option_has_default_value()
    {
        $command = new InstallCommand();
        $definition = $command->getDefinition();
        $typeOption = $definition->getOption('type');
        
        $this->assertEquals('blade', $typeOption->getDefault());
    }

    public function test_check_tailwind_css_detects_existing_installation()
    {
        $command = $this->getMockBuilder(InstallCommand::class)
            ->onlyMethods(['info'])
            ->getMock();
            
        $command->expects($this->once())
            ->method('info')
            ->with('✅ Tailwind CSS is already installed');
            
        $dependencies = ['tailwindcss' => '^3.0.0'];
        
        $reflection = new \ReflectionClass($command);
        $method = $reflection->getMethod('checkTailwindCSS');
        $method->setAccessible(true);
        
        $method->invoke($command, $dependencies);
    }

    public function test_check_flowbite_detects_existing_installation()
    {
        $command = $this->getMockBuilder(InstallCommand::class)
            ->onlyMethods(['info'])
            ->getMock();
            
        $command->expects($this->once())
            ->method('info')
            ->with('✅ Flowbite is already installed');
            
        $dependencies = ['flowbite' => '^1.0.0'];
        
        $reflection = new \ReflectionClass($command);
        $method = $reflection->getMethod('checkFlowbite');
        $method->setAccessible(true);
        
        $method->invoke($command, $dependencies);
    }

    public function test_get_selected_components_parses_comma_separated_list()
    {
        $command = $this->getMockBuilder(InstallCommand::class)
            ->onlyMethods(['option', 'info'])
            ->getMock();
            
        $command->expects($this->once())
            ->method('option')
            ->with('components')
            ->willReturn('button, alert,badge');
            
        $command->expects($this->once())
            ->method('info')
            ->with('📦 Installing selected components: button, alert, badge');
            
        $reflection = new \ReflectionClass($command);
        $method = $reflection->getMethod('getSelectedComponents');
        $method->setAccessible(true);
        
        $result = $method->invoke($command);
        
        $this->assertEquals(['button', 'alert', 'badge'], $result);
    }

    public function test_get_selected_components_returns_null_for_all_components()
    {
        $command = $this->getMockBuilder(InstallCommand::class)
            ->onlyMethods(['option', 'confirm', 'info'])
            ->getMock();
            
        $command->expects($this->once())
            ->method('option')
            ->with('components')
            ->willReturn(null);
            
        $command->expects($this->once())
            ->method('confirm')
            ->with('Install all available components?', true)
            ->willReturn(true);
            
        $command->expects($this->once())
            ->method('info')
            ->with('📦 Installing all components');
            
        $reflection = new \ReflectionClass($command);
        $method = $reflection->getMethod('getSelectedComponents');
        $method->setAccessible(true);
        
        $result = $method->invoke($command);
        
        $this->assertNull($result);
    }

    public function test_command_class_exists_and_extends_command()
    {
        $this->assertTrue(class_exists(InstallCommand::class));
        $this->assertInstanceOf(\Illuminate\Console\Command::class, new InstallCommand());
    }
}