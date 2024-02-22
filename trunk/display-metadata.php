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
use Trasweb\Screen;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_View;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_Factory;

if (!defined('ABSPATH')) {
    die('Hello, World!');
}

if (!is_admin()) {
    return;
}

add_action('admin_init', static function (): void {
    include_once __DIR__ . '/src/vendor/Trasweb/class-autoload.php';
    include_once __DIR__ . '/src/vendor/Trasweb/class-screen.php';

    define(__NAMESPACE__ . '\PLUGIN_NAME', basename(__DIR__));

    spl_autoload_register((new  Autoload(__NAMESPACE__, __DIR__.'/src'))->find_class(...));

    // Fix for profile admin pages.
    if (defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE) {
        $_GET['user_id'] = get_current_user_id();
    }

    $metabox_view = new Metabox_View(__DIR__.'/views');
    $metabox_factory = new Metabox_Factory($_GET ?: []);
    $screen = new Screen();

    ${'display-metadata'} = new Display_Metadata($metabox_factory, $screen, $metabox_view);

    ${'display-metadata'}();
});