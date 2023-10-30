<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Type;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox;
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
	 * @var string Field name where meta is saved for item_id
	 */
	protected const FIELD_META_ID = 'post_id';

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
     * @inheritDoc
     */
    public function can_be_registered(string $screen_slug): bool
    {
        if(!parent::can_be_registered($screen_slug)){
            return false;
        }

        return 'post' === $screen_slug;
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
	 * Retrieve metadata table name for current WordPress
	 *
	 * @return string table name.
	 */
	protected function get_meta_table_name(): string {
		global $wpdb;

		return $wpdb->postmeta;
	}
}