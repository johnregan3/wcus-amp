<?php
/**
 * Inline style template.
 *
 * @package WCUS_AMP
 */

/**
 * Example of using theme mods from the customizer.
 *
 * You'll need to add the controls for this yourself.
 *
 * @link https://codex.wordpress.org/Theme_Customization_API
 *
 * $header_background_color = ! empty( get_theme_mod( 'amp_header_background_color' ) ) ? get_theme_mod( 'amp_header_background_color' ) : '#fff';
 */
$body_background_color = '#f1f1f1';
$header_background_color = '#fff';
$text_color = '#333';
$post_footer_background_color = '#f7f7f7';
?>
/* Noto Serif Font */
@import 'https://fonts.googleapis.com/css?family=Noto+Sans:400,400i,700.700i|Noto+Serif:400,400i,700,700i';

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
.amp-wp-content, .amp-wp-title-bar div, .after-post, .page-footer .wrap {
	<?php $content_max_width = absint( $this->get( 'content_max_width' ) ); ?>
<?php if ( $content_max_width > 0 ) : ?>
	max-width: <?php echo esc_html( sprintf( '%dpx', $content_max_width ) ); ?>;
	margin: 0 auto;
<?php endif; ?>
}

body {
font-family: 'Noto Serif', Serif;
font-size: 16px;
line-height: 1.8;
background: <?php echo esc_html( $body_background_color ); ?>;
color: <?php echo esc_html( $text_color ); ?>;
padding-bottom: 100px;
}

.amp-wp-content {
overflow-wrap: break-word;
word-wrap: break-word;
font-weight: 400;
color: #3d596d;
background: #fff;
box-shadow: 0 0 1px rgba(0, 0, 0, 0.15);
}

.amp-wp-title {
padding: 16px;
margin: 0;
font-family: 'Noto Sans', Sans;
font-size: 36px;
line-height: 1.258;
font-weight: 700;
color: #2e4453;
}

p,
ol,
ul,
figure {
margin: 0 0 24px 0;
}

a,
a:visited {
color: #0087be;
}

a:hover,
a:active,
a:focus {
color: #33bbe3;
}

.amp-wp-content > p {
margin: 16px;
}


/* UI Fonts */
.amp-wp-meta,
nav.amp-wp-title-bar,
.wp-caption-text {
font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Roboto", "Oxygen-Sans", "Ubuntu", "Cantarell", "Helvetica Neue", sans-serif;
font-size: 15px;
}


/* Meta */
ul.amp-wp-meta {
padding: 0 24px 0 24px;
margin: 0 0 24px 0;
}

ul.amp-wp-meta li {
list-style: none;
display: inline-block;
margin: 0;
line-height: 24px;
white-space: nowrap;
overflow: hidden;
text-overflow: ellipsis;
max-width: 300px;
}

ul.amp-wp-meta li:before {
content: "\2022";
margin: 0 8px;
}

ul.amp-wp-meta li:first-child:before {
display: none;
}

.amp-wp-meta,
.amp-wp-meta a {
color: #4f748e;
}

.amp-wp-meta .screen-reader-text {
/* from twentyfifteen */
clip: rect(1px, 1px, 1px, 1px);
height: 1px;
overflow: hidden;
position: absolute;
width: 1px;
}

.amp-wp-byline amp-img {
border-radius: 50%;
border: 0;
background: #f3f6f8;
position: relative;
top: 6px;
margin-right: 6px;
}

.post-footer {
padding: 16px;
background: <?php echo esc_html( $post_footer_background_color ); ?>;
}

.after-post {
text-align: center;
margin: 36px auto;
}

.page-footer {
background: <?php echo esc_html( $header_background_color ); ?>;
padding: 16px 16px;
box-shadow: 0 0 1px rgba(0, 0, 0, 0.15);
text-align: center;
}

.page-footer a {
color: <?php echo esc_html( $text_color ); ?>;
}

/* Titlebar */
nav.amp-wp-title-bar {
background: <?php echo esc_html( $header_background_color ); // Not ideal for escaping here, but better than nothing. ?>;
font-family: 'Noto Sans', sans-serif;
padding: 16px 16px;
font-weight: bold;
font-size: 32px;
box-shadow: 0 0 1px rgba(0, 0, 0, 0.15);
margin-bottom: 36px;
position: relative;
}

nav.amp-wp-title-bar div {
line-height: 36px;
color: <?php echo esc_html( $text_color ); ?>;
}

nav.amp-wp-title-bar a {
color: <?php echo esc_html( $text_color ); ?>;
text-decoration: none;
}

nav.amp-wp-title-bar .amp-wp-site-icon {
/** site icon is 32px **/
float: left;
margin: 11px 8px 0 0;
border-radius: 50%;
}

/* Captions */
.wp-caption-text {
padding: 8px 16px;
font-style: italic;
}


/* Quotes */
blockquote {
padding: 16px;
margin: 8px 0 24px 0;
border-left: 2px solid #87a6bc;
color: #4f748e;
background: #e9eff3;
}

blockquote p:last-child {
margin-bottom: 0;
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
background: #f3f6f8 url( <?php echo esc_url( $this->get( 'placeholder_image_url' ) ); ?> ) no-repeat center 40%;
background-size: 48px 48px;
min-height: 48px;
}

#site-menu {
/* Override !important used in amp-sidebar default styles */
padding: 0 64px 0 32px !important;
background: #fff;
box-shadow: 0 0 1px rgba(0, 0, 0, 0.15);
padding: 48px;
}

#site-menu h2 {
margin-top: 16px;
margin-bottom: 0;
font-size: 32px;
font-family: 'Noto Sans', sans-serif;
}

#site-menu ul {
list-style: none;
margin: 0;
padding: 0;
border-top: 1px solid rgba(51, 51, 51, 0.1);
}

#site-menu li {
border-bottom: 1px solid rgba(51, 51, 51, 0.1);
line-height: 1.5;
padding: 0.5em 0;
min-width: 15em;
}

#site-menu a {
color: #333;
text-decoration: none;

}

.menu-button {
background: transparent;
border: 1px solid #eaeaea;
height: 41px;
overflow: hidden;
padding: 0;
position: absolute;
top: 16px;
right: 16px;
text-align: center;
width: 42px;
}

.menu-button img {
width: 36px;
height: auto;
margin: 3px 0 0 2px;
}

.cus-amp-featured-image {
margin-bottom: 16px;
}
