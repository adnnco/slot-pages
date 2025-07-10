<?php
/**
 * Server-side rendering for the Slots Grid block
 *
 * @package slot-pages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get block attributes with defaults
$limit   = isset( $attributes['limit'] ) ? intval( $attributes['limit'] ) : 6;
$columns = isset( $attributes['columns'] ) ? intval( $attributes['columns'] ) : 2;
$order   = $attributes['order'] ?? 'recent';
$class   = isset( $attributes['className'] ) ? esc_attr( $attributes['className'] ) : '';

// Build query args
$args = [
	'post_type'      => 'slot',
	'posts_per_page' => $limit,
	'post_status'    => 'publish',
	'orderby'        => $order === 'random' ? 'rand' : 'date',
	'order'          => $order === 'random' ? '' : 'DESC',
];

// Query slots
$slots_query = new WP_Query( $args );

ob_start();
?>
    <div class="slots-grid-container <?php echo $class; ?> ">
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
                        <div class="slot-meta-info">
							<?php
							$meta_fields = [
								'_slot_rating'    => [ 'label' => 'Rating', 'format' => 'stars' ],
								'_slot_provider'  => [ 'label' => 'Provider', 'format' => 'taxonomy' ],
								'_slot_rtp'       => [ 'label' => 'RTP', 'format' => 'percentage' ],
								'_slot_min_wager' => [ 'label' => 'Min Wager', 'format' => 'currency' ],
								'_slot_max_wager' => [ 'label' => 'Max Wager', 'format' => 'currency' ],
							];

							foreach ( $meta_fields as $key => $config ) {
								$value = get_post_meta( get_the_ID(), $key, true );
								if ( $value ) {
									echo '<div class="slot-meta-item ' . esc_attr( str_replace( '_slot_', '', $key ) ) . '">';
									echo '<span class="meta-label">' . esc_html( $config['label'] ) . ': </span>';
									switch ( $config['format'] ) {
										case 'stars':
											$stars = str_repeat( '★', min( 5, (int) $value ) ) . str_repeat( '☆', 5 - min( 5, (int) $value ) );
											echo '<span class="meta-value star-rating">' . esc_html( $stars ) . '</span>';
											break;
										case 'taxonomy':
											$term = get_term( $value, 'slot_provider' );
											if ( $term && ! is_wp_error( $term ) ) {
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
							?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="slot-more-info"><?php esc_html_e( 'More Info', 'slot-pages' ); ?></a>
                    </div>
				<?php endwhile; ?>
            </div>
		<?php else : ?>
            <p class="no-slots"><?php esc_html_e( 'No slots found.', 'slot-pages' ); ?></p>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
    </div>
<?php
echo ob_get_clean();