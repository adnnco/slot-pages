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

// Get the additional class if provided
$class_name = isset( $attributes['className'] ) ? ' ' . sanitize_html_class( $attributes['className'] ) : '';

// Determine which slot to display
$post_id = 0;
$selected_slot = isset( $attributes['selectedSlot'] ) ? intval( $attributes['selectedSlot'] ) : 0;

if ( $selected_slot > 0 ) {
    // Use the selected slot
    $post_id = $selected_slot;
    $slot_post = get_post( $post_id );

    // Verify the post exists and is a slot
    if ( ! $slot_post || get_post_type( $slot_post ) !== PostTypes::POST_TYPE ) {
        return '';
    }
} else {
    // Fallback to current post if it's a slot
    if ( get_post_type() !== PostTypes::POST_TYPE ) {
        return '';
    }
    $post_id = get_the_ID();
    $slot_post = get_post( $post_id );
}

// Get meta fields configuration
$meta_fields = Blocks::get_meta_fields_config();

// Start output buffering to capture the HTML output
ob_start();
?>
    <div class="slot-detail-container<?php echo esc_attr( $class_name ); ?>">
        <div class="slot-header">
            <?php if ( has_post_thumbnail( $post_id ) ) : ?>
                <?php echo get_the_post_thumbnail( $post_id, 'large', [ 'class' => 'slot-thumbnail' ] ); ?>
            <?php endif; ?>
            <h1 class="slot-title"><?php echo esc_html( get_the_title( $post_id ) ); ?></h1>
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
