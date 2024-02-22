<?php
declare(strict_types=1);

namespace Trasweb;

/**
 * Class Parser
 */
class Parser
{
    private string $view_path;
    private string $assets_path;
    private string $output = '';

    /**
     * @param string $view_path
     * @param string $assets_path
     */
    public function __construct(string $view_path, string $assets_path)
    {
        $this->view_path = $view_path;
        $this->assets_path = $assets_path;
    }

    /**
     * Parse view and retrieve the result.
     *
     * @param string $view_name
     * @param array<string, string> $vars
     *
     * @return string
     */
    public function parse_view(string $view_name, array $vars = []): string
    {
        $view_name = str_replace('.php', '', $view_name);
        $view = $this->view_path . "/{$view_name}.php";

        $vars['composeAssetPath'] = $this->composeAssetPath(...);
        $vars['view_path'] = $this->view_path;

        ob_start();
        (static function (string $view, array $vars): void {
            extract($vars, EXTR_SKIP);
            include $view;
        })(
            $view,
            $vars
        );

        return $this->output = ob_get_clean();
    }

    /**
     * @param string $asset_subpath
     *
     * @return string
     */
    private function composeAssetPath(string $asset_subpath = ''): string
    {
        return $this->assets_path . $asset_subpath;
    }

    /**
     * Retrieve parsed content
     *
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }
}