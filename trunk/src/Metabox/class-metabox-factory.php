<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

/**
 * This class instances typed metabox classes.
 */
class Metabox_Factory {

	private const NONE_ID                         = 0;

	private const DEFAULT_METABOX                 = __NAMESPACE__ . '\None';

    private array $metabox_types_by_screen_var_key;

    private const DEFAULT_METABOX_TYPES = [
		'post'    => __NAMESPACE__ . '\Post',
		'tag_ID'  => __NAMESPACE__ . '\Term',
		'user_id' => __NAMESPACE__ . '\User',
		'c'       => __NAMESPACE__ . '\Comment',
	];

    public function __construct(array $metabox_types_by_screen_var_key = self::DEFAULT_METABOX_TYPES)
    {
        $this->metabox_types_by_screen_var_key = $metabox_types_by_screen_var_key;
    }

	/**
	 * Retrieve an instance according to screen_vars.
	 *
	 * @param array $screen_vars Vars from query string.
	 *
	 * @return Metabox
	 */
    final public function get_current_metabox(array $screen_vars): Metabox
	{
		$screen_vars = array_filter( $screen_vars, 'is_numeric' );

        foreach ($this->metabox_types_by_screen_var_key as $item_id_key => $metabox_type) {
			if ( empty( $screen_vars[ $item_id_key ] ) || !is_a( $metabox_type, Metabox::class, $allow_string = true ) ) {
				continue;
			}

			$item_id = absint( $screen_vars[ $item_id_key ] );

			return $metabox_type::from_item_id( $item_id );
		}

		return ( self::DEFAULT_METABOX )::from_item_id( self::NONE_ID );
	}
}