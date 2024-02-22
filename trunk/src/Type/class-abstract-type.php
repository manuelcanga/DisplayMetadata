<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Type;

use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_View;
use Trasweb\Plugins\DisplayMetadata\Model\Abstract_Model;

/**
 * This class manages `Display Metadata` metaboxes.
 */
abstract class Abstract_Type
{
    protected const NEEDED_CAPABILITY = 'display_metadata_metabox';
    /**
     * @var Abstract_Model Metabox database access.
     */
    protected Abstract_Model $model;
    protected Metabox_View $metabox_view;

    public function __construct(Abstract_Model $metabox_model, Metabox_View $metabox_view)
    {
        $this->model = $metabox_model;
        $this->metabox_view = $metabox_view;
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
    public function display(): void
    {
        $this->metabox_view->display($this->get_model(), 'metabox');
    }

    /**
     * Return a model for current Metabox
     *
     * @return Abstract_Model
     */
    public function get_model(): Abstract_Model
    {
        return $this->model;
    }
}