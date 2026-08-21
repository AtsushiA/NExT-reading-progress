<?php
/**
 * Helper for turning block attributes into a safe inline `style` attribute value.
 *
 * @package NExT_Reading_Progress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Builds an escaped, CSS-sanitized `style` attribute value from a list of declarations.
 *
 * Declaration values originate from block attributes (colors, gradients, units) that any
 * user with edit_posts capability can set via the block editor or REST API, even without
 * unfiltered_html. esc_attr() alone only protects the HTML attribute boundary — it does not
 * validate the value as CSS, so an unescaped `;` in a value could append extra declarations.
 * Joining the declarations first and running them through safecss_filter_attr() (the same
 * core function used to sanitize style="" for less-privileged users) closes that gap while
 * still allowing gradients and var(--...) custom properties.
 *
 * @param array<string, string|int|float> $declarations Map of CSS property => value. Empty/null
 *                                                       values are skipped.
 * @return string Escaped value ready to be printed inside style="...".
 */
function next_reading_progress_build_style_attr( array $declarations ) {
	$parts = array();

	foreach ( $declarations as $property => $value ) {
		if ( null === $value || '' === $value ) {
			continue;
		}

		$parts[] = sprintf( '%s:%s', $property, $value );
	}

	return esc_attr( safecss_filter_attr( implode( ';', $parts ) ) );
}
