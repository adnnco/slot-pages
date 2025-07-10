<?php
/**
 * Template for displaying single slot posts
 */


// Check if the block is registered
if (function_exists( 'register_block_type' ) && WP_Block_Type_Registry::get_instance()->is_registered( 'slot-pages/slot-detail' )) {
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
$theme = wp_get_theme()->get_stylesheet();
echo do_blocks( '<!-- wp:template-part {"slug":"header","theme":"' . esc_js( $theme ) . '","tagName":"header"} /-->' );
echo do_blocks( '<!-- wp:slot-pages/slot-detail /-->' );
if ( function_exists( 'wp_head' ) ) {
	wp_head();
}
echo do_blocks( '<!-- wp:template-part {"slug":"footer","theme":"' . esc_js( $theme ) . '","tagName":"footer"} /-->' );
?>
<?php if ( function_exists( 'wp_footer' ) ) {
	wp_footer();
} ?>
</body>
</html>
<?php
} else {
	get_header();

	// Fallback to traditional template
	?>
    <div class="slot-detail">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <h1><?php the_title(); ?></h1>
            <div class="slot-thumbnail"><?php the_post_thumbnail( 'large' ); ?></div>
            <div class="slot-content"><?php the_content(); ?></div>

            <ul class="slot-meta">
                <li>
                    <strong>Star Rating:</strong>
					<?php
					$rating = get_post_meta( get_the_ID(), '_slot_rating', true );
					echo esc_html( $rating );
					?>
                </li>
                <li>
                    <strong>Provider:</strong>
					<?php echo esc_html( get_post_meta( get_the_ID(), '_slot_provider', true ) ); ?>
                </li>
                <li>
                    <strong>RTP:</strong>
					<?php echo esc_html( get_post_meta( get_the_ID(), '_slot_rtp', true ) ); ?>
                </li>
                <li>
                    <strong>Min Wager:</strong>
					<?php echo esc_html( get_post_meta( get_the_ID(), '_slot_min_wager', true ) ); ?>
                </li>
                <li>
                    <strong>Max Wager:</strong>
					<?php echo esc_html( get_post_meta( get_the_ID(), '_slot_max_wager', true ) ); ?>
                </li>
            </ul>
		<?php endwhile; endif; ?>
    </div>
	<?php
	get_footer();
}
