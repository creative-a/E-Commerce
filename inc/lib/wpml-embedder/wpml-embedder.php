<?php
/**
 *    Kalium WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

require_once 'vendor/otgs/installer/loader.php';

WP_Installer_Setup( $wp_installer_instance,
	array(
		'plugins_install_tab'  => 1, // optional, default value: 0
		'affiliate_id:wpml'    => '150643', // optional, default value: empty
		'affiliate_key:wpml'   => 'VWCj6GPGWxBE', // optional, default value: empty
		'src_name'             => 'Oxygen', // optional, default value: empty, needed for coupons
		'src_author'           => 'Laborator',// optional, default value: empty, needed for coupons
		'repositories_include' => array( 'wpml' ) // optional, default to empty (show all)
	)
);
