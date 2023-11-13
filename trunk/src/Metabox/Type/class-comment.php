<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Type;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox_Type;

/**
 * This class manages `Display Metadata` comment metabox.
 */
final class Comment extends Metabox_Type
{
    /**
     * Register a metabox in order to display it later.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('add_meta_boxes_comment', [$this, 'display']);
    }

    /**
     * @inheritDoc
     */
    public function can_be_registered(string $screen_slug): bool
    {
        if (!parent::can_be_registered($screen_slug)) {
            return false;
        }

        return 'comment' === $screen_slug;
    }
}