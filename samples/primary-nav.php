<?php
/**
 * Register the amp-sidebar component script with WP AMP.
 *
 * You may have already done this elsewhere.
 */
function wcus_amp_sidebar_component_script( $data ) {
	$data['amp_component_scripts']['amp-sidebar'] = 'https://cdn.ampproject.org/v0/amp-sidebar-0.1.js';
	return $data;
}
add_filter( 'amp_post_template_data', 'wcus_amp_sidebar_component_script' );

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
function wcus_clean_nav_menu_items( $location ) {
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

/*
 * Add the following HTML markup immediately after the opening
 * <body> tag in your AMP template file (single.php).
 *
 * The AMP Spec requires that this be a direct child of <body>,
 * and only one of these is allowed on a page.
 */
?>
<amp-sidebar id="site-menu" layout="nodisplay">
	<ul>
		<?php // Replace 'primary' with whatever menu location you need. ?>
		<?php echo wp_kses_post( wcus_clean_nav_menu_items( 'primary' ) ); ?>
		<li on="tap:site-menu.close"><?php esc_html_e( 'Close', 'wcus-amp' ); ?></li>
	</ul>
</amp-sidebar>

<?php
/*
 * Add the following button to your single.php template file
 * wherever you need it.
 *
 * The "site-menu" label can be called whatever you want,
 * but it must match the id of the amp-sidebar element.
 */
?>
<button class="menu-toggle" on='tap:site-menu.toggle' aria-label="Toggle Navigation">
	<?php esc_html_e( 'Navigation', 'wcus-amp' ); ?>
</button>
