<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Model;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox_Model;

/**
 * Class Post_Model
 */
class Post_Model extends Metabox_Model
{
    protected const TITLE = 'Post information';

    /**
     * @var string Field name where meta is saved for item_id
     */
    protected const FIELD_META_ID = 'post_id';

    /**
     * Retrieve item properties/fields. E.g: ID, post_title, ...
     *
     * @return array
     */
    public function get_item_properties(): array
    {
        return get_post($this->item_id, ARRAY_A) ?: [];
    }


    /**
     * Retrieve metadata table name for current WordPress
     *
     * @return string table name.
     */
    protected function get_meta_table_name(): string
    {
        global $wpdb;

        return $wpdb->postmeta;
    }

}