<?php

declare(strict_types=1);

use Trasweb\Autoload;
use Trasweb\Parser;
use Trasweb\Plugins\DisplayMetadata\Display_Metadata as Plugin;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_Factory;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_View;
use Trasweb\Plugins\DisplayMetadata\UserCase\Register_Metabox;
use Trasweb\Screen;

return [
    'plugin' => static function(string $base_dir, array $services): Plugin {
        include_once $base_dir . '/src/class-display-metadata.php';
        $plugin_config = include $base_dir . '/config/display-metadata.conf.php';

        $plugin = new Plugin($plugin_config, $services);
        $plugin->bootstrap();

        return $plugin;
    },

    'autoload' => static function (string $base_namespace, string $base_dir): Autoload {
        include_once $base_dir . '/vendor/Trasweb/class-autoload.php';
        include_once $base_dir . '/vendor/Trasweb/class-screen.php';
        include_once $base_dir . '/vendor/Trasweb/class-parser.php';

        return new  Autoload($base_namespace, $base_dir);
    },

    'parser' => static fn(): Parser => new Parser(
        view_path: Plugin::dir() . Plugin::config('views.subpath'),
        assets_path: Plugin::dir() . Plugin::config('assets.subpath'),
    ),

    'screen' => static fn(): Screen => new Screen(),

    'metabox_view' => static fn(): Metabox_View => new Metabox_View(
        parser: Plugin::service('parser'),
    ),

    'metabox_factory' => static function (): Metabox_Factory {
        $screen_vars = $_GET ?: [];

        // fix profile admin pages screen vars
        if (defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE) {
            $screen_vars['user_id'] = get_current_user_id();
        }

        return new Metabox_Factory(
            $screen_vars,
            Plugin::config('metabox-types'),
        );
    },

    'metabox_register' => static fn(): Register_Metabox => new Register_Metabox(
        Plugin::service('metabox_factory'),
        Plugin::service('metabox_view'),
        Plugin::service('screen')
    ),
];