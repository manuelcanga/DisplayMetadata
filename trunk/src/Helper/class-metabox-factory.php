<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Helper;

use Trasweb\Plugins\DisplayMetadata\Dto\Id_Url_Params;
use Trasweb\Plugins\DisplayMetadata\Iterator\Metabox_Types_Iterator;
use Trasweb\Plugins\DisplayMetadata\Model;
use Trasweb\Plugins\DisplayMetadata\Type;
use Trasweb\Plugins\DisplayMetadata\Type\Metabox_Type;

/**
 * Factory class to instantiate the appropriate metabox class based on the current screen variables.
 *
 * This factory class is responsible for determining the correct type of metabox to be displayed,
 * using the provided metabox types iterator and URL parameters. It delegates the instantiation
 * of the appropriate metabox type based on the given context.
 *
 * @psalm-type MetaboxTypeArray = array{type: class-string<Metabox_Type>, model: class-string<Model\Custom_Model>}
 */
class Metabox_Factory
{
    /**
     * @var Metabox_Types_Iterator $metabox_types Iterator over metabox types.
     */
    private Metabox_Types_Iterator $metabox_types;

    /**
     * @var Id_Url_Params $id_params Contains the URL parameters used to identify the entity IDs.
     */
    private Id_Url_Params $id_params;

    /**
     * Constructor to initialize the factory with the ID parameters and metabox types iterator.
     *
     * @param Id_Url_Params $id_params The URL parameters containing IDs.
     * @param Metabox_Types_Iterator $metabox_types The iterator for the different metabox types.
     */
    public function __construct(Id_Url_Params $id_params, Metabox_Types_Iterator $metabox_types)
    {
        $this->id_params = $id_params;
        $this->metabox_types = $metabox_types;
    }

    /**
     * Instantiates and returns the appropriate metabox type for the current screen context.
     *
     * This method checks the screen variables to determine which metabox type should be instantiated
     * based on the current URL parameters. It iterates over the available metabox types and, if a
     * match is found, creates the metabox with the corresponding model.
     *
     * @param Metabox_View $metabox_View The view object responsible for rendering the metabox.
     *
     * @return Metabox_Type The instantiated metabox object corresponding to the current context.
     */
    final public function instance_current_metabox(Metabox_View $metabox_View): Type\Metabox_Type
    {
        // Retrieve the default metabox type
        $default_metabox = $this->metabox_types->get_default_metabox();
        $current_metabox = new $default_metabox(new Model\Custom_Model(), $metabox_View);

        /**
         * Iterate over the metabox types and match the current ID from URL parameters.
         *
         * @psalm-var MetaboxTypeArray $metabox_data
         */
        foreach ($this->metabox_types as $item_id_key => $metabox_data) {
            ['type' => $metabox_type, 'model' => $metabox_model] = $metabox_data;
            $item_id = $this->id_params->get($item_id_key);

            // If a valid ID, metabox type, and model are found, instantiate the specific metabox
            if ($item_id && $metabox_type && $metabox_model) {
                $metabox_model = new $metabox_model($item_id);
                $current_metabox = new $metabox_type($metabox_model, $metabox_View);
                break;
            }
        }

        return $current_metabox;
    }
}