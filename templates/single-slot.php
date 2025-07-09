<?php
/**
 * Template for displaying single Slot posts
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
// Render FSE header if supported
if ( function_exists( 'do_blocks' ) ) {
	$theme = wp_get_theme()->get_stylesheet();
	echo do_blocks( '<!-- wp:template-part {"slug":"header","theme":"' . esc_js( $theme ) . '","tagName":"header"} /-->' );
}

// Render Slot Detail block if registered
if ( function_exists( 'register_block_type' ) && WP_Block_Type_Registry::get_instance()->is_registered( 'slots-pages/slot-detail' ) ) {
	echo do_blocks( '<!-- wp:slots-pages/slot-detail /-->' );
} else {
	// Fallback content
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
			the_title( '<h1>', '</h1>' );
			the_content();
		endwhile;
	endif;
}

// Render FSE footer if supported
if ( function_exists( 'do_blocks' ) ) {
	echo do_blocks( '<!-- wp:template-part {"slug":"footer","theme":"' . esc_js( $theme ) . '","tagName":"footer"} /-->' );
}

wp_footer();
?>
</body>
</html>