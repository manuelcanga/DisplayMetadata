<?php
declare(strict_types=1);

namespace Trasweb;

/**
 * Class responsible for parsing views and injecting variables for rendering.
 *
 * This class allows the dynamic inclusion of view files (like templates) and passing
 * variables to them for rendering, similar to a templating engine.
 */
class Parser
{
    /**
     * @var string $view_path Path to the directory containing view files.
     */
    private string $view_path;

    /**
     * @var string $assets_path Path to the directory containing asset files (CSS, JS, etc.).
     */
    private string $assets_path;

    /**
     * @var string $output Holds the output content after a view is parsed.
     */
    private string $output = '';

    /**
     * Constructor to initialize the Parser with the paths for views and assets.
     *
     * @param string $view_path Path to the view templates.
     * @param string $assets_path Path to the asset files.
     */
    public function __construct(string $view_path, string $assets_path)
    {
        $this->view_path = $view_path;
        $this->assets_path = $assets_path;
    }

    /**
     * Parses a view file and returns the rendered content.
     *
     * This method locates the specified view file, injects the provided variables, and captures
     * the output as a string for later use. The view file is expected to be a PHP file.
     *
     * @param string $view_name Name of the view file (without `.php` extension).
     * @param array<string, string> $vars Associative array of variables to pass to the view.
     *
     * @return string Rendered view content as a string.
     */
    public function parse_view(string $view_name, array $vars = []): string
    {
        // Ensure the view name does not contain the ".php" extension.
        $view_name = str_replace('.php', '', $view_name);
        // Construct the full path to the view file.
        $view = $this->view_path . "/{$view_name}.php";

        // Add helper variables to the context.
        $vars['composeAssetPath'] = $this->composeAssetPath(...);  // For generating asset paths.
        $vars['view_path'] = $this->view_path;

        // Start output buffering to capture the included view output.
        ob_start();
        (static function (string $view, array $vars): void {
            // Extract variables to be available within the view file.
            extract($vars, EXTR_SKIP);
            // Include the view file.
            include $view;
        })($view, $vars);

        // Capture and return the buffered output.
        return $this->output = ob_get_clean();
    }

    /**
     * Composes the full path to an asset file based on its subpath.
     *
     * @param string $asset_subpath Optional subpath to the asset file.
     *
     * @return string Full URL or path to the asset file.
     */
    private function composeAssetPath(string $asset_subpath = ''): string
    {
        return $this->assets_path . $asset_subpath;
    }

    /**
     * Retrieves the previously parsed output content.
     *
     * This method returns the result of the most recent view parsing.
     *
     * @return string Parsed content from the view.
     */
    public function getOutput(): string
    {
        return $this->output;
    }
}