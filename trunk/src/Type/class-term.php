<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Type;

/**
 * This class manages `Display Metadata` term metabox.
 */
final class Term extends Metabox_Type
{
    /**
     * Register a metabox in order to display it later.
     *
     * @return void
     */
    public function register(): void
    {
        $term = $this->get_model()->get_item_properties();

        if (empty($term['term_id'])) {
            return;
        }

        add_action($term['taxonomy'] . '_edit_form',  $this->display(...));
    }

    /**
     * @inheritDoc
     */
    public function can_be_registered(string $screen_slug): bool
    {
        if (!parent::can_be_registered($screen_slug)) {
            return false;
        }

        return 'term' === $screen_slug;
    }
}