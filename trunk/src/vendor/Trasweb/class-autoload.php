<?php
declare(strict_types=1);

namespace Trasweb;

/**
 * Class responsible for autoloading classes in WordPress style (e.g., class-{Class_Name}.php).
 *
 * This autoloader dynamically loads classes when they are needed, following the WordPress
 * naming convention for classes and file names.
 */
final class Autoload
{
    /**
     * @var string $base_namespace The base namespace of the application.
     */
    private string $base_namespace;

    /**
     * @var string $base_dir The base directory where the class files are located.
     */
    private string $base_dir;

    /**
     * Constructor to initialize the autoloader with the base namespace and directory.
     *
     * @param string $base_namespace The base namespace of the application.
     * @param string $base_dir The base directory where class files are stored.
     */
    public function __construct(string $base_namespace, string $base_dir)
    {
        $this->base_namespace = $base_namespace;
        $this->base_dir = $base_dir;
    }

    /**
     * Finds and includes the file for a given class based on its fully qualified name.
     *
     * This method transforms the class namespace into a file path following the WordPress
     * class naming convention and then includes the corresponding PHP file.
     *
     * @param string $class_name The fully qualified name of the class (including namespace).
     *
     * @return void
     */
    final public function find_class(string $class_name): void
    {
        // Remove base namespace and get the class-specific part.
        $class_relative_namespace = explode($this->base_namespace, $class_name)[1] ?? '';

        // If the class name is not part of the base namespace, return early.
        if (!$class_relative_namespace) {
            return;
        }

        // Convert the class namespace to a class name.
        $class_name = $this->class_namespace_to_class_name($class_relative_namespace);

        // Get the filename based on the class name.
        $file_name = $this->class_name_to_file_name($class_name);

        // Build the path from the namespace and the file name.
        $class_path = $this->class_path_from_file($class_relative_namespace, $class_name);

        include $this->base_dir . "{$class_path}{$file_name}.php";
    }

    /**
     * Converts a full namespace to the class name.
     *
     * @example If provided with `\Trasweb\Plugins\Wpo_Checker`, this returns `Wpo_Checker`.
     *
     * @param string $class_fqn The class name including namespace (without base namespace).
     *
     * @return string The extracted class name.
     */
    private function class_namespace_to_class_name(string $class_fqn): string
    {
        // Extract everything after the last '\' character, trimming any excess.
        return trim(strrchr($class_fqn, '\\'), '\\');
    }

    /**
     * Converts a class name to the corresponding file name following WordPress convention.
     *
     * @example For `Wpo_Checker`, this returns `class-wpo-checker.php`.
     *
     * @param string $class_name The class name without the namespace.
     *
     * @return string The corresponding file name in WordPress naming style.
     */
    private function class_name_to_file_name(string $class_name): string
    {
        // Convert CamelCase or snake_case to lowercase with dashes.
        $snake_case_name = strtolower(str_replace('_', '-', $class_name));

        // Return the formatted file name with the `class-` prefix.
        return "class-{$snake_case_name}";
    }

    /**
     * Converts the namespace into a directory path, preserving the structure.
     *
     * @example `\Trasweb\Plugins\Entities\Site` becomes `/Trasweb/Plugins/Entities`.
     *
     * @param string $class_fqn The class name with namespace (without base namespace).
     * @param string $file_name The file name derived from the class name.
     *
     * @return string The directory path corresponding to the namespace.
     */
    private function class_path_from_file(string $class_fqn, string $file_name): string
    {
        // Remove the class name part from the fully qualified name.
        $namespace_path = substr($class_fqn, 0, -strlen($file_name));

        // Replace backslashes with forward slashes to form a directory structure.
        return str_replace('\\', '/', $namespace_path);
    }
}