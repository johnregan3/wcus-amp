<?php
/**
 * A Customized single post template.
 *
 * This is a modified version of
 *
 * @link https://github.com/Automattic/amp-wp/blob/master/templates/single.php
 *
 * @package WCUS_AMP
 */

?>
<!doctype html>
<html amp <?php language_attributes(); ?>>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
	<?php do_action( 'amp_post_template_head', $this ); ?>

	<style amp-custom>
		<?php $this->load_parts( array( 'style' ) ); ?>
		<?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
</head>
<body>
<?php $this->load_parts( array( 'header-bar' ) ); ?>
<div class="amp-wp-content">
	<h1 class="amp-wp-title"><?php echo wp_kses_data( $this->get( 'post_title' ) ); ?></h1>
	<ul class="amp-wp-meta">
		<?php $this->load_parts( apply_filters( 'amp_post_template_meta_parts', array( 'meta-author', 'meta-time', 'meta-taxonomy' ) ) ); ?>
	</ul>
	<?php echo $this->get( 'post_amp_content' ); // amphtml content; no kses. ?>
	<div class="post-footer">
		<?php

		/*
		 * Here are our custom share buttons.
		 */
		do_action( 'wcus_amp_post_footer' );
		?>
	</div>
</div>
<div class="after-post">
	<?php

	/*
	 * Here is our ad.
	 */
	do_action( 'wcus_amp_after_post' );
	?>
</div>
<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>
</html>
