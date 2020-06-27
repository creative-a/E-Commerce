<?php
/**
 *    Cart
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Cart header
 */
if ( ! function_exists( 'oxygen_woocommerce_cart_page_heading' ) ) {

	function oxygen_woocommerce_cart_page_heading() {
		$cart_contents_count = WC()->cart->cart_contents_count;
		?>
        <header class="woocommerce-products-header">
            <h1><?php esc_html_e( 'Shopping Cart', 'oxygen' ); ?></h1>
            <span class="small-title"><?php echo sprintf( _n( '%d item', '%d items', $cart_contents_count, 'oxygen' ), $cart_contents_count ); ?></span>
        </header>
		<?php
	}
}

add_action( 'woocommerce_before_cart', 'oxygen_woocommerce_cart_page_heading', 10 );

/**
 * Empty Cart header
 */
if ( ! function_exists( 'oxygen_woocommerce_cart_empty_page_heading' ) ) {

	function oxygen_woocommerce_cart_empty_page_heading() {
		$cart_contents_count = WC()->cart->cart_contents_count;
		?>
        <header class="woocommerce-products-header cart-empty">
            <h1><?php esc_html_e( 'Shopping Cart', 'oxygen' ); ?></h1>
            <span class="small-title"><?php echo wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your cart is currently empty', 'oxygen' ) ) ) ?></span>
        </header>
		<?php
	}
}

remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
add_action( 'woocommerce_cart_is_empty', 'oxygen_woocommerce_cart_empty_page_heading', 5 );

/**
 * Cart table and collaterals wrapper
 */
if ( ! function_exists( 'oxygen_woocommerce_cart_wrapper_start' ) ) {

	function oxygen_woocommerce_cart_wrapper_start() {
		echo '<div class="cart-wrapper">';
	}
}

add_action( 'woocommerce_before_cart', 'oxygen_woocommerce_cart_wrapper_start', 15 );

/**
 * Cart table and collaterals wrapper (end)
 */
if ( ! function_exists( 'oxygen_woocommerce_cart_wrapper_end' ) ) {

	function oxygen_woocommerce_cart_wrapper_end() {
		echo '</div>';
	}
}

add_action( 'woocommerce_after_cart', 'oxygen_woocommerce_cart_wrapper_end', 10 );

/**
 * Cart item thumbnail
 */
if ( ! function_exists( 'oxygen_woocommerce_cart_product_thumbnail' ) ) {

	function oxygen_woocommerce_cart_product_thumbnail( $image, $cart_item, $cart_item_key ) {
		return oxygen_image_placeholder_wrap_element( $image );
	}
}

add_filter( 'woocommerce_cart_item_thumbnail', 'oxygen_woocommerce_cart_product_thumbnail', 10, 3 );

/**
 * Cart items in cart collaterals table
 */
if ( ! function_exists( 'oxygen_woocommerce_display_cart_totals_items' ) ) {

	function oxygen_woocommerce_display_cart_totals_items() {

		?>
        <ul class="cart-items">
			<?php

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :

				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) :

					?>
                    <li>
                        <div class="name">
							<?php
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
							?>
                        </div>
                        <div class="price">
							<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
							?>
                        </div>
                    </li>
				<?php

				endif;

			endforeach;

			?>
        </ul>
		<?php
	}
}

add_action( 'oxygen_woocommerce_cart_totals_items', 'oxygen_woocommerce_display_cart_totals_items', 10 );

/**
 * Update cart and checkout link
 */
if ( ! function_exists( 'oxygen_woocommerce_cart_update_cart_buttons' ) ) {

	function oxygen_woocommerce_cart_update_cart_buttons() {

		?>
        <div class="cart-update-buttons">
            <a href="<?php echo esc_url( wc_get_cart_url() ); ?>"
               class="cart-update-buttons--update-cart button disabled">
                <i class="entypo-pencil"></i>
				<?php _e( 'Update Cart', 'oxygen' ); ?>
            </a>

            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>"
               class="cart-update-buttons--checkout button alt wc-forward">
                <i class="entypo-basket"></i>
				<?php esc_html_e( 'Checkout', 'oxygen' ); ?>
            </a>
        </div>
		<?php
	}
}

add_action( 'woocommerce_cart_collaterals', 'oxygen_woocommerce_cart_update_cart_buttons', 20 );

/**
 * Form Coupon on Cart Page
 */
if ( ! function_exists( 'oxygen_woocommerce_cart_form_coupon' ) ) {

	function oxygen_woocommerce_cart_form_coupon() {

		if ( wc_coupons_enabled() ) :
			?>
            <div class="cart-coupon">

                <label for="coupon_code_alt"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>

                <div class="coupon">
                    <input type="text" name="coupon_code_alt" class="input-text" id="coupon_code_alt" value=""
                           placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>"/>
                    <button type="submit" class="button" name="apply_coupon_alt"
                            value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
                </div>

            </div>
		<?php
		endif;
	}
}

add_action( 'woocommerce_cart_collaterals', 'oxygen_woocommerce_cart_form_coupon', 30 );

/**
 * Shipping calculator on cart page
 */
if ( ! function_exists( 'oxygen_woocommerce_cart_shipping_calculator' ) ) {

	function oxygen_woocommerce_cart_shipping_calculator() {

		if ( 'no' === get_option( 'woocommerce_enable_shipping_calc' ) || ! WC()->cart->needs_shipping() ) {
			return;
		}

		?>
        <div class="shipping-calculator-container">
			<?php woocommerce_shipping_calculator(); ?>
        </div>
		<?php
	}
}

add_action( 'woocommerce_after_cart_table', 'oxygen_woocommerce_cart_shipping_calculator', 10 );
