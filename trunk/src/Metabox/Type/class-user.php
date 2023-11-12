<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Type;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox;

/**
 * This class manages `Display Metadata` user metabox.
 */
final class User extends Metabox {

	protected const TITLE = 'User information';

	/**
	 * @var string Field name where meta is saved for item_id
	 */
    public const FIELD_META_ID = 'user_id';

	/**
	 * Register a metabox in order to display it later.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'edit_user_profile', [ $this, 'display' ] );
		add_action( 'show_user_profile', [ $this, 'display' ] );
	}

    /**
     * @inheritDoc
     */
    public function can_be_registered(string $screen_slug): bool
    {
        if(!parent::can_be_registered($screen_slug)){
            return false;
        }

        return 'user-edit' === $screen_slug || 'profile' === $screen_slug;
    }

	/**
	 * Retrieve item properties/fields. E.g: ID, user_login, ...
	 *
	 * @return array
	 */
	public function get_item_properties(): array {
		return \json_decode( \json_encode( get_user_by('id', $this->item_id ) ), true );
	}

	/**
	 * Retrieve metadata table name for current WordPress
	 *
	 * @return string table name.
	 */
	protected function get_meta_table_name(): string {
		global $wpdb;

		return $wpdb->usermeta;
	}
}