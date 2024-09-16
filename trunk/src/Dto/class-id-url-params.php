<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Dto;


/**
 * Class IdUrlParams
 */
class Id_Url_Params
{
    /**
     * @var array<string, int> $id_params
     */
    private array $id_params;

    public function __construct(array $params)
    {
        $this->id_params = $this->check_params($params);
    }

    /**
     * Url vars should be numeric (they, which we use, are ids)
     *
     * @param array<string, mixed> $url_params
     *
     * @return array<string, int>
     */
    private function check_params(array $url_params): array
    {
        $screen_var_checker = fn($item_id) => (int)($item_id ?? 0);

        return array_filter(array_map($screen_var_checker, $url_params));
    }

    /**
     * Named method in order to build params from global vars (like GET).
     * )
     * @return static
     */
    public static function create_from_globals(): static
    {
        $params = $_GET ?? [];

        if (defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE) {
            $params['user_id'] = get_current_user_id();
        }

        return new static($params);
    }

    /**
     * Retrieve a value of params according to its key.
     *
     * @param string $item_id_key
     * @param int $default Default value when key is not found.
     *
     * @return int
     */
    public function get(string $item_id_key, int $default = 0): int
    {
        return (int)($this->id_params[$item_id_key] ?? $default);
    }
}