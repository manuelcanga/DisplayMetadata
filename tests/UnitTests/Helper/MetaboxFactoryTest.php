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
     * Check when no IDs are found in URLs.
     * This should return the default metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function testEmptyIdParamsWhenInstanceCurrentMetabox(): void
    {
        // Arrange:
        $id_params = new Id_Url_Params([]);
        $default_metabox = Plugin::config('default-metabox');

        // Act:
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert:
        $this->assertInstanceOf($default_metabox, $instanced_metabox);
    }

    /**
     * Check when the ID to search doesn't exist in params.
     * This should return the default metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function testWhenIdParamsIsNotFoundWhenInstanceCurrentMetabox(): void
    {
        // Arrange:
        $id_params = new Id_Url_Params(['this_id_exists' => 4]);
        $default_metabox = Plugin::config('default-metabox');

        // Act:
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert:
        $this->assertInstanceOf($default_metabox, $instanced_metabox);
    }

    /**
     * Check when the ID of a post exists in params.
     * This should return the Post type metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function testWhenPostIdIsFound(): void
    {
        // Arrange:
        $id_params = new Id_Url_Params(['post' => 10]);

        // Act:
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert:
        $this->assertInstanceOf(Post::class, $instanced_metabox);
    }

    /**
     * Check when a dangerous ID of a post exists in params.
     * This should return the Default type metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function testWhenDangerousPostIdIsFound(): void
    {
        // Arrange:
        $id_params = new Id_Url_Params(['post' => '; - Select * FROM wp_users;']);
        $default_metabox = Plugin::config('default-metabox');

        // Act:
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert:
        $this->assertInstanceOf($default_metabox, $instanced_metabox);
    }

    /**
     * Check when the ID of a term exists in params.
     * This should return the Term type metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function testWhenTermIdIsFound(): void
    {
        // Arrange:
        $id_params = new Id_Url_Params(['tag_ID' => 10]);

        // Act:
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert:
        $this->assertInstanceOf(Term::class, $instanced_metabox);
    }

    /**
     * Check when the ID of a user exists in params.
     * This should return the User type metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function testWhenUserIdIsFound(): void
    {
        // Arrange:
        $id_params = new Id_Url_Params(['user_id' => 10]);

        // Act:
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert:
        $this->assertInstanceOf(User::class, $instanced_metabox);
    }

    /**
     * Check when the ID of a comment exists in params.
     * This should return the Comment type metabox.
     *
     * @group unit
     *
     * @return void
     */
    public function testWhenCommentIdIsFound(): void
    {
        // Arrange:
        $id_params = new Id_Url_Params(['c' => 10]);

        // Act:
        $instanced_metabox = $this->instanceMetabox($id_params);

        // Assert:
        $this->assertInstanceOf(Comment::class, $instanced_metabox);
    }

    /**
     * Helper: Instance a Metabox_Factory using $id_params and retrieve the current metabox.
     *
     * @param Id_Url_Params $id_params Values of IDs for the current screen.
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