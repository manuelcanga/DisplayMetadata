<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Framework;

/**
 * Class Screen
 */
class Screen
{
    /**
     * @return void
     */
    public function __construct()
    {
        include_once ABSPATH . 'wp-admin/includes/post.php';
        include_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
    }

    /**
     * Retrieve a WP_Screen object based on current context in admin area.
     *
     * @return \WP_Screen
     */
    public function get_current(): \WP_Screen {


        $pagenow = $this->get_page_now();
        $screen = \WP_Screen::get($pagenow);

        $screen->slug = $screen->base ?: '';
        $screen->base = strtok($screen->slug, '-') ?: '';
        $screen->place = strtok('') ?: 'main';

        return $screen;
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
     * Helper: Retrieve page now from request
     *
     * @return string
     */
    private function get_page_now(): string
    {
        global $pagenow;

        if ( ! $pagenow ) {
            unset($pagenow);

            $current_base_url = parse_url($_SERVER[ 'REQUEST_URI' ], PHP_URL_PATH) ?: '';

            $pagenow = strrchr($current_base_url, '/') ?: $current_base_url;
        }

        return basename($pagenow, '.php');
    }
}