<?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */
defined( 'ABSPATH' ) || exit;

wp_enqueue_script( 'perfect-scrollbar' );
wp_enqueue_style( 'perfect-scrollbar' );

?>
<div class="main-sidebar<?php echo get_data( 'header_menu_search' ) ? ' has-search' : ''; ?>">

    <div class="sidebar-inner">

		<?php get_template_part( 'tpls/logo' ); ?>

		<?php get_template_part( 'tpls/mobile-menu' ); ?>

        <div class="sidebar-menu<?php echo get_data( 'sidebar_menu_links_display' ) == 'Collapsed' ? ' collapsed-subs' : ''; ?>">
			<?php
			$args = array(
				'theme_location' => 'main-menu',
				'container'      => '',
				'menu_class'     => 'nav'
			);

			wp_nav_menu( $args );

			if ( get_data( 'top_menu_social' ) ) {
				echo do_shortcode( '[lab_social_networks]' );
			}
			?>
        </div>

    </div>


	<?php if ( get_data( 'header_menu_search' ) ) : ?>
        <form action="<?php echo home_url(); ?>" method="get" class="search"
              enctype="application/x-www-form-urlencoded">
            <input type="text" class="search_input" name="s" alt=""
                   placeholder="<?php esc_html_e( 'Search...', 'oxygen' ); ?>"
                   value="<?php echo esc_attr( get_search_query( true ) ); ?>" autocomplete="off"/>
            <span class="glyphicon glyphicon-search float_right"></span>
        </form>
	<?php endif; ?>

</div>
