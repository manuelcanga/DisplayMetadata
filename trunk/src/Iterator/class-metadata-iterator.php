<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Iterator;

use ArrayIterator;
use Trasweb\Parser;

use function is_object;
use function json_decode;
use function json_encode;

/**
 * Iterator for traversing and rendering metadata lists.
 *
 * This iterator handles different metadata formats and depths, ensuring that meta keys and values
 * are sanitized and displayed securely. It supports both scalar and array types, as well as serialized data.
 *
 * @package Trasweb\Plugins\DisplayMetadata\Helper
 */
class Metadata_Iterator extends ArrayIterator
{
    protected const META_LIST_VIEW = 'meta_list';

    private int $depth;
    private Parser $parser;

    /**
     * Retrieve the sanitized meta key.
     *
     * If the depth is 2, it assumes the current value contains an associative array
     * where 'meta_key' is the desired key. The key is then sanitized for safe output.
     *
     * @return string The sanitized meta key.
     */
    public function key(): string
    {
        $key = parent::key();
        $value = parent::current();

        // Allow to display meta keys as separate entries if depth is 2
        if (2 === $this->depth && isset($value['meta_key'])) {
            $key = $value['meta_key'];
        }

        // Sanitize the key to prevent XSS attacks
        return esc_html((string)$key);
    }

    /**
     * Retrieve the sanitized meta value.
     *
     * Handles unserialized data and objects, converting them to arrays if necessary.
     * Arrays are recursively traversed, while scalar values are sanitized and made clickable.
     *
     * @return \Stringable|string The sanitized meta value, or an instance of Metadata_Iterator if the value is an array.
     */
    public function current(): \Stringable|string
    {
        // Unserialize the current meta value if needed
        $meta_value = maybe_unserialize(parent::current());

        // If depth is 2 and the meta_value is an array, extract the 'meta_value' key
        if (2 === $this->depth && isset($meta_value['meta_key'])) {
            $meta_value = maybe_unserialize($meta_value['meta_value']);
        }

        // Convert objects to arrays for further processing
        if (is_object($meta_value)) {
            $meta_value = json_decode(json_encode($meta_value), true);
        }

        // If the value is an array, recursively iterate through it using Metadata_Iterator
        if (is_array($meta_value)) {
            ksort($meta_value);  // Sort array by keys

            return Metadata_Iterator::from_vars_list($meta_value, $this->parser, $this->depth + 1);
        }

        // If the value is null, return 'NULL'
        if (is_null($meta_value)) {
            return 'NULL';
        }

        // Sanitize and make scalar values clickable
        return make_clickable(esc_html((string)$meta_value));
    }

    /**
     * Named constructor to create an instance from a list of variables.
     *
     * Initializes the iterator with a list of metadata and a parser object, handling
     * recursive traversal through multiple depths.
     *
     * @param array $vars_list List of metadata variables.
     * @param Parser $parser The parser responsible for rendering views.
     * @param integer $depth The current depth level of the iteration (default is 1).
     *
     * @return static The instantiated Metadata_Iterator.
     */
    public static function from_vars_list(array $vars_list, Parser $parser, int $depth = 1): self
    {
        // Initialize the iterator with the given vars_list and set depth and parser
        $iterator = new self($vars_list, ArrayIterator::STD_PROP_LIST);
        $iterator->parser = $parser;
        $iterator->depth = $depth;

        return $iterator;
    }

    /**
     * Retrieve CSS classes or attributes based on the current metadata.
     *
     * Adds different attributes depending on the type of data (e.g., scalar, array, empty array).
     *
     * @return string The concatenated list of attributes.
     */
    public function get_attributes(): string
    {
        // Define CSS attributes based on the current depth and data type
        $attrs[] = (1 === $this->get_depth()) ? 'meta_headers' : 'meta_item';
        $attrs[] = 'depth_' . $this->get_depth();
        $attrs[] = (is_array(parent::current()) && empty(parent::current())) ? 'meta_empty_array' : '';

        // Add 'meta_scalar' for non-array and non-serialized values, otherwise 'meta_array'
        if ('' !== parent::current()) {
            $attrs[] = (!is_array(parent::current()) && !is_serialized(
                    parent::current()
                )) ? 'meta_scalar' : 'meta_array';
        }

        return implode(' ', $attrs);  // Return attributes as a single string
    }

    /**
     * Get the current depth of the metadata iteration.
     *
     * @return integer The depth level.
     */
    private function get_depth(): int
    {
        return (int)$this->depth;
    }

    /**
     * Generate a string representation of the current metadata list view.
     *
     * Uses the parser to generate the HTML view for the metadata list.
     *
     * @return string The rendered metadata list view.
     */
    public function __toString()
    {
        // Parse and render the metadata list view using the parser
        return $this->parser->parse_view(static::META_LIST_VIEW, ['metadata_list' => $this]);
    }

    /**
     * Convert the iterator data into an associative array.
     *
     * Recursively converts nested Metadata_Iterator instances to arrays.
     *
     * @return array The metadata in array format.
     */
    public function toArray(): array
    {
        $array = [];
        foreach($this as $key => $value)
        {
            $array[$key] = $value;
            // If the value is a nested Metadata_Iterator, convert it to an array
            if(is_object($value) && is_a($value, self::class)) {
                $array[$key] = $value->toArray();
            }
        }

        return $array;
    }
}