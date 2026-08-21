<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 *
 * @package NExT_Reading_Progress
 */

$use_custom_width  = ! empty( $attributes['useCustomWidth'] );
$custom_width      = $attributes['customWidth'] ?? 100;
$custom_width_unit = $attributes['customWidthUnit'] ?? '%';
$bar_height        = $attributes['barHeight'] ?? 6;
$bar_height_unit   = $attributes['barHeightUnit'] ?? 'px';
$fill_color        = $attributes['fillColor'] ?? '';
$fill_gradient     = $attributes['fillGradient'] ?? '';
$track_color       = $attributes['trackColor'] ?? '#e0e0e0';
$z_index           = isset( $attributes['zIndex'] ) ? (int) $attributes['zIndex'] : null;

// Registers the shared global state. Real percentage is only knowable client-side
// (scroll position isn't available at PHP render time), so 0 is the correct,
// consistent starting value for both server markup and client hydration.
//
// percentageRounded/percentageCss are derived values on the client (see view.js
// getters), but data-wp-bind/data-wp-style directives are also evaluated here on
// the server to produce the initial markup, and that evaluation only sees plain
// state registered via wp_interactivity_state() — it cannot invoke a JS getter.
// Without a matching PHP-side entry the directive resolves to null and the
// processor strips the attribute/style property entirely. Registering the same
// initial values here keeps SSR output consistent; the client-side getters take
// over once the store hydrates.
wp_interactivity_state(
	'next-reading-progress',
	array(
		'percentage'        => 0,
		'percentageRounded' => 0,
		'percentageCss'     => '0%',
	)
);

// See includes/style-builder.php for why declarations are sanitized with
// safecss_filter_attr() rather than esc_attr() alone.
$track_style_attr = next_reading_progress_build_style_attr(
	array(
		'width'            => $use_custom_width ? $custom_width . $custom_width_unit : null,
		'height'           => $bar_height . $bar_height_unit,
		'background-color' => $track_color,
		'z-index'          => null !== $z_index ? (string) $z_index : null,
	)
);

$fill_style_attr = next_reading_progress_build_style_attr(
	array(
		'height'           => '100%',
		'width'            => '0%',
		'background-image' => $fill_gradient ? $fill_gradient : null,
		'background-color' => ! $fill_gradient && $fill_color ? $fill_color : null,
	)
);

$wrapper_attributes = get_block_wrapper_attributes();
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from get_block_wrapper_attributes(). ?>>
	<div
		class="next-reading-progress"
		style="<?php echo $track_style_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already esc_attr()'d above. ?>"
		role="progressbar"
		aria-valuemin="0"
		aria-valuemax="100"
		aria-valuenow="0"
		aria-label="<?php esc_attr_e( 'Reading progress', 'next-reading-progress' ); ?>"
		data-wp-interactive="next-reading-progress"
		data-wp-init="callbacks.init"
		data-wp-bind--aria-valuenow="state.percentageRounded"
	>
		<div
			class="next-reading-progress__fill"
			style="<?php echo $fill_style_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- already esc_attr()'d above. ?>"
			data-wp-style--width="state.percentageCss"
		></div>
	</div>
</div>
