<?php
declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Helper;

use Trasweb\Plugins\DisplayMetadata\Model;
use Trasweb\Plugins\DisplayMetadata\Type;
use Trasweb\Plugins\DisplayMetadata\Type\Metabox_Type;

/**
 * This class instances typed metabox classes.
 */
class Metabox_Factory
{
    private const DEFAULT_METABOX = Type\None::class;
    /**
     * array<string,array{type:class-string,model:class-string}>
     */
    private const DEFAULT_METABOX_TYPES = [
        'post' => [
            'type' => Type\Post::class,
            'model' => Model\Post_Model::class,
        ],
        'tag_ID' => [
            'type' => Type\Term::class,
            'model' => Model\Term_Model::class,
        ],
        'user_id' => [
            'type' => Type\User::class,
            'model' => Model\User_Model::class,
        ],
        'c' => [
            'type' => Type\Comment::class,
            'model' => Model\Comment_Model::class,
        ],
    ];
    private array $metabox_types_by_screen_var_key;
    /**
     * @var array<string, int>
     */
    private array $screen_vars;

    /**
     * @param array $screen_vars Vars from query string.
     *
     * @param array $metabox_types_by_screen_var_key
     * @return void
     */
    public function __construct(
        array $screen_vars,
        array $metabox_types_by_screen_var_key = self::DEFAULT_METABOX_TYPES
    ) {
        $this->screen_vars = $this->check_screen_vars($screen_vars);
        $this->metabox_types_by_screen_var_key = $this->check_metabox_types($metabox_types_by_screen_var_key);
    }

    /**
     * Screen vars should be numeric (they, which we use, are ids)
     *
     * @param array<string, mixed> $screen_vars
     *
     * @return array<string, int>
     */
    private function check_screen_vars(array $screen_vars): array
    {
        $screen_var_checker = fn($item_id) => absint($item_id ?? 0);

        return array_filter(array_map($screen_var_checker, $screen_vars));
    }

    /**
     * Metabox types should to be Metabox objects.
     *
     * @param array<string, mixed> $metabox_types
     *
     * @return array<string,array{type: class-string, model:class-string}>
     */
    private function check_metabox_types(array $metabox_types): array
    {
        $type_checker = static fn($metabox_type) => is_a($metabox_type['type'], Type\Metabox_Type::class, allow_string: true);
        $model_checker = static fn($metabox_type) => is_a($metabox_type['model'], Model\Metabox_Model::class, allow_string: true);

        return array_filter(array_filter($metabox_types, $type_checker), $model_checker);
    }

    /**
     * Retrieve an instance according to screen_vars.
     *
     * @param Metabox_View $metabox_View
     * @return Metabox_Type
     */
    final public function get_current_metabox(Metabox_View $metabox_View): Type\Metabox_Type
    {

        $current_metabox = new (self::DEFAULT_METABOX)(new Model\Custom_Model(), $metabox_View);

        foreach ($this->metabox_types_by_screen_var_key as $item_id_key => $metabox) {
            $item_id = (int)( $this->screen_vars[$item_id_key] ?? 0 );

            $metabox_type = $metabox['type'] ?? null;
            $metabox_model = $metabox['model'] ?? null;

            if ($item_id && $metabox_type && $metabox_model ) {
                $metabox_model = new $metabox_model($item_id);
                $current_metabox = new $metabox_type($metabox_model, $metabox_View);
                break;
            }
        }

        return $current_metabox;
    }
}