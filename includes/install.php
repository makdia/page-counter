<?php
// Initialize the page counter count.
function page_counter_initialize() {
    // Get all published posts.
    $posts = get_posts( array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'numberposts' => -1,
    ) );

    // Initialize page counter count for each post.
    foreach ( $posts as $post ) {
        add_post_meta( $post->ID, 'page_counter_count', 0, true );
    }
}
add_action( 'init', 'page_counter_initialize' );
