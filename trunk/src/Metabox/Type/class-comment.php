<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Type;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox;

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
     * @inheritDoc
     */
    public function can_be_registered(string $screen_slug): bool
    {
        if(!parent::can_be_registered($screen_slug)){
            return false;
        }

        return 'comment' === $screen_slug;
    }

    /**
     * Retrieve item properties/fields. E.g: comment_ID, comment_approved, ...
     *
     * @return array
     */
    public function get_item_properties(): array
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