<?php
/**
 * Template for rendering the Slot Detail block
 *
 * @package Slot_Pages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Ensure the post type is 'slot'
if ( get_post_type() !== 'slot' ) {
	return '';
}

// Get the additional class if provided
$className = isset( $attributes['className'] ) ? ' ' . sanitize_html_class( $attributes['className'] ) : '';

// Get post metadata once to avoid multiple database calls
$post_id = get_the_ID();

$meta_fields = [
	'_slot_rating'    => [ 'label' => 'Rating', 'format' => 'stars' ],
	'_slot_provider'  => [ 'label' => 'Provider', 'format' => 'taxonomy' ],
	'_slot_rtp'       => [ 'label' => 'RTP', 'format' => 'percentage' ],
	'_slot_min_wager' => [ 'label' => 'Min Wager', 'format' => 'currency' ],
	'_slot_max_wager' => [ 'label' => 'Max Wager', 'format' => 'currency' ],
];

ob_start();
?>
    <div class="slot-detail-container <?php echo esc_attr( $className ); ?>">
        <div class="slot-header">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'large', [ 'class' => 'slot-thumbnail' ] ); ?>
			<?php endif; ?>
            <h1 class="slot-title"><?php the_title(); ?></h1>
        </div>

        <div class="slot-meta-info">
			<?php
			foreach ( $meta_fields as $key => $config ) {
				$value = get_post_meta( $post_id, $key, true );
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
    </div>
<?php
echo ob_get_clean(); // Clear the output buffer and return the content
