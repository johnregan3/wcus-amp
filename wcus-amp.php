<?php
/**
 * Plugin Name: WordCamp US AMP Demo
 * Plugin URI: https://github.com/johnregan3/wcus-amp/
 * Description: A demonstration of customizing AMP Plugin.  This will not function without the WordPress AMP Plugin by Automattic being active.
 * Version: 1.0.0
 * Author: John Regan
 * Author URI: https://profiles.wordpress.org/johnregan3
 * Text Domain: wcus-amp
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * This plugin demonstrates how to customize the output
 * and appearance of an AMP template using the WordPress
 * AMP Plugin.
 *
 * There are code samples found in the /samples directory.
 * They are not used in the functionality of this plugin.
 *
 * This plugin requires the AMP Plugin by Automattic.
 *
 * @link https://wordpress.org/plugins/amp/
 *
 * @package WCUS_AMP
 */

// Include plugin.php so we have access to is_plugin_active().
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

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
	if ( ! is_plugin_active( 'amp-wp/amp.php' ) ) {
		return;
	}

	// AMP Plugin hooks.
	add_action( 'amp_init', 'wcus_amp_register_post_types' );
	add_action( 'pre_amp_render_post', 'wcus_amp_add_custom_actions' );

	// AMP Plugin Template hooks.
	add_filter( 'amp_post_template_file', 'wcus_amp_custom_templates', 10, 3 );
	add_filter( 'amp_post_template_data', 'wcus_amp_component_scripts' );
	add_filter( 'amp_post_template_data', 'wcus_amp_set_custom_placeholder_image' );
	add_filter( 'amp_post_template_footer', 'wcus_amp_footer' );

	// Custom hooks we've added to our templates.
	add_action( 'wcus_amp_primary_nav', 'wcus_amp_render_primary_nav' );
	add_action( 'wcus_amp_post_footer', 'wcus_amp_render_share_buttons' );
	add_action( 'wcus_amp_after_post', 'wcus_amp_render_ad_slot' );

	/*
	 * This is located within this function
	 * so we can ensure the AMP plugin exists
	 * before we register it.
	 */
	wcus_amp_register_nav_menu();

}

/**
 * Register CPTs to be supported by Automattic's AMP Plugin.
 *
 * Be sure to visit Settings > Permalinks and save
 * to ensure the rewrite rules are flushed after adding this.
 *
 * Note that the AMP_QUERY_VAR will be overridden with
 * the filter we added above.
 *
 * Aren't hooks nice?
 *
 * @see wcus_amp_change_endpoint().
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
 * Add Featured Image Support.
 *
 * @link https://github.com/Automattic/amp-wp#featured-image
 *
 * @see wcus_amp_add_custom_actions
 *
 * @filter wcus_amp_add_featured_image
 *
 * @param string $content The post content.
 *
 * @return string
 */
function wcus_amp_add_featured_image( $content ) {
	if ( has_post_thumbnail() ) {
		// Just add the raw <img /> tag; the WP AMP sanitizer will take care of it later.
		$image = sprintf( '<div class="wcus-amp-featured-image">%s</div>', get_the_post_thumbnail() );
		$content = $image . $content;
	}
	return $content;
}

/**
 * Add custom AMP template files.
 *
 * This example includes a custom template for
 * a "book" CPT that does not exist within this plugin.
 *
 * You will also need to register your CPTs with the plugin.
 *
 * @see samples/register-cpts.php
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
		// Register a "book" CPT template.
		$file = $dir . 'book.php';

	} elseif ( 'header-bar' === $type ) {
		// Register our custom header-bar template.
		$file = $dir . 'header-bar.php';

	} elseif ( 'single' === $type ) {
		// Register our single template.
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
	$data['amp_component_scripts']['amp-ad'] = $amp_cdn . 'amp-ad-0.1.js';
	$data['amp_component_scripts']['amp-sidebar'] = $amp_cdn . 'amp-sidebar-0.1.js';
	$data['amp_component_scripts']['amp-audio'] = $amp_cdn . 'amp-audio-0.1.js';
	$data['amp_component_scripts']['amp-social-share'] = $amp_cdn . 'amp-social-share-0.1.js';
	return $data;
}

/**
 * Register a custom iframe placeholder image.
 *
 * @action amp_post_template_data
 *
 * @param array $data AMP Data.
 *
 * @return array
 */
function wcus_amp_set_custom_placeholder_image( $data ) {
	$data['placeholder_image_url'] = plugin_dir_url( __FILE__ ) . 'templates/img/placeholder.jpg';
	return $data;
}

/**
 * Register an AMP-specific Nav Menu.
 *
 * This example is using 'site_menu_amp'.
 *
 * Navigate to Appearance > Menus to
 * fill the "AMP Site Menu" menu location with items.
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
 * I'm using an action hook to insert this markup, but
 * you can call this function from your template file.
 *
 * You can also insert the enclosed HTML markup
 * immediately after the opening <body> tag in your
 * AMP template file (e.g., single.php).
 *
 * The AMP Spec requires that this be a direct child of <body>,
 * and only one of these is allowed on a page.
 *
 * This example uses the site_menu_amp menu we registered in
 * wcus_amp_register_nav_menu().
 *
 * @action wcus_amp_primary_nav
 */
function wcus_amp_render_primary_nav() {
	// @todo check if nav actually exists && is assigned.
	if ( ! has_nav_menu( 'site_menu_amp' ) ) {
		return false;
	}
	?>
	<amp-sidebar id="site-menu" layout="nodisplay">
		<h2><?php esc_html_e( 'Menu', 'wcus-amp' ); ?>
			<img class="menu-button" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'templates/img/close.svg' ); ?>" alt="<?php esc_html_e( 'Toggle Navigation', 'wcus-amp' ); ?>" on='tap:site-menu.close' aria-label="<?php esc_html_e( 'Toggle Navigation', 'wcus-amp' ); ?>" /></h2>
		<ul>
			<?php echo wp_kses_post( wcus_amp_clean_nav_menu_items( 'site_menu_amp' ) ); ?>
		</ul>

	</amp-sidebar>
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
 * Uses amp-social-share to handle JS functionality. Be
 * sure you have this component script registered in
 * wcus_amp_component_scripts().
 *
 * You'll need to get a Facebook App ID for this to
 * function correctly.  Using a dummy App ID here to
 * show how it works.
 *
 * @see wcus_amp_component_scripts()
 *
 * @action wcus_amp_post_footer
 */
function wcus_amp_render_share_buttons() {
	$fb_app_id = '0000000000'
	?>
	<div class="share-buttons">
		<?php esc_html_e( 'Share this post:', 'wcus-amp' ); ?><br />

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
 * Insert an ad.
 *
 * This is a simple example of inserting
 * an ad into a page.
 *
 * Each ad provider uses their own custom
 * parameters, and these are designated by using
 * the "data-" prefix.
 *
 * Also, if a parameter is called "fooBar,"
 * use 'data-foo-bar' to represent this value.
 *
 * @link https://github.com/ampproject/amphtml/blob/master/extensions/amp-ad/amp-ad.md
 *
 * Note: wp_kses() doesn't handle HTML tags with
 * hyphens, so escaping when echoing this output
 * can be tricky if you're going that route.
 *
 * @link https://core.trac.wordpress.org/ticket/34105
 *
 * @action wcus_amp_after_post
 */
function wcus_amp_render_ad_slot() {
	?>
	<amp-ad width="300"
		height="250"
		type="a9"
		data-aax_size="300x250"
		data-aax_pubname="test123"
		data-aax_src="302">
	</amp-ad>
	<?php
}

/**
 * Render our Page Footer.
 *
 * @action amp_post_template_footer
 */
function wcus_amp_footer() {
	?>
	<footer class="page-footer">
		<small>&copy;&nbsp;<?php echo esc_html( date( 'Y' ) ); ?>, <a href="<?php echo esc_url( get_site_url() ); ?>"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>&nbsp;|&nbsp;<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'wcus-amp' ) ); ?>"><?php echo esc_html( sprintf( __( 'Proudly powered by %s', 'wcus-amp' ), 'WordPress' ) ); ?></a></small>
	</footer>
	<?php
}


