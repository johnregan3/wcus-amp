<?php
/**
 * Our custom header-bar template file.
 *
 * This is a modified version of
 * @link https://github.com/Automattic/amp-wp/blob/master/templates/header-bar.php
 *
 * @package WCUS_AMP
 */


$site_icon_url = $this->get( 'site_icon_url' );

// This will load our custom Nav Menu (amp-sidebar).
do_action( 'wcus_amp_primary_nav' );
?>
<nav class="amp-wp-title-bar">
	<div>
		<a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
			<?php if ( $site_icon_url ) : ?>
				<amp-img src="<?php echo esc_url( $site_icon_url ); ?>" width="32" height="32" class="amp-wp-site-icon"></amp-img>
			<?php endif; ?>

			<?php echo esc_html( $this->get( 'blog_name' ) ); ?>
		</a>

		<?php // This will load our custom Nav Menu button. ?>
		<button class="menu-toggle" on='tap:site-menu.toggle' aria-label="<?php esc_html_e( 'Toggle Navigation', 'wcus-amp' ); ?>">
			<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'templates/img/hamburger.svg' ); ?>" alt="<?php esc_html_e( 'Open Menu', 'wcus-amp' ); ?>" />
			<?php esc_html_e( 'Open Menu', 'wcus-amp' ); ?>
		</button>

	</div>
</nav>
