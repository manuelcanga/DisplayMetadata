<?php declare( strict_types = 1 );

namespace Trasweb\Plugins\DisplayMetadata\Metabox\Type;

use Trasweb\Plugins\DisplayMetadata\Metabox\Metabox;

use Trasweb\Plugins\DisplayMetadata\Metabox\Model;

/**
 * This class manages `Display Metadata` user metabox.
 */
final class User extends Metabox {
    /**
     * Metabox constructor
     *
     * @return void
     */
    public function __construct(int $item_id = 0, ?Model $model = null)
    {
        $this->item_id = $item_id;
        $this->model = $model ?? new Model\User_Model($item_id);
    }

	/**
	 * Register a metabox in order to display it later.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'edit_user_profile', [ $this, 'display' ] );
		add_action( 'show_user_profile', [ $this, 'display' ] );
	}

    /**
     * @inheritDoc
     */
    public function can_be_registered(string $screen_slug): bool
    {
        if(!parent::can_be_registered($screen_slug)){
            return false;
        }

        return 'user-edit' === $screen_slug || 'profile' === $screen_slug;
    }

    /**
     * Return a model for current Metabox
     *
     * @return Model
     */
    public function get_model(): Model
    {
        return new Model\User_Model($this->item_id);
    }
}