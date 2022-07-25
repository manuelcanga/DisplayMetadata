<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

use Trasweb\Plugins\DisplayMetadata\Plugin;

use const ARRAY_A;

/**
 * This class manages `Display Metadata` comment metabox.
 */
final class Comment extends Metabox {

    protected const TITLE = 'Comment information';
	/**
	 * @var string Field name where meta is saved for item_id
	 */
	protected const FIELD_META_ID = 'comment_id';

    /**
     * Register a metabox in order to display it later.
     *
     * @return void
     */
    public function register(): void
    {
        add_action( 'add_meta_boxes_comment', [ $this, 'display' ] );
    }

    /**
     * Check if a metabox can be registered
     *
     * @return boolean
     */
    public function can_be_registered(): bool
    {
        $current_screen = Plugin::get_current_screen();

        return 'comment' === $current_screen->slug;
    }

    /**
     * Retrieve item properties/fields. E.g: comment_ID, comment_approved, ...
     *
     * @return array
     */
    protected function get_item_properties(): array
    {
        return get_comment( $this->item_id, ARRAY_A ) ?: [];
    }

	/**
	 * Retrieve metadata table name for current WordPress
	 *
	 * @return string table name.
	 */
	protected function get_meta_table_name(): string {
		global $wpdb;

		return $wpdb->commentmeta;
	}
}