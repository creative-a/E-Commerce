<?php
/**
 *    Checkout
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Checkout login and coupon form wrapper (start)
 */
if ( ! function_exists( 'oxygen_woocommerce_checkout_login_coupon_wrapper_start' ) ) {

	function oxygen_woocommerce_checkout_login_coupon_wrapper_start() {

		?>
        <div class="login-coupon-wrapper">
		<?php
	}
}

add_action( 'woocommerce_before_checkout_form', 'oxygen_woocommerce_checkout_login_coupon_wrapper_start', 5 );

/**
 * Checkout login and coupon form wrapper (end)
 */
if ( ! function_exists( 'oxygen_woocommerce_checkout_login_coupon_wrapper_end' ) ) {

	function oxygen_woocommerce_checkout_login_coupon_wrapper_end() {

		?>
        </div>
		<?php
	}
}

add_action( 'woocommerce_before_checkout_form', 'oxygen_woocommerce_checkout_login_coupon_wrapper_end', 10 );

/**
 * Checkout login form lock icon
 */
if ( ! function_exists( 'oxygen_woocommerce_checkout_login_form_icon' ) ) {

	function oxygen_woocommerce_checkout_login_form_icon( $message ) {
		return '<i class="entypo-lock"></i> ' . $message;
	}
}

add_filter( 'woocommerce_checkout_login_message', 'oxygen_woocommerce_checkout_login_form_icon', 10 );

/**
 * Checkout login form lock icon
 */
if ( ! function_exists( 'oxygen_woocommerce_checkout_coupon_form_icon' ) ) {

	function oxygen_woocommerce_checkout_coupon_form_icon( $message ) {
		return '<i class="entypo-tag"></i> ' . $message;
	}
}

add_filter( 'woocommerce_checkout_coupon_message', 'oxygen_woocommerce_checkout_coupon_form_icon', 10 );

/**
 * Payment methods heading title
 */
if ( ! function_exists( 'oxygen_woocommerce_checkout_payment_method_title' ) ) {

	function oxygen_woocommerce_checkout_payment_method_title() {

		?>
        <div class="checkout-payment-method-title">
            <h3><?php _e( 'Payment method', 'oxygen' ); ?></h3>
        </div>
		<?php
	}
}

add_action( 'woocommerce_review_order_before_payment', 'oxygen_woocommerce_checkout_payment_method_title', 10 );

/**
 * Prepend "Quantity" word in cart items of shop_table
 */
if ( ! function_exists( 'oxygen_woocommerce_checkout_cart_item_quantity' ) ) {

	function oxygen_woocommerce_checkout_cart_item_quantity( $quantity ) {
		return '<div class="cart-item-quantity">' . esc_html__( 'Quantity', 'woocommerce' ) . $quantity . '</div>';
	}
}

add_action( 'woocommerce_checkout_cart_item_quantity', 'oxygen_woocommerce_checkout_cart_item_quantity', 10 );

/**
 * Thank you page, BACS wrapper
 */
if ( ! function_exists( 'oxygen_woocommerce_checkout_bacs_wrapper_start' ) ) {

	function oxygen_woocommerce_checkout_bacs_wrapper_start() {
		echo '<div class="bacs-wrapper">';
	}
}

add_action( 'woocommerce_thankyou_bacs', 'oxygen_woocommerce_checkout_bacs_wrapper_start', 10 );

/**
 * Thank you page, BACS wrapper (end)
 */
if ( ! function_exists( 'oxygen_woocommerce_checkout_bacs_wrapper_end' ) ) {

	function oxygen_woocommerce_checkout_bacs_wrapper_end() {
		echo '</div>';
	}
}

add_action( 'woocommerce_thankyou', 'oxygen_woocommerce_checkout_bacs_wrapper_end', 1 );
