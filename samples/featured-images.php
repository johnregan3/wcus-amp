<?php
/**
 * Example of how to add Featured Image support to an AMP Template.
 *
 * @package WCUS_AMP
 */

/**
 * Allow custom actions within our AMP template.
 */
function wcus_amp_add_custom_actions() {
	add_filter( 'the_content', 'wcus_amp_add_featured_image' );
}
add_action( 'pre_amp_render_post', 'wcus_amp_add_custom_actions' );

/**
 * Add Featured Image Support.
 *
 * @link https://github.com/Automattic/amp-wp#featured-image
 *
 * @param string $content WP Post content.
 *
 * @return string
 */
function wcus_amp_add_featured_image( $content ) {
	if ( has_post_thumbnail() ) {
		// Just add the raw <img /> tag; the WP AMP sanitizer will take care of it later.
		$image = sprintf( '<p class="wcus-amp-featured-image">%s</p>', get_the_post_thumbnail() );
		$content = $image . $content;
	}
	return $content;
}
