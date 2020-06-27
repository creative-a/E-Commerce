<?php
/**
 *    Products Carousel Shortcode for Visual Composer
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

class WPBakeryShortCode_laborator_blog_posts extends WPBakeryShortCode {

	/**
	 * Shortcode content
	 */
	public function content( $atts, $content = null ) {
		global $parsed_from_vc, $row_clear, $is_blog_posts;

		// Atts
		$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

		extract( shortcode_atts( array(
			'blog_query'       => '',
			'row_clear'        => '4',
			'equal_heights'    => '',
			'more'             => '',
			'carousel_enabled' => '',
			'auto_rotate'      => '',
			'el_class'         => '',
			'css'              => '',
		), $atts ) );

		$link     = vc_build_link( $more );
		$a_href   = $link['url'];
		$a_title  = $link['title'];
		$a_target = trim( $link['target'] );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'lab_wpb_blog_posts  wpb_content_element ' . ( $carousel_enabled ? ' carousel-enabled' : '' ) . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'] );

		$rand_id = "el_" . time() . mt_rand( 10000, 99999 );

		list( $args, $blog_query ) = vc_build_loop_query( $blog_query );

		// Enqueue Slick Carousel
		oxygen_enqueue_slick_carousel();

		ob_start();


		?>
        <div class="<?php echo $css_class; ?>" id="<?php echo $rand_id; ?>">

            <div class="blog-posts row"<?php echo $equal_heights && ! $carousel_enabled ? ' data-equal=".blog-post"' : ''; ?>>

				<?php
				$i = 1;

				while ( $blog_query->have_posts() ):

					$blog_query->the_post();

					switch ( $row_clear ) {
						case 1:
							$column_class = 'col-md-12';
							break;

						case 2:
							$column_class = 'col-md-6';
							break;

						case 3:
							$column_class = 'col-md-4';
							break;

						default:
							$column_class = 'col-md-3';
							break;
					}
					?>

					<?php if ( true || ! $carousel_enabled ) : ?>
                    <div class="col <?php echo $column_class; ?>">
				<?php endif; ?>

                    <article
                            class="blog-post<?php echo in_array( $row_clear, array( 3, 4 ) ) ? ' block-image' : ''; ?>">

						<?php if ( has_post_thumbnail() ) : ?>
                            <div class="image">

                                <a href="<?php the_permalink(); ?>">

									<?php the_post_thumbnail( 'blog-thumb-3' ); ?>

                                    <span class="hover-overlay"></span>
                                    <span class="hover-readmore">
										<?php _e( 'Read more...', 'oxygen' ); ?>
									</span>
                                </a>

                            </div>
						<?php endif; ?>

                        <div class="post">
                            <h3>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

							<?php if ( get_data( 'blog_post_date' ) ) : ?>
                                <div class="date">
									<?php the_date(); ?>
                                </div>
							<?php endif; ?>

                            <div class="content">
								<?php echo apply_filters( 'the_excerpt', wp_trim_words( get_the_excerpt(), apply_filters( 'oxygen_blog_posts_excerpt_words_length', has_post_thumbnail() ? 20 : 35 ) ) ); ?>
                            </div>
                        </div>

                    </article>

					<?php if ( true || ! $carousel_enabled ) : ?>
                    </div>
				<?php endif; ?>
					<?php

					if ( ! $carousel_enabled ) {
						echo $i % $row_clear == 0 ? '<div class="clear"></div>' : '';
					}

					$i ++;

				endwhile;

				?>
            </div>


			<?php
			if ( $blog_query->have_posts() && $a_href && $a_title ) {
				?>
                <div class="more-link">
                    <a href="<?php echo $a_href; ?>" class="btn btn-white" target="<?php echo $a_target; ?>">
						<?php echo $a_title; ?>
                    </a>
                </div>
				<?php
			}
			?>

        </div>

		<?php if ( $carousel_enabled ) : ?>
            <script type="text/javascript">
                jQuery( document ).ready( function ( $ ) {
                    var $carousel_el = $( '#<?php echo $rand_id; ?> .blog-posts' );

                    $carousel_el.slick( {
                        slide: '.col',
                        slidesToShow: <?php echo $row_clear; ?>,
                        swipeToSlide: true,
                        infinite: false,
                        arrows: false,
                        dots: true,
                        rtl: isRTL()
                    } );

					<?php if ( $equal_heights ) : ?>
                    $carousel_el.find( '.col' ).equalHeights();

                    imagesLoaded( $carousel_el, function () {
                        $carousel_el.find( '.col' ).equalHeights();
                    } );
					<?php endif; ?>
                } );
            </script>
		<?php endif; ?>

		<?php


		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

// Shortcode Options
$opts = array(
	"name"        => "Blog Posts",
	"description" => 'Display blog posts with carousel.',
	"base"        => "laborator_blog_posts",
	"class"       => "vc_laborator_blog_posts",
	"icon"        => "icon-lab-blog-posts",
	"controls"    => "full",
	"category"    => 'Laborator',
	"params"      => array(


		array(
			"type"        => "loop",
			"heading"     => "Products Query",
			"param_name"  => "blog_query",
			'settings'    => array(
				'size'      => array( 'hidden' => false, 'value' => 12 ),
				'order_by'  => array( 'value' => 'date' ),
				'post_type' => array( 'value' => 'post', 'hidden' => false )
			),
			"description" => "Create WordPress loop, to populate products from your site."
		),

		array(
			"type"        => "dropdown",
			"heading"     => "Columns count",
			"param_name"  => "row_clear",
			"std"         => 2,
			"value"       => array(
				"4 Columns" => 4,
				"3 Columns" => 3,
				"2 Columns" => 2,
				"1 Column"  => 1,
			),
			"description" => "Based on layout columns you use, select number of columns to wrap the product."
		),

		array(
			"type"        => "checkbox",
			"heading"     => "Equal Heights",
			"param_name"  => "equal_heights",
			"value"       => array(
				"Yes" => true
			),
			"description" => "Set equal heights for all blog posts."
		),

		array(
			"type"        => "vc_link",
			"heading"     => "More URL",
			"param_name"  => "more",
			"description" => "Will add a link at the end of posts to continue to read more. (Optional)"
		),

		array(
			"type"        => "checkbox",
			"heading"     => "Enable Carousel",
			"param_name"  => "carousel_enabled",
			"value"       => array(
				"Yes" => true
			),
			"description" => "Activate horizontal scroll carousel for this widget."
		),

		array(
			"type"        => "textfield",
			"heading"     => "Auto Rotate",
			"param_name"  => "auto_rotate",
			"value"       => "5",
			"description" => "You can set automatical rotation of carousel, unit is seconds. Enter 0 to disable.",
			'dependency'  => array( 'element' => 'carousel_enabled', 'not_empty' => true )
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
