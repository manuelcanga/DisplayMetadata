<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

use Trasweb\Plugins\DisplayMetadata\Plugin;

/**
 * This class manages `Display Metadata` user metabox.
 */
final class User extends Metabox {

	protected const TITLE = 'User information';

	/**
	 * Register a metabox in order to display it later.
	 *
	 * @return void
	 */
	public function register(): void {
		if ( !$this->can_be_registered() ) {
			return;
		}

		add_action( 'edit_user_profile', [ $this, 'display' ] );
		add_action( 'show_user_profile', [ $this, 'display' ] );
	}

	/**
	 * Check if a metabox can be registered
	 *
	 * @return bool
	 */
	protected function can_be_registered(): bool {
		$current_screen = Plugin::get_current_screen();

		return 'user-edit' === $current_screen->slug || 'profile' === $current_screen->slug;
	}

	/**
	 * Retrieve item properties/fields. E.g: ID, user_login, ...
	 *
	 * @return array
	 */
	protected function get_item_properties(): array {
		return \json_decode( \json_encode( get_user_by('id', $this->item_id ) ), true );
	}

	/**
	 * Retrieve item metaata. E.g: nickname, first_name, last_name, ...
	 *
	 * @return array
	 */
	protected function get_item_metadata(): array {
        return array_map( [ $this, 'shift_metadata' ],get_user_meta( $this->item_id )?: [] );
	}
}