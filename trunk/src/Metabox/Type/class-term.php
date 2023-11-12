<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Type;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox;

use const ARRAY_A;

/**
 * This class manages `Display Metadata` term metabox.
 */
final class Term extends Metabox {

    protected const TITLE = 'Term information';
	/**
	 * @var string Field name where meta is saved for item_id
	 */
    public const FIELD_META_ID = 'term_id';

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
     * @inheritDoc
     */
    public function can_be_registered(string $screen_slug): bool
    {
        if(!parent::can_be_registered($screen_slug)){
            return false;
        }

        return 'term' === $screen_slug;
    }

    /**
     * Retrieve item properties/fields. E.g: term_id, name, ...
     *
     * @return array
     */
    public function get_item_properties(): array
    {
        return get_term( $this->item_id, '', ARRAY_A ) ?: [];
    }

	/**
	 * Retrieve metadata table name for current WordPress
	 *
	 * @return string table name.
	 */
	protected function get_meta_table_name(): string {
		global $wpdb;

		return $wpdb->termmeta;
	}
}