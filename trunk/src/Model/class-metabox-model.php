<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Model;

use const Trasweb\Plugins\DisplayMetadata\PLUGIN_NAME;

/**
 * Class Model
 */
abstract class Metabox_Model
{
    protected const TITLE = '';

    /**
     * @var string Field name where meta is saved for item_id
     */
    protected const FIELD_META_ID = '';

    protected int $item_id;

    /**
     * @param int $item_id
     * @return void
     */
    public function __construct(int $item_id = 0)
    {
        $this->item_id = $item_id;
    }

    /**
     * Retrieve item properties/fields. E.g: ID, post_title, ...
     *
     * @return array
     */
    abstract public function get_item_properties(): array;

    /**
     * Retrieve metadata table name for current WordPress
     *
     * @return string table name.
     */
    abstract protected function get_meta_table_name(): string;

    /**
     * Retrieve metadatas from table name using field and current item value.
     *
     * @return array{meta_key: string, meta_value:string}
     */
    public function get_item_metadata(): array
    {
        global $wpdb;

        $table_name = $this->get_meta_table_name();
        $field = static::FIELD_META_ID;
        $value = $this->get_item_id();

        $query = <<<SQL
        SELECT meta_key, meta_value
         FROM {$table_name}
         WHERE {$field} = {$value}
        SQL;

        return $wpdb->get_results($query, ARRAY_A) ?: [];
    }

    public function get_item_id(): int {
        return $this->item_id;
    }

    /**
     * Retrieve titlte for current metabox
     *
     * @return string
     */
    public function get_title(): string
    {
        return __(static::TITLE, PLUGIN_NAME);
    }
}