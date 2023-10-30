<?php declare(strict_types = 1);

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

/**
 * This class instances typed metabox classes.
 */
class Metabox_Factory {

    private const NONE_ID = 0;

    private const DEFAULT_METABOX = __NAMESPACE__ . '\None';
    private const DEFAULT_METABOX_TYPES = [
        'post'    => __NAMESPACE__ . '\Post',
        'tag_ID'  => __NAMESPACE__ . '\Term',
        'user_id' => __NAMESPACE__ . '\User',
        'c'       => __NAMESPACE__ . '\Comment',
    ];
    private array $metabox_types_by_screen_var_key;
    /**
     * @var array<string, int>
     */
    private array $screen_vars;

    /**
     * @param array $screen_vars Vars from query string.
     *
     * @param array $metabox_types_by_screen_var_key
     * @return void
     */
    public function __construct(
        array $screen_vars,
        array $metabox_types_by_screen_var_key = self::DEFAULT_METABOX_TYPES
    ) {
        $this->screen_vars = $this->check_screen_vars($screen_vars);
        $this->metabox_types_by_screen_var_key = $this->check_metabox_types($metabox_types_by_screen_var_key);
    }

    /**
     * Screen vars should be numeric (they, which we use, are ids)
     *
     * @param array $screen_vars
     *
     * @return array
     */
    private function check_screen_vars(array $screen_vars): array{
        $screen_var_checker = fn($item_id)=> absint($item_id ?? 0);

        return array_filter(array_map($screen_var_checker, $screen_vars));
    }

    /**
     * Metabox types should to be Metabox objects.
     *
     * @param array $metabox_types
     *
     * @return array
     */
    private function check_metabox_types(array $metabox_types): array
    {
        $type_checker = fn($metabox_type) => is_a($metabox_type, Metabox::class, allow_string: true);

        return array_filter($metabox_types, $type_checker);
    }

    /**
     * Retrieve an instance according to screen_vars.
     * @return Metabox
     */
    final public function get_current_metabox(): Metabox {
        foreach ( $this->metabox_types_by_screen_var_key as $item_id_key => $metabox_type ) {
            $item_id = $this->screen_vars[$item_id_key] ?? 0;

            if ( ! $item_id ) {
                continue;
            }

            return $metabox_type::from_item_id($item_id);
        }

        return (self::DEFAULT_METABOX)::from_item_id(self::NONE_ID);
    }
}