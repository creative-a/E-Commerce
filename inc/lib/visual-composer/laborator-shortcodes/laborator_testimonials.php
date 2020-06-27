<?php
/**
 *    Text Banner Shortcode for Visual Composer
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

class WPBakeryShortCode_laborator_testimonials extends WPBakeryShortCode {

	/**
	 * Shortcode content
	 */
	public function content( $atts, $content = null ) {

		// Atts
		$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

		extract( shortcode_atts( array(
			'testimonials_query' => '',
			'autoswitch'         => 5,
			'el_class'           => '',
			'css'                => '',
		), $atts ) );

		list( $args, $testimonials_query ) = vc_build_loop_query( $testimonials_query );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'lab_wpb_testimonials wpb_content_element ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'] );

		ob_start();

		?>
        <div class="<?php echo $css_class; ?>" data-autoswitch="<?php echo absint( $autoswitch ); ?>">

            <div class="testimonials-inner">
				<?php
				if ( $testimonials_query->have_posts() ) :

					$i = 0;
					while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post();
						global $post;

						setup_postdata( $post );

						$link_to_author = oxygen_get_field( 'link_to_author' );
						$new_win        = oxygen_get_field( 'open_in_new_window' );
						?>
                        <div class="testimonial-entry<?php echo $i > 0 ? ' hidden' : ''; ?>">

							<?php if ( has_post_thumbnail() ) : ?>
                                <div class="testimonial-thumbnail">

									<?php if ( $link_to_author ) : ?><a href="<?php echo $link_to_author; ?>"
                                                                        target="<?php echo $new_win ? '_blank' : '_self'; ?>"><?php endif; ?>
										<?php echo oxygen_get_attachment_image( get_post_thumbnail_id(), array(
											144,
											144
										) ); ?>
										<?php if ( $link_to_author ) : ?></a><?php endif; ?>

                                </div>
							<?php endif; ?>

                            <div class="testimonial-blockquote">
								<?php the_content(); ?>

								<?php if ( get_the_title() ) : ?>
                                    <cite>
										<?php if ( $link_to_author ) : ?><a href="<?php echo $link_to_author; ?>"
                                                                            target="<?php echo $new_win ? '_blank' : '_self'; ?>"><?php endif; ?>
											<?php the_title(); ?>
											<?php if ( $link_to_author ) : ?></a><?php endif; ?>
                                    </cite>
								<?php endif; ?>
                            </div>

                        </div>
						<?php

						$i ++;
					endwhile;
					?>
				<?php

				endif;
				?>
            </div>

        </div>
		<?php

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

// Shortcode Options
$opts = array(
	"name"        => "Testimonials",
	"description" => 'Show what your clients say.',
	"base"        => "laborator_testimonials",
	"class"       => "vc_laborator_testimonials",
	"icon"        => "icon-lab-testimonials",
	"controls"    => "full",
	"category"    => 'Laborator',
	"params"      => array(

		array(
			"type"        => "loop",
			"heading"     => "Testimonials Query",
			"param_name"  => "testimonials_query",
			'settings'    => array(
				'size'       => array( 'hidden' => false, 'value' => 'All' ),
				'order_by'   => array( 'value' => 'date' ),
				'categories' => array( 'hidden' => true ),
				'tags'       => array( 'hidden' => true ),
				'tax_query'  => array( 'hidden' => true ),
				'authors'    => array( 'hidden' => true ),
				'post_type'  => array( 'value' => 'testimonial', 'hidden' => false )
			),
			"description" => "Create WordPress loop, to show testimonials from your site."
		),

		array(
			"type"        => "textfield",
			"heading"     => "Auto Switch",
			"param_name"  => "autoswitch",
			"value"       => "5",
			"description" => "Set autoswitch interval to change testimonials. Set 0 to disable."
		),

		array(
			"type"        => "textfield",
			"heading"     => "Extra class name",
			"param_name"  => "el_class",
			"value"       => "",
			"description" => "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file."
		),

		array(
			"type"       => "css_editor",
			"heading"    => 'Css',
			"param_name" => "css",
			"group"      => 'Design options'
		)
	)
);

// Add & init the shortcode
if ( function_exists( 'vc_map' ) ) {
	vc_map( $opts );
} else {
	wpb_map( $opts );
}