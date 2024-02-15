<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Helper;

use WP_Screen;

/**
 * Class Screen
 */
class Screen
{
    private string $current_url;

    /**
     * @return void
     */
    public function __construct(?string $request_uri = null, bool $should_wp_screen_files_be_included = true)
    {
        if ($should_wp_screen_files_be_included) {
            include_once ABSPATH . 'wp-admin/includes/post.php';
            include_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
        }

        $this->current_url = $request_uri ?? $_SERVER['REQUEST_URI'] ?? '/';
    }

    /**
     * Retrieve current screen type
     *
     * @return string
     */
    public function get_current_screen_type(): string
    {
        return $this->get_current()->slug ?? '';
    }

    /**
     * Retrieve a WP_Screen object based on current context in admin area.
     *
     * @return WP_Screen
     */
    public function get_current(): WP_Screen
    {
        $pagenow = $this->get_page_now();
        $screen = WP_Screen::get($pagenow);

        $screen->slug = $screen->base ?: '';
        $screen->base = strtok($screen->slug, '-') ?: '';
        $screen->place = strtok('') ?: 'main';

        return $screen;
    }

    /**
     * Helper: Retrieve page now from request
     *
     * @return string
     */
    private function get_page_now(): string
    {
        global $pagenow;

        if (!$pagenow) {
            unset($pagenow);

            $current_base_url = parse_url($this->current_url, PHP_URL_PATH) ?: '';

            $pagenow = strrchr($current_base_url, '/') ?: $current_base_url;
        }

        return basename($pagenow, '.php');
    }
}