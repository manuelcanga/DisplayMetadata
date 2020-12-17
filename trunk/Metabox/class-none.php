<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

/**
 * This is a NoOp class.
 */
class None extends Metabox {

	/**
	 * Nothing to do.
	 */
	public function register(): void {
		//None
	}

	/**
	 * cannot can se registered
	 *
	 * @return array
	 */
	protected function can_be_registered(): bool {
		return false;
	}

	/**
	 * Retrieve none
	 *
	 * @return array
	 */
	protected function get_item_properties(): array {
		return [];
	}

	/**
	 * Retrieve none
	 *
	 * @return array
	 */
	protected function get_item_metadata(): array {
		return [];
	}
}