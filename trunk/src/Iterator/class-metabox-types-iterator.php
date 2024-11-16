<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Iterator;

use Trasweb\Plugins\DisplayMetadata\Model;
use Trasweb\Plugins\DisplayMetadata\Type;
use Traversable;

/**
 * Class MetaboxTypesIterator
 */
class Metabox_Types_Iterator implements \IteratorAggregate
{
    /**
     * array<string,array{type:class-string,model:class-string}>
     */
    private array $metabox_types;
    private string $default_metabox;

    public function __construct(array $metabox_types, string $default_metabox)
    {
        $this->metabox_types = $metabox_types;
        $this->default_metabox = $default_metabox;
    }

    /**
     * Retrieve FQN of default metabox
     *
     * @return string
     */
    public function get_default_metabox(): string
    {
        return $this->default_metabox;
    }

    /**
     * Loop through all metabox types
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        foreach ($this->metabox_types as $metabox_type_key_id => $metabox) {
            $metabox_type = $metabox['type'] ?? null;
            $metabox_model = $metabox['model'] ?? null;

            if (null !== $metabox_type && null !== $metabox_model) {
                yield $metabox_type_key_id => $metabox;
            }
        }
    }

    /**
     * Metabox types should to be Metabox objects.
     *
     * @param array<string, mixed> $metabox_types
     *
     * @return array<string,array{type: class-string, model:class-string}>
     */
    private function check_metabox_types(array $metabox_types): array
    {
        $type_checker = static fn($metabox_type) => is_a(
            $metabox_type['type'],
            Type\Metabox_Type::class,
            allow_string: true
        );
        $model_checker = static fn($metabox_type) => is_a(
            $metabox_type['model'],
            Model\Metabox_Model::class,
            allow_string: true
        );

        return array_filter(array_filter($metabox_types, $type_checker), $model_checker);
    }
}