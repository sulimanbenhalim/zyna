<?php

namespace Zyna\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zyna:install 
                            {--type=blade : Component type (blade or livewire)}
                            {--components= : Specific components to install (comma-separated)}
                            {--skip-dependencies : Skip dependency installation}
                            {--force : Force installation even if dependencies exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and configure Zyna components';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Installing Zyna...');

        // Validate implementation type
        $type = $this->option('type');
        if (!in_array($type, ['blade', 'livewire'])) {
            $this->error('Invalid type. Must be blade or livewire');
            return 1;
        }

        $this->info("Installing Zyna with {$type} components...");

        // Check dependencies
        if (!$this->option('skip-dependencies')) {
            $this->checkAndInstallDependencies();
        }

        // Update configuration
        $this->updateConfiguration($type);

        // Handle component selection
        $components = $this->getSelectedComponents();

        // Publish views
        $this->publishViews($type, $components);

        // Publish assets
        $this->publishAssets();

        $this->info('✅ Zyna installation complete!');
        $this->line('');
        $this->info('Next steps:');
        $this->line('- Include Zyna assets in your Blade layout');
        $this->line('- Start using components: <zyna:button>Click me</zyna:button>');

        return 0;
    }

    /**
     * Check and install required dependencies.
     */
    protected function checkAndInstallDependencies()
    {
        $this->info('🔍 Checking dependencies...');

        $packageJsonPath = base_path('package.json');
        $dependencies = $this->getInstalledDependencies($packageJsonPath);

        $this->checkTailwindCSS($dependencies);
        $this->checkFlowbite($dependencies);
    }

    /**
     * Get installed npm dependencies.
     */
    protected function getInstalledDependencies(string $packageJsonPath): array
    {
        if (!File::exists($packageJsonPath)) {
            $this->warn('package.json not found. Creating basic package.json...');
            $this->createBasicPackageJson();
            return [];
        }

        $packageJson = json_decode(File::get($packageJsonPath), true);
        return array_merge(
            $packageJson['dependencies'] ?? [],
            $packageJson['devDependencies'] ?? []
        );
    }

    /**
     * Create a basic package.json file.
     */
    protected function createBasicPackageJson()
    {
        $packageJson = [
            'name' => 'laravel-app',
            'version' => '1.0.0',
            'private' => true,
            'scripts' => [
                'dev' => 'vite',
                'build' => 'vite build'
            ],
            'devDependencies' => []
        ];

        File::put(base_path('package.json'), json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Check and install Tailwind CSS.
     */
    protected function checkTailwindCSS(array $dependencies)
    {
        if (isset($dependencies['tailwindcss'])) {
            $this->info('✅ Tailwind CSS is already installed');
            return;
        }

        if ($this->option('force') || $this->confirm('Tailwind CSS not found. Install it?', true)) {
            $this->info('📦 Installing Tailwind CSS...');
            
            $result = Process::run('npm install tailwindcss @tailwindcss/vite --save-dev');
            
            if ($result->successful()) {
                $this->info('✅ Tailwind CSS installed successfully');
                $this->updateAppCssForTailwind();
            } else {
                $this->error('❌ Failed to install Tailwind CSS');
                $this->line($result->errorOutput());
            }
        }
    }

    /**
     * Check and install Flowbite.
     */
    protected function checkFlowbite(array $dependencies)
    {
        if (isset($dependencies['flowbite'])) {
            $this->info('✅ Flowbite is already installed');
            return;
        }

        if ($this->option('force') || $this->confirm('Flowbite not found. Install it?', true)) {
            $this->info('📦 Installing Flowbite...');
            
            $result = Process::run('npm install flowbite --save');
            
            if ($result->successful()) {
                $this->info('✅ Flowbite installed successfully');
                $this->updateAppCssForFlowbite();
            } else {
                $this->error('❌ Failed to install Flowbite');
                $this->line($result->errorOutput());
            }
        }
    }

    /**
     * Update app.css for Tailwind CSS.
     */
    protected function updateAppCssForTailwind()
    {
        $cssPath = resource_path('css/app.css');
        
        if (!File::exists($cssPath)) {
            File::ensureDirectoryExists(dirname($cssPath));
            File::put($cssPath, '');
        }

        $cssContent = File::get($cssPath);
        $tailwindImports = [
            '@import "tailwindcss/base";',
            '@import "tailwindcss/components";',
            '@import "tailwindcss/utilities";'
        ];

        foreach ($tailwindImports as $import) {
            if (!str_contains($cssContent, $import)) {
                $cssContent = $import . "\n" . $cssContent;
            }
        }

        File::put($cssPath, $cssContent);
        $this->info('📝 Updated app.css with Tailwind imports');
    }

    /**
     * Update app.css for Flowbite.
     */
    protected function updateAppCssForFlowbite()
    {
        $cssPath = resource_path('css/app.css');
        
        if (!File::exists($cssPath)) {
            return;
        }

        $cssContent = File::get($cssPath);
        $flowbiteImport = '@import "flowbite";';

        if (!str_contains($cssContent, $flowbiteImport)) {
            $cssContent .= "\n" . $flowbiteImport;
            File::put($cssPath, $cssContent);
            $this->info('📝 Updated app.css with Flowbite import');
        }
    }

    /**
     * Update configuration with selected implementation type.
     */
    protected function updateConfiguration(string $type)
    {
        $this->info("🔧 Updating configuration for {$type} implementation...");

        // Publish config if it doesn't exist
        if (!File::exists(config_path('zyna.php'))) {
            $this->call('vendor:publish', [
                '--tag' => 'zyna-config'
            ]);
        }

        // Update implementation type in config
        $configPath = config_path('zyna.php');
        if (File::exists($configPath)) {
            $config = File::get($configPath);
            $pattern = "/'implementation' => '[^']*'/";
            $replacement = "'implementation' => '{$type}'";
            
            $updatedConfig = preg_replace($pattern, $replacement, $config);
            File::put($configPath, $updatedConfig);
            
            $this->info("✅ Configuration updated to use {$type} implementation");
        }
    }

    /**
     * Get selected components for installation.
     */
    protected function getSelectedComponents(): ?array
    {
        $components = $this->option('components');
        
        if ($components) {
            $selectedComponents = array_map('trim', explode(',', $components));
            $this->info('📦 Installing selected components: ' . implode(', ', $selectedComponents));
            return $selectedComponents;
        }

        if ($this->confirm('Install all available components?', true)) {
            $this->info('📦 Installing all components');
            return null; // null means all components
        }

        // Allow user to select specific components
        $availableComponents = [
            'button', 'alert', 'badge', 'card', 'dropdown', 'breadcrumb',
            'avatar', 'progress', 'spinner', 'timeline', 'toast', 'tooltip'
        ];

        $selected = [];
        foreach ($availableComponents as $component) {
            if ($this->confirm("Install {$component} component?", false)) {
                $selected[] = $component;
            }
        }

        return empty($selected) ? null : $selected;
    }

    /**
     * Publish view files.
     */
    protected function publishViews(string $type, ?array $components = null)
    {
        $this->info('📁 Publishing view files...');

        $this->call('vendor:publish', [
            '--tag' => 'zyna-views',
            '--force' => $this->option('force')
        ]);

        // If specific components were selected, clean up others
        if ($components !== null) {
            $this->cleanupUnselectedComponents($type, $components);
        }

        $this->info('✅ View files published successfully');
    }

    /**
     * Publish asset files.
     */
    protected function publishAssets()
    {
        $this->info('📁 Publishing asset files...');

        $this->call('vendor:publish', [
            '--tag' => 'zyna-assets',
            '--force' => $this->option('force')
        ]);

        $this->info('✅ Asset files published successfully');
    }

    /**
     * Clean up unselected component files.
     */
    protected function cleanupUnselectedComponents(string $type, array $selectedComponents)
    {
        $viewsPath = resource_path('views/vendor/zyna/components');
        
        if (!File::isDirectory($viewsPath)) {
            return;
        }

        $implementationPath = $viewsPath . '/' . $type;
        
        if (!File::isDirectory($implementationPath)) {
            return;
        }

        $allFiles = File::files($implementationPath);
        
        foreach ($allFiles as $file) {
            $componentName = $file->getFilenameWithoutExtension();
            
            if (!in_array($componentName, $selectedComponents)) {
                File::delete($file->getPathname());
                $this->line("Removed unused component: {$componentName}");
            }
        }
    }
}