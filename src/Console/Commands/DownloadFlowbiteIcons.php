<?php

namespace Zyna\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use ZipArchive;

class DownloadFlowbiteIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zyna:download-icons 
                            {--force : Force download even if icons exist}
                            {--categories=* : Specific categories to download}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Flowbite icons from GitHub repository';

    /**
     * GitHub repository URL
     */
    protected string $repoUrl = 'https://github.com/themesberg/flowbite-icons/archive/refs/heads/main.zip';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting Flowbite icons download...');

        $iconsPath = base_path('vendor/sulimanbenhalim/zyna/resources/icons');
        
        // Check if icons already exist
        if (!$this->option('force') && File::exists($iconsPath . '/outline') && File::exists($iconsPath . '/solid')) {
            $this->warn('Icons already exist. Use --force to re-download.');
            return Command::SUCCESS;
        }

        // Create temporary directory
        $tempDir = storage_path('app/temp/flowbite-icons');
        File::ensureDirectoryExists($tempDir);

        try {
            // Download the ZIP file
            $this->info('Downloading icons from GitHub...');
            $zipPath = $tempDir . '/flowbite-icons.zip';
            
            $response = Http::timeout(120)->get($this->repoUrl);
            
            if (!$response->successful()) {
                throw new \Exception('Failed to download icons from GitHub');
            }
            
            File::put($zipPath, $response->body());
            $this->info('Download complete.');

            // Extract the ZIP file
            $this->info('Extracting icons...');
            $zip = new ZipArchive();
            
            if ($zip->open($zipPath) === true) {
                $zip->extractTo($tempDir);
                $zip->close();
                $this->info('Extraction complete.');
            } else {
                throw new \Exception('Failed to extract ZIP file');
            }

            // Find the extracted directory (GitHub adds a suffix)
            $extractedDir = collect(File::directories($tempDir))
                ->first(fn($dir) => str_contains($dir, 'flowbite-icons'));

            if (!$extractedDir) {
                throw new \Exception('Could not find extracted directory');
            }

            // Copy icons to the package directory
            $this->info('Copying icons to package directory...');
            
            $sourceIconsDir = $extractedDir . '/src';
            $categories = $this->option('categories') ?: [];

            // Copy outline icons
            if (File::exists($sourceIconsDir . '/outline')) {
                $this->copyIcons($sourceIconsDir . '/outline', $iconsPath . '/outline', $categories);
            }

            // Copy solid icons
            if (File::exists($sourceIconsDir . '/solid')) {
                $this->copyIcons($sourceIconsDir . '/solid', $iconsPath . '/solid', $categories);
            }

            // Clean up temporary files
            $this->info('Cleaning up temporary files...');
            File::deleteDirectory($tempDir);

            $this->info('✅ Flowbite icons downloaded successfully!');
            
            // Show statistics
            $outlineCount = $this->countIcons($iconsPath . '/outline');
            $solidCount = $this->countIcons($iconsPath . '/solid');
            
            $this->table(
                ['Style', 'Icon Count'],
                [
                    ['Outline', $outlineCount],
                    ['Solid', $solidCount],
                    ['Total', $outlineCount + $solidCount],
                ]
            );

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            
            // Clean up on error
            if (File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            
            return Command::FAILURE;
        }
    }

    /**
     * Copy icons from source to destination
     */
    protected function copyIcons(string $source, string $destination, array $categories = []): void
    {
        File::ensureDirectoryExists($destination);

        // If no specific categories, copy everything
        if (empty($categories)) {
            File::copyDirectory($source, $destination);
            return;
        }

        // Copy only specified categories
        foreach ($categories as $category) {
            $categoryPath = $source . '/' . $category;
            if (File::exists($categoryPath)) {
                File::copyDirectory($categoryPath, $destination . '/' . $category);
                $this->info("Copied category: {$category}");
            } else {
                $this->warn("Category not found: {$category}");
            }
        }
    }

    /**
     * Count the number of SVG files in a directory
     */
    protected function countIcons(string $directory): int
    {
        if (!File::exists($directory)) {
            return 0;
        }

        $count = 0;
        
        // Count SVG files in root directory
        $files = File::files($directory);
        foreach ($files as $file) {
            if ($file->getExtension() === 'svg') {
                $count++;
            }
        }

        // Count SVG files in subdirectories
        $directories = File::directories($directory);
        foreach ($directories as $dir) {
            $files = File::files($dir);
            foreach ($files as $file) {
                if ($file->getExtension() === 'svg') {
                    $count++;
                }
            }
        }

        return $count;
    }
}