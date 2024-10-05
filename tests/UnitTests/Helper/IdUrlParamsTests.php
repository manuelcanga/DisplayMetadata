<?php

declare(strict_types=1);

namespace Trasweb\Plugins\DisplayMetadata\Tests\UnitTests\Helper;

use PHPUnit\Framework\TestCase;
use Trasweb\Plugins\DisplayMetadata\Dto\Id_Url_Params;

final class IdUrlParamsTests extends TestCase
{
    /**
     * Test constructor with valid parameters.
     *
     * @group unit
     */
    public function test_constructor_with_valid_params(): void
    {
        // Arrange:
        $params = [
            'item1' => '123',
            'item2' => '456',
            'item3' => 'abc', // this should be filtered
        ];

        // Act:
        $idUrlParams = new Id_Url_Params($params);

        // Asserts:
        $this->assertEquals(123, $idUrlParams->get('item1'));
        $this->assertEquals(456, $idUrlParams->get('item2'));
        $this->assertEquals(0, $idUrlParams->get('item3')); // Default value
    }

    /**
     * Test constructor with empty parameters.
     *
     * @group unit
     */
    public function test_constructor_with_empty_params(): void
    {
        // Arrange:
        $params = [];

        // Act:
        $idUrlParams = new Id_Url_Params($params);

        // Asserts:
        $this->assertEquals(0, $idUrlParams->get('non_existing_key')); // Default value
    }

    /**
     * Test get method with default value.
     *
     * @group unit
     */
    public function test_get_with_default_value(): void
    {
        // Arrange:
        $params = [
            'item1' => '123',
        ];

        // Act:
        $idUrlParams = new Id_Url_Params($params);

        // Asserts:
        $this->assertEquals(0, $idUrlParams->get('non_existing_key', 0)); // Default value
        $this->assertEquals(10, $idUrlParams->get('non_existing_key', 10)); // Default value
    }

    /**
     * Test create_from_globals method.
     *
     * @group unit
     */
    public function test_create_from_globals(): void
    {
        // Arrange:
        $_GET = [
            'item1' => '789',
            'item2' => 'xyz', // this should be filtered
        ];

        // Act:
        $idUrlParams = Id_Url_Params::create_from_globals();

        // Asserts:
        $this->assertEquals(789, $idUrlParams->get('item1'));
        $this->assertEquals(0, $idUrlParams->get('item2')); // Default value
    }

    /**
     * Test create_from_globals method.
     *
     * @group unit
     */
    public function test_create_from_globals_in_profile_page(): void
    {
        // Arrange:
        global $test_functions_values;

        $_GET = [];
        define('IS_PROFILE_PAGE', true);
        $test_functions_values['get_current_user_id'] = 3234;

        // Act:
        $idUrlParams = Id_Url_Params::create_from_globals();

        // Assets:
        $this->assertEquals(3234, $idUrlParams->get('user_id'));
    }

    /**
     * Test expects only 'valid_id' and 'zero_value' keep theirs values.
     *
     * @group unit
     */
    public function test_check_params_filtering(): void
    {
        // Arrange:
        $params = [
            'valid_id' => '111',
            'invalid_id' => 'xyz',
            'zero_value' => '0',
        ];

        // Act:
        $idUrlParams = new Id_Url_Params($params);

        // Assets:
        $this->assertEquals(111, $idUrlParams->get('valid_id'));
        $this->assertEquals(0, $idUrlParams->get('zero_value'));
        $this->assertEquals(0, $idUrlParams->get('invalid_id')); // Filtered
    }
}