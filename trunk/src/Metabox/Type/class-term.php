<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Type;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox;
use Trasweb\Plugins\DisplayMetadata\Metabox\Model;

/**
 * This class manages `Display Metadata` term metabox.
 */
final class Term extends Metabox
{
    /**
     * Metabox constructor
     *
     * @return void
     */
    public function __construct(int $item_id = 0, ?Model $model = null)
    {
        $this->item_id = $item_id;
        $this->model = $model ?? new Model\Term_Model($item_id);
    }

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

        add_action($term['taxonomy'] . '_edit_form', [$this, 'display']);
    }

    /**
     * Return a model for current Metabox
     *
     * @return Model
     */
    public function get_model(): Model
    {
        return new Model\Term_Model($this->item_id);
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