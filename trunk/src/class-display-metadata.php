<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata;

/**
 * Class Plugin. Initialize and configure plugin
 */
final class Display_Metadata
{
    private static array $plugin_config;
    private static array $plugin_services;

    /**
     * @param array $plugin_config
     * @param array $plugin_services
     *
     */
    public function __construct(array $plugin_config, array $plugin_services)
    {
        static::$plugin_config = $plugin_config;
        static::$plugin_services = $plugin_services;
    }

    public static function dir(): string
    {
        return static::config('plugin.dir');
    }

    public static function config(string $key_dot_notation, string|int|null $default_value = null): mixed
    {
        $keys = explode('.', $key_dot_notation);

        $config_value = static::$plugin_config;
        foreach ($keys as $next_key) {
            $config_value = $config_value[$next_key] ?? $default_value;
        }

        return $config_value;
    }

    public static function service(string $service_name, ...$extra_args): object
    {
        return static::$plugin_services[$service_name](...$extra_args);
    }

    /**
     * Run plugin
     *
     * @return void
     */
    public function run(): void
    {
        $register = static::service('metabox_register');
        $register->__invoke();
    }

    /**
     * Initialize basic dependencies
     *
     * @return void
     */
    public function bootstrap(): void
    {
        define(__NAMESPACE__ . '\PLUGIN_NAME', static::config('plugin.name'));

        spl_autoload_register(static::service('autoload', __NAMESPACE__, __DIR__)->find_class(...));
    }
}