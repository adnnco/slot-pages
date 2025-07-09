<?php

/**
 * Template for rendering the Slot Detail block
 *
 * @package Slot_Pages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Ensure the post type is 'slot'
if (get_post_type() !== 'slot') {
    return; // Exit if not a slot post
}

?>
<div class="slot-detail-container">
    <div class="slot-header">
        <?php the_post_thumbnail('large', ['class' => 'slot-thumbnail']); ?>
        <h1 class="slot-title"><?php the_title(); ?></h1>
    </div>
    <div class="slot-meta">
        <p><strong>Provider:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_slot_provider', true)); ?></p>
        <p><strong>RTP:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_slot_rtp', true)); ?>%</p>
        <p><strong>Min Wager:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_slot_min_wager', true)); ?></p>
        <p><strong>Max Wager:</strong> <?php echo esc_html(get_post_meta(get_the_ID(), '_slot_max_wager', true)); ?></p>
        <div class="slot-rating">
            <?php
            $rating = intval(get_post_meta(get_the_ID(), '_slot_rating', true));
            for ($i = 1; $i <= 5; $i++) {
                echo '<span class="star' . ($i <= $rating ? ' filled' : '') . '">★</span>';
            }
            ?>
        </div>
    </div>
    <div class="slot-description">
        <?php the_content(); ?>
    </div>
</div>
<?php

