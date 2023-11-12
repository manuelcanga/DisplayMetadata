<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

use Trasweb\Plugins\DisplayMetadata\Plugin;

use const ARRAY_A;
use const Trasweb\Plugins\DisplayMetadata\PLUGIN_NAME;

/**
 * This class manages `Display Metadata` metaboxes.
 */
abstract class Metabox {
    protected const NEEDED_CAPABILITY = 'display_metadata_metabox';

    protected const TITLE        = '';

	/**
	 * @var string Field name where meta is saved for item_id
	 */
	protected const FIELD_META_ID = '';

    /**
     * @var string $item_id ID from user, post or term.
     */
    protected $item_id;

    /**
     * Metabox constructor
     *
     * @return void
     */
    public function __construct(int $item_id = 0)
    {
        $this->item_id = $item_id;
    }

    private function __clone()
    {
        // not cloning please
    }

    /**
     * Register a metabox in order to display it later.
     *
     * @return void
     */
    abstract public function register(): void;

    /**
     * Check if a metabox can be registered
     *
     * @param string $screen_slug Slug from current admin url. @see \WP_Screen
     *
     * @return boolean
     */
    public function can_be_registered(string $screen_slug): bool
    {
        return current_user_can('administrator') || current_user_can(static::NEEDED_CAPABILITY);
    }

    /**
     * Retrieve item properties/fields. E.g: ID, post_title, ...
     *
     * @return array
     */
    abstract public function get_item_properties(): array;

	/**
	 * Retrieve metadata table name for current WordPress
	 *
	 * @return string table name.
	 */
	abstract protected function get_meta_table_name(): string;

	/**
	 * Retrieve metadatas from table name using field and current item value.
	 *
	 * @return array<meta_key: string, meta_value:string>
	 */
	public function get_item_metadata(): array {
		global $wpdb;

		$table_name = $this->get_meta_table_name();
		$field = static::FIELD_META_ID;
		$value = (int)$this->item_id;

		$query=<<<SQL
SELECT meta_key, meta_value
 FROM {$table_name}
 WHERE {$field} = {$value}
SQL;

		return $wpdb->get_results( $query, ARRAY_A )?: [];
	}

    /**
     * Retrieve titlte for current metabox
     *
     * @return string
     */
    public function get_title(): string {
        return __( static::TITLE, PLUGIN_NAME );
    }

    /**
     * Display metadata metabox.
     *
     * @return void
     */
    public function display( ): void
    {
        do_action('trasweb_metabox_display', $this, 'metabox' );
    }
}