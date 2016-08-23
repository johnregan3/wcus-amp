<?php
/**
 * Example of using amp-social-share within WP.
 *
 * This will go in a template file.
 *
 * Note that Facebook requires an App ID ($your_fb_amp_id).
 *
 * Be sure to include the amp-social-share component script.
 *
 * @link https://developers.facebook.com/docs/apps/register
 *
 * @package WCUS_AMP
 */

$your_fb_amp_id = '1234567890';
?>
<div class="share-buttons">
	<amp-social-share type="facebook" width="32" height="32"
	                  data-url="<?php echo esc_url( get_permalink() ); ?>"
	                  data-param-app_id="<?php echo esc_attr( $your_fb_amp_id ); ?>"
	                  data-media="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>"
	                  data-description="<?php the_title_attribute(); ?>">
	</amp-social-share>
	<amp-social-share type="twitter" width="32" height="32"
	                  data-url="<?php echo esc_url( get_permalink() ); ?>"
	                  data-media="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>"
	                  data-description="<?php the_title_attribute(); ?>">
	</amp-social-share>
	<amp-social-share type="pinterest" width="32" height="32"
	                  data-url="<?php echo esc_url( get_permalink() ); ?>"
	                  data-media="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>"
	                  data-description="<?php the_title_attribute(); ?>">
	</amp-social-share>
</div>
