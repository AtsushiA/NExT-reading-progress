<?php
/**
 * Tests for includes/style-builder.php.
 *
 * @package NExT_Reading_Progress
 */

/**
 * Class StyleBuilderTest
 */
class StyleBuilderTest extends WP_UnitTestCase {

	public function test_builds_basic_declarations() {
		$result = next_reading_progress_build_style_attr(
			array(
				'height'           => '6px',
				'background-color' => '#e0e0e0',
				'z-index'          => '27',
			)
		);

		$this->assertStringContainsString( 'height:6px', $result );
		$this->assertStringContainsString( 'background-color:#e0e0e0', $result );
		$this->assertStringContainsString( 'z-index:27', $result );
	}

	public function test_skips_empty_and_null_values() {
		$result = next_reading_progress_build_style_attr(
			array(
				'width'  => null,
				'height' => '',
				'color'  => '#000',
			)
		);

		$this->assertSame( 'color:#000', $result );
	}

	public function test_allows_css_custom_property_value() {
		// Matches real usage: trackColor bound to a theme.json custom property.
		$result = next_reading_progress_build_style_attr(
			array( 'background-color' => 'var(--unitone--color--background)' )
		);

		$this->assertStringContainsString( 'background-color:var(--unitone--color--background)', $result );
	}

	public function test_allows_linear_gradient() {
		$gradient = 'linear-gradient(135deg,rgb(199,0,60) 0%,rgb(255,0,157) 100%)';

		$result = next_reading_progress_build_style_attr(
			array( 'background-image' => $gradient )
		);

		$this->assertStringContainsString( 'linear-gradient(135deg', $result );
	}

	public function test_strips_javascript_protocol_from_url() {
		$result = next_reading_progress_build_style_attr(
			array( 'background-image' => 'url(javascript:alert(1))' )
		);

		$this->assertStringNotContainsString( 'javascript:', $result );
	}

	public function test_html_special_characters_are_escaped() {
		// A value with an unescaped `;` could otherwise append extra declarations,
		// and a raw `"`/`<` could break out of the style="" attribute entirely.
		$malicious = '#000;"><script>alert(1)</script>';

		$result = next_reading_progress_build_style_attr(
			array( 'background-color' => $malicious )
		);

		$this->assertStringNotContainsString( '<script', $result );
		$this->assertStringNotContainsString( '"', $result );
	}
}
