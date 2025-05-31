# Zyna Icon System

The Zyna package includes a powerful icon system that integrates Flowbite's extensive icon collection with Laravel components.

## Features

- **430+ Icons**: Access to Flowbite's complete icon collection
- **Two Styles**: Both outline and solid icon variants
- **Efficient Caching**: Icons are cached for optimal performance
- **Simple Syntax**: Easy-to-use Blade component syntax
- **Customizable**: Sizes, colors, and custom classes
- **Livewire Support**: Works with both Blade and Livewire implementations

## Installation

After installing the Zyna package, download the Flowbite icons:

```bash
php artisan zyna:download-icons
```

This command downloads all icons from the Flowbite GitHub repository and stores them in your package directory.

### Download Options

Download specific categories only:
```bash
php artisan zyna:download-icons --categories=general --categories=arrows
```

Force re-download (overwrites existing icons):
```bash
php artisan zyna:download-icons --force
```

## Basic Usage

Use icons in your Blade templates with the simple component syntax:

```blade
<x-zyna:icon name="home" />
```

## Available Properties

### name (required)
The icon name without the .svg extension.

```blade
<x-zyna:icon name="user" />
<x-zyna:icon name="shopping-cart" />
<x-zyna:icon name="bell-active" />
```

### size
Control the icon size. Available options: `xs`, `sm`, `md` (default), `lg`, `xl`, `2xl`

```blade
<x-zyna:icon name="star" size="xs" />  {{-- 12x12px --}}
<x-zyna:icon name="star" size="sm" />  {{-- 16x16px --}}
<x-zyna:icon name="star" size="md" />  {{-- 20x20px --}}
<x-zyna:icon name="star" size="lg" />  {{-- 24x24px --}}
<x-zyna:icon name="star" size="xl" />  {{-- 32x32px --}}
<x-zyna:icon name="star" size="2xl" /> {{-- 40x40px --}}
```

### style
Choose between outline (default) and solid styles:

```blade
<x-zyna:icon name="heart" style="outline" />
<x-zyna:icon name="heart" style="solid" />
```

### color
Apply predefined color themes or custom Tailwind classes:

```blade
{{-- Predefined colors --}}
<x-zyna:icon name="bell" color="primary" />   {{-- Blue --}}
<x-zyna:icon name="bell" color="secondary" /> {{-- Gray --}}
<x-zyna:icon name="bell" color="success" />   {{-- Green --}}
<x-zyna:icon name="bell" color="danger" />    {{-- Red --}}
<x-zyna:icon name="bell" color="warning" />   {{-- Yellow --}}
<x-zyna:icon name="bell" color="info" />      {{-- Cyan --}}

{{-- Custom Tailwind classes --}}
<x-zyna:icon name="bell" color="text-purple-500" />
```

### class
Add custom CSS classes:

```blade
<x-zyna:icon name="refresh" class="animate-spin" />
<x-zyna:icon name="bell" class="animate-bounce hover:text-blue-500" />
```

## Practical Examples

### Button with Icon
```blade
<button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
    <x-zyna:icon name="download" size="sm" class="mr-2" />
    Download Report
</button>
```

### Alert with Icon
```blade
<div class="flex items-start p-4 bg-green-50 border border-green-200 rounded-lg">
    <x-zyna:icon name="check-circle" size="md" color="success" class="mr-3 flex-shrink-0" />
    <p class="text-green-800">Your changes have been saved successfully!</p>
</div>
```

### Navigation Menu
```blade
<nav class="space-y-1">
    <a href="/dashboard" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
        <x-zyna:icon name="home" size="md" class="mr-3" />
        Dashboard
    </a>
    <a href="/users" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
        <x-zyna:icon name="users-group" size="md" class="mr-3" />
        Users
    </a>
    <a href="/settings" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
        <x-zyna:icon name="cog" size="md" class="mr-3" />
        Settings
    </a>
</nav>
```

### Loading States
```blade
<button class="relative inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg" wire:click="save">
    <x-zyna:icon name="refresh" size="sm" class="mr-2 animate-spin" wire:loading wire:target="save" />
    <span wire:loading.remove wire:target="save">Save Changes</span>
    <span wire:loading wire:target="save">Saving...</span>
</button>
```

## Finding Icons

### List Available Icons
View all downloaded icons:

```bash
php artisan zyna:list-icons
```

Filter by style:
```bash
php artisan zyna:list-icons --style=solid
```

Search for specific icons:
```bash
php artisan zyna:list-icons --search=user
```

Filter by category:
```bash
php artisan zyna:list-icons --category=arrows
```

### Icon Categories
Flowbite icons are organized into categories:
- arrows
- e-commerce
- education
- emoji
- files:folders
- food:beverage
- general
- media
- text
- user
- weather

## Configuration

Customize the icon system in `config/zyna.php`:

```php
'icons' => [
    'provider' => 'flowbite',
    'default_style' => 'outline', // or 'solid'
    'default_size' => 'md',
    'cache_ttl' => 86400, // 24 hours
    'sizes' => [
        'xs' => 'w-3 h-3',
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        'xl' => 'w-8 h-8',
        '2xl' => 'w-10 h-10',
    ],
    'colors' => [
        'primary' => 'text-blue-600',
        'secondary' => 'text-gray-600',
        'success' => 'text-green-600',
        'danger' => 'text-red-600',
        'warning' => 'text-yellow-600',
        'info' => 'text-cyan-600',
    ],
],
```

## Performance

The icon system includes several performance optimizations:

1. **Caching**: Icons are cached for 24 hours by default
2. **Lazy Loading**: Icons are only loaded when used
3. **Optimized SVGs**: Flowbite icons are already optimized for web use

### Clear Icon Cache
If needed, you can clear the icon cache:

```php
app(\Zyna\Support\IconManager::class)->clearCache();
```

## Troubleshooting

### Icons Not Showing
1. Ensure you've run `php artisan zyna:download-icons`
2. Check that the icon name is correct (use `php artisan zyna:list-icons`)
3. Verify the style (outline/solid) exists for that icon

### Styling Issues
1. Ensure Tailwind CSS is properly configured
2. Check that custom color classes are included in your Tailwind config
3. Use the `class` prop for additional styling

## Advanced Usage

### Custom Icon Sources
You can add your own SVG icons to the icon directories:
- Outline icons: `vendor/zyna/resources/icons/outline/`
- Solid icons: `vendor/zyna/resources/icons/solid/`

### Programmatic Access
Access the IconManager directly:

```php
$iconManager = app(\Zyna\Support\IconManager::class);
$svgContent = $iconManager->getIcon('home', 'outline');
```