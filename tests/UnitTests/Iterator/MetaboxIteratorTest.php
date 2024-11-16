<?php
declare(strict_types=1);


namespace Trasweb\Plugins\DisplayMetadata\Tests\UnitTests\Iterator;

use PHPUnit\Framework\TestCase;
use Trasweb\Parser;
use Trasweb\Plugins\DisplayMetadata\Iterator\Metadata_Iterator;
use stdClass;

/**
 * Test cases for the Metadata_Iterator class
 *
 * These tests verify the functionality of the Metadata_Iterator class, including:
 * - Data sanitization and security
 * - Key and value handling
 * - Nested data structures
 * - Attribute generation
 * - String representation
 */
class MetaboxIteratorTest extends TestCase
{
    private Parser $parser;

    /**
     * Set up the test environment before each test
     * Creates a mock Parser object
     */
    protected function setUp(): void
    {
        $this->parser = $this->createMock(Parser::class);
    }

    /**
     * Tests that simple keys are handled correctly
     */
    public function testKeyWithSimpleValue(): void
    {
        $iterator = Metadata_Iterator::from_vars_list(['test_key' => 'value'], $this->parser);
        $iterator->rewind();

        $this->assertEquals('test_key', $iterator->key());
    }

    /**
     * Tests proper handling of depth two data structures
     */
    public function testKeyWithDepthTwo(): void
    {
        $data = [
            ['meta_key' => 'custom_key', 'meta_value' => 'value']
        ];
        $iterator = Metadata_Iterator::from_vars_list($data, $this->parser, 2);
        $iterator->rewind();

        $this->assertEquals('custom_key', $iterator->key());
    }

    /**
     * Tests basic XSS prevention in keys
     */
    public function testKeyWithXSSAttempt(): void
    {
        $iterator = Metadata_Iterator::from_vars_list(
            ['<script>alert("xss")</script>' => 'value'],
            $this->parser
        );
        $iterator->rewind();

        $this->assertEquals('&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', $iterator->key());
    }

    /**
     * Tests handling of malicious HTML in keys
     */
    public function testKeyWithMaliciousHTML(): void
    {
        $maliciousKeys = [
            '<img src="x" onerror="alert(1)">' => 'value1',
            '<a href="javascript:alert(1)">' => 'value2',
            '"><script>alert(document.cookie)</script>' => 'value3',
            'onmouseover="alert(1)"' => 'value4'
        ];

        foreach ($maliciousKeys as $key => $value) {
            $iterator = Metadata_Iterator::from_vars_list([$key => $value], $this->parser);
            $iterator->rewind();

            // Verify that the output is properly escaped
            $this->assertStringNotContainsString('<script>', $iterator->key());
            $this->assertStringNotContainsString('"javascript:', $iterator->key());
            $this->assertStringNotContainsString('onerror="', $iterator->key());
            $this->assertStringNotContainsString('onmouseover="', $iterator->key());
        }
    }

    /**
     * Tests sanitization of malicious content in values
     */
    public function testCurrentWithMaliciousContent(): void
    {
        $maliciousValues = [
            '<script>alert("xss")</script>',
            '<img src="x" onerror="alert(1)">',
            '<style>@import "data:text/plain,alert(1)";</style>',
            '<SCRIPT/XSS SRC="http://ha.ckers.org/xss.js"></SCRIPT>',
            '<META HTTP-EQUIV="refresh" CONTENT="0;url=data:text/html;base64,PHNjcmlwdD5hbGVydCgxKTwvc2NyaXB0Pg==">'
        ];

        foreach ($maliciousValues as $value) {
            $iterator = Metadata_Iterator::from_vars_list(['key' => $value], $this->parser);
            $iterator->rewind();

            $output = $iterator->current();

            // Verify that dangerous content is escaped
            $this->assertStringNotContainsString('<script>', $output);
            $this->assertStringContainsString('&lt;', $output);
            $this->assertStringContainsString('&gt;', $output);
        }
    }

    /**
     * Tests handling of SQL injection attempts in values
     */
    public function testCurrentWithSQLInjectionAttempts(): void
    {
        $sqlInjections = [
            "'; DROP TABLE users; --",
            "1; SELECT * FROM users WHERE 1=1",
            "' OR '1'='1",
            "); DELETE FROM users; --"
        ];

        foreach ($sqlInjections as $injection) {
            $iterator = Metadata_Iterator::from_vars_list(['key' => $injection], $this->parser);
            $iterator->rewind();

            $output = $iterator->current();

            // Verify that SQL injection attempts are treated as regular strings
            $this->assertEquals(esc_html($injection), $output);
        }
    }

    /**
     * Tests that scalar values are handled correctly
     */
    public function testCurrentWithScalarValue(): void
    {
        $iterator = Metadata_Iterator::from_vars_list(['key' => 'test_value'], $this->parser);
        $iterator->rewind();

        $this->assertEquals('test_value', $iterator->current());
    }

    /**
     * Tests proper handling of null values
     */
    public function testCurrentWithNullValue(): void
    {
        $iterator = Metadata_Iterator::from_vars_list(['key' => null], $this->parser);
        $iterator->rewind();

        $this->assertEquals('NULL', $iterator->current());
    }

    /**
     * Tests that array values are converted to iterators
     */
    public function testCurrentWithArrayValue(): void
    {
        $data = ['key' => ['nested' => 'value']];
        $iterator = Metadata_Iterator::from_vars_list($data, $this->parser);
        $iterator->rewind();

        $this->assertInstanceOf(Metadata_Iterator::class, $iterator->current());
    }

    /**
     * Tests conversion of objects to array-based iterators
     */
    public function testCurrentWithObjectValue(): void
    {
        $obj = new stdClass();
        $obj->property = 'value';
        $iterator = Metadata_Iterator::from_vars_list(['key' => $obj], $this->parser);
        $iterator->rewind();

        $this->assertInstanceOf(Metadata_Iterator::class, $iterator->current());
    }

    /**
     * Tests proper handling of serialized data
     */
    public function testCurrentWithSerializedData(): void
    {
        $data = serialize(['nested' => 'value']);
        $iterator = Metadata_Iterator::from_vars_list(['key' => $data], $this->parser);
        $iterator->rewind();

        $this->assertInstanceOf(Metadata_Iterator::class, $iterator->current());
    }

    /**
     * Tests proper handling of depth two meta values
     */
    public function testCurrentWithDepthTwoMetaValue(): void
    {
        $data = [
            ['meta_key' => 'key', 'meta_value' => 'depth_two_value']
        ];
        $iterator = Metadata_Iterator::from_vars_list($data, $this->parser, 2);
        $iterator->rewind();

        $this->assertEquals('depth_two_value', $iterator->current());
    }

    /**
     * Tests generation of attributes for depth one elements
     */
    public function testGetAttributesDepthOne(): void
    {
        $iterator = Metadata_Iterator::from_vars_list(['key' => 'value'], $this->parser, 1);
        $iterator->rewind();

        $attributes = $iterator->get_attributes();
        $this->assertStringContainsString('meta_headers', $attributes);
        $this->assertStringContainsString('depth_1', $attributes);
        $this->assertStringContainsString('meta_scalar', $attributes);
    }

    /**
     * Tests attribute generation for empty arrays
     */
    public function testGetAttributesWithEmptyArray(): void
    {
        $iterator = Metadata_Iterator::from_vars_list(['key' => []], $this->parser);
        $iterator->rewind();

        $attributes = $iterator->get_attributes();
        $this->assertStringContainsString('meta_empty_array', $attributes);
    }

    /**
     * Tests conversion of nested iterators to arrays
     */
    public function testToArrayWithNestedIterator(): void
    {
        $data = [
            'simple' => 'value',
            'nested' => ['sub' => 'nested_value']
        ];
        $iterator = Metadata_Iterator::from_vars_list($data, $this->parser);

        $expected = [
            'simple' => 'value',
            'nested' => ['sub' => 'nested_value']
        ];

        $this->assertEquals($expected, $iterator->toArray());
    }

    /**
     * Tests initialization with custom depth
     */
    public function testFromVarsListWithCustomDepth(): void
    {
        $data = ['key' => 'value'];
        $customDepth = 3;
        $iterator = Metadata_Iterator::from_vars_list($data, $this->parser, $customDepth);

        $this->assertInstanceOf(Metadata_Iterator::class, $iterator);
        $this->assertEquals($data, $iterator->getArrayCopy());

        // Test depth is reflected in attributes
        $iterator->rewind();
        $this->assertStringContainsString('depth_3', $iterator->get_attributes());
    }

    /**
     * Tests handling of potentially dangerous serialized data
     */
    public function testCurrentWithDangerousSerializedData(): void
    {
        // Simulating potentially dangerous serialized data
        $dangerousData = [
            'key1' => 'O:8:"stdClass":1:{s:4:"eval";s:14:"phpinfo();die;";}',
            'key2' => 'a:1:{s:4:"file";s:11:"/etc/passwd";}',
            'key3' => serialize(['dangerous' => '<?php system($_GET["cmd"]); ?>'])
        ];

        foreach ($dangerousData as $key => $value) {
            $iterator = Metadata_Iterator::from_vars_list([$key => $value], $this->parser);
            $iterator->rewind();

            $output = $iterator->current();

            // Verify that dangerous serialized content is properly handled
            $this->assertStringNotContainsString('<?php', (string)$output);
            $this->assertStringNotContainsString('system(', (string)$output);
            $this->assertStringNotContainsString('eval(', (string)$output);
        }
    }
}