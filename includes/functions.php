<?php
// Function to display the page counter.
function page_counter_display( $post_id ) {
    // Get the visit count for the given post ID.
    $count = intval( get_post_meta( $post_id, 'page_counter_count', true ) );
    $count++;
    update_post_meta( $post_id, 'page_counter_count', $count );

    // HTML markup for the counter section.
    $output = '<div class="page-counter-section">';
    $output .= '<p class="page-counter-text">Total views : ';
    $output .= '<span class="page-counter-number">' . $count . ' times</span></p>';
    $output .= '</div>';

    // Display the counter section.
    echo wp_kses_post( $output );

}
?>
