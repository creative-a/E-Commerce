<?php
/**
 *    Other
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Cart Fragments for Minicart
 */
function oxygen_woocommerce_woocommerce_add_to_cart_fragments( $fragments_arr ) {
	$fragments_arr['labMiniCart']         = oxygen_woocommerce_get_mini_cart_contents();
	$fragments_arr['labMiniCartCount']    = WC()->cart->cart_contents_count;
	$fragments_arr['labMiniCartSubtotal'] = WC()->cart->get_cart_subtotal();

	return $fragments_arr;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'oxygen_woocommerce_woocommerce_add_to_cart_fragments' );

/**
 * Oxygen Minicart (accessible from cart ribbon)
 */
if ( ! function_exists( 'oxygen_woocommerce_get_mini_cart_contents' ) ) {

	function oxygen_woocommerce_get_mini_cart_contents() {
		ob_start();

		$cart = array_reverse( WC()->cart->get_cart() );

		foreach ( $cart as $cart_item_key => $cart_item ) :

			$product_id = $cart_item['product_id'];

			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			$quantity = $cart_item['quantity'];

			?>
            <div class="cart-item-column">

                <div class="cart-item">

                    <a href="<?php echo $_product->get_permalink(); ?>"><?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'shop-thumb-2' ), $cart_item, $cart_item_key );
						echo oxygen_image_placeholder_wrap_element( $thumbnail );
						?></a>

                    <div class="details">
                        <a href="<?php echo $_product->get_permalink(); ?>"
                           class="title"><?php echo get_the_title( $product_id ); ?></a>

                        <div class="price-quantity">
							<?php if ( $price_html = $_product->get_price_html() ) : ?>
                                <span class="price"><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?></span>
							<?php endif; ?>

                            <span class="quantity"><?php printf( __( 'Q: %d', 'oxygen' ), $quantity ); ?></span>
                        </div>
                    </div>

                </div>

            </div>
		<?php

		endforeach;

		return ob_get_clean();
	}
}

/**
 * Cart item image
 */
if ( ! function_exists( 'oxygen_woocommerce_cart_item_thumbnail' ) ) {

	function oxygen_woocommerce_cart_item_thumbnail( $image, $cart_item, $cart_item_key ) {
		$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		return $_product->get_image( apply_filters( 'lab_wc_cart_image_size', 'shop-thumb-2' ) );
	}
}

add_filter( 'woocommerce_cart_item_thumbnail', 'oxygen_woocommerce_cart_item_thumbnail', 10, 3 );

/**
 * Show rating under product name
 */
if ( ! function_exists( 'oxygen_woocommerce_cart_item_after_name_show_rating' ) ) {

	function oxygen_woocommerce_cart_item_after_name_show_rating( $product ) {
		echo oxygen_woocommerce_get_product_rating_html( $product->get_average_rating() );
	}
}

add_action( 'oxygen_woocommerce_cart_item_after_name', 'oxygen_woocommerce_cart_item_after_name_show_rating', 10, 1 );

/**
 * Custom YITH Wishlist Button for Oxygen theme
 */
if ( ! function_exists( 'oxygen_yith_wcwl_add_to_wishlist' ) ) {

	function oxygen_yith_wcwl_add_to_wishlist() {
		global $product;

		$id   = $product->get_id();
		$type = $product->get_type();

		echo do_shortcode( "<div class=\"yith-add-to-wishlist\">[yith_wcwl_add_to_wishlist product_id='{$id}' product_type='{$type}' label='' browse_wishlist_text='' already_in_wishslist_text='']</div>" );
	}
}

/**
 * Check if is YITH wishlist page
 */
function oxygen_woocommerce_is_yith_wishlist_page() {
	if ( function_exists( 'yith_wcwl_object_id' ) && is_page() ) {
		$wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );

		return $wishlist_page_id && is_page( $wishlist_page_id );
	}

	return false;
}

/**
 * Yith Infinite Scrolling selectors and containers
 */
if ( defined( 'YITH_INFS' ) ) {

	function oxygen_yith_infinite_scroll_selectors_config( $opts ) {
		$opts['yith-infs-navselector']     = '.woocommerce-pagination';
		$opts['yith-infs-nextselector']    = '.woocommerce-pagination .next';
		$opts['yith-infs-itemselector']    = '.type-product';
		$opts['yith-infs-contentselector'] = '#main';

		return $opts;
	}

	add_filter( 'pre_option_yit_infs_options', 'oxygen_yith_infinite_scroll_selectors_config', 100 );
}
