<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Helper;

use Trasweb\Plugins\DisplayMetadata\Model\Abstract_Model;

use const Trasweb\Plugins\DisplayMetadata\PLUGIN_NAME;

/**
 * Class Metabox_View
 */
class Metabox_View
{
    private string $view_path;

    /**
     * @param string $view_path
     */
    public function __construct(string $view_path)
    {
        $this->view_path = $view_path;
    }

    public function display(Abstract_Model $metabox_model, string $metabox_type = 'metabox')
    {
        $metabox_type = sanitize_key($metabox_type);

        $metabox_title = $metabox_model->get_title();
        $metadata_list = $this->get_metadata_list($metabox_model);

        include $this->view_path . "/{$metabox_type}.php";
    }

    /**
     * Generaate metadata to show.
     *
     * @return Metadata_Iterator
     */
    public function get_metadata_list(Abstract_Model $metabox_model): Metadata_Iterator
    {
        $item_properties = $metabox_model->get_item_properties();
        $item_metadata = $metabox_model->get_item_metadata();

        $item_vars = [
            __('Properties', PLUGIN_NAME) => $item_properties,
            __('Metadata', PLUGIN_NAME) => $item_metadata,
        ];

        return Metadata_Iterator::from_vars_list($item_vars);
    }
}