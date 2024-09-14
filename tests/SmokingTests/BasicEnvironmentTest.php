<?php

declare(strict_types=1);


namespace SmokingTests;

use PHPUnit\Framework\TestCase;

/**
 * Class BasicEnvironmentTest
 */
class BasicEnvironmentTest extends TestCase
{
    private const WP_VERSION_FILE = ABSPATH . '/wp-includes/version.php';

    /**
     * Test a minimal PHP version which plugin needs
     *
     * @return void
     */
    public function testValidPHPVersion(): void
    {
        $is_valid_version = version_compare(phpversion(), '8.1.0', '>=');
        $this->assertTrue($is_valid_version);
    }

    /**
     * Test a minimal WordPress version which plugin needs
     *
     * @return void
     */
    public function testWodPressVersion(): void
    {
        $wp_version = '4.9.0';
        if (file_exists(static::WP_VERSION_FILE)) {
            include static::WP_VERSION_FILE;
        }

        $is_valid_wordpress_version = version_compare($wp_version, '4.9.0', '>=');
        $this->assertTrue($is_valid_wordpress_version);
    }
}