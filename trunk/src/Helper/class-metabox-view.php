<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Helper;

use Trasweb\Plugins\DisplayMetadata\Model\Abstract_Model;
use Trasweb\Plugins\DisplayMetadata\Plugin;

use const Trasweb\Plugins\DisplayMetadata\PLUGIN_NAME;

/**
 * Class Metabox_View
 */
class Metabox_View
{
    private Abstract_Model $metabox_model;
    private string $view_path;

    /**
     * @param Abstract_Model $metabox_model
     * @return void
     */
    public function __construct(Abstract_Model $metabox_model, string $view_path)
    {
        $this->metabox_model = $metabox_model;
        $this->view_path = $view_path;
    }


    public function display(string $metabox_type = 'metabox')
    {
        $metabox_type = sanitize_key($metabox_type);

        $metabox_title = $this->metabox_model->get_title();
        $metadata_list = $this->get_metadata_list();

        include $this->view_path . "/{$metabox_type}.php";
    }

    /**
     * Generaate metadata to show.
     *
     * @return Metadata_Iterator
     */
    public function get_metadata_list(): Metadata_Iterator
    {
        $item_properties = $this->metabox_model->get_item_properties();
        $item_metadata = $this->metabox_model->get_item_metadata();

        $item_vars = [
            __('Properties', PLUGIN_NAME) => $item_properties,
            __('Metadata', PLUGIN_NAME) => $item_metadata,
        ];

        return Metadata_Iterator::from_vars_list($item_vars);
    }
}