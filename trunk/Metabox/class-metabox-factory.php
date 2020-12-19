<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

/**
 * This class instances typed metabox classes.
 */
class Metabox_Factory {

	private const NONE_ID                         = 0;
	private const DEFAULT_METABOX                 = __NAMESPACE__ . '\None';
	private const METABOX_TYPES_BY_SCREEN_VAR_KEY = [
		'post'    => __NAMESPACE__ . '\Post',
		'tag_ID'  => __NAMESPACE__ . '\Term',
		'user_id' => __NAMESPACE__ . '\User',
	];

	/**
	 * Retrieve an instance according to screen_vars.
	 *
	 * @param array $screen_vars Vars from query string.
	 *
	 * @return Metabox
	 */
	final public static function get_current_metabox( array $screen_vars ): Metabox {
		$screen_vars = array_filter( $screen_vars, 'is_numeric' );

		foreach ( self::METABOX_TYPES_BY_SCREEN_VAR_KEY as $item_id_key => $metabox_type ) {

			if ( empty( $screen_vars[ $item_id_key ] ) || ! is_a( $metabox_type, Metabox::class, $allow_string = true )  ) {
				continue;
			}

			$item_id    = absint( $screen_vars[ $item_id_key ] );

			return $metabox_type::from_item_id( $item_id );
		}

		return ( self::DEFAULT_METABOX )::from_item_id( self::NONE_ID );
	}
}