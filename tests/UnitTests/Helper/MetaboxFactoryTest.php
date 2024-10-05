<?php
declare(strict_types=1);


namespace Trasweb\Plugins\DisplayMetadata\Tests\UnitTests\Helper;

use PHPUnit\Framework\TestCase;
use Trasweb\Plugins\DisplayMetadata\Dto\Id_Url_Params;
use Trasweb\Plugins\DisplayMetadata\Display_Metadata as Plugin;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_Factory;
use Trasweb\Plugins\DisplayMetadata\Type\Comment;
use Trasweb\Plugins\DisplayMetadata\Type\Metabox_Type;
use Trasweb\Plugins\DisplayMetadata\Type\Post;
use Trasweb\Plugins\DisplayMetadata\Type\Term;
use Trasweb\Plugins\DisplayMetadata\Type\User;

/**
 * Class MetaboxFactoryTest
 */
class MetaboxFactoryTest extends TestCase
{
    /**
     * Check when not ids is found in urls.
     * This should return the default metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function test_empty_id_params_when_instance_current_metabox()
    {
        // Arrange:
        $id_params = new Id_Url_Params([]);
        $default_metabox = Plugin::config('default-metabox');

        //Act
        $instanced_metabox = $this->instanceMetabox($id_params);


        // Assert
        $this->assertInstanceOf($default_metabox, $instanced_metabox);
    }

    /**
     * Check when id to search doesn't exist in params.
     * This should return the default metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function test_when_id_params_is_not_found_when_instance_current_metabox()
    {
        // Arrange:
        $id_params = new Id_Url_Params(['this_id_exists' => 4]);
        $default_metabox = Plugin::config('default-metabox');

        //Act
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert
        $this->assertInstanceOf($default_metabox, $instanced_metabox);
    }

    /**
     * Check when id of a post exists in params.
     * This should return the Post type metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function test_when_post_id_is_found()
    {
        // Arrange:
        $id_params = new Id_Url_Params(['post' => 10]);

        // Act
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert
        $this->assertInstanceOf(Post::class, $instanced_metabox);
    }

    /**
     * Check when dangerous id of a post exists in params.
     * This should return the Default type metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function test_when_dangerous_post_id_is_found()
    {
        // Arrange:
        $id_params = new Id_Url_Params(['post' => '; - Select * FROM wp_users;']);
        $default_metabox = Plugin::config('default-metabox');

        // Act
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert
        $this->assertInstanceOf($default_metabox, $instanced_metabox);
    }

    /**
     * Check when id of a term exists in params.
     * This should return the Term type metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function test_when_term_id_is_found()
    {
        // Arrange:
        $id_params = new Id_Url_Params(['tag_ID' => 10]);

        // Act
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert
        $this->assertInstanceOf(Term::class, $instanced_metabox);
    }

    /**
     * Check when id of a user exists in params.
     * This should return the User type metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function test_when_user_id_is_found()
    {
        // Arrange:
        $id_params = new Id_Url_Params(['user_id' => 10]);

        // Act
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert
        $this->assertInstanceOf(User::class, $instanced_metabox);
    }

    /**
     * Check when id of a comment exists in params.
     * This should return the Comment type metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function test_when_comment_id_is_found()
    {
        // Arrange:
        $id_params = new Id_Url_Params(['c' => 10]);

        // Act
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert
        $this->assertInstanceOf(Comment::class, $instanced_metabox);
    }

    /**
     * Helper: Instance a Metabox_Factory using $id_params and retrieve current metabox
     *
     * @param Id_Url_Params $id_params Values dof ids of current screen.
     *
     * @return Metabox_Type
     */
    private function instanceMetabox(Id_Url_Params $id_params): Metabox_Type
    {
        $metabox_types = Plugin::service('metabox_iterator');
        $metabox_view = Plugin::service('metabox_view');

        $metabox_factory = new Metabox_Factory($id_params, $metabox_types);

        return $metabox_factory->instance_current_metabox($metabox_view);
    }
}