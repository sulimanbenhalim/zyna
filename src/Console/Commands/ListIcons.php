<?php

namespace Zyna\Console\Commands;

use Illuminate\Console\Command;
use Zyna\Support\IconManager;

class ListIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zyna:list-icons 
                            {--style=outline : Icon style (outline or solid)}
                            {--search= : Search for icons containing this term}
                            {--category= : Filter by category}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available Zyna icons';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $iconManager = app(IconManager::class);
        $style = $this->option('style');
        $search = $this->option('search');
        $category = $this->option('category');

        $this->info("Listing {$style} icons...");

        $icons = $iconManager->getAllIcons($style);

        if (empty($icons)) {
            $this->warn('No icons found. Run "php artisan zyna:download-icons" to download Flowbite icons.');
            return Command::FAILURE;
        }

        // Filter by search term
        if ($search) {
            $icons = collect($icons)->filter(function ($icon) use ($search) {
                $name = is_array($icon) ? $icon['name'] : $icon;
                return str_contains(strtolower($name), strtolower($search));
            })->values()->toArray();
        }

        // Filter by category
        if ($category) {
            $icons = collect($icons)->filter(function ($icon) use ($category) {
                return is_array($icon) && isset($icon['category']) && 
                       str_contains(strtolower($icon['category']), strtolower($category));
            })->values()->toArray();
        }

        if (empty($icons)) {
            $this->warn('No icons found matching your criteria.');
            return Command::SUCCESS;
        }

        // Group by category
        $grouped = [];
        $uncategorized = [];

        foreach ($icons as $icon) {
            if (is_array($icon) && isset($icon['category'])) {
                $grouped[$icon['category']][] = $icon['name'];
            } else {
                $uncategorized[] = is_array($icon) ? $icon['name'] : $icon;
            }
        }

        // Display uncategorized icons
        if (!empty($uncategorized)) {
            $this->line("\n<fg=yellow>Uncategorized Icons:</>");
            $this->displayIconsInColumns($uncategorized);
        }

        // Display categorized icons
        foreach ($grouped as $category => $categoryIcons) {
            $this->line("\n<fg=yellow>" . ucfirst(str_replace([':', '-', '_'], ' ', $category)) . ":</>");
            $this->displayIconsInColumns($categoryIcons);
        }

        $totalCount = count($icons);
        $this->info("\nTotal icons: {$totalCount}");

        return Command::SUCCESS;
    }

    /**
     * Display icons in columns
     */
    protected function displayIconsInColumns(array $icons): void
    {
        sort($icons);
        $chunks = array_chunk($icons, 4);

        foreach ($chunks as $chunk) {
            $row = '';
            foreach ($chunk as $icon) {
                $row .= str_pad($icon, 30);
            }
            $this->line($row);
        }
    }
}