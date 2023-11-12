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

    /**
     * @var Model Metabox database access.
     */
    protected Model $model;

    /**
     * @var string $item_id ID from user, post or term.
     */
    protected $item_id;

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
     * Display metadata metabox.
     *
     * @return void
     */
    public function display( ): void
    {
        do_action('trasweb_metabox_display', $this->get_model(), 'metabox' );
    }

    /**
     * Return a model for current Metabox
     *
     * @return Model
     */
    public function get_model(): ?Model
    {
        return $this->model;
    }
}