<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Helper;

use Trasweb\Plugins\DisplayMetadata\Dto\Id_Url_Params;
use Trasweb\Plugins\DisplayMetadata\Iterator\Metabox_Types_Iterator;
use Trasweb\Plugins\DisplayMetadata\Model;
use Trasweb\Plugins\DisplayMetadata\Type;
use Trasweb\Plugins\DisplayMetadata\Type\Metabox_Type;

/**
 * This class instances typed metabox classes.
 */
class Metabox_Factory
{
    private Metabox_Types_Iterator $metabox_types;

    private Id_Url_Params $id_params;

    public function __construct(Id_Url_Params $id_params, Metabox_Types_Iterator $metabox_types)
    {
        $this->id_params = $id_params;
        $this->metabox_types = $metabox_types;
    }

    /**
     * Retrieve an instance according to screen_vars.
     *
     * @param Metabox_View $metabox_View
     * @return Metabox_Type
     */
    final public function instance_current_metabox(Metabox_View $metabox_View): Type\Metabox_Type
    {
        $default_metabox = $this->metabox_types->get_default_metabox();
        $current_metabox = new $default_metabox(new Model\Custom_Model(), $metabox_View);

        foreach ($this->metabox_types as $item_id_key => list('type' => $metabox_type, 'model' => $metabox_model)) {
            $item_id = $this->id_params->get($item_id_key);

            if ($item_id && $metabox_type && $metabox_model) {
                $metabox_model = new $metabox_model($item_id);
                $current_metabox = new $metabox_type($metabox_model, $metabox_View);
                break;
            }
        }

        return $current_metabox;
    }
}