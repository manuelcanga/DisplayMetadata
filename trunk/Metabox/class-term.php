<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

use Trasweb\Plugins\DisplayMetadata\Plugin;

use const ARRAY_A;

/**
 * This class manages `Display Metadata` term metabox.
 */
final class Term extends Metabox {

    protected const TITLE = 'Term information';

    /**
     * Register a metabox in order to display it later.
     *
     * @return void
     */
    public function register(): void
    {
        $term = $this->get_item_properties();

        if ( empty( $term[ 'term_id' ] ) ) {
            return;
        }

        add_action( $term[ 'taxonomy' ] . '_edit_form', [ $this, 'display' ] );
    }

    /**
     * Check if a metabox can be registered
     *
     * @return boolean
     */
    public function can_be_registered(): bool
    {
        $current_screen = Plugin::get_current_screen();

        return 'term' === $current_screen->slug;
    }

    /**
     * Retrieve item properties/fields. E.g: term_id, name, ...
     *
     * @return array
     */
    protected function get_item_properties(): array
    {
        return get_term( $this->item_id, '', ARRAY_A ) ?: [];
    }

    /**
     * Retrieve item metaata.
     *
     * @return array
     */
    protected function get_item_metadata(): array
    {
        return array_map( [ $this, 'shift_metadata' ],  get_term_meta( $this->item_id ) ?: [] );
    }
}