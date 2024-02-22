<?php

declare(strict_types=1);

use Trasweb\Plugins\DisplayMetadata\Display_Metadata;

$plugin_dir = __DIR__ . '/../trunk';

require_once $plugin_dir . '/src/class-display-metadata.php';

${'display-metadata'} = new Display_Metadata($plugin_dir, $_GET ?: []);
${'display-metadata'}->bootstrap();

// Throw tests with  vendor/bin/phpunit --bootstrap tests/bootstrap.php tests