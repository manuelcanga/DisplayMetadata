<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Model;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox_Model;

/**
 * Class Custom_Model
 */
class Custom_Model extends Metabox_Model
{
    protected const TITLE = 'Custom information';
    /**
     * @var string Field name where meta is saved for item_id
     */
    protected const FIELD_META_ID = 'custom_id';
    protected array $item_properties = [];
    protected string $table_name = '';

    /**
     * Set a custom properties
     *
     * @param array $new_properties
     *
     * @return void
     */
    public function set_item_properties(array $new_properties) {
        $this->item_properties = $new_properties;
    }
    
    /**
     * Retrieve item properties/fields. E.g: comment_ID, comment_approved, ...
     *
     * @return array
     */
    public function get_item_properties(): array
    {
        return $this->item_properties;
    }

    /**
     * Set a custom table name
     *
     * @param string $new_table_name
     *
     * @return void
     */
    public function set_meta_table_name(string $new_table_name) {
        $this->table_name = $new_table_name;
    }

    /**
     * Retrieve metadata table name for current WordPress
     *
     * @return string table name.
     */
    protected function get_meta_table_name(): string
    {
        return $this->table_name;
    }
}