<?php
/**
 *    Mobile menu
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

?>
<div class="mobile-menu hidden">

	<?php
	// Search field
	if ( get_data( 'header_menu_search' ) ) :
		?>
        <form action="<?php echo home_url(); ?>" method="get" class="search-form"
              enctype="application/x-www-form-urlencoded">

            <a href="#">
                <span class="glyphicon glyphicon-search"></span>
            </a>

            <div class="search-input-env<?php echo get_search_query() ? ' visible' : ''; ?>">
                <input type="text" class="search-input" name="s" placeholder="<?php esc_html_e( 'Search...', 'oxygen' ); ?>" value="<?php echo get_search_query( true ); ?>">
            </div>

        </form>
	<?php
	endif;

	// Nav menu
	wp_nav_menu( array(
		'theme_location' => 'main-menu',
		'container'      => '',
		'menu_class'     => 'nav'
	) );

	// Cart counter
	if ( get_data( 'cart_ribbon_show' ) && oxygen_is_shop_supported() ) :
		?>
        <a href="<?php echo wc_get_cart_url(); ?>" class="cart-items">
            <span><?php echo WC()->cart->get_cart_contents_count(); ?></span>
			<?php esc_html_e( 'Cart', 'oxygen' ); ?>
        </a>
	<?php
	endif;

	// Social networks	
	if ( get_data( 'social_mobile_menu' ) ) :
		?>
        <div class="social-networks-mobile">
			<?php echo do_shortcode( '[lab_social_networks]' ); ?>
        </div>
	<?php
	endif;

	// Top menu
	if ( get_data( 'top_menu_mobile' ) ) :
		$top_menu_args = array(
			'theme_location' => 'top-menu',
			'container'      => '',
			'menu_class'     => 'sec-nav-menu',
			'depth'          => 1,
			'echo'           => false
		);

		$top_menu = wp_nav_menu( $top_menu_args );

		?>
        <div class="top-menu-mobile">
			<?php echo $top_menu; ?>
        </div>
	<?php
	endif;
	?>
</div>
