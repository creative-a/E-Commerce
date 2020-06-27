<?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */

if ( ! get_data( 'cart_ribbon_show' ) || ! oxygen_is_shop_supported() || oxygen_woocommerce_is_catalog_mode() ) {
	return;
}

wp_enqueue_script( 'owl-carousel' );
wp_enqueue_style( 'owl-carousel-theme' );

// Eneuque Slick Carousel
oxygen_enqueue_slick_carousel();
?>
<div class="header-cart">

    <div class="col-md-10 col-sm-9">

        <div class="row cart-items">

            <div class="no-items">
				<?php _e( 'Loading cart contents...', 'oxygen' ); ?>
            </div>

        </div>

    </div>

    <div class="col-md-2 col-sm-3">

        <a class="btn btn-block btn-gray" href="<?php echo wc_get_cart_url(); ?>">
            <span class="glyphicon bucket-icon"></span>
			<?php _e( 'View Cart', 'oxygen' ); ?>
        </a>

        <a class="btn btn-block btn-default" href="<?php echo wc_get_checkout_url(); ?>">
            <span class="glyphicon cart-icon"></span>
			<?php _e( 'Checkout', 'oxygen' ); ?>
        </a>

        <div class="cart-sub-total">
			<?php _e( 'Cart subtotal', 'oxygen' ); ?>:
            <span>&hellip;</span>
        </div>

    </div>

</div>