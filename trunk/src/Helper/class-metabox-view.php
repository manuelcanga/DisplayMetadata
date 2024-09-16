<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Helper;

use Trasweb\Parser;
use Trasweb\Plugins\DisplayMetadata\Iterator\Metadata_Iterator;
use Trasweb\Plugins\DisplayMetadata\Model\Metabox_Model;

use const Trasweb\Plugins\DisplayMetadata\PLUGIN_NAME;

/**
 * Class Metabox_View
 */
class Metabox_View
{
    private Parser $parser;

    /**
     * @param string $view_path
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param Metabox_Model $metabox_model
     *
     * @param string $metabox_type
     * @return void
     */
    public function display(Metabox_Model $metabox_model, string $metabox_type = 'metabox')
    {
        $metabox_type = sanitize_key($metabox_type);

        $metabox_title = $metabox_model->get_title();
        $metadata_list = $this->get_metadata_list($metabox_model);

        $vars = compact('metabox_type', 'metabox_title', 'metadata_list');

        echo $this->parser->parse_view($metabox_type, $vars);
    }

    /**
     * Generaate metadata to show.
     *
     * @return Metadata_Iterator
     */
    public function get_metadata_list(Metabox_Model $metabox_model): Metadata_Iterator
    {
        $item_properties = $metabox_model->get_item_properties();
        $item_metadata = $metabox_model->get_item_metadata();

        $item_vars = [
            __('Properties', PLUGIN_NAME) => $item_properties,
            __('Metadata', PLUGIN_NAME) => $item_metadata,
        ];

        return Metadata_Iterator::from_vars_list($item_vars, $this->parser);
    }
}