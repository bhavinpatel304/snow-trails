<?php
/**
 * Twenty Twenty-Five Child Theme functions
 */

// Enqueue parent and child theme styles
function twentytwentyfive_child_enqueue_styles() {
    // Load parent theme stylesheet
    wp_enqueue_style(
        'twentytwentyfive-style',
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'bootstrap-cdn', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css', // CDN URL
        array(), 
        null
    );

    wp_enqueue_style(
        'bootstrap-icons-cdn', 
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css', // CDN URL
        array(), 
        null
    );

    wp_enqueue_style(
        'twentytwentyfive-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('twentytwentyfive-style') // make sure child loads after parent
    );
}
add_action('wp_enqueue_scripts', 'twentytwentyfive_child_enqueue_styles');

function my_theme_enqueue_scripts() {
    wp_enqueue_script(
        'jquery-cdn', 
        'https://code.jquery.com/jquery-3.7.1.min.js', 
        array(), 
        null, 
        true // Load in footer
    );
    wp_enqueue_script(
        'bootstrap-cdn', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js',
        array(), 
        null, 
        true // Load in footer
    );    
}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_scripts');

