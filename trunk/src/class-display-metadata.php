<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata;

/**
 * Class Display_Metadata
 *
 * This class handles the initialization, configuration, and service location for the plugin.
 * It acts as a central point for accessing configurations and services, as well as bootstrapping the plugin.
 */
final class Display_Metadata
{
    /**
     * @var array<string, mixed> $plugin_config Stores plugin configuration in dot notation format.
     */
    private static array $plugin_config;

    /**
     * @var array<string, callable> $plugin_services Service container that stores closures to initialize services.
     */
    private static array $plugin_services;

    /**
     * Display_Metadata constructor.
     *
     * @param array<string, mixed> $plugin_config Configuration array for the plugin.
     * @param array<string, callable> $plugin_services Service container with closures for service initialization.
     */
    public function __construct(array $plugin_config, array $plugin_services)
    {
        static::$plugin_config = $plugin_config;
        static::$plugin_services = $plugin_services;
    }

    /**
     * Retrieve the directory of the plugin.
     *
     * @return string The directory path where the plugin is located.
     */
    public static function dir(): string
    {
        return static::config('plugin.dir');
    }

    /**
     * Retrieve a configuration value using dot notation.
     *
     * @param string $key_dot_notation The configuration key in dot notation format (e.g., "plugin.name").
     * @param string|int|null $default_value The default value to return if the key is not found.
     *
     * @return mixed The configuration value or the default value if the key does not exist.
     */
    public static function config(string $key_dot_notation, string|int|null $default_value = null): mixed
    {
        $keys = explode('.', $key_dot_notation);

        // Traverse the configuration array using the dot notation keys.
        $config_value = static::$plugin_config;
        foreach ($keys as $next_key) {
            $config_value = $config_value[$next_key] ?? $default_value;
        }

        return $config_value;
    }

    /**
     * Retrieve a service from the service container.
     *
     * @param string $service_name The name of the service to retrieve.
     * @param mixed ...$extra_args Optional additional arguments to pass to the service initializer.
     *
     * @return object The initialized service object.
     */
    public static function service(string $service_name, ...$extra_args): object
    {
        return static::$plugin_services[$service_name](...$extra_args);
    }

    /**
     * Run the plugin by invoking the main metabox registration service.
     *
     * @return void
     */
    public function run(): void
    {
        $register = static::service('metabox_register');
        $register->__invoke();
    }

    /**
     * Bootstrap the plugin by setting up basic dependencies like autoloaders and constants.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Avoid bootstrap twice the plugin if already was done.
        if (defined(__NAMESPACE__ . '\PLUGIN_NAME')) {
            return;
        }

        // Define the plugin name constant.
        define(__NAMESPACE__ . '\PLUGIN_NAME', static::config('plugin.name'));

        // Register the autoload function for classes.
        spl_autoload_register(static::service('autoload', __NAMESPACE__, __DIR__)->find_class(...));
    }
}