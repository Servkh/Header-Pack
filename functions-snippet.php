<?php
/**
 * Hackman Sticky Header — enqueue snippet
 * Paste this into your child theme's functions.php
 * (or use a code snippet plugin like Code Snippets)
 */
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'hsh-header',
        get_stylesheet_directory_uri() . '/hsh/header.css',
        [],
        '1.0.0'
    );

    wp_enqueue_script(
        'hsh-sticky',
        get_stylesheet_directory_uri() . '/hsh/sticky-header.js',
        [],
        '1.0.0',
        true  // load in footer
    );
} );
