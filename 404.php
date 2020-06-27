<?php
/**
 *	Oxygen WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */
defined( 'ABSPATH' ) || exit;

define( 'NO_HEADER_MENU', true );
define( 'NO_FOOTER_MENU', true );

add_filter( 'body_class', oxygen_hook_merge_array_value( 'not-found' ) );

// Header
get_header();

?>
<div class="wrapper">

	<div class="center">
	
		<div class="col-lg-5">
			<a href="<?php echo home_url(); ?>">
				<img class="404-image" src="<?php echo oxygen_get_theme_assets_uri(); ?>/images/404.png" width="266" />
			</a>
		</div>
		
		<div class="col-lg-7">
			
			<h2><?php _e( 'This page does not exist!', 'oxygen' ); ?></h2>
			
			<a href="<?php echo home_url(); ?>">
				<span>&laquo;</span> 
				<?php _e( 'Go back to home page', 'oxygen' ); ?>
			</a>
			
		</div>
		
	</div>
	
</div>
<?php

// Footer
get_footer();