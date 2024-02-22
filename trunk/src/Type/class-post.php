<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Type;

/**
 * This class manages `Display Metadata` post metabox.
 */
final class Post extends Abstract_Type
{
    /**
     * Register a metabox in order to display it later.
     *
     * @return void
     */
    public function register(): void
    {
        add_meta_box(
            'trasweb_metadata_metabox',     // Unique ID
            $this->get_model()->get_title(),                 // Box title
            [$this, 'display'],                     // Content callback
            $this->get_accepted_cpt()          // Post type
        );
    }

    /**
     * Helepr: Retrieve list of post type with show-ui. This cpt are where the metabox will be displayed.
     *
     * @return array
     */
    protected function get_accepted_cpt(): array
    {
        return array_values(get_post_types(['show_ui' => true]) ?: []);
    }

    /**
     * @inheritDoc
     */
    public function can_be_registered(string $screen_slug): bool
    {
        if (!parent::can_be_registered($screen_slug)) {
            return false;
        }

        return 'post' === $screen_slug;
    }

    /**
     * Display metadata metabox.
     *
     * @return void
     */
    public function display(): void
    {
        $this->metabox_view->display($this->get_model(), 'simple-metabox');
    }
}