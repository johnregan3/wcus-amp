<?php
/**
 * Example of how to create a Navigation Menu in AMP.
 *
 * Be sure to register amp-sidebar component script.
 *
 * @see component-scripts.php
 *
 * @package WCUS_AMP
 */

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
	<?php endforeach; ?>
	<li on="tap:site-menu.close"><?php esc_html_e( 'Close', 'wcus-amp' ); ?></li>
	<?php
	return ob_get_clean();
}

/*
 * Add the following HTML markup immediately after the opening
 * <body> tag in your AMP template file (header-bar.php or single.php).
 *
 * I personally recommend wrapping each of these in a function, then firing them
 * with a custom action hook in your template.
 *
 * The AMP Spec requires that this be a direct child of <body>,
 * and only one of these amp-sidebars is allowed on a page.
 */
?>
<amp-sidebar id="site-menu" layout="nodisplay">
	<ul>
		<?php // Replace 'primary' with whatever menu location you need. ?>
		<?php echo wp_kses_post( wcus_amp_clean_nav_menu_items( 'primary' ) ); ?>

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
<button class="menu-toggle" on='tap:site-menu.toggle' aria-label="<?php esc_html_e( 'Toggle Navigation', 'wcus-amp' ); ?>" class="hamburger-menu">
	<?php esc_html_e( 'Open Menu', 'wcus-amp' ); ?>
</button>
