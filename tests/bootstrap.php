<?php
declare(strict_types=1);

use Trasweb\Plugins\DisplayMetadata\Helper\Autoload;
use Trasweb\Plugins\DisplayMetadata\Display_Metadata;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__ . '/../trunk/src/class-plugin.php';
require_once __DIR__ . '/../trunk/src/Helper/class-autoload.php';

$autoload = new  Autoload(Display_Metadata::NAMESPACE, Display_Metadata::PATH);
spl_autoload_register([$autoload, 'find_class']);

// Throw tests with  vendor/bin/phpunit --bootstrap tests/bootstrap.php tests