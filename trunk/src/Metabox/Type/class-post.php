<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Type;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox;
use Trasweb\Plugins\DisplayMetadata\Metabox\Model;

/**
 * This class manages `Display Metadata` post metabox.
 */
final class Post extends Metabox
{
    /**
     * Metabox constructor
     *
     * @return void
     */
    public function __construct(int $item_id = 0, ?Model $model = null)
    {
        $this->item_id = $item_id;
        $this->model = $model ?? new Model\Post_Model($item_id);
    }

    /**
     * Register a metabox in order to display it later.
     *
     * @return void
     */
    public function register(): void
    {
        add_meta_box(
            'trasweb_metadata_metabox',     // Unique ID
            $this->model->get_title(),                 // Box title
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
        do_action('trasweb_metabox_display', $this->get_model());
    }
}