<?php
/**
 * Server-side rendering for the Slots Grid block
 *
 * @package slot-pages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get block attributes
$limit   = isset( $attributes['limit'] ) ? intval( $attributes['limit'] ) : 6;
$columns = isset( $attributes['columns'] ) ? intval( $attributes['columns'] ) : 3;
$order   = $attributes['order'] ?? 'recent';

// Build query args
$args = [
	'post_type'      => 'slot',
	'posts_per_page' => $limit,
	'post_status'    => 'publish',
];

// Set ordering
if ( $order === 'random' ) {
	$args['orderby'] = 'rand';
} else {
	$args['orderby'] = 'date';
	$args['order']   = 'DESC';
}

// Run the query
$slots_query = new WP_Query( $args );

?>

<div class="wp-block-slots-pages-slots-grid">
	<?php if ( $slots_query->have_posts() ) : ?>
        <div class="slots-grid" style="grid-template-columns: repeat(<?php echo esc_attr( $columns ); ?>, 1fr);">
			<?php while ( $slots_query->have_posts() ) : $slots_query->the_post(); ?>
                <div class="slot-card">
					<?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="slot-thumbnail">
							<?php the_post_thumbnail( 'medium' ); ?>
                        </a>
					<?php endif; ?>

                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

					<?php
					// Display slot metadata
					$meta_fields = [
						'_slot_rating'    => [
							'label'  => 'Rating',
							'format' => 'stars',  // Special format for stars
						],
						'_slot_provider'  => [
							'label'  => 'Provider',
							'format' => 'taxonomy',  // Special format for taxonomy
						],
						'_slot_rtp'       => [
							'label'  => 'RTP',
							'format' => 'percentage',  // Format with % sign
						],
						'_slot_min_wager' => [
							'label'  => 'Min Wager',
							'format' => 'currency',  // Format as currency
						],
						'_slot_max_wager' => [
							'label'  => 'Max Wager',
							'format' => 'currency',  // Format as currency
						],
					];

					echo '<div class="slot-meta-info">';

					foreach ( $meta_fields as $key => $config ) {
						$value = get_post_meta( get_the_ID(), $key, true );

						if ( ! empty( $value ) ) {
							echo '<div class="slot-meta-item ' . esc_attr( str_replace( '_slot_', '', $key ) ) . '">';

							// Display label
							echo '<span class="meta-label">' . esc_html( $config['label'] ) . ': </span>';

							// Display value with appropriate formatting
							switch ( $config['format'] ) {
								case 'stars':
									$stars  = '';
									$rating = min( 5, max( 0, floatval( $value ) ) );
									for ( $i = 1; $i <= 5; $i ++ ) {
										$stars .= $i <= $rating ? '★' : '☆';
									}
									echo '<span class="meta-value star-rating">' . esc_html( $stars ) . '</span>';
									break;

								case 'taxonomy':
									$term = get_term( $value, 'slot_provider' );
									if ( ! is_wp_error( $term ) && $term ) {
										echo '<span class="meta-value">' . esc_html( $term->name ) . '</span>';
									}
									break;

								case 'percentage':
									echo '<span class="meta-value">' . esc_html( $value ) . '%</span>';
									break;

								case 'currency':
									echo '<span class="meta-value">' . esc_html( number_format( (float) $value, 2 ) ) . '</span>';
									break;

								default:
									echo '<span class="meta-value">' . esc_html( $value ) . '</span>';
							}

							echo '</div>';
						}
					}

					echo '</div>';
					?>
                    <a href="<?php the_permalink(); ?>" class="more-info"><?php _e( 'More Info', 'slot-pages' ); ?></a>
                </div>
			<?php endwhile; ?>
        </div>
	<?php else : ?>
        <p class="no-slots"><?php _e( 'No slots found.', 'slot-pages' ); ?></p>
	<?php endif; ?>

	<?php wp_reset_postdata(); ?>
</div>
