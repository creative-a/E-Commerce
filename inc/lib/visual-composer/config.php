<?php
/**
 * Laborator Visual Composer Settings
 *
 * Developed by: Arlind Nushi (www.arlindnushi.com)
 *
 * www.laborator.co
 *
 * File Date: 22/04/2014
 *
 */

// Layout Elements
define( 'OXYGEN_VC_DIR', get_template_directory() . '/inc/lib/visual-composer/' );

// Register Own Param Types
include_once( OXYGEN_VC_DIR . '/param-types/fontelloicon/fontelloicon_param_type.php' );

// Shortcodes
function laborator_vc_shortcodes() {
	include_once( OXYGEN_VC_DIR . '/laborator-shortcodes/laborator_banner.php' );
	include_once( OXYGEN_VC_DIR . '/laborator-shortcodes/laborator_banner2.php' );
	include_once( OXYGEN_VC_DIR . '/laborator-shortcodes/laborator_featuretab.php' );
	include_once( OXYGEN_VC_DIR . '/laborator-shortcodes/laborator_blog.php' );
	include_once( OXYGEN_VC_DIR . '/laborator-shortcodes/laborator_button.php' );
	include_once( OXYGEN_VC_DIR . '/laborator-shortcodes/laborator_testimonials.php' );

	if ( oxygen_is_plugin_active( 'woocommerce/woocommerce.php' ) || class_exists( 'WooCommerce' ) ) {
		include_once( OXYGEN_VC_DIR . '/laborator-shortcodes/laborator_products.php' );
		include_once( OXYGEN_VC_DIR . '/laborator-shortcodes/laborator_products_carousel.php' );
		include_once( OXYGEN_VC_DIR . '/laborator-shortcodes/laborator_lookbook.php' );
	}
}

add_action( 'vc_before_init', 'laborator_vc_shortcodes' );

// VC Tabs 4.7
function lab_vc_tta_tabs_setup() {

	$new_param = array( 'Theme Styled (if selected, other style settings will be ignored)' => 'theme-styled' );

	$tabs_param      = WPBMap::getParam( 'vc_tta_tabs', 'style' );
	$accordion_param = WPBMap::getParam( 'vc_tta_accordion', 'style' );

	$tabs_param['value']      = array_merge( $new_param, $tabs_param['value'] );
	$accordion_param['value'] = array_merge( $new_param, $accordion_param['value'] );

	vc_update_shortcode_param( 'vc_tta_tabs', $tabs_param );
	vc_update_shortcode_param( 'vc_tta_accordion', $accordion_param );
}

add_action( 'vc_after_mapping', 'lab_vc_tta_tabs_setup' );

// Admin styles
function laborator_vc_styles() {
	$laborator_vc_style = get_template_directory_uri() . '/inc/lib/visual-composer/assets/laborator_vc_main.css';
	wp_enqueue_style( 'laborator_vc_main', $laborator_vc_style );
}

add_action( 'admin_enqueue_scripts', 'laborator_vc_styles' );

// Custom Params
function lab_vc_before_mapping() {
	// VC Row
	vc_add_params( 'vc_row', array(
		array(
			"type"        => 'checkbox',
			"heading"     => 'Block with Background',
			"param_name"  => "block_with_background",
			"description" => "Make this block with background.",
			"value"       => array( 'Yes' => 'yes' ),
			"weight"      => 1
		),

		array(
			"type"        => 'checkbox',
			"heading"     => 'Add Default Margin',
			"param_name"  => "add_default_margin",
			"description" => "Add the default margin for the elements container.",
			"value"       => array( 'Yes' => 'yes' ),
			"weight"      => 1
		)
	) );


	// VC Column
	vc_add_param( 'vc_column_text', array(
		"type"        => 'checkbox',
		"heading"     => 'Block with Background',
		"param_name"  => "block_with_background",
		"description" => "Make this block with background.",
		"value"       => array( 'Yes' => 'yes' )
	) );


	// VC Message
	vc_remove_param( 'vc_message', 'style' );


	// VC Button
	vc_remove_param( 'vc_button', 'icon' );

	vc_add_param( 'vc_btn', array(
		"type"        => 'checkbox',
		"heading"     => 'Bordered',
		"param_name"  => "bordered",
		"description" => "Remove the background and show only border.",
		"value"       => array( 'Yes' => 'yes' )
	) );


	// VC Text Separator
	vc_add_params( 'vc_text_separator', array(
		array(
			"type"        => "dropdown",
			"heading"     => "Separator Style",
			"param_name"  => "separator_style",
			"value"       => array(
				"Double Bordered Thick" => 'double-bordered-thick',
				"Double Bordered Thin"  => 'double-bordered-thin',
				"Double Bordered"       => 'double-bordered',
				"One Line Border"       => 'one-line-border',
			),
			"description" => "Select separator style",
			"weight"      => 1
		),
		array(
			"type"        => "textfield",
			"heading"     => "Sub Title",
			"param_name"  => "subtitle",
			"description" => "You can apply subtitle but its optional.",
			"value"       => "",
		)
	) );
}

add_action( 'vc_before_mapping', 'lab_vc_before_mapping' );

// Filter to Replace default css class for vc_row shortcode and vc_column
function laborator_css_classes_for_vc( $class_string, $tag, $atts_values = array() ) {
	// Row and inner row
	if ( $tag == 'vc_row' || $tag == 'vc_row_inner' ) {
		$class_string = str_replace( array( 'wpb_row vc_row-fluid' ), array( 'row' ), $class_string );

		// No Margin Row
		if ( isset( $atts_values['add_default_margin'] ) && $atts_values['add_default_margin'] == 'yes' ) {
			$class_string .= ' with-margin';
		}

		// Block background
		if ( isset( $atts_values['block_with_background'] ) && $atts_values['block_with_background'] == 'yes' ) {
			$class_string .= ' block-bg';
		}
	} // Column
	elseif ( $tag == 'vc_column' || $tag == 'vc_column_inner' ) {
		if ( preg_match( '/vc_span(\d+)/', $class_string, $matches ) ) {
			$span_columns = $matches[1];

			$col_type = $tag == 'vc_column' ? 'sm' : 'md';

			$class_string = str_replace( $matches[0], "col-{$col_type}-{$span_columns}", $class_string );
		}
	} // Text column
	elseif ( $tag == 'vc_column_text' ) {
		// Block background
		if ( isset( $atts_values['block_with_background'] ) && $atts_values['block_with_background'] == 'yes' ) {
			$class_string .= ' block-bg';
		}
	} // Button
	elseif ( $tag == 'vc_button' ) {
		$class_string = str_replace( array( 'wpb_button', 'wpb_button', 'wpb_btn' ), array(
			'btn',
			'',
			'btn'
		), $class_string );

		// Bordered Button
		if ( isset( $atts_values['bordered'] ) && $atts_values['bordered'] == 'yes' ) {
			$class_string .= ' btn-bordered';
		}
	} // Widget sidebar
	elseif ( $tag == 'vc_widget_sidebar' ) {
		$class_string .= ' shop_sidebar';
	} // Text separator
	elseif ( $tag == 'vc_text_separator' ) {
		$subtitle     = isset( $atts_values['subtitle'] ) ? $atts_values['subtitle'] : '';
		$accent_color = isset( $atts_values['accent_color'] ) && $atts_values['accent_color'] ? $atts_values['accent_color'] : '';

		if ( isset( $atts_values['separator_style'] ) ) {
			$class_string .= ' ' . $atts_values['separator_style'] . ( $accent_color ? ( " custom-color-" . str_replace( '#', '', $accent_color ) ) : '' );
		}

		if ( $subtitle ) {
			$class_string .= ' __' . str_replace( ' ', '-', $subtitle ) . '__';
		}
	}

	return $class_string;
}

add_filter( 'vc_shortcodes_css_class', 'laborator_css_classes_for_vc', 10, 4 );

// Oxygen VC Query Builder
function oxygen_vc_query_builder( $query ) {

	if ( class_exists( 'VcLoopQueryBuilder' ) ) {

		if ( ! class_exists( 'OxygenVcLoopQueryBuilder' ) ) {
			class OxygenVcLoopQueryBuilder extends VcLoopQueryBuilder {
				public function getQueryArgs() {
					return $this->args;
				}
			}
		}

		$query = new OxygenVcLoopQueryBuilder( VcLoopSettings::parseData( $query ) );

		return $query->getQueryArgs();
	}

	return array();
}
