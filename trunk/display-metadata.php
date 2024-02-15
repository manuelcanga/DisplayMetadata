<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata;

/*
 * Plugin Name: Display Metadata
 * Plugin URI: https://github.com/manuelcanga/DisplayMetadata
 * Description: Shows metas in a metabox for posts( any CPT ), terms( any taxonomy ), comments and users. Metadata are displayed for humans( organized and unserialized ). This metabox only will be displayed per either administrator users or users with `display_metadata_metabox` capability.
 * Version: 0.4.1
 * Author: Manuel Canga
 * Author URI: https://manuelcanga.dev
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: display-metadata
 * Domain Path: /languages
*/


use Trasweb\Autoload;

if (!defined('ABSPATH')) {
    die('Hello, World!');
}

if (!is_admin()) {
    return;
}

(static function (): void {
    include_once __DIR__ . '/src/vendor/Trasweb/class-autoload.php';

    define(__NAMESPACE__ . '\PLUGIN_NAME', basename(__DIR__));

    spl_autoload_register((new  Autoload(__NAMESPACE__, __DIR__.'/src'))->find_class(...));

    ${'display-metadata'} = new Plugin($_GET ?: []);

    add_action('admin_init', ${'display-metadata'});
})();