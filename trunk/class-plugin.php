<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata;

use Trasweb\Plugins\DisplayMetadata\Metabox\MetaboxFactory;
use function define;

/**
 * Class Plugin. Initialize and configure plugin
 */
final class Plugin {

	public const PATH                 = __DIR__;
	public const NAMESPACE            = __NAMESPACE__;
	public const VIEWS_PATH           = self::PATH . '/views';
	public const ASSETS_PATH          = self::PATH . '/assets';
	public const METABOX_FILE_PATTERN = self::PATH . '/Metabox/class-%s.php';

	private $screen_vars;

	/**
	 * Plugin constructor.
	 *
	 * @param array $screen_vars Params( normally $_GET ) from current screen.
	 */
	public function __construct( array $screen_vars ) {
		$this->screen_vars = $screen_vars;
	}

	/**
	 * Plugin Starts
	 *
	 * @return void
	 */
	public function __invoke(): void {
		$this->bootstrap();

		$metabox = MetaboxFactory::get_current_metabox( $this->screen_vars );
		$metabox->register();
	}

	/**
	 * Define basic of plugin in order to can be loaded.
	 *
	 * @return void
	 */
	final private function bootstrap(): void {
		define( self::NAMESPACE . '\PLUGIN_NAME', basename( self::PATH ) );
		define( self::NAMESPACE . '\PLUGIN_TITLE', __( 'Display metadata', PLUGIN_NAME ) );

		//Fix for profile admin pages
		if ( defined( 'IS_PROFILE_PAGE' ) && IS_PROFILE_PAGE ) {
			$this->screen_vars[ 'user_id' ] = get_current_user_id();
		}

		$this->register_metabox_autoload();
	}

	/**
	 * Register plugin autoload for metabo classes.
	 *
	 * @return void
	 */
	final private function register_metabox_autoload(): void {
		spl_autoload_register( function ( $class_name ) {
			if ( strpos( $class_name, self::NAMESPACE ) ) {
				return;
			}

			$file_name = strtolower( trim( strrchr( $class_name, '\\' ), '\\' ) );
			require sprintf( self::METABOX_FILE_PATTERN, $file_name );
		}, $throw_exception = false );
	}

	/**
	 * Retrieve current screen.
	 *
	 * @return \WP_Screen
	 */
	final public static function get_current_screen(): \WP_Screen {
		include_once ABSPATH . 'wp-admin/includes/post.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';

		$pagenow = self::get_page_now();
		$screen  = \WP_Screen::get( $pagenow );

		$screen->slug  = $screen->base ?: '';
		$screen->base  = strtok( $screen->slug, '-' ) ?: '';
		$screen->place = strtok( '' ) ?: 'main';

		return $screen;
	}

	/**
	 * Helper: Retrieve page now from request
	 *
	 * @return string
	 */
	final private static function get_page_now(): string {
		global $pagenow;

		if ( !$pagenow ) {
			unset( $pagenow );

			$current_base_url = parse_url( $_SERVER[ 'REQUEST_URI' ], PHP_URL_PATH ) ?: '';

			$pagenow = strrchr( $current_base_url, '/' ) ?: $current_base_url;
		}

		return basename( $pagenow, '.php' );
	}
}
