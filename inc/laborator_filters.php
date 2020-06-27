<?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * WooCommerce Styles
 */
add_filter( 'woocommerce_enqueue_styles', '__return_false' );

/**
 * Laborator Theme Options Translate
 */
function laborator_add_menu_classes( $items ) {
	global $submenu;

	foreach ( $submenu as $menu_id => $sub ) {
		if ( $menu_id == 'laborator_options' ) {
			$submenu[ $menu_id ][0][0] = 'Theme Options';
		}
	}

	return $submenu;
}

add_filter( 'admin_menu', 'laborator_add_menu_classes', 100 );

/**
 * Excerpt Length & More
 */
add_filter( 'excerpt_length', oxygen_hook_return_value( get_data( 'blog_sidebar_position' ) == 'Hide' ? 80 : 38 ) );
add_filter( 'excerpt_more', oxygen_hook_return_value( '&hellip;' ) );

/**
 * Render Comment Fields
 */
function laborator_comment_fields( $fields ) {
	foreach ( $fields as $field_type => $field_html ) {
		preg_match( "/<label(.*?)>(.*?)\<\/label>/", $field_html, $html_label );
		preg_match( "/<input(.*?)\/>/", $field_html, $html_input );

		$html_label = strip_tags( $html_label[2] );
		$html_input = $html_input[0];

		$html_input = str_replace( "<input", '<input class="form-control" placeholder="' . esc_attr( $html_label ) . '" ', $html_input );
		$html_label = str_replace( '*', '<span class="red">*</span>', $html_label );

		$fields[ $field_type ] = "<div class=\"col-lg-4 mobile-padding\">
			<label>" . $html_label . "</label>
			{$html_input}
		</div>";
	}

	return $fields;
}

/**
 * Body Class
 */
function oxygen_body_class( $classes ) {

	// Right sidemenu
	if ( 'right' == get_data( 'sidebar_menu_position' ) ) {
		$classes[] = 'right-sidebar';
	}

	if ( in_array( HEADER_TYPE, array( 2, 3, 4 ) ) ) {
		$classes[] = 'oxygen-top-menu';

		if ( HEADER_TYPE == 3 ) {
			$classes[] = 'top-header-flat';
		}

		if ( HEADER_TYPE == 4 ) {
			$classes[] = 'top-header-center';
		}

		$classes[] = 'ht-' . HEADER_TYPE;

		if ( 'left' == get_data( 'cart_ribbon_position' ) ) {
			$classes[] = 'ribbon-left';
		}
	} else if ( HEADER_TYPE == 1 ) {
		$classes[] = 'oxygen-sidebar-menu ht-1';
	}

	if ( get_data( 'header_sticky_menu' ) ) {
		$classes[] = 'sticky-menu';
	}

	if ( ! get_data( 'cart_ribbon_show' ) ) {
		$classes[] = 'cart-ribbon-hidden';
	}

	// Catalog mode
	if ( function_exists( 'oxygen_woocommerce_is_catalog_mode' ) && oxygen_woocommerce_is_catalog_mode() ) {
		$classes[] = 'woocommerce-catalog-mode';
	}

	return $classes;
}

add_filter( 'body_class', 'oxygen_body_class', 100 );

/**
 * Add Do-shortcode for text widgets
 */
function oxygen_widget_text_do_shortcodes( $text ) {
	return do_shortcode( $text );
}

add_filter( 'widget_text', 'oxygen_widget_text_do_shortcodes', 100 );

/**
 * Shortcode for Social Networks [lab_social_networks]
 */
function oxygen_shortcode_lab_social_networks( $atts = array(), $content = '' ) {
	$social_order = get_data( 'social_order' );

	$social_order_list = array(
		'fb'  => array( 'title' => 'Facebook', 'icon' => 'fa-facebook' ),
		'tw'  => array( 'title' => 'Twitter', 'icon' => 'fa-twitter' ),
		'lin' => array( 'title' => 'LinkedIn', 'icon' => 'fa-linkedin' ),
		'yt'  => array( 'title' => 'YouTube', 'icon' => 'fa-youtube' ),
		'vm'  => array( 'title' => 'Vimeo', 'icon' => 'fa-vimeo' ),
		'drb' => array( 'title' => 'Dribbble', 'icon' => 'fa-dribbble' ),
		'ig'  => array( 'title' => 'Instagram', 'icon' => 'fa-instagram' ),
		'pi'  => array( 'title' => 'Pinterest', 'icon' => 'fa-pinterest' ),
		'vk'  => array( 'title' => 'VKontakte', 'icon' => 'fa-vk' ),
		'tu'  => array( 'title' => 'Tumblr', 'icon' => 'fa-tumblr' ),
	);


	$html = '<ul class="social-networks">';

	foreach ( $social_order['visible'] as $key => $title ) {
		if ( $key == 'placebo' || ! isset( $social_order_list[ $key ] ) ) {
			continue;
		}

		$sn = $social_order_list[ $key ];

		$html .= '<li>';
		$html .= '<a href="' . get_data( "social_network_link_{$key}" ) . '" target="_blank" class="icon-' . ( str_replace( 'fa-', 'social-', $sn['icon'] ) ) . '">';
		$html .= '<i class="fa ' . $sn['icon'] . '"></i>';
		$html .= '</a>';
		$html .= '</li>';
	}

	$html .= '</ul>';


	return $html;

}

add_shortcode( 'lab_social_networks', 'oxygen_shortcode_lab_social_networks' );

/**
 * Skin Compiler
 */
function oxygen_custom_skin_compiler( $data ) {
	if ( isset( $data['use_custom_skin'] ) && $data['use_custom_skin'] ) {
		try {
			oxygen_custom_skin_compile( array(
				'link-color'       => $data['custom_skin_main_color'],
				'menu-link'        => $data['custom_skin_menu_link_color'],
				'background-color' => $data['custom_skin_background_color'],
			) );
		} catch ( Exception $e ) {
		}
	}

	return $data;
}

add_filter( 'of_options_before_save', 'oxygen_custom_skin_compiler' );

/**
 * Testimonial Thumbnail
 */
function laborator_testimonial_featured_image_column( $columns ) {
	if ( lab_get( 'post_type' ) == 'testimonial' ) {
		$columns_new = array(
			'cb'                         => $columns['cb'],
			'testimonial_featured_image' => 'Image'
		);

		$columns = array_merge( $columns_new, $columns );
	}

	return $columns;
}

function laborator_testimonial_featured_image_column_content( $column_name, $id ) {
	if ( $column_name === 'testimonial_featured_image' ) {
		echo '<center>';

		if ( has_post_thumbnail() ) {
			echo oxygen_get_attachment_image( get_post_thumbnail_id(), array( 144, 144 ) );
		} else {
			echo "<small>No Image</small>";
		}

		echo '</center>';
	}
}

add_filter( 'manage_posts_columns', 'laborator_testimonial_featured_image_column', 5 );
add_filter( 'manage_pages_columns', 'laborator_testimonial_featured_image_column', 5 );

add_action( 'manage_posts_custom_column', 'laborator_testimonial_featured_image_column_content', 5, 2 );
add_action( 'manage_pages_custom_column', 'laborator_testimonial_featured_image_column_content', 5, 2 );

/**
 * Disable Oxygen Open Graph data generation when Yoast is enabled
 */
if ( defined( 'WPSEO_VERSION' ) ) {
	$social = WPSEO_Options::get_option( 'wpseo_social' );

	if ( isset( $social['opengraph'] ) ) {
		add_filter( 'oxygen_open_graph_meta', '__return_false' );
	}
}

/**
 * Privacy policy shortcode
 */
function oxygen_privacy_policy_shortcode( $atts, $content = '' ) {
	// Shortcode atts
	extract( shortcode_atts( array(
		'title' => '',
		'link'  => '',
	), $atts, 'oxygen_privacy_policy' ) );

	$privacy_policy_url = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '';
	$link               = ! empty( $link ) ? $link : $privacy_policy_url;

	return sprintf( '<a href="%s" class="privacy-policy-link" target="_blank">%s</a>', esc_url( $link ), esc_html( $title ) );
}

add_shortcode( 'oxygen_privacy_policy', 'oxygen_privacy_policy_shortcode' );
