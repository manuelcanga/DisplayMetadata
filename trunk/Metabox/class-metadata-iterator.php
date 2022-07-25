<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox;

use ArrayIterator;
use Trasweb\Plugins\DisplayMetadata\Plugin;

/**
 * Iterator for metadata list.
 *
 * @package Trasweb\Plugins\DisplayMetadata\Metabox
 */
class Metadata_Iterator extends ArrayIterator {

	protected const META_LIST_VIEW = Plugin::VIEWS_PATH . '/meta_list.php';

	private $depth;

	/**
	 * Named constructor.
	 *
	 * @param array   $vars_list List of vars.
	 * @param integer $depth     Current depth for these vars.
	 *
	 * @return static
	 */
	public static function from_vars_list( array $vars_list, int $depth = 1 ): self
	{
		$iterator = new self( $vars_list, ArrayIterator::STD_PROP_LIST );
		$iterator->depth = $depth;

		return $iterator;
	}

	public function key()
	{
		$key = parent::key();
		$value = parent::current();

		// Allow to see the meta keys as separated keys.
		if ( 2 === $this->depth && isset($value[ 'meta_key' ])  ) {
			return $value[ 'meta_key' ];
		}

		return $key;
	}

	/**
	 * Retrieve current value of current meta.
	 *
	 * @return $this|mixed|string
	 */
	public function current()
	{
		$meta_value = maybe_unserialize( parent::current() );

		// Allow to see the meta keys as separated keys.
		if ( 2 === $this->depth && isset( $meta_value[ 'meta_key' ] ) ) {
			$meta_value = maybe_unserialize( $meta_value[ 'meta_value' ] );
		}

		// Sometimes, unserialize returns objects
		if ( \is_object( $meta_value ) ) {
			$meta_value = \json_decode( \json_encode( $meta_value ), true );
		}

		if ( is_array( $meta_value ) ) {
			ksort( $meta_value );

			return Metadata_Iterator::from_vars_list( $meta_value, $this->depth + 1 );
		}

		if ( is_null( $meta_value ) ) {
			return 'NULL';
		}

		return make_clickable( htmlentities( (string) $meta_value ) );
	}

	public function get_attributes(): string
	{
		$attrs[] = ( 1 === $this->get_depth() ) ? 'meta_headers' : 'meta_item';
		$attrs[] = 'depth_' . $this->get_depth();
		$attrs[] = ( is_array( parent::current() ) && empty( parent::current() ) ) ? 'meta_empty_array' : '';

		if ( '' !== parent::current() ) {
			$attrs[] = ( !is_array( parent::current() ) && !is_serialized( parent::current() ) ) ? 'meta_scalar' : 'meta_array';
		}

		return implode( ' ', $attrs );
	}

	/**
	 * Current depth.
	 *
	 * @return integer
	 */
	private function get_depth(): int
	{
		return (int) $this->depth;
	}

	/**
	 * Genererate a view for current metadata list.
	 *
	 * @return string
	 */
	public function __toString()
	{
		ob_start();
		( static function ( Metadata_Iterator $metadata_list ) {
			include static::META_LIST_VIEW;
		} )( $this );

		return \ob_get_clean() ?: '';
	}
}