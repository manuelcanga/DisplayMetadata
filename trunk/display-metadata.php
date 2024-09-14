<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata;

/*
 * Plugin Name: Display Metadata
 * Plugin URI: https://github.com/manuelcanga/DisplayMetadata
 * Description: Displays metadata in a metabox on the creation/editing pages for posts (any CPT), terms (any taxonomy), and users. The metadata is shown in a human-readable format, organized and unserialized. This metabox will only be visible to administrator users or users with the `display_metadata_metabox` capability.
 * Version: 0.4.1
 * Author: Manuel Canga
 * Author URI: https://manuelcanga.dev
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: display-metadata
 * Domain Path: /languages
*/

if (!defined('ABSPATH')) {
    die('Hello, World!');
}

if (!is_admin()) {
    return;
}

add_action('admin_init', static function (): void {
    $plugin_services = include __DIR__ . '/config/services.conf.php';

    ${'display-metadata'} = $plugin_services['plugin'](base_dir: __DIR__, services: $plugin_services);
    ${'display-metadata'}->run();
});