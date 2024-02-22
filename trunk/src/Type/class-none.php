<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Type;

/**
 * This is a NoOp class.
 */
class None extends Metabox_Type
{
    /**
     * Nothing to do.
     */
    public function register(): void
    {
        // None
    }

    /**
     * @inheritDoc
     */
    public function can_be_registered(string $screen_slug): bool
    {
        return false;
    }

    /**
     * Retrieve none
     *
     * @return array
     */
    public function get_item_properties(): array
    {
        return [];
    }

    /**
     * Retrieve none
     *
     * @return array
     */
    public function get_item_metadata(): array
    {
        return [];
    }

    /**
     * Display metadata metabox.
     *
     * @return void
     */
    public function display(): void
    {
        ;
    }

    /**
     * Retrieve none.
     *
     * @return string
     */
    protected function get_meta_table_name(): string
    {
        return '';
    }
}