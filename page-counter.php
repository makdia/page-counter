<?php
/*
Plugin Name: Page Counter
Description: A simple page counter plugin for WordPress.
Version: 1.0.0
Author: Makdia Hussain
Author URI: https://github.com/makdia
Plugin URI: 
License: GPLv2 or later
Text Domain: page-counter
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Register deactivation hook.
register_deactivation_hook( __FILE__, 'page_counter_deactivate' );

// Check if WordPress version is compatible.
global $wp_version;
$required_wp_version = '4.5';
if ( version_compare( $wp_version, $required_wp_version, '>=' ) ) {
    // Define constants.
    define( 'PAGE_COUNTER_VERSION', '1.0.0' );
    define( 'PAGE_COUNTER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

    // Include necessary files.
    require_once PAGE_COUNTER_PLUGIN_DIR . 'includes/functions.php';

    // Initialize the plugin.
    function page_counter_init() {
        // Initialize the page counter count.
        require_once PAGE_COUNTER_PLUGIN_DIR . 'includes/install.php';
    }
    add_action( 'plugins_loaded', 'page_counter_init' );

    // Add shortcode for displaying page counter.
    function page_counter_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'post_id' => get_the_ID(),
        ), $atts, 'page_counter' );

        // Sanitize the post ID.
        $post_id = absint( $atts['post_id'] );

        ob_start();
        page_counter_display( $post_id );
        return ob_get_clean();
    }
    add_shortcode( 'page_counter', 'page_counter_shortcode' );
} else {
    // Display error notice if WordPress version is not compatible.
    add_action( 'admin_notices', 'page_counter_version_error_notice' );

    // Error notice function.
    function page_counter_version_error_notice() {
        $required_wp_version = '4.5';
        echo '<div class="error"><p>' . esc_html__( 'Page Counter plugin requires WordPress version ', 'page-counter' ) . esc_html( $required_wp_version ) . esc_html__( ' or higher to be activated.', 'page-counter' ) . '</p></div>';
    }
}

// Deactivation function.
function page_counter_deactivate() {
    // Get all published posts.
    $posts = get_posts( array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'numberposts' => -1,
    ) );

    // Loop through each post and delete the page counter meta.
    foreach ( $posts as $post ) {
        delete_post_meta( $post->ID, 'page_counter_count' );
    }
}
