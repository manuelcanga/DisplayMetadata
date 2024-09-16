<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Tests\SmokingTests;

use PHPUnit\Framework\TestCase;

use const Trasweb\Plugins\DisplayMetadata\Tests\PLUGIN_DIR;

/**
 * Class BasicConfigTest
 */
class BasicConfigTest extends TestCase
{
    private const CONFIG_FILE = PLUGIN_DIR.'/config/display-metadata.conf.php';
    private const MINIMAL_KEYS = ['plugin', 'views', 'assets', 'default-metabox', 'metabox-types'];

    /**
     * Check if file of basic config exits. Otherwise fail
     *
     * @return void
     */
    public function testFileExists(): void
    {
        $this->assertFileExists(static::CONFIG_FILE);
    }

    /**
     * Check if config has a minimal of basic keys
     *
     * @return void
     */
    public function testMinimalValues(): void
    {
        $config_values = include static::CONFIG_FILE;

        $this->assertSame(static::MINIMAL_KEYS, array_keys($config_values));
    }
}