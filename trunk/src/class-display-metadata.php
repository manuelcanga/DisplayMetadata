<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata;

use Trasweb\Screen;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_Factory;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_View;
use Trasweb\Plugins\DisplayMetadata\Type\Abstract_Type;

/**
 * Class Plugin. Initialize and configure plugin
 */
final class Display_Metadata
{
    public const PATH = __DIR__;
    public const NAMESPACE = __NAMESPACE__;
    public const VIEWS_PATH = self::PATH . '/../views';
    public const ASSETS_PATH = self::PATH . '/../assets';
    private Screen $screen;
    private Metabox_Factory $metabox_factory;
    private Metabox_View $metabox_view;

    /**
     * Plugin constructor.
     *
     * @param array $screen_vars Params( normally $_GET ) from current screen.
     */
    public function __construct(Metabox_Factory $metabox_factory, Screen $screen, Metabox_View $metabox_view)
    {
        $this->metabox_factory = $metabox_factory;
        $this->screen = $screen;
        $this->metabox_view = $metabox_view;
    }

    /**
     * Plugin Starts: Register metabox for current url.
     *
     * @return void
     */
    public function __invoke(): void
    {
        $metabox = $this->get_current_metabox();
        if ($metabox->can_be_registered($this->get_current_screen_slug())) {
            $metabox->register();
        }
    }

    /**
     * Retrieve object of Metabox factory.
     *
     * @return Abstract_Type
     */
    protected function get_current_metabox(): Abstract_Type
    {
        return $this->metabox_factory->get_current_metabox($this->metabox_view);
    }

    /**
     * Retrieve current screen slug
     *
     * @return string
     */
    protected function get_current_screen_slug(): string
    {
        return $this->screen->get_current_screen_type();
    }
}