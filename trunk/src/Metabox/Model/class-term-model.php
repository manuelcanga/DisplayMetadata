<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Model;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox_Model;

/**
 * Class Term_Model
 */
class Term_Model extends Metabox_Model
{
    protected const TITLE = 'Term information';
    /**
     * @var string Field name where meta is saved for item_id
     */
    public const FIELD_META_ID = 'term_id';

    /**
     * Retrieve item properties/fields. E.g: term_id, name, ...
     *
     * @return array
     */
    public function get_item_properties(): array
    {
        return get_term($this->item_id, '', ARRAY_A) ?: [];
    }

    /**
     * Retrieve metadata table name for current WordPress
     *
     * @return string table name.
     */
    protected function get_meta_table_name(): string
    {
        global $wpdb;

        return $wpdb->termmeta;
    }
}