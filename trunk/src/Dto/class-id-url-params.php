<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Dto;

/**
 * Data Transfer Object (DTO) to manage ID URL parameters.
 *
 * This class is responsible for validating and sanitizing URL parameters that represent
 * various entity IDs (e.g., post, term, user, comment). It ensures that all ID parameters
 * are numeric and provides methods for retrieving specific IDs based on keys.
 *
 * @psalm-type IdParamsArray = array<string, int>
 * @psalm-suppress MissingConstructor
 */
class Id_Url_Params
{
    /**
     * @var IdParamsArray $id_params
     */
    private array $id_params;

    /**
     * Constructor that initializes the ID parameters after validation.
     *
     * It takes an array of URL parameters, validates that they are numeric, and then
     * stores them as a sanitized array of IDs.
     *
     * @param array<string, mixed> $params An array of URL parameters.
     */
    public function __construct(array $params)
    {
        $this->id_params = $this->check_params($params);
    }

    /**
     * Validate and sanitize URL parameters to ensure they are numeric IDs.
     *
     * This method checks each parameter to confirm that it is a valid numeric ID,
     * returning only those parameters that can be cast to integers. Non-numeric
     * values are filtered out.
     *
     * @param array<string, mixed> $url_params An array of URL parameters to be checked.
     *
     * @return IdParamsArray The filtered array of numeric ID parameters.
     */
    private function check_params(array $url_params): array
    {
        /**
         * Callback function to ensure the item ID is an integer.
         * @psalm-param mixed $item_id
         * @psalm-return int
         */
        $screen_var_checker = fn($item_id): int => (int)($item_id ?? 0);

        // Map through the URL params and filter only valid numeric IDs
        return array_filter(array_map($screen_var_checker, $url_params));
    }

    /**
     * Static factory method to create an instance using global GET variables.
     *
     * This method is intended for constructing the object based on global URL parameters,
     * such as those present in a GET request. Additionally, if it's a profile page, it adds
     * the current user's ID to the params array.
     *
     * @return static An instance of Id_Url_Params with parameters from global variables.
     */
    public static function create_from_globals(): static
    {
        // Fetch URL parameters from the global $_GET array
        $params = $_GET ?? [];

        // If on the profile page, add the current user ID to the parameters
        if (defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE) {
            $params['user_id'] = get_current_user_id();
        }

        return new static($params);
    }

    /**
     * Retrieve the value of a specific ID parameter by key.
     *
     * If the specified key does not exist in the parameters, a default value is returned.
     * This ensures that the method returns an integer in all cases.
     *
     * @param string $item_id_key The key representing the ID to retrieve.
     * @param int $default The default value to return if the key is not found (default is 0).
     *
     * @return int The value of the ID parameter or the default value.
     */
    public function get(string $item_id_key, int $default = 0): int
    {
        // Return the ID associated with the key or the default if not found
        return (int)($this->id_params[$item_id_key] ?? $default);
    }
}