<?php

declare(strict_types=1);

$plugin_dir = __DIR__ . '/../trunk';

$plugin_services = include $plugin_dir . '/config/services.conf.php';

${'display-metadata'} = $plugin_services['plugin'](base_dir: $plugin_dir, services: $plugin_services);

// Throw tests with  vendor/bin/phpunit --bootstrap tests/bootstrap.php tests