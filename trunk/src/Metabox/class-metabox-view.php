<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

use Trasweb\Plugins\DisplayMetadata\Plugin;

use const Trasweb\Plugins\DisplayMetadata\PLUGIN_NAME;

/**
 * Class Metabox_View
 */
class Metabox_View
{
    public const VIEWS_PATH = Plugin::PATH . '/../views';

    private Model $metabox_model;

    /**
     * @param Model $metabox_model
     * @return void
     */
    public function __construct(Model $metabox_model)
    {
        $this->metabox_model = $metabox_model;
    }


    public function display(string $metabox_type = 'metabox', string $metabox_path = self::VIEWS_PATH)
    {
        $metabox_type = sanitize_key($metabox_type);

        $metabox_title = $this->metabox_model->get_title();
        $metadata_list = $this->get_metadata_list();

        include $metabox_path . "/{$metabox_type}.php";
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