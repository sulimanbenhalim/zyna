<?php

namespace Zyna\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Zyna\Support\AssetManager;

class AssetManagerTest extends TestCase
{
    protected AssetManager $assetManager;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->assetManager = new AssetManager([
            'base_path' => '/vendor/zyna',
            'manifest_path' => '/tmp/manifest.json', // Set explicit path for testing
            'use_manifest' => false, // Disable manifest for testing
            'development_server' => null, // Disable dev server for testing
            'force_production' => true, // Force production mode for testing
        ]);
    }

    public function test_can_be_instantiated_with_config()
    {
        $this->assertInstanceOf(AssetManager::class, $this->assetManager);
    }

    public function test_get_javascript_url_returns_fallback_in_production()
    {
        $url = $this->assetManager->getJavaScriptUrl();
        
        $this->assertEquals('/vendor/zyna/core.es.js', $url);
    }

    public function test_get_css_url_returns_null_when_no_manifest()
    {
        $url = $this->assetManager->getCssUrl();
        
        $this->assertNull($url);
    }

    public function test_get_assets_returns_array_with_js_and_css()
    {
        $assets = $this->assetManager->getAssets();
        
        $this->assertIsArray($assets);
        $this->assertArrayHasKey('js', $assets);
        $this->assertArrayHasKey('css', $assets);
        $this->assertEquals('/vendor/zyna/core.es.js', $assets['js']);
        $this->assertNull($assets['css']);
    }

    public function test_get_script_tag_generates_correct_html()
    {
        $tag = $this->assetManager->getScriptTag();
        
        $this->assertStringContainsString('<script src="/vendor/zyna/core.es.js"', $tag);
        $this->assertStringContainsString('</script>', $tag);
    }

    public function test_get_stylesheet_tag_returns_null_when_no_css()
    {
        $tag = $this->assetManager->getStylesheetTag();
        
        $this->assertNull($tag);
    }

    public function test_get_asset_tags_returns_array_of_tags()
    {
        $tags = $this->assetManager->getAssetTags();
        
        $this->assertIsArray($tags);
        $this->assertCount(1, $tags); // Only JS tag since CSS is null
        $this->assertStringContainsString('<script', $tags[0]);
    }

    public function test_render_asset_tags_returns_html_string()
    {
        $html = $this->assetManager->renderAssetTags();
        
        $this->assertIsString($html);
        $this->assertStringContainsString('<script src="/vendor/zyna/core.es.js"', $html);
    }

    public function test_get_livewire_assets_returns_proper_structure()
    {
        $livewireAssets = $this->assetManager->getLivewireAssets();
        
        $this->assertIsArray($livewireAssets);
        $this->assertArrayHasKey('scripts', $livewireAssets);
        $this->assertArrayHasKey('styles', $livewireAssets);
        $this->assertArrayHasKey('preload', $livewireAssets);
        
        $this->assertIsArray($livewireAssets['scripts']);
        $this->assertIsArray($livewireAssets['styles']);
        $this->assertTrue($livewireAssets['preload']);
        
        $this->assertContains('/vendor/zyna/core.es.js', $livewireAssets['scripts']);
    }

    public function test_set_config_updates_configuration()
    {
        $originalConfig = $this->assetManager->getConfig('base_path');
        $this->assertEquals('/vendor/zyna', $originalConfig);
        
        $this->assetManager->setConfig(['base_path' => '/custom/path']);
        
        $newConfig = $this->assetManager->getConfig('base_path');
        $this->assertEquals('/custom/path', $newConfig);
    }

    public function test_get_config_returns_default_when_key_not_found()
    {
        $value = $this->assetManager->getConfig('non_existent_key', 'default_value');
        
        $this->assertEquals('default_value', $value);
    }

    public function test_clear_cache_resets_manifest()
    {
        $result = $this->assetManager->clearCache();
        
        $this->assertSame($this->assetManager, $result);
    }

    public function test_asset_url_includes_version_when_provided()
    {
        $assetManager = new AssetManager([
            'base_path' => '/vendor/zyna',
            'manifest_path' => '/tmp/manifest.json',
            'version' => 'test-version',
            'use_manifest' => false,
            'development_server' => null,
            'force_production' => true,
        ]);
        
        $url = $assetManager->getJavaScriptUrl();
        
        $this->assertStringContainsString('v=test-version', $url);
    }

    public function test_development_mode_detection_returns_false_without_server()
    {
        $reflection = new \ReflectionClass($this->assetManager);
        $method = $reflection->getMethod('isDevelopmentMode');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->assetManager);
        
        $this->assertFalse($result);
    }

    public function test_vite_server_running_detection_returns_false_for_invalid_server()
    {
        $reflection = new \ReflectionClass($this->assetManager);
        $method = $reflection->getMethod('isViteServerRunning');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->assetManager);
        
        $this->assertFalse($result);
    }

    public function test_get_manifest_returns_empty_array_when_file_not_exists()
    {
        $reflection = new \ReflectionClass($this->assetManager);
        $method = $reflection->getMethod('getManifest');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->assetManager);
        
        $this->assertEquals([], $result);
    }
}