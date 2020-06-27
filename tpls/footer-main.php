<?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */

global $post;

$menu_locations = get_nav_menu_locations();

$footer_text       = get_data( 'footer_text' );
$footer_text_right = get_data( 'footer_text_right' );

$has_right_footer_text = strlen( trim( $footer_text_right ) ) > 0;

?>
<div class="footer-env">

    <div class="footer-env-container">

		<?php
		// Footer widgets
		if ( get_data( 'footer_widgets' ) ) {
			get_template_part( 'tpls/footer-widgets' );
		}
		?>

        <footer class="footer-container">

            <div class="footer_main row">

                <div class="col-md-12 hidden-sm hidden-xs">
                    <hr class="divider"/>
                </div>

                <div class="clear"></div>

				<?php
				// Footer Menu
				if ( isset( $menu_locations['footer-menu'] ) && $menu_locations['footer-menu'] > 0 ) :

					?>
                    <div class="col-sm-12">

                        <div class="footer-nav">
							<?php
							wp_nav_menu( array(
								'theme_location' => 'footer-menu',
								'container'      => '',
								'depth'          => 1
							) );
							?>
                        </div>

                    </div>

                    <div class="clear"></div>
				<?php

				endif;
				?>

                <div class="footer-columns<?php echo ! $has_right_footer_text ? ' footer-columns--single-column' : ''; ?>">

                    <div class="footer-column">

                        <div class="copyright_text">

							<?php echo do_shortcode( $footer_text ); ?>

                        </div>

                    </div>

					<?php if ( $has_right_footer_text ) : ?>

                        <div class="footer-column footer-column--right">

							<?php echo do_shortcode( $footer_text_right ); ?>

                        </div>

					<?php endif; ?>
                </div>
            </div>

        </footer>

    </div>

</div>