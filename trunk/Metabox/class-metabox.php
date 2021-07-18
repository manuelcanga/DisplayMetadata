<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

use Trasweb\Plugins\DisplayMetadata\Plugin;
use const Trasweb\Plugins\DisplayMetadata\PLUGIN_NAME;

/**
 * This class manages `Display Metadata` metaboxes.
 */
abstract class Metabox {

    protected const METABOX_FILE = Plugin::VIEWS_PATH . '/metabox.php';

    protected const ASSETS_FILE  = Plugin::VIEWS_PATH . '/assets.php';

    protected const HEADER_FILE  = Plugin::VIEWS_PATH . '/header.php';

    protected const TITLE        = '';

    protected const FOOTER_FILE  = Plugin::VIEWS_PATH . '/footer.php';

    /**
     * @var string $item_id ID from user, post or term.
     */
    protected $item_id;

    private function __construct()
    {
        // not direct instance, please
    }

    private function __clone()
    {
        // not cloning please
    }

    /**
     * Named constructor. Create an instance from an item id.
     *
     * @param integer $item_id
     *
     * @return static
     */
    final public static function from_item_id( int $item_id ): self
    {
        $metabox = new static();
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
     * @return boolean
     */
    abstract public function can_be_registered(): bool;

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
     * Shift metadata in order to avoid remove metas with equal key
     *
     * @param mixed $meta
     *
     * @return mixed
     */
    protected function shift_metadata( $meta ) {
        return isset( $meta[1] ) ? $meta : maybe_unserialize( $meta[0] );
    }

    /**
     * Display metadata metabox.
     *
     * @return void
     */
    final public function display(): void
    {
        $metabox_title = __( static::TITLE, PLUGIN_NAME );

        $item_properties = $this->get_item_properties();
        $item_metadata = $this->get_item_metadata();

        $item_vars = [
            __( 'Properties', PLUGIN_NAME ) => $item_properties,
            __( 'Metadata', PLUGIN_NAME )   => $item_metadata,
        ];

        $metadata_list = Metadata_Iterator::from_vars_list( $item_vars );

        include static::METABOX_FILE;
    }
}