<?php
/*
Plugin Name: AI Enhanced Search
Description: Enhances WordPress search with AI recommendations and advanced search.
Version: 1.0
Author: Your Name
*/

// Enqueue scripts and styles
function aes_enqueue_scripts_styles() {
    wp_enqueue_style( 'aes-style', plugin_dir_url( __FILE__ ) . 'style.css' );
    wp_enqueue_script( 'aes-script', plugin_dir_url( __FILE__ ) . 'script.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'aes_enqueue_scripts_styles' );

// Add settings page to admin menu
function aes_add_admin_menu() {
    add_options_page( 'AI Enhanced Search Settings', 'AI Enhanced Search', 'manage_options', 'aes-settings', 'aes_settings_page' );
}
add_action( 'admin_menu', 'aes_add_admin_menu' );

// Create settings page
function aes_settings_page() {
    ?>
    <div class="wrap">
        <h2>AI Enhanced Search Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'aes_settings_group' );
            do_settings_sections( 'aes-settings' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register and sanitize settings
function aes_register_settings() {
    register_setting( 'aes_settings_group', 'aes_api_key', 'sanitize_text_field' );
    add_settings_section( 'aes_settings_section', 'API Settings', 'aes_settings_section_callback', 'aes-settings' );
    add_settings_field( 'aes_api_key_field', 'ChatGPT API Key', 'aes_api_key_field_callback', 'aes-settings', 'aes_settings_section' );
}
add_action( 'admin_init', 'aes_register_settings' );

// Section callback
function aes_settings_section_callback() {
    echo 'Enter your ChatGPT API Key below:';
}

// API key field callback
function aes_api_key_field_callback() {
    $api_key = get_option( 'aes_api_key' );
    echo '<input type="text" id="aes_api_key" name="aes_api_key" value="' . esc_attr( $api_key ) . '" />';
}

// Register custom widget
function register_aes_custom_widget() {
    register_widget( 'AES_Custom_Widget' );
}
add_action( 'widgets_init', 'register_aes_custom_widget' );

// Custom widget class
class AES_Custom_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'aes_custom_widget',
            __( 'AI Enhanced Search', 'aes_domain' ),
            array( 'description' => __( 'Enhanced search with AI recommendations and advanced search', 'aes_domain' ) )
        );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        echo '<div class="aes-search-widget">';
        echo '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">';
        echo '<label>';
        echo '<span class="screen-reader-text">' . _x( 'Search for:', 'label', 'aes_domain' ) . '</span>';
        echo '<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search …', 'placeholder', 'aes_domain' ) . '" value="' . get_search_query() . '" name="s" />';
        echo '</label>';
        echo '<button type="submit" class="search-submit">' . esc_html_x( 'Search', 'submit button', 'aes_domain' ) . '</button>';
        echo '<button type="button" class="aes-recommendation-button">AI Recommendation</button>';
        echo '</form>';
        echo '</div>';
        echo $args['after_widget'];
    }
}
