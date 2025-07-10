<?php
/**
 * Template for displaying single slot details
 *
 * This template is used when a theme doesn't provide its own template for displaying slot details.
 * It provides a fallback that ensures slot details are displayed consistently across themes.
 *
 * @package SlotPages
 */

namespace SlotPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Ensure we're viewing a slot post type
if ( get_post_type() !== PostTypes::POST_TYPE ) {
	return;
}

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

	<?php
	while ( have_posts() ) :
		the_post();
		
		// Get post meta-data once to avoid multiple database calls
		$post_id = get_the_ID();
		
		// Get meta fields configuration
		$meta_fields = Blocks::get_meta_fields_config();
		?>
		
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'slot-single' ); ?>>
			<div class="slot-detail-container">
				<div class="slot-header">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="slot-thumbnail-wrapper">
							<?php the_post_thumbnail( 'large', [ 'class' => 'slot-thumbnail' ] ); ?>
						</div>
					<?php endif; ?>
					
					<h1 class="slot-title entry-title"><?php the_title(); ?></h1>
				</div>

				<div class="slot-meta-info">
					<?php
					foreach ( $meta_fields as $key => $config ) {
						$value = get_post_meta( $post_id, $key, true );
						if ( $value ) {
							echo '<div class="slot-meta-item ' . esc_attr( str_replace( '_slot_', '', $key ) ) . '">';
							echo '<span class="meta-label">' . esc_html( $config['label'] ) . ': </span>';
							echo Blocks::format_meta_value( $value, $config['format'] );
							echo '</div>';
						}
					}
					?>
				</div>

				<div class="slot-content entry-content">
					<?php the_content(); ?>
				</div>
			</div>
		</article>

	<?php endwhile; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();