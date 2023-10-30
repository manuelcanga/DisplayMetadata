<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata;

use Trasweb\Plugins\DisplayMetadata\Framework\Autoload;
use Trasweb\Plugins\DisplayMetadata\Framework\Screen;
use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox_Factory;

use function define;

/**
 * Class Plugin. Initialize and configure plugin
 */
final class Plugin {

    public const PATH = __DIR__;
    public const NAMESPACE = __NAMESPACE__;
    public const VIEWS_PATH = self::PATH . '/../views';
    public const ASSETS_PATH = self::PATH . '/../assets';
    public const NEEDED_CAPABILITY = 'display_metadata_metabox';

    private $screen_vars;

    /**
     * Plugin constructor.
     *
     * @param array $screen_vars Params( normally $_GET ) from current screen.
     */
    public function __construct( array $screen_vars )
    {
        $this->screen_vars = $screen_vars;
    }

    /**
     * Retrieve current screen.
     *
     * @return \WP_Screen
     */
    final public static function get_current_screen(): \WP_Screen
    {
        return (new Screen())->get_current();
    }

    /**
     * Plugin Starts
     *
     * @return void
     */
    public function __invoke(): void
    {
        if ( ! current_user_can('administrator') && ! \current_user_can(self::NEEDED_CAPABILITY) ) {
            return;
        }

        $this->bootstrap();

        $metabox = (new Metabox_Factory())->get_current_metabox($this->screen_vars);
        if ( $metabox->can_be_registered() ) {
            $metabox->register();
        }
    }

    /**
     * Define basic of plugin in order to can be loaded.
     *
     * @return void
     */
    private function bootstrap(): void
    {
        define(self::NAMESPACE . '\PLUGIN_NAME', basename(self::PATH));
        define(self::NAMESPACE . '\PLUGIN_TITLE', __('Display metadata', PLUGIN_NAME));

        // Fix for profile admin pages.
        if ( defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE ) {
            $this->screen_vars[ 'user_id' ] = get_current_user_id();
        }

        $autoload = new  Autoload(self::NAMESPACE, self::PATH );

        spl_autoload_register([$autoload, 'find_class']);
    }
}