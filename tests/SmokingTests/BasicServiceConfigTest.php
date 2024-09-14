<?php

declare(strict_types=1);


namespace SmokingTests;

use PHPUnit\Framework\TestCase;
use Trasweb\Autoload;
use Trasweb\Parser;
use Trasweb\Plugins\DisplayMetadata\Display_Metadata as Plugin;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_Factory;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_View;

use const Trasweb\Plugins\DisplayMetadata\Tests\PLUGIN_DIR;

/**
 * Class BasicServiceConfigTest
 */
class BasicServiceConfigTest extends TestCase
{
    private const CONFIG_FILE = PLUGIN_DIR . '/config/services.conf.php';
    private const MINIMAL_KEYS = [
        'plugin',
        'autoload',
        'parser',
        'screen',
        'metabox_view',
        'metabox_factory',
        'metabox_register'
    ];

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
        $services_values = include static::CONFIG_FILE;

        $this->assertSame(static::MINIMAL_KEYS, array_keys($services_values));
    }

    /**
     * Check if instances of some services are rights.
     *
     * @return void
     */
    public function testServicesInstances(): void
    {
        $services_values = include static::CONFIG_FILE;

        $this->assertInstanceOf(Plugin::class, $services_values['plugin'](PLUGIN_DIR, $services_values));
        $this->assertInstanceOf(Autoload::class, $services_values['autoload']('\Trasweb\Plugins\DisplayMetadata', PLUGIN_DIR.'/src'));
        $this->assertInstanceOf(Parser::class, $services_values['parser']());
        $this->assertInstanceOf(Metabox_View::class, $services_values['metabox_view']());
        $this->assertInstanceOf(Metabox_Factory::class, $services_values['metabox_factory']());
    }
}