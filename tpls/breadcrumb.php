<?php
/**
 *	Oxygen WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */

// Available only for header 1
if ( in_array( HEADER_TYPE, array( 2, 3, 4 ) ) ) {
	return;
}
	
$menu_locations = get_nav_menu_locations();
$has_top_menu = isset( $menu_locations['top-menu'] ) && $menu_locations['top-menu'] > 0;
?>
<div class="top-first">

	<div class="row">
	
		<div class="col-lg-<?php echo $has_top_menu ? 5 : 11; ?>">
		
			<div class="left-widget">
				<?php 
					oxygen_breadcrumb();
				?>
			</div>
			
		</div>
		
		<?php if ( $has_top_menu ) : ?>
		<div class="col-lg-7">	
		
			<div class="right-widget">
				<div class="breadcrumb-menu">
					<?php 
						wp_nav_menu( array(
							'theme_location' => 'top-menu',
							'container' => '',
							'menu_class' => 'nav',
							'depth' => 1
							
						) );
					?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		
		
		<?php get_template_part( 'tpls/cart-ribbon' ); ?>
		
	</div>
	
</div>
