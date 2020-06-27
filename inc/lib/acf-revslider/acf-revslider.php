<?php
/**
 *    Revolution slider field type
 *
 *    Created by Arlind Nushi
 *
 *    Laborator.co
 *    www.laborator.co
 */


function include_field_types_revslider( $version ) {
	include_once( 'acf-revslider-v5.php' );
}

add_action( 'acf/include_field_types', 'include_field_types_revslider' );

function register_fields_revslider() {
	include_once( 'acf-revslider-v4.php' );
}

add_action( 'acf/register_fields', 'register_fields_revslider' );
