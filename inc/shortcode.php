<?php
/**
 * Shortcodes
 *
 * @package Farm Factory
 */

/**
 * Fram inline scripts
 *
 * @param number $id Pos id.
 */
function farmfactory_shortcode_inline_scripts( $id ) {
	$inline_scripts  = "\n";
	$inline_scripts .= "\t" . 'const widget' . esc_js( $id ) . ' = new farmFactory.Widget({' . "\n";
	$inline_scripts .= "\t\t" . 'selector: "ff-widget-' . esc_js( $id ) . '",' . "\n";
	$inline_scripts .= "\t\t" . 'farmAddress: "' . get_post_meta( $id, 'farm_address', true ) . '",' . "\n";
	$inline_scripts .= "\t\t" . 'rewardsAddress: "' . get_post_meta( $id, 'reward_address', true ) . '",' . "\n";
	$inline_scripts .= "\t\t" . 'stakingAddress: "' . get_post_meta( $id, 'staking_address', true ) . '",' . "\n";
	$inline_scripts .= "\t\t" . 'apy: "' . get_post_meta( $id, 'farm_apy', true ) . '",' . "\n";
	$inline_scripts .= "\t\t" . 'apyLabel: "' . get_post_meta( $id, 'farm_apy_label', true ) . '",' . "\n";
	$inline_scripts .= "\t\t" . 'rewardsTokenIcon: "' . get_the_post_thumbnail_url( $id, 'medium' ) . '",' . "\n";
	$inline_scripts .= "\t\t" . 'stakingTokenIcon: "' . wp_get_attachment_image_url( get_post_meta( $id, '_farm_thumbnail_id', true ), 'medium' ) . '",' . "\n";
	$inline_scripts .= "\t" . '});' . "\n";
	return $inline_scripts;
}

/**
 * Main Shortcode
 */
function farmfactory_main_shortcode( $atts ) {

	$atts = shortcode_atts( array(
		'id' => null,
	), $atts );

	$id             = $atts['id'];
	$html           = '';
	$farms          = wp_count_posts( 'farmfactory' )->publish;
	$inline_scripts = '';
	$html_before    = '<div class="ff-widgets-container">';
	$html_after     = '</div>';

	if ( null !== $id && get_post( $id ) ) {

		$inline_scripts = farmfactory_shortcode_inline_scripts( $id );

		$html = '<div id="ff-widget-' . esc_attr( $id ) . '"></div>';

	} elseif ( null === $id ) {

		$farm_args  = array(
			'post_type'      => 'farmfactory',
			'posts_per_page' => -1,
		);
		$farm_query = new WP_Query( $farm_args );

		if ( $farm_query->have_posts() ) :
			while ( $farm_query->have_posts() ) :
				$farm_query->the_post();
				$id = get_the_ID();

				$inline_scripts .= farmfactory_shortcode_inline_scripts( $id );

				$html .= '<div id="ff-widget-' . esc_attr( $id ) . '"></div>';

			endwhile;
		endif;

		wp_reset_postdata();

	}

	if ( $farms ) {
		$html = $html_before . $html . $html_after;
		wp_add_inline_script( 'farmfactory-js', $inline_scripts, 'after' );
	}

	return $html;
}
add_shortcode( 'farmfactory', 'farmfactory_main_shortcode' );

/**
 * Timer Shortcode
 */
function farmfactory_timer_shortcode() {
	return '<div id="farmfactory-timer-root"></div>';
}
add_shortcode( 'farmfactoryTimer', 'farmfactory_timer_shortcode' );

/**
 * Price Shortcode
 */
function farmfactory_price_shortcode( $attrs ) {
  $attrs = shortcode_atts( array(
    'address' => null,
    'network' => null
	), $attrs );

  return '<span data-farm-container="price" data-token="' . $attrs['address'] . '" data-network="' . $attrs['network'] .'" data-api-key="YdC2b3OQsjrbBaBrFO62rsI6idlLENgfBp0taq8Dvj7z35k9B3VamcXhIlS6rMFw">...</span>';
}
add_shortcode( 'farmfactoryPrice', 'farmfactory_price_shortcode' );
