<?php
/**
 * Example Post Template file customization config.
 *
 * @package WCUS_AMP
 */

/**
 * Add custom AMP template files.
 *
 * If you want to customize the appearance of
 * your AMP templates, this is where you begin.
 *
 * Create a directory in your theme called /templates,
 * then add a single.php and style.php file.  Note
 * that the style file is in fact a .php file.
 *
 * Start by copying the contents of single.php and style.php
 * from the templates directory within Automattic's AMP
 * plugin, then start honing the markup and styles.
 *
 * If you're really feeling saucy, you can do the same with
 * header-bar.php.  Just add the this to the conditionals below:
 *
 * elseif ( 'header-bar' === $type ) {
 *   // Register our custom header-bar template.
 *   $file = $dir . 'header-bar.php';
 * }
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
		// Register our single template.
		$file = $dir . 'single.php';
	} elseif ( 'style' === $type ) {
		// Register our "stylesheet" PHP file.
		$file = $dir . 'style.php';
	}
	return $file;
}
add_filter( 'amp_post_template_file', 'wcus_amp_custom_templates', 10, 3 );
