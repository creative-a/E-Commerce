<?php
/**
 *	Oxygen WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */
defined( 'ABSPATH' ) || exit;

// Header
get_header();

// Page content
while ( have_posts() ) : the_post();

	// Analyze page content
	$content = trim( get_the_content() );
	$is_vc_page = preg_match( '/\[vc_row.*?\]/', $content );
	
	// WooCommerce Related Pages
	if ( function_exists( 'is_woocommerce' ) && ( is_cart() || is_checkout() || is_account_page() || oxygen_woocommerce_is_yith_wishlist_page() ) ) {
		
		the_content();
		
	} else {
	
		?>
		<div class="page-container">
		
			<?php 
				if ( ! $is_vc_page ) :
				
					?><div class="col-md-12">
						<div class="white-block block-pad">
							<h1 class="single-page-title"><?php the_title(); ?></h1>
							
							<div class="post-content">
								<?php the_content(); ?>
							</div>
							
						</div>
					</div><?php 
						
					else : 
						the_content(); 
				endif; 
			?>
			
		</div>
		<?php
	}
		
endwhile;

// Footer
get_footer();