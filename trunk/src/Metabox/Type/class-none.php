<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Type;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox;

/**
 * This is a NoOp class.
 */
class None extends Metabox {
    /**
     * Nothing to do.
     */
    public function register(): void
    {
        // None
    }

    /**
     * @inheritDoc
     */
    public function can_be_registered(string $screen_slug): bool
    {
        return false;
    }

    /**
     * Retrieve none
     *
     * @return array
     */
    public function get_item_properties(): array
    {
        return [];
    }

    /**
     * Retrieve none
     *
     * @return array
     */
    public function get_item_metadata(): array
    {
        return [];
    }

	/**
	 * Retrieve none.
	 *
	 * @return string
	 */
	protected function get_meta_table_name(): string {
		return '';
	}
}