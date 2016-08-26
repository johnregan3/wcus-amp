<?php
/**
 * Customize the AMP Endpoint.
 *
 * By default, URLs appended with "/amp" will
 * display the AMP version of a given page.
 *
 * Here is how to change that "amp" string
 * to whatever you desire.
 *
 * If you want to leave it as "amp," then
 * simply ignore this function.
 *
 * Note:
 * Be sure to flush your rewrite rules after
 * changing this by visiting Settings > Permalinks.
 *
 * @package WCUS_AMP
 */

/**
 * Customize the AMP Endpoint.
 *
 * @filter amp_query_var
 *
 * @return string
 */
function wcus_amp_modify_endpoint() {
	return 'wcus-amp';
}
add_filter( 'amp_query_var' , 'wcus_amp_modify_endpoint' );
