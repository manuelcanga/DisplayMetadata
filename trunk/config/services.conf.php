<?php

declare(strict_types=1);

use Trasweb\Autoload;
use Trasweb\Parser;
use Trasweb\Plugins\DisplayMetadata\Display_Metadata as Plugin;
use Trasweb\Plugins\DisplayMetadata\Dto\Id_Url_Params;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_Factory;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_View;
use Trasweb\Plugins\DisplayMetadata\Iterator\Metabox_Types_Iterator;
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
        $metabox_types_config = Plugin::config('metabox-types');
        $default_metabox = Plugin::Config('default-metabox');
        $metabox_types = new Metabox_Types_Iterator($metabox_types_config, $default_metabox);

        return new Metabox_Factory(
            Id_Url_Params::create_from_globals(),
            $metabox_types,
        );
    },

    'metabox_register' => static fn(): Register_Metabox => new Register_Metabox(
        Plugin::service('metabox_factory'),
        Plugin::service('metabox_view'),
        Plugin::service('screen')
    ),
];