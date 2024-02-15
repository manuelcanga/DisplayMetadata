<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Model;

use function json_decode;
use function json_encode;

/**
 * Class User_Model
 */
class User_Model extends Abstract_Model
{

    protected const TITLE = 'User information';

    /**
     * @var string Field name where meta is saved for item_id
     */
    public const FIELD_META_ID = 'user_id';


    /**
     * Retrieve item properties/fields. E.g: ID, user_login, ...
     *
     * @return array
     */
    public function get_item_properties(): array
    {
        return json_decode(json_encode(get_user_by('id', $this->item_id)), true);
    }

    /**
     * Retrieve metadata table name for current WordPress
     *
     * @return string table name.
     */
    protected function get_meta_table_name(): string
    {
        global $wpdb;

        return $wpdb->usermeta;
    }
}