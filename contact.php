<?php
/*
	Template Name: Contact
*/

/**
 *	Oxygen WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */
defined( 'ABSPATH' ) || exit;

// Enqueue necessary resources
wp_enqueue_script( array( 'google-map', 'oxygen-contact' ) );

// Header
get_header();

// Contact page template
get_template_part( 'tpls/contact' );

// Footer
get_footer();