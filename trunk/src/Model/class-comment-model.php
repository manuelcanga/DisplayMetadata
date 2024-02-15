<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Model;

/**
 * Class Comment_Model
 */
class Comment_Model extends Abstract_Model
{
    protected const TITLE = 'Comment information';
    /**
     * @var string Field name where meta is saved for item_id
     */
    protected const FIELD_META_ID = 'comment_id';

    /**
     * Retrieve item properties/fields. E.g: comment_ID, comment_approved, ...
     *
     * @return array
     */
    public function get_item_properties(): array
    {
        return get_comment($this->item_id, ARRAY_A) ?: [];
    }

    /**
     * Retrieve metadata table name for current WordPress
     *
     * @return string table name.
     */
    protected function get_meta_table_name(): string
    {
        global $wpdb;

        return $wpdb->commentmeta;
    }
}