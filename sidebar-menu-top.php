<?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */
defined( 'ABSPATH' ) || exit;

global $use_uploaded_logo, $custom_logo_image, $custom_logo_image_responsive, $custom_logo_max_width, $has_responsive_image;

// Menu Items
$nav_menu_locations = get_theme_mod( 'nav_menu_locations' );

$main_menu = OXYGEN_MENU_MAIN;
$top_menu  = OXYGEN_MENU_TOP;

$has_megamenu = class_exists( 'UberMenuStandard' );

if ( $has_megamenu ) {
	$has_megamenu = in_array( 'main-menu', get_option( 'wp-mega-menu-nav-locations' ) );
}

if ( ! isset( $nav_menu_locations['main-menu'] ) || $nav_menu_locations['main-menu'] == 0 ) {
	$main_menu = preg_replace( '/^<div class="nav">\s*<ul>/', '<div class="nav-container"><ul class="nav">', $main_menu );
	$main_menu = str_replace( array(
		'page_item',
		'class=\'children\'',
		'menu-item_has_children'
	), array(
		'page_item menu-item',
		'class="sub-menu"',
		'menu-item_has_children menu-item-has-children'
	), $main_menu );
}

$top_menu_social = get_data( 'top_menu_social' );

if ( $top_menu_social ) {
	$top_menu .= '<div class="top-menu-social">' . do_shortcode( '[lab_social_networks]' ) . '</div>';
}

// Top menu is not assigned
if ( empty( $nav_menu_locations['top-menu'] ) ) {
	$top_menu = '';
}
?>

<?php if ( HEADER_TYPE == 2 ) : ?>
    <div class="top-menu">

        <div class="main">

            <div class="row">

                <div class="col-sm-12">

                    <div class="tl-header with-cart-ribbon">

						<?php get_template_part( 'tpls/logo' ); ?>

						<?php get_template_part( 'tpls/mobile-menu' ); ?>

                        <nav class="sec-nav">

							<?php echo $top_menu; ?>

                        </nav>

						<?php get_template_part( 'tpls/cart-ribbon' ); ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="main-menu-top<?php echo HAS_SLIDER ? ' has-slider' : '';
	echo ! defined( 'GRAY_MENU' ) ? ' white-menu' : ''; ?>">

        <div class="main">

            <div class="row">

                <div class="col-md-12">

                    <nav class="main-menu-env top-menu-type-2 clearfix">

						<?php
						if ( get_data( 'header_sticky_menu' ) ) : ?>
                            <a href="<?php echo home_url(); ?>" class="logo-sticky">
								<?php echo oxygen_get_logo( true ); ?>
                            </a>
						<?php endif; ?>

						<?php echo $main_menu; ?>

						<?php if ( $has_megamenu == false && get_data( 'header_menu_search' ) ) : ?>
                            <form action="<?php echo home_url(); ?>" method="get" class="search-form"
                                  enctype="application/x-www-form-urlencoded">

                                <a href="#">
                                    <span class="glyphicon glyphicon-search"></span>
                                </a>

                                <div class="search-input-env">
                                    <input type="text" class="search-input" name="s"
                                           placeholder="<?php _e( 'Search...', 'oxygen' ); ?>">
                                </div>

                            </form>
						<?php endif; ?>

                    </nav>

                </div>

            </div>

        </div>

    </div>
<?php endif; // END OF: Header Type 2 ?>


<?php if ( HEADER_TYPE == 3 ) : ?>
    <div class="top-menu main-menu-top<?php echo HAS_SLIDER ? ' has-slider' : ''; ?>">

        <div class="main">

            <div class="row">

                <div class="col-sm-12">

                    <div class="tl-header with-cart-ribbon">

						<?php get_template_part( 'tpls/logo' ); ?>

						<?php get_template_part( 'tpls/mobile-menu' ); ?>

                        <div class="sec-nav">

							<?php echo $top_menu; ?>

                            <nav class="main-menu-env">

								<?php echo $main_menu; ?>

								<?php if ( get_data( 'header_menu_search' ) ) : ?>
                                    <form action="<?php echo home_url(); ?>" method="get" class="search-form"
                                          enctype="application/x-www-form-urlencoded">

                                        <a href="#">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </a>

                                        <div class="search-input-env">
                                            <input type="text" class="search-input" name="s" alt=""
                                                   placeholder="<?php _e( 'Search...', 'oxygen' ); ?>"/>
                                        </div>

                                    </form>
								<?php endif; ?>

                            </nav>

                        </div>

						<?php get_template_part( 'tpls/cart-ribbon' ); ?>

                    </div>

                </div>

            </div>

        </div>

    </div>
<?php endif; // END OF: Header Type 3 ?>


<?php if ( HEADER_TYPE == 4 ) : ?>
    <div class="top-menu-centered<?php echo HAS_SLIDER ? ' has-slider' : ''; ?>">

        <div class="main">

            <div class="row">

                <div class="col-sm-12">

                    <div class="tl-header with-cart-ribbon">

						<?php get_template_part( 'tpls/logo' ); ?>

						<?php get_template_part( 'tpls/mobile-menu' ); ?>

                        <div class="navs">

                            <nav class="main-menu-env">

								<?php if ( get_data( 'header_menu_search' ) ) : ?>
                                    <form action="<?php echo home_url(); ?>" method="get" class="search-form"
                                          enctype="application/x-www-form-urlencoded">

                                        <a href="#">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </a>

                                        <div class="search-input-env<?php echo get_search_query() ? ' visible' : ''; ?>">
                                            <input type="text" class="search-input" name="s" alt=""
                                                   placeholder="<?php _e( 'Search...', 'oxygen' ); ?>"
                                                   value="<?php echo get_search_query( true ); ?>"/>
                                        </div>

                                    </form>
								<?php endif; ?>

								<?php echo $main_menu; ?>
                            </nav>

                            <br/>

							<?php echo $top_menu; ?>

                        </div>

						<?php get_template_part( 'tpls/cart-ribbon' ); ?>

                    </div>

                </div>

            </div>

        </div>

    </div>
<?php endif; // END OF: Header Type 4
