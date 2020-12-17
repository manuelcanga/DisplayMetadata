<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

use Trasweb\Plugins\DisplayMetadata\Plugin;

/**
 * This class manages `Display Metadata` metaboxes.
 */
abstract class Metabox {

	protected const METABOX_FILE   = Plugin::VIEWS_PATH . '/metabox.php';
	protected const ASSETS_FILE    = Plugin::VIEWS_PATH . '/assets.php';
	protected const HEADER_FILE    = Plugin::VIEWS_PATH . '/header.php';
	protected const TITLE          = '';
	protected const META_LIST_FILE = Plugin::VIEWS_PATH . '/meta_list.php';
	protected const FOOTER_FILE    = Plugin::VIEWS_PATH . '/footer.php';

	/**
	 * @var string $item_id ID from user, post or term.
	 */
	protected $item_id;

	private function __construct() {
		//not direct instance, please
	}

	private function __clone() {
		//not cloning please
	}

	/**
	 * Named constructor. Create an instance from an item id.
	 *
	 * @param int $item_id
	 *
	 * @return static
	 */
	final public static function from_item_id( int $item_id ): self {
		$metabox          = new static();
		$metabox->item_id = $item_id;

		return $metabox;
	}

	/**
	 * Register a metabox in order to display it later.
	 *
	 * @return void
	 */
	abstract public function register(): void;

	/**
	 * Check if a metabox can be registered
	 *
	 * @return bool
	 */
	abstract protected function can_be_registered(): bool;

	/**
	 * Retrieve item properties/fields. E.g: ID, post_title, ...
	 *
	 * @return array
	 */
	abstract protected function get_item_properties(): array;

	/**
	 * Retrieve item metaata. E.g:
	 *
	 * @return array
	 */
	abstract protected function get_item_metadata(): array;

	/**
	 * Display metadata metabox.
	 *
	 * @return void
	 */
	final public function display(): void {
		$metabox_title = static::TITLE;

		$item_properties = $this->get_item_properties();
		$item_metadata   = $this->get_item_metadata();
		$item_vars       = [ 'Properties' => $item_properties, 'Metadata' => $item_metadata ];

		require static::METABOX_FILE;
	}
}