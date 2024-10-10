<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Helper;

use Trasweb\Parser;
use Trasweb\Plugins\DisplayMetadata\Iterator\Metadata_Iterator;
use Trasweb\Plugins\DisplayMetadata\Model\Metabox_Model;

use const Trasweb\Plugins\DisplayMetadata\PLUGIN_NAME;

/**
 * Class responsible for rendering metabox views.
 *
 * The `Metabox_View` class handles the display of metadata in metaboxes by using
 * a `Parser` to render views. It interacts with `Metabox_Model` to retrieve the
 * necessary data for rendering the metabox in a structured format.
 */
class Metabox_View
{
    /**
     * @var Parser $parser The parser instance used for rendering views.
     */
    private Parser $parser;

    /**
     * Constructor to initialize the view with a parser.
     *
     * @param Parser $parser The parser responsible for rendering views.
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Renders and displays the metabox for the given model.
     *
     * This method fetches the title and metadata from the provided `Metabox_Model`
     * and uses the `Parser` to render the appropriate view for the metabox.
     *
     * @param Metabox_Model $metabox_model The model that provides metadata for the metabox.
     * @param string $metabox_type The type of the metabox view to render (defaults to 'metabox').
     *
     * @return void
     */
    public function display(Metabox_Model $metabox_model, string $metabox_type = 'metabox'): void
    {
        // Sanitize the metabox type to ensure it is safe for output.
        $metabox_type = sanitize_key($metabox_type);

        // Retrieve the title and metadata for the metabox.
        $metabox_title = $metabox_model->get_title();
        $metadata_list = $this->get_metadata_list($metabox_model);

        // Prepare the variables to be passed to the view.
        $vars = compact('metabox_type', 'metabox_title', 'metadata_list');

        echo $this->parser->parse_view($metabox_type, $vars);
    }

    /**
     * Retrieves the metadata to be displayed in the metabox.
     *
     * This method generates a list of metadata properties and values to be shown
     * in the metabox. It organizes the properties and metadata under labels like
     * "Properties" and "Metadata" before passing them to an iterator for display.
     *
     * @param Metabox_Model $metabox_model The model that contains the item properties and metadata.
     *
     * @return Metadata_Iterator The iterator that contains the structured metadata list.
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