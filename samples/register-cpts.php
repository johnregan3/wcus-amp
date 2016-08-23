<?php
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
add_action( 'amp_init', 'wcus_amp_register_post_types' );
