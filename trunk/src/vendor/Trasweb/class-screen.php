<?php

declare(strict_types=1);

namespace Trasweb;

use WP_Screen;

use const ABSPATH;

/**
 * Class Screen
 *
 * Helper class to determine the current screen context in the WordPress admin area.
 * It identifies the type of page being viewed (e.g., post editor, user profile) and
 * helps decide whether to display certain elements like metaboxes.
 */
class Screen
{
    /**
     * @var string $current_url The current URL, usually from $_SERVER['REQUEST_URI'].
     */
    private string $current_url;

    /**
     * Screen constructor.
     *
     * This method optionally includes WordPress admin files necessary for WP_Screen functionality
     * and sets the current URL.
     *
     * @param string|null $request_uri Optional URI to set for the current request. Defaults to $_SERVER['REQUEST_URI'].
     * @param bool $should_wp_screen_files_be_included Whether to include WP files for screen detection.
     *
     * @return void
     */
    public function __construct(?string $request_uri = null, bool $should_wp_screen_files_be_included = true)
    {
        if ($should_wp_screen_files_be_included) {
            // Include WordPress admin files for WP_Screen functionality.
            include_once ABSPATH . 'wp-admin/includes/post.php';
            include_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
        }

        // Set current URL or fallback to root if not available.
        $this->current_url = $request_uri ?? $_SERVER['REQUEST_URI'] ?? '/';
    }

    /**
     * Get the current screen type based on the current WordPress context.
     *
     * This method returns the slug of the current screen (e.g., "edit-post", "profile").
     *
     * @return string The slug representing the current screen type.
     */
    public function get_current_screen_type(): string
    {
        return $this->get_current()->slug ?? '';
    }

    /**
     * Retrieve the current WP_Screen object for the current admin page.
     *
     * This method constructs and returns a `WP_Screen` object based on the current page context.
     *
     * @return WP_Screen The current WP_Screen object.
     */
    public function get_current(): WP_Screen
    {
        // Get the current page identifier (e.g., "post.php", "user-edit.php").
        $pagenow = $this->get_page_now();

        // Retrieve the WP_Screen object for the current page.
        $screen = WP_Screen::get($pagenow);

        // Set screen properties based on the page context.
        $screen->slug = $screen->base ?: '';
        $screen->base = strtok($screen->slug, '-') ?: '';
        $screen->place = strtok('') ?: 'main';  // This represents the context (e.g., "main" for top-level pages).

        return $screen;
    }

    /**
     * Helper method to determine the current admin page.
     *
     * This method extracts the current page name from the URL (e.g., "post.php" or "user-edit.php").
     *
     * @return string The base name of the current page.
     */
    private function get_page_now(): string
    {
        // Global variable set by WordPress to identify the current admin page.
        global $pagenow;

        // If $pagenow is not set, determine it from the current URL.
        if (!$pagenow) {
            unset($pagenow);

            // Parse the path from the current URL.
            $current_base_url = parse_url($this->current_url, PHP_URL_PATH) ?: '';

            // Extract the final part of the path (e.g., "/wp-admin/post.php").
            $pagenow = strrchr($current_base_url, '/') ?: $current_base_url;
        }

        // Return just the base name of the page (e.g., "post.php").
        return basename($pagenow, '.php');
    }
}