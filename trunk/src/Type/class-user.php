<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Type;

/**
 * This class manages `Display Metadata` user metabox.
 */
final class User extends Metabox_Type
{
    /**
     * Register a metabox in order to display it later.
     *
     * @return void
     */
    public function register(): void
    {
        add_action('edit_user_profile', [$this, 'display']);
        add_action('show_user_profile', [$this, 'display']);
    }

    /**
     * @inheritDoc
     */
    public function can_be_registered(string $screen_slug): bool
    {
        if (!parent::can_be_registered($screen_slug)) {
            return false;
        }

        return 'user-edit' === $screen_slug || 'profile' === $screen_slug;
    }
}