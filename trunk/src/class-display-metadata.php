<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata;

use Trasweb\Autoload;
use Trasweb\Parser;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_Factory;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_View;
use Trasweb\Plugins\DisplayMetadata\UserCase\Register_Metabox;
use Trasweb\Screen;

/**
 * Class Plugin. Initialize and configure plugin
 */
final class Display_Metadata
{
    private const VIEWS_SUBPATH = '/views';
    private const ASSETS_SUBPATH = '/assets';
    private const CONFIG_METABOX_TYPES = '/config/metabox-types.conf.php';
    private array $screen_vars;
    private string $plugin_dir;

    /**
     * @param string $plugin_dir
     * @param array $screen_vars
     *
     * @return void
     */
    public function __construct(string $plugin_dir, array $screen_vars)
    {
        $this->fix_profile_admin_pages_screen_vars($screen_vars);

        $this->plugin_dir = $plugin_dir;
        $this->screen_vars = $screen_vars;
    }

    /**
     * Run plugin
     *
     * @return void
     */
    public function run(): void
    {
        $parser = new Parser($this->plugin_dir . self::VIEWS_SUBPATH, $this->plugin_dir . self::ASSETS_SUBPATH);
        $metabox_view = new Metabox_View($parser);

        $metabox_types = include $this->plugin_dir.self::CONFIG_METABOX_TYPES;
        $metabox_factory = new Metabox_Factory($this->screen_vars, $metabox_types);

        $screen = new Screen();

        $register = new Register_Metabox($metabox_factory, $metabox_view, $screen);
        $register->__invoke();
    }

    /**
     * Initialize basic dependencies
     *
     * @return void
     */
    public function bootstrap(): void
    {
        include_once $this->plugin_dir . '/src/vendor/Trasweb/class-autoload.php';
        include_once $this->plugin_dir . '/src/vendor/Trasweb/class-screen.php';
        include_once $this->plugin_dir . '/src/vendor/Trasweb/class-parser.php';

        define(__NAMESPACE__ . '\PLUGIN_NAME', basename($this->plugin_dir));
        spl_autoload_register((new  Autoload(__NAMESPACE__, __DIR__))->find_class(...));
    }

    /**
     * @param array $screen_vars Vars by reference from global environment.
     *
     * @return void
     */
    private function fix_profile_admin_pages_screen_vars(array &$screen_vars): void
    {
        if (defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE) {
            $screen_vars['user_id'] = get_current_user_id();
        }
    }
}