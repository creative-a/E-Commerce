<?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */
defined( 'ABSPATH' ) || exit;

// Enqueue necessary resources
wp_enqueue_script( array( 'comment-reply', 'nivo-lightbox' ) );
wp_enqueue_style( array( 'nivo-lightbox', 'nivo-lightbox-default' ) );

// Header
get_header();

// Blog single template
get_template_part( 'tpls/blog-single' );

// Footer
get_footer();
