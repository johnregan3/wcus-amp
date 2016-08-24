<?php
/**
 * Example of how to filter a shortcode for AMP compliance.
 *
 * @package WCUS_AMP
 */

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
add_filter( 'the_content', 'wcus_amp_filter_shortcode_podcast' );

/**
 * Get a value from a shortcode.
 *
 * Helper function for wcus_amp_filter_shortcode_podcast().
 *
 * Example:
 *
 * Search for the podcast shortcode that looks like this in the Post content:
 * [podcast url="http://example.com/audio.mp3"]
 * and you want to get the value of "url"...
 *
 * Do this:
 * $regex = get_shortcode_regex( 'podcast' );
 * $url_value = wcus_amp_get_shortcode_attr( $content, $regex, 'url' );
 *
 * The $url_value will now be "http://example.com/audio.mp3".
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

	// Find item in atts array that starts with $search.
	$matches = array_filter( $shortcode_attrs, function( $var ) use ( $search ) {
		return ( 0 === strpos( trim( $var ), $search ) );
	});

	if ( empty( $matches ) ) {
		return false;
	}

	// Get the first occurrence of our string.
	$value_string = array_shift( array_values( $matches ) );
	$val = str_replace( $search , '', $value_string );

	// Strip out both double and possible single quotes.
	$val = str_replace( '"', '', $val );
	$val = str_replace( "'", '', $val );
	return trim( $val );
}
