<?php

namespace Zyna\Support;

/**
 * Theme management class for Zyna components.
 * 
 * This class handles theme configuration storage and retrieval,
 * allowing for runtime theme customization and merging.
 */
class Theme
{
    /**
     * The theme configuration array.
     *
     * @var array
     */
    protected array $config;

    /**
     * Create a new Theme instance.
     *
     * @param array|null $config
     */
    public function __construct(?array $config = null)
    {
        $this->config = $config ?? [];
    }

    /**
     * Get a theme configuration value.
     *
     * @param string $key The configuration key using dot notation
     * @param mixed $default The default value if the key doesn't exist
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $key) {
            if (!is_array($value) || !array_key_exists($key, $value)) {
                return $default;
            }
            $value = $value[$key];
        }

        return $value;
    }

    /**
     * Set a theme configuration value.
     *
     * @param string $key The configuration key using dot notation
     * @param mixed $value The value to set
     * @return self
     */
    public function set(string $key, $value): self
    {
        $keys = explode('.', $key);
        $config = &$this->config;

        foreach ($keys as $i => $key) {
            if (count($keys) === 1) {
                break;
            }

            unset($keys[$i]);

            if (!isset($config[$key]) || !is_array($config[$key])) {
                $config[$key] = [];
            }

            $config = &$config[$key];
        }

        $config[array_shift($keys)] = $value;

        return $this;
    }

    /**
     * Merge additional configuration into the theme.
     *
     * @param array $config The configuration to merge
     * @return self
     */
    public function merge(array $config): self
    {
        $this->config = $this->mergeRecursive($this->config, $config);
        
        return $this;
    }

    /**
     * Get all theme configuration.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->config;
    }

    /**
     * Check if a theme configuration key exists.
     *
     * @param string $key The configuration key using dot notation
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->get($key) !== null;
    }

    /**
     * Recursively merge two arrays.
     *
     * @param array $array1
     * @param array $array2
     * @return array
     */
    protected function mergeRecursive(array $array1, array $array2): array
    {
        $merged = $array1;

        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->mergeRecursive($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}