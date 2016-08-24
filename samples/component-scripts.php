<?php
/**
 * Register the amp-sidebar and amp-accordion component scripts with WP AMP.
 *
 * @package WCUS_AMP
 */

/**
 * Register Component Scripts.
 *
 * @param array $data AMP Data.
 *
 * @return array
 */
function wcus_amp_component_scripts( $data ) {
	$amp_cdn = 'https://cdn.ampproject.org/v0/';
	$data['amp_component_scripts']['amp-sidebar'] = $amp_cdn . 'amp-sidebar-0.1.js';
	$data['amp_component_scripts']['amp-accordion'] = $amp_cdn . 'amp-accordion-0.1.js';
	return $data;
}
add_filter( 'amp_post_template_data', 'wcus_amp_component_scripts' );
