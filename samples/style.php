<?php
/**
 * Slightly modified version of the default WP AMP stylesheet, style.php.
 *
 * This is pared down for clarity.  There is a fully-customized version of this
 * in wcus-amp/templates/style.php.
 *
 * @link https://github.com/Automattic/amp-wp/blob/master/templates/style.php
 *
 * @package WCUS_AMP
 */

$body_color = '#333';
?>
/* Merriweather fonts */
@font-face {
font-family:'Merriweather';
src:url('https://s1.wp.com/i/fonts/merriweather/merriweather-regular-webfont.woff2') format('woff2'),
url('https://s1.wp.com/i/fonts/merriweather/merriweather-regular-webfont.woff') format('woff'),
url('https://s1.wp.com/i/fonts/merriweather/merriweather-regular-webfont.ttf') format('truetype'),
url('https://s1.wp.com/i/fonts/merriweather/merriweather-regular-webfont.svg#merriweatherregular') format('svg');
font-weight:400;
font-style:normal;
}

/* Generic WP styling */
amp-img.alignright { float: right; margin: 0 0 1em 1em; }
amp-img.alignleft { float: left; margin: 0 1em 1em 0; }
amp-img.aligncenter { display: block; margin-left: auto; margin-right: auto; }
.alignright { float: right; }
.alignleft { float: left; }
.aligncenter { display: block; margin-left: auto; margin-right: auto; }

.wp-caption.alignleft { margin-right: 1em; }
.wp-caption.alignright { margin-left: 1em; }

.amp-wp-enforced-sizes {
/** Our sizes fallback is 100vw, and we have a padding on the container; the max-width here prevents the element from overflowing. **/
max-width: 100%;
}

.amp-wp-unknown-size img {
/** Worst case scenario when we can't figure out dimensions for an image. **/
/** Force the image into a box of fixed dimensions and use object-fit to scale. **/
object-fit: contain;
}

/* Template Styles */
.amp-wp-content, .amp-wp-title-bar div {
<?php $content_max_width = absint( $this->get( 'content_max_width' ) ); ?>
<?php if ( $content_max_width > 0 ) : ?>
	max-width: <?php echo esc_html( sprintf( '%dpx', $content_max_width ) ); ?>;
	margin: 0 auto;
<?php endif; ?>
}

body {
font-family: 'Merriweather', Serif;
font-size: 16px;
line-height: 1.8;
background: #fff;
color: <?php echo esc_html( $body_color ); // This is currently the best method available for escaping output in CSS. ?>;
padding-bottom: 100px;
}

/* Other Elements */
amp-carousel {
background: #000;
}

amp-iframe,
amp-youtube,
amp-instagram,
amp-vine {
background: #f3f6f8;
}

amp-carousel > amp-img > img {
object-fit: contain;
}

.amp-wp-iframe-placeholder {
/** The below data is set via a hook in the plugin. **/
background: #f3f6f8 url( <?php echo esc_url( $this->get( 'placeholder_image_url' ) ); ?> ) no-repeat center 40%;
background-size: 48px 48px;
min-height: 48px;
}
