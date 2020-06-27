<?php
/**
 *    Core
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * WooCommerce Init
 */
function oxygen_woocommerce_init() {

	// Product loop description toggles
	if ( get_data( 'shop_product_category_listing' ) ) {
		add_action( 'oxygen_woocommerce_after_loop_item_title', 'oxygen_woocommerce_product_loop_categories', 10 );
	}

	if ( oxygen_woocommerce_show_product_loop_price() ) {
		add_action( 'oxygen_woocommerce_after_loop_item_title', 'woocommerce_template_loop_price', 30 );
	}

	if ( oxygen_woocommerce_show_product_loop_add_to_cart() ) {
		add_action( 'oxygen_woocommerce_after_loop_item_title', 'woocommerce_template_loop_add_to_cart', 40 );
	}

	// Sorting and results counter
	if ( ! get_data( 'shop_sorting_show' ) ) {
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	}

	// Product quickview
	if ( oxygen_woocommerce_quickview_enabled() ) {
		add_action( 'woocommerce_after_shop_loop_item', 'oxygen_woocommerce_product_quickview_entry' );
	}

	// Product classes
	add_action( 'woocommerce_post_class', 'oxygen_woocommerce_product_class', 10 );

	// When catalog mode is enabled
	if ( oxygen_woocommerce_is_catalog_mode() ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

		if ( oxygen_woocommerce_catalog_mode_hide_prices() ) {
			add_filter( 'woocommerce_get_price_html', '__return_empty_string' );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );
		}
	}

	// Hide product meta
	if ( ! get_data( 'shop_single_meta_show' ) ) {
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	}

	// Additional variation images plugin does not supports product images layout of Oxygen
	$active_plugins                      = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
	$oxygen_gallery_drop_support_plugins = array(
		'woocommerce-additional-variation-images/woocommerce-additional-variation-images.php',
		'yith-woocommerce-zoom-magnifier/init.php'
	);

	if ( array_intersect( $active_plugins, $oxygen_gallery_drop_support_plugins ) ) {
		add_filter( 'oxygen_woocommerce_use_custom_product_image_gallery_layout', '__return_false' );
	}

	// Replace product image gallery
	if ( oxygen_woocommerce_use_custom_product_image_gallery_layout() ) {
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		add_action( 'woocommerce_before_single_product_summary', 'oxygen_woocommerce_show_product_images', 25 );
		add_filter( 'woocommerce_available_variation', 'oxygen_woocommerce_available_variation', 10, 3 );
	}
}

add_action( 'woocommerce_init', 'oxygen_woocommerce_init' );

/**
 * Remove WooCommerce styles and scripts.
 */
function oxygen_woocommerce_woocommerce_remove_lightboxes() {
	// Styles
	wp_dequeue_style( 'woocommerce_prettyPhoto_css' );

	// Scripts
	wp_dequeue_script( 'prettyPhoto' );
	wp_dequeue_script( 'prettyPhoto-init' );
	wp_dequeue_script( 'fancybox' );
	wp_dequeue_script( 'enable-lightbox' );
}

add_action( 'wp_enqueue_scripts', 'oxygen_woocommerce_woocommerce_remove_lightboxes', 99 );

/**
 * Remove breadcrumb from shop archive page
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/**
 * Hide default WooCommerce
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Archive with sidebar wrapper (start)
 */
if ( ! function_exists( 'oxygen_woocommerce_archive_sidebar_wrapper_start' ) ) {

	function oxygen_woocommerce_archive_sidebar_wrapper_start() {
		$sidebar_position = oxygen_woocommerce_get_sidebar_position();
		$classes          = array( 'products-archive' );

		if ( $sidebar_position ) {
			$classes[] = 'products-archive--has-sidebar';
			$classes[] = 'products-archive--sidebar-' . $sidebar_position;
		}

		?>
        <div class="clear"></div>
        <div <?php oxygen_class_attr( $classes ); ?>>
        <div class="products-archive--products">
		<?php
	}
}

add_action( 'woocommerce_before_main_content', 'oxygen_woocommerce_archive_sidebar_wrapper_start', 50 );

/**
 * Archive with sidebar wrapper (end)
 */
if ( ! function_exists( 'oxygen_woocommerce_archive_sidebar_wrapper_end' ) ) {

	function oxygen_woocommerce_archive_sidebar_wrapper_end() {

		?>
        </div>

		<?php if ( oxygen_woocommerce_get_sidebar_position() ) : ?>
            <div class="products-archive--sidebar">
				<?php
				get_sidebar( 'shop' );
				?>
            </div>
		<?php endif; ?>
        </div>
		<?php
	}
}

add_action( 'woocommerce_after_main_content', 'oxygen_woocommerce_archive_sidebar_wrapper_end', 5 );

/**
 * Shop archive header
 */
if ( ! function_exists( 'oxygen_woocommerce_archive_header' ) ) {

	function oxygen_woocommerce_archive_header() {

		if ( get_data( 'shop_title_show' ) && ( is_shop() || is_product_taxonomy() ) ) :
			?>
            <header class="woocommerce-products-header">
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                    <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
				<?php endif; ?>

				<?php
				/**
				 * Hook: woocommerce_archive_description.
				 *
				 * @hooked woocommerce_taxonomy_archive_description - 10
				 * @hooked woocommerce_product_archive_description - 10
				 */
				do_action( 'woocommerce_archive_description' );
				?>
            </header>
		<?php
		endif;
	}
}

add_action( 'woocommerce_before_main_content', 'oxygen_woocommerce_archive_header', 40 );

/**
 * Shop footer widgets
 */
if ( ! function_exists( 'oxygen_woocommerce_display_footer_widgets' ) ) {

	function oxygen_woocommerce_display_footer_widgets() {

		// Shop footer widgets
		if ( oxygen_woocommerce_loop_footer_widgets() && is_active_sidebar( 'shop_footer_sidebar' ) && ( is_shop() || is_product_taxonomy() || is_product() ) ) :

			?>
            <div class="products-archive--footer-widgets">
				<?php
				// Footer widgets
				dynamic_sidebar( 'shop_footer_sidebar' );
				?>
            </div>
		<?php

		endif;
	}
}

add_action( 'woocommerce_after_main_content', 'oxygen_woocommerce_display_footer_widgets', 100 );

/**
 * Get product rating in HTML
 */
if ( ! function_exists( 'oxygen_woocommerce_get_product_rating_html' ) ) {

	function oxygen_woocommerce_get_product_rating_html( $rating ) {
		$rating_int       = intval( $rating );
		$rating_classes   = array( 'rating' );
		$rating_classes[] = "filled-{$rating_int}";

		if ( $rating - $rating_int > 0.49 ) {
			$rating_classes[] = 'and-half';
		}

		ob_start();
		?>
        <div <?php oxygen_class_attr( $rating_classes ); ?>>
            <span class="glyphicon glyphicon-star star-1"></span>
            <span class="glyphicon glyphicon-star star-2"></span>
            <span class="glyphicon glyphicon-star star-3"></span>
            <span class="glyphicon glyphicon-star star-4"></span>
            <span class="glyphicon glyphicon-star star-5"></span>
        </div>
		<?php

		return ob_get_clean();
	}
}

/**
 * Show product rating if enabled
 */
if ( ! function_exists( 'oxygen_woocommerce_show_product_rating' ) ) {

	function oxygen_woocommerce_show_product_rating() {
		global $product;

		if ( oxygen_woocommerce_rating_visible() ) {
			echo oxygen_woocommerce_get_product_rating_html( $product->get_average_rating() );
		}
	}
}

/**
 * Replace WooCommerce Rating to stars
 */
if ( ! function_exists( 'oxygen_woocommerce_product_get_rating_html' ) ) {

	function oxygen_woocommerce_product_get_rating_html( $html, $rating, $count ) {

		return oxygen_woocommerce_get_product_rating_html( $rating );
	}
}

add_filter( 'woocommerce_product_get_rating_html', 'oxygen_woocommerce_product_get_rating_html', 10, 3 );

/**
 * Product classes
 */
function oxygen_woocommerce_product_class( $classes ) {
	global $product;

	// Products per row in mobile devices
	if ( 'two' == get_data( 'shop_products_mobile_two_per_row' ) ) {
		$classes[] = 'mobile-cols-2';
	}

	// Yith Wishlist
	if ( oxygen_is_yith_wishlist_supported() && ! is_product() ) {

		$product_id        = $product->get_id();
		$default_wishlists = is_user_logged_in() ? YITH_WCWL()->get_wishlists( array( 'is_default' => true ) ) : false;

		if ( ! empty( $default_wishlists ) ) {
			$default_wishlist = $default_wishlists[0]['ID'];
		} else {
			$default_wishlist = false;
		}

		$exists = YITH_WCWL()->is_product_in_wishlist( $product_id, oxygen_yith_wishlist_get_default_wishlist_id() );

		if ( $exists ) {
			$classes[] = 'yith-added-to-wishlist';
		}
	}

	return $classes;
}

/**
 * Pagination next and previous text
 */
function oxygen_woocommerce_pagination_args( $args ) {
	$args['prev_text'] = esc_html__( 'Previous', 'woocommerce' );
	$args['next_text'] = esc_html__( 'Next', 'woocommerce' );

	return $args;
}

add_filter( 'woocommerce_pagination_args', 'oxygen_woocommerce_pagination_args' );

/**
 * Product category classes
 */
function oxygen_woocommerce_product_cat_class( $classes ) {
	if ( 'two' == get_data( 'shop_categories_mobile_per_row' ) ) {
		$classes[] = 'mobile-cols-2';
	}

	return $classes;
}

add_filter( 'product_cat_class', 'oxygen_woocommerce_product_cat_class' );

/**
 * Archive title toggle
 */
function laborator_woocommerce_show_page_title() {
	return get_data( 'shop_title_show' );
}

add_filter( 'woocommerce_show_page_title', 'laborator_woocommerce_show_page_title' );

/**
 * Login form wrapper (start)
 */
if ( ! function_exists( 'oxygen_woocommerce_myaccount_login_wrapper_start' ) ) {

	function oxygen_woocommerce_myaccount_login_wrapper_start() {
		$classes = array(
			'myaccount-login-wrapper',
		);

		if ( 'yes' == get_option( 'woocommerce_enable_myaccount_registration' ) ) {
			$classes[] = 'registration-allowed';
		} else {
			$classes[] = 'login-only';
		}

		?>
        <div <?php oxygen_class_attr( $classes ); ?>>
		<?php
	}
}

add_action( 'woocommerce_before_customer_login_form', 'oxygen_woocommerce_myaccount_login_wrapper_start', 10 );

/**
 * Login form wrapper (end)
 */
if ( ! function_exists( 'oxygen_woocommerce_myaccount_login_wrapper_end' ) ) {

	function oxygen_woocommerce_myaccount_login_wrapper_end() {
		?>
        </div>
		<?php
	}
}

add_action( 'woocommerce_after_customer_login_form', 'oxygen_woocommerce_myaccount_login_wrapper_end', 10 );

/**
 * Sidebar position, returns false if sidebar is set to hide
 */
function oxygen_woocommerce_get_sidebar_position() {
	$shop_sidebar = is_product() ? get_data( 'shop_single_sidebar' ) : get_data( 'shop_sidebar' );

	if ( in_array( $shop_sidebar, array( 'Show Sidebar on Left', 'Show Sidebar on Right' ) ) ) {
		return 'Show Sidebar on Left' == $shop_sidebar ? 'left' : 'right';
	}

	return false;
}

/**
 * YITH Wishlist Supported
 */
function oxygen_is_yith_wishlist_supported() {
	return oxygen_is_plugin_active( 'yith-woocommerce-wishlist/init.php' ) || defined( 'YITH_WCWL' );
}

/**
 * Check if rating is visible
 */
function oxygen_woocommerce_rating_visible() {
	global $product;

	return get_data( 'shop_rating_show' ) && 'no' != get_option( 'woocommerce_enable_review_rating' ) && $product->get_average_rating() > 0;
}

/**
 * Catalog mode check
 */
function oxygen_woocommerce_is_catalog_mode() {
	return get_data( 'shop_catalog_mode' ) == true;
}

/**
 * Catalog mode hide prices
 */
function oxygen_woocommerce_catalog_mode_hide_prices() {
	return oxygen_woocommerce_is_catalog_mode() && get_data( 'shop_catalog_mode_hide_prices' ) == true;
}

/**
 * Use Oxygens's default product gallery layout
 */
function oxygen_woocommerce_use_custom_product_image_gallery_layout() {
	return apply_filters( 'oxygen_woocommerce_use_custom_product_image_gallery_layout', true );
}

/**
 * Check if quickview is enabled
 */
function oxygen_woocommerce_quickview_enabled() {
	return get_data( 'shop_quickview' );
}

/**
 * Check if product price could be shown in loop
 */
function oxygen_woocommerce_show_product_loop_price() {
	if ( oxygen_woocommerce_is_catalog_mode() && oxygen_woocommerce_catalog_mode_hide_prices() ) {
		return false;
	}

	return get_data( 'shop_product_price_listing' );
}

/**
 * Check if product add to cart link could be shown in loop
 */
function oxygen_woocommerce_show_product_loop_add_to_cart() {
	if ( oxygen_woocommerce_is_catalog_mode() ) {
		return false;
	}

	return get_data( 'shop_add_to_cart_listing' );
}

/**
 * Get category columns number
 */
function oxygen_woocommerce_get_category_columns() {
	$count   = 4;
	$per_row = get_data( 'shop_categories_per_row' );

	switch ( $per_row ) {
		case '1 category per row' :
			$count = 1;
			break;

		case '2 categories per row' :
			$count = 2;
			break;

		case '3 categories per row' :
			$count = 3;
			break;

		case '4 categories per row' :
			$count = 4;
			break;

		case '5 categories per row' :
			$count = 5;
			break;

		case '6 categories per row' :
			$count = 6;
			break;
	}

	return $count;
}

/**
 * Check if single product image magnifier is enabled
 */
function oxygen_woocommerce_single_product_image_zoom() {
	return '0' !== get_data( 'shop_magnifier' );
}

/**
 * Check if single product image lightbox is enabled
 */
function oxygen_woocommerce_single_product_image_lightbox() {
	return '0' !== get_data( 'shop_single_lightbox' );
}

/**
 * Product gallery image size
 */
function oxygen_woocommerce_get_thumbnail_image_size() {
	return apply_filters( 'woocommerce_gallery_thumbnail_size', 'woocommerce_gallery_thumbnail' );
}

/**
 * Product single image size
 */
function oxygen_woocommerce_get_single_image_size() {
	$deprecated_size_filter = apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' );

	return apply_filters( 'single_product_archive_thumbnail_size', $deprecated_size_filter );
}

/**
 * Check if shop footer widgets are visible
 */
function oxygen_woocommerce_loop_footer_widgets() {
	return '0' != get_data( 'shop_sidebar_footer' );
}
