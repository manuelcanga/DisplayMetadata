<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata;

use Trasweb\Plugins\DisplayMetadata\Framework\Autoload;
use Trasweb\Plugins\DisplayMetadata\Framework\Screen;
use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox_Factory;

use function current_user_can;

/**
 * Class Plugin. Initialize and configure plugin
 */
final class Plugin {

    public const PATH = __DIR__;
    public const NAMESPACE = __NAMESPACE__;
    public const VIEWS_PATH = self::PATH . '/../views';
    public const ASSETS_PATH = self::PATH . '/../assets';

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
     * Retrieve current screen slug
     *
     * @return string
     */
    protected function get_current_screen_slug(): string
    {
        return (new Screen())->get_current_screen_type();
    }

    /**
     * Retrieve object of Metabox factory.
     *
     * @return Metabox_Factory
     */
    protected function get_metabox_factory(): Metabox_Factory
    {
        return new Metabox_Factory($this->screen_vars);
    }

    /**
     * Plugin Starts
     *
     * @return void
     */
    public function __invoke(): void
    {
        $this->bootstrap();
        $this->register_metabox();
    }


    /**
     * Register metabox for current url.
     *
     * @return void
     */
    public function register_metabox(): void
    {
        $metabox = $this->get_metabox_factory()->get_current_metabox();
        if ($metabox->can_be_registered($this->get_current_screen_slug())) {
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
        // Fix for profile admin pages.
        if ( defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE ) {
            $this->screen_vars[ 'user_id' ] = get_current_user_id();
        }

        $autoload = new  Autoload(self::NAMESPACE, self::PATH );

        spl_autoload_register([$autoload, 'find_class']);
    }
}