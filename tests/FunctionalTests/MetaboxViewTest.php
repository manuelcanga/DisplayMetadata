<?php
declare(strict_types=1);

namespace FunctionalTests;

use PHPUnit\Framework\TestCase;
use Trasweb\Parser;
use Trasweb\Plugins\DisplayMetadata\Helper\Metabox_View;
use Trasweb\Plugins\DisplayMetadata\Model\Custom_Model;


/**
 * Class MetaboxViewTests
 *
 * Functional tests for the Metabox_View class to ensure that properties and metadata keys and values
 * are correctly sanitized, protecting against XSS and other malicious inputs.
 */
class MetaboxViewTest extends TestCase
{
    /**
     * Test that property and metadata keys are sanitized correctly to prevent injection of malicious code.
     *
     * @group functional
     *
     * @return void
     */
    public function test_keys_are_sanitized_rightly(): void
    {
        // Arrange: Prepare property and metadata arrays with malicious code in the keys
        $property = ['property <script>alert("key");</script>' => 'Property keys are secure'];
        $metadata = ['meta <?= echo "key" ?>' => 'Metadata keys are secure'];

        // Act: Call the displayMetabox method to process the data
        $display_vars =  $this->displayMetabox($property, $metadata);

        // Assert: Check that the keys are correctly sanitized and escaped
        $this->assertArrayHasKey("property &lt;script&gt;alert(&quot;key&quot;);&lt;/script&gt;", $display_vars['Properties']);
        $this->assertArrayHasKey("meta &lt;?= echo &quot;key&quot; ?&gt;", $display_vars['Metadata']);
    }

    /**
     * Test that nested property and metadata keys are sanitized correctly.
     *
     * @group functional
     *
     * @return void
     */
    public function test_nested_keys_are_sanitized_rightly(): void
    {
        // Arrange: Prepare nested arrays with malicious code in the keys
        $property = [
            'Property' => ['property <script>alert("key");</script>' => 'Property keys are secure' ]
        ];
        $metadata = [
            'Meta' => [ 'meta <?= echo "key" ?>' => 'Metadata keys are secure' ],
        ];

        // Act: Call the displayMetabox method to process the nested data
        $display_vars =  $this->displayMetabox($property, $metadata);

        // Assert: Ensure nested keys are properly sanitized
        $this->assertArrayHasKey("property &lt;script&gt;alert(&quot;key&quot;);&lt;/script&gt;", $display_vars['Properties']['Property']);
        $this->assertArrayHasKey("meta &lt;?= echo &quot;key&quot; ?&gt;", $display_vars['Metadata']['Meta']);
    }

    /**
     * Test that serialized property and metadata keys are sanitized correctly to prevent injection of malicious code.
     *
     * @group functional
     *
     * @return void
     */
    public function test_serialized_keys_are_sanitized_rightly(): void
    {
        // Arrange: Prepare property and metadata arrays with malicious code in the keys
        $property = ['s:39:"property <script>alert("key");</script>";' => 'Property keys are secure'];
        $metadata = ['s:22:"meta <?= echo "key" ?>";' => 'Metadata keys are secure'];

        // Act: Call the displayMetabox method to process the data
        $display_vars =  $this->displayMetabox($property, $metadata);

        // Assert: Check that the keys are correctly sanitized and escaped
        $this->assertArrayHasKey("s:39:&quot;property &lt;script&gt;alert(&quot;key&quot;);&lt;/script&gt;&quot;;", $display_vars['Properties']);
        $this->assertArrayHasKey("s:22:&quot;meta &lt;?= echo &quot;key&quot; ?&gt;&quot;;", $display_vars['Metadata']);
    }

    /**
     * Test that property and metadata values are sanitized correctly to prevent script injections.
     *
     * @group functional
     *
     * @return void
     */
    public function test_values_are_sanitized_rightly(): void
    {
        // Arrange: Prepare property and metadata arrays with malicious code in the values
        $property = ['Property' => 'property <script>alert("value");</script>'];
        $metadata = ['Meta' => 'meta <?= echo "value" ?>'];

        // Act: Call the displayMetabox method to process the values
        $display_vars =  $this->displayMetabox($property, $metadata);

        // Assert: Check that the values are sanitized and escaped properly
        $this->assertSame("property &lt;script&gt;alert(&quot;value&quot;);&lt;/script&gt;", $display_vars['Properties']['Property']);
        $this->assertSame("meta &lt;?= echo &quot;value&quot; ?&gt;", $display_vars['Metadata']['Meta']);
    }

    /**
     * Test that nested property and metadata values are sanitized correctly.
     *
     * @group functional
     *
     * @return void
     */
    public function test_nested_values_are_sanitized_rightly(): void
    {
        // Arrange: Prepare nested arrays with malicious code in the values
        $property = [
            'Property' => ['Nested Property' => 'property <script>alert("value");</script>'],
        ];
        $metadata = [
            'Meta' => ['Nested Meta' => 'meta <?= echo "value" ?>'],
        ];

        // Act: Call the displayMetabox method to process the nested values
        $display_vars = $this->displayMetabox($property, $metadata);

        // Assert: Ensure nested values are properly sanitized and escaped
        $this->assertSame(
            "property &lt;script&gt;alert(&quot;value&quot;);&lt;/script&gt;",
            $display_vars['Properties']['Property']['Nested Property']
        );
        $this->assertSame("meta &lt;?= echo &quot;value&quot; ?&gt;", $display_vars['Metadata']['Meta']['Nested Meta']);
    }

    /**
     * Test that serialized property and metadata values are sanitized correctly to prevent script injections.
     *
     * @group functional
     *
     * @return void
     */
    public function test_serialized_values_are_sanitized_rightly(): void
    {
        // Arrange: Prepare property and metadata arrays with malicious code in the values
        $property = ['Property' => 's:41:"property <script>alert("value");</script>";'];
        $metadata = ['Meta' => 's:24:"meta <?= echo "value" ?>";'];

        // Act: Call the displayMetabox method to process the values
        $display_vars =  $this->displayMetabox($property, $metadata);

        // Assert: Check that the values are sanitized and escaped properly
        $this->assertSame("property &lt;script&gt;alert(&quot;value&quot;);&lt;/script&gt;", $display_vars['Properties']['Property']);
        $this->assertSame("meta &lt;?= echo &quot;value&quot; ?&gt;", $display_vars['Metadata']['Meta']);
    }

    /**
     * Helper method to simulate the display of the metabox by processing property and metadata arrays.
     *
     * @param array $property The properties array with keys and values to be displayed.
     * @param array $metadata The metadata array with keys and values to be displayed.
     *
     * @return array The processed and sanitized output vars from the metabox view.
     */
    private function displayMetabox($property, $metadata): array
    {
        // Create a mocked parser and metabox view
        $parser = $this->mockedParser();
        $metabox_view = new Metabox_View($parser);
        $metabox_model = new Custom_Model(0);

        // Set properties and metadata to the model
        $metabox_model->set_item_properties($property);
        $metabox_model->set_item_metadata($metadata);

        // Display the metabox with the provided data
        $metabox_view->display($metabox_model, 'metabox');

        // Return the parsed and sanitized metadata list
        return $parser->vars['metadata_list']->toArray();
    }

    /**
     * Mocks the Parser class to capture the output of the metabox view without rendering it.
     *
     * @return Parser A mock object of the Parser class.
     */
    private function mockedParser()
    {
        // Return an anonymous class extending Parser, capturing the view name and variables
        return new class() extends Parser {
            public string $view_name;
            public array $vars;

            public function __construct(){}

            public function parse_view(string $view_name, array $vars = []): string{
                // Store view name and variables for later assertion
                $this->view_name = $view_name;
                $this->vars = $vars;

                // Return empty string to simulate view rendering
                return '';
            }
        };
    }
}