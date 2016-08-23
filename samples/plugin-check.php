<?php
/**
 * Show an Admin Notice if the AMP plugin is not found.
 *
 * This is a basic implementation.  Naturally, you'll want
 * to add ajax support so that once this is dismissed, it goes
 * away permanently.  Also, you may want to limit the admin
 * pages this displays to prevent annoying your users.
 *
 * @return bool
 */
function wcus_amp_is_active() {
	if ( is_plugin_active( 'amp/amp.php' ) ) {
		return false;
	}
	?>
	<div class="notice notice-error is-dismissible">
		<p>
			<a href="https://wordpress.org/plugins/amp/" target="_blank"><?php esc_html_e( 'The AMP plugin by Automattic', 'wcus-amp' ); ?></a>&nbsp;<?php esc_html_e( 'is not installed or activated.', 'wcus-amp' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'wcus_amp_is_active' );
