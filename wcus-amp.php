<?php
/**
 * Plugin Name: WCUS AMP
 * Plugin URI: https://github.com/johnregan3/wcus-amp/
 * Description: A sample AMP Plugin implementation.  This will not function without the WordPress AMP Plugin by Automattic being active.
 * Version: 1.0.0
 * Author: John Regan
 * Author URI: https://profiles.wordpress.org/johnregan3
 * Text Domain: wcus-amp
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * This plugin requires the AMP Plugin by Automattic
 * @link https://wordpress.org/plugins/amp/
 *
 * Code samples are found in the /samples directory.
 * They are not used in this plugin directly.
 *
 * @package WCUS_AMP
 */

/*
 * Launch the plugin.
 *
 * Uses priority 9 because WP_Customize_Widgets::register_settings()
 * happens at after_setup_theme priority 10.
 */
add_action( 'after_setup_theme', 'wcus_amp_init', 9 );

/**
 * Initialize the class.
 *
 * Contains actions and filters for the AMP Templates.
 *
 * @action after_setup_theme
 */
function wcus_amp_init() {
	// Ensure the AMP plugin is being used.
	if ( ! is_plugin_active( 'amp/amp.php' ) ) {
		return;
	}

	// AMP hooks.
	add_action( 'amp_init', 'wcus_amp_register_post_types' );
	add_action( 'pre_amp_render_post', 'wcus_amp_add_custom_actions' );

	// AMP Template hooks.
	add_filter( 'amp_post_template_file', 'wcus_amp_custom_templates', 10, 3 );
	add_filter( 'amp_post_template_data', 'wcus_amp_component_scripts' );

	// Our Custom Template AMP hooks.
	add_action( 'wcus_amp_amp_primary_nav', 'wcus_amp_render_primary_nav' );
	add_action( 'wcus_amp_sidebar', 'wcus_amp_render_share_buttons' );

	// Other filters.
	add_filter( 'the_content', 'wcus_amp_filter_shortcode_podcast' );

	// This fires on 'after_setup_theme' as well.
	wcus_amp_register_nav_menu();

}

/**
 * Register CPTs to be supported by Automattic's AMP Plugin.
 *
 * Be sure to visit Settings > Permalinks and save
 * to ensure the rewrite rules are flushed after adding this.
 *
 * @action amp_init
 */
function wcus_amp_register_post_types() {
	if ( ! defined( 'AMP_QUERY_VAR' ) ) {
		define( 'AMP_QUERY_VAR', apply_filters( 'amp_query_var', 'amp' ) );
	}
	add_post_type_support( 'book', AMP_QUERY_VAR );
}

/**
 * Allow custom actions within our AMP template.
 *
 * @action pre_amp_render_post
 */
function wcus_amp_add_custom_actions() {
	add_filter( 'the_content', 'wcus_amp_add_featured_image' );
}

/**
 * Add custom AMP template files.
 *
 * This example includes a custom template for
 * a "book" CPT that does not exist within this plugin.
 *
 * Registers templates from within a /templates subdirectory.
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
		// Register a "book" CPT template
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

/**
 * Register the component scripts with the AMP Plugin.
 *
 * @action amp_post_template_data
 *
 * @param array $data AMP Data.
 *
 * @return array
 */
function wcus_amp_component_scripts( $data ) {
	$amp_cdn = 'https://cdn.ampproject.org/v0/';
	$data['amp_component_scripts']['amp-sidebar'] = $amp_cdn . 'amp-sidebar-0.1.js';
	$data['amp_component_scripts']['amp-audio'] = $amp_cdn . 'amp-audio-0.1.js';
	$data['amp_component_scripts']['amp-social-share'] = $amp_cdn . 'amp-social-share-0.1.js';
	return $data;
}

/**
 * Add Featured Image Support.
 *
 * @link https://github.com/Automattic/amp-wp#featured-image
 *
 * @param string $content The post content.
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

/**
 * Register an AMP-specific Nav Menu.
 *
 * This example is using 'site_menu_amp'.
 *
 * Navigate to Appearance > Menus to
 * fill this menu with items.
 *
 * @see wcus_amp_render_primary_nav()
 *
 * @action after_setup_theme
 */
function wcus_amp_register_nav_menu() {
	register_nav_menu( 'site_menu_amp', __( 'AMP Site Menu', 'wcus-amp' ) );
}


/**
 * Utility functions below.
 */

/**
 * Render the Primary Nav Menu
 *
 * Uses amp-sidebar to handle the slideout animation.
 *
 * Call this function from your template file.
 *
 * Alternatively, add the following HTML markup immediately after the opening
 * <body> tag in your AMP template file (single.php).
 *
 * The AMP Spec requires that this be a direct child of <body>,
 * and only one of these is allowed on a page.
 *
 * This example uses the site_menu_amp menu we registered in
 * wcus_amp_register_nav_menu().
 *
 * @action wcus_amp_amp_primary_nav
 */
function wcus_amp_render_primary_nav() {
	?>
	<ul>
		<?php echo wp_kses_post( wcus_amp_clean_nav_menu_items( 'site_menu_amp' ) ); ?>
	</ul>
	<button on='tap:site-menu.close' title="<?php esc_attr( 'Close', 'wcus-amp' ); ?>"></button>
	<?php
}

/**
 * Render a menu with clean markup.
 *
 * A custom helper function that strips out unwanted code
 * to create a simple link/text menu items.
 *
 * @param string $location The desired Menu Location.
 *
 * @return string
 */
function wcus_amp_clean_nav_menu_items( $location ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations[ $location ] ) ) {
		return '';
	}
	$menu = wp_get_nav_menu_object( $locations[ $location ] );
	$menu_items = wp_get_nav_menu_items( $menu->term_id );
	if ( empty( $menu_items ) || ! is_array( $menu_items ) ) {
		return '';
	}
	ob_start();
	foreach ( $menu_items as $key => $menu_item ) : ?>
		<li><a href="<?php echo esc_url( $menu_item->url ); ?>"><?php echo esc_html( $menu_item->title ); ?></a></li>
	<?php endforeach;
	return ob_get_clean();
}

/**
 * Render social share buttons.
 *
 * Uses amp-social-share to handle JS functionality.
 *
 * @action wcus_amp_sidebar
 */
function wcus_amp_render_share_buttons() {
	$fb_app_id = '1234567890'
	?>
	<div class="share-buttons">
		<h6><?php esc_html_e( 'share:', 'wcus-amp' ); ?></h6>

		<amp-social-share type="pinterest" width="32" height="32"
		                  data-url="<?php echo esc_url( get_permalink() ); ?>"
		                  data-media="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>"
		                  data-description="<?php the_title(); ?>">
		</amp-social-share>

		<?php if ( ! empty( $fb_app_id ) ) : ?>
			<amp-social-share type="facebook" width="32" height="32"
			                  data-param-app_id="<?php echo esc_attr( $fb_app_id ); ?>"
			                  data-url="<?php echo esc_url( get_permalink() ); ?>"
			                  data-media="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>"
			                  data-description="<?php the_title(); ?>">
			</amp-social-share>
		<?php endif; ?>

		<amp-social-share type="twitter" width="32" height="32"
		                  data-url="<?php echo esc_url( get_permalink() ); ?>"
		                  data-media="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>"
		                  data-description="<?php the_title(); ?>">
		</amp-social-share>

		<amp-social-share type="email" width="32" height="32"
		                  data-subject="<?php the_title(); ?>"
		                  data-body="<?php echo esc_url( get_permalink() ); ?>">
		</amp-social-share>

	</div>
	<?php
}

/**
 * Hijack a hypothetical [podcast] custom shortcode for AMP.
 *
 * Replaces [podcast] output with <amp-audio>.
 *
 * Be sure you've added the amp-audio component script.
 *
 * Note: This will only locate the first occurrence of [podcast], so
 * it would require further expansion for true use.
 *
 * This filter could also be fired on 'pre_amp_render_post'.
 * It is left separate here because this is just example usage.
 *
 * @see wcus_amp_add_custom_actions()
 *
 * @filter the_content
 *
 * @param string $content The Post content.
 *
 * @return string
 */
function wcus_amp_filter_shortcode_podcast( $content ) {
	global $shortcode_tags;
	if ( ! is_amp_endpoint() || empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) ) {
		return $content;
	}

	$regex = get_shortcode_regex( array( 'podcast' ) );
	if ( ! $url = wcus_amp_get_shortcode_attr( $content, $regex, 'mp3' ) ) {
		// Strip out the shortcode to prevent errant output.
		return preg_replace( "/$regex/", '', $content );
	}

	// AMP's amp-audio only supports https:// and //, so ensure we're not using http://.
	$url = str_replace( 'http:', '', $url );
	$url = trim( $url );

	ob_start();
	?>
	<amp-audio width="auto"
	           height="50"
	           src="<?php echo esc_url( $url ); ?>">
		<div fallback>
			<p><?php esc_html_e( 'Your browser doesn\'t support HTML5 audio', 'wcus-amp' ); ?></p>
		</div>
	</amp-audio>
	<?php
	$amp_audio = ob_get_clean();
	$content = preg_replace( "/$regex/", $amp_audio, $content );
	return $content;
}

/**
 * Get a value from a shortcode.
 *
 * Protip: use get_shortcode_regex() to get the proper regex.
 *
 * @param string $content Post content.
 * @param string $regex Regex to compare.
 * @param string $search The Shortcode param to find.
 *
 * @return mixed Param content, else false.
 */
function wcus_amp_get_shortcode_attr( $content, $regex, $search ) {
	// Find the shortcode.
	preg_match( "/$regex/", $content, $shortcode_attrs );

	$search = $search . '=';

	if ( empty( $shortcode_attrs ) ) {
		return false;
	}

	// Find item in attrs array that starts with $search.
	$matches = array_filter( $shortcode_attrs, function( $var ) use ( $search ) {
		return ( 0 === strpos( trim( $var ), $search ) );
	}
	);

	if ( empty( $matches ) ) {
		return false;
	}

	// Get the first occurrence of our string.
	$value_string = array_shift( array_values( $matches ) );

	// Ensure the rest of the match is stripped off (other attrs).
	$value_array = explode( ' ', $value_string );

	// Remove the "search" string.
	$val = str_replace( $search , '', $value_array[1] );

	// Strip out both double and/or single quotes.
	$val = str_replace( '"', '', $val );
	$val = str_replace( "'", '', $val );
	return trim( $val );
}


