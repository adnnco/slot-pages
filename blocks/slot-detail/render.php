<?php
/**
 * Template for rendering the Slot Detail block
 *
 * @package SlotPages
 */

namespace SlotPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Ensure the post-type is 'slot'
if ( get_post_type() !== PostTypes::POST_TYPE ) {
	return '';
}

// Get the additional class if provided
$class_name = isset( $attributes['className'] ) ? ' ' . sanitize_html_class( $attributes['className'] ) : '';

// Get post meta-data once to avoid multiple database calls
$post_id = get_the_ID();

// Get meta fields configuration
$meta_fields = Blocks::get_meta_fields_config();

// Start output buffering to capture the HTML output
ob_start();
?>
    <div class="slot-detail-container<?php echo esc_attr( $class_name ); ?>">
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
					echo Blocks::format_meta_value( $value, $config['format'] );
					echo '</div>';
				}
			}
			?>
        </div>
    </div>
	<?php echo ob_get_clean(); // Clear the output buffer and return the content
