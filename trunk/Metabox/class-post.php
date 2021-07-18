<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

use Trasweb\Plugins\DisplayMetadata\Plugin;

use const ARRAY_A;

/**
 * This class manages `Display Metadata` post metabox.
 */
final class Post extends Metabox {

    protected const TITLE       = 'Post information';

    protected const HEADER_FILE = Plugin::VIEWS_PATH . '/nothing.php';

    protected const FOOTER_FILE = Plugin::VIEWS_PATH . '/nothing.php';

    /**
     * Register a metabox in order to display it later.
     *
     * @return void
     */
    public function register(): void
    {
        add_meta_box( 'trasweb_metadata_metabox',     // Unique ID
            static::TITLE,                            // Box title
            [ $this, 'display' ],                     // Content callback
            $this->get_accepted_cpt()          // Post type
        );
    }

    /**
     * Check if a metabox can be registered
     *
     * @return boolean
     */
    public function can_be_registered(): bool
    {
        $current_screen = Plugin::get_current_screen();

        return 'post' === $current_screen->slug;
    }

    /**
     * Helepr: Retrieve list of post type with show-ui. This cpt are where the metabox will be displayed.
     *
     * @return array
     */
    protected function get_accepted_cpt(): array
    {
        return array_values( get_post_types( [ 'show_ui' => true ] ) ?: [] );
    }

    /**
     * Retrieve item properties/fields. E.g: ID, post_title, ...
     *
     * @return array
     */
    protected function get_item_properties(): array
    {
        return get_post( $this->item_id, ARRAY_A ) ?: [];
    }

    /**
     * Retrieve item metaata. E.g: _edit_lock
     *
     * @return array
     */
    protected function get_item_metadata(): array
    {
        return array_map( [ $this, 'shift_metadata' ], get_post_meta( $this->item_id ) ?: [] );
    }
}