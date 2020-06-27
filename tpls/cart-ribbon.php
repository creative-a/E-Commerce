<?php
/**
 *	Oxygen WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */

if ( oxygen_is_shop_supported() && false == oxygen_woocommerce_is_catalog_mode() && get_data( 'cart_ribbon_show' ) ) : 

	$ribbons = array(
		'cart-icon-1' => oxygen_get_theme_assets_uri() . '/images/cart-icon-1.png',
		'cart-icon-2' => oxygen_get_theme_assets_uri() . '/images/cart-icon-2.png',
		'cart-icon-3' => oxygen_get_theme_assets_uri() . '/images/cart-icon-3.png',
		'cart-icon-4' => oxygen_get_theme_assets_uri() . '/images/cart-icon-4.png',
	);
	
	$cart_ribbon_image = $ribbons['cart-icon-1'];
	
	if ( preg_match( '/cart-icon-[0-9]+/', wp_basename( get_data( 'cart_ribbon_image' ) ), $cart_ribbon_icon ) ) {
		$cart_ribbon_image = $ribbons[ $cart_ribbon_icon[0] ];
	}
	
	?>
	<div class="cart-ribbon<?php echo apply_filters( 'oxygen_cart_ribbon_direct_link', false ) ? ' direct-link' : ''; ?>">
		<a href="<?php echo wc_get_cart_url(); ?>">
			<span class="cart_content">
				<span class="bucket" style="background-image: url(<?php echo $cart_ribbon_image; ?>);"></span>
				<span class="number">&hellip;</span>
			</span>
	
			<span class="bucket-bottom"></span>
		</a>
	</div>
	<?php 
		
endif;
