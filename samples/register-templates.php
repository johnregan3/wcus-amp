<?php
/**
 * Add custom AMP template files.
 *
 * This example includes a custom template for
 * a "book" CPT.
 *
 * @see register-template.php for a simpler example.
 *
 * Registers templates in a /templates subdirectory.
 *
 * @filter amp_post_template_file
 *
 * @param string $file The file name input.
 * @param string $type The type of template.
 * @param object $post WP Post Object.
 *
 * @return string
 */
function wcus_amp_custom_templates( $file, $type, $post ) {
	$dir = dirname( __FILE__ ) . '/templates/';
	if ( 'single' === $type && ( 'book' === $post->post_type ) ) {
		$file = $dir . 'book.php';
	} elseif ( 'single' === $type ) {
		// Register our single template
		$file = $dir . 'single.php';
	} elseif ( 'style' === $type ) {
		// Register our "stylesheet" PHP file.
		$file = $dir . 'style.php';
	}
	return $file;
}
add_filter( 'amp_post_template_file', 'wcus_amp_custom_templates', 10, 3 );
