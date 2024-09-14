<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Tests;

$plugin_dir = __DIR__ . '/../trunk';
/**
 * @const \Trasweb\Plugins\DisplayMetadata\Tests\PLUGIN_DIR
 */
define(__NAMESPACE__.'\PLUGIN_DIR', $plugin_dir);
/**
 * @const ABSPATH
 */
define('ABSPATH', __DIR__.'/../../../');

$plugin_services = include $plugin_dir . '/config/services.conf.php';

${'display-metadata'} = $plugin_services['plugin'](base_dir: $plugin_dir, services: $plugin_services);

// Throw tests with  vendor/bin/phpunit --bootstrap tests/bootstrap.php tests