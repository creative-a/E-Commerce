<?php
/**
 *    Look Book Products Shortcode for Visual Composer
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

class WPBakeryShortCode_laborator_lookbook_carousel extends WPBakeryShortCode {

	/**
	 * Shortcode Content
	 */
	public function content( $atts, $content = null ) {
		global $parsed_from_vc, $quickview_enabled, $row_clear, $is_lookbook_carousel, $quickview_wp_query;

		// Atts
		$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

		extract( shortcode_atts( array(
			'title'            => '',
			'full_width'       => '',
			'products_query'   => '',
			'row_clear'        => '',
			'auto_rotate'      => '',
			'size'             => '',
			'overlay_bg'       => '',
			'spacing'          => 0,
			'pager_pagination' => '',
			'el_class'         => '',
			'css'              => '',
		), $atts ) );

		// CSS class
		$css_class = 'lab_wpb_lookbook_carousel woocommerce wpb_content_element products-hidden ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' );

		if ( $row_clear == 1 ) {
			$css_class .= ' single-column';
		}

		if ( $full_width ) {
			$css_class .= ' is-fullwidth';
		} else {
			$css_class .= ' normal-width';
		}

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class, $this->settings['base'] );

		// Product query
		if ( false === strpos( $products_query, 'post_type' ) ) {
			$products_query .= '|post_type:product';
		}

		list( $args, $products_query ) = vc_build_loop_query( $products_query );
		$quickview_wp_query = clone $products_query;

		// Element ID
		$rand_id = 'el_' . time() . mt_rand( 10000, 99999 );

		// Pager and/or pagination
		$pager_pagination = explode( ',', $pager_pagination );

		ob_start();

		?>
        <style>
            #
            <?php echo $rand_id; ?>.lab_wpb_lookbook_carousel .lookbook-carousel .product-item:hover .lookbook-hover-info {
                background-color: <?php echo $overlay_bg; ?>;
            }

            #
            <?php echo $rand_id; ?>.lab_wpb_lookbook_carousel .lookbook-carousel .product-item {
                padding-left: <?php echo absint( $spacing ) / 2; ?>px;
                padding-right: <?php echo absint( $spacing ) / 2; ?>px;
            }

            #
            <?php echo $rand_id; ?>.lab_wpb_lookbook_carousel .lookbook-carousel .product-item .lookbook-hover-info {
                left: <?php echo absint( $spacing ) / 2; ?>px;
                right: <?php echo absint( $spacing ) / 2; ?>px;
            }
        </style>
        <div class="<?php echo $css_class; ?>" id="<?php echo $rand_id; ?>">

			<?php if ( in_array( 'pager', $pager_pagination ) || $title ) : ?>
                <div class="lookbook-header">
                    <h2><?php echo $title; ?></h2>

                    <div class="pager"></div>
                </div>
			<?php endif; ?>

            <div class="products-loading">
                <div class="loader">
                    <strong><?php _e( 'Loading products...', 'oxygen' ); ?></strong>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

			<?php
			if ( $products_query->have_posts() ) :

				?>
                <div class="lookbook-carousel">
					<?php

					while ( $products_query->have_posts() ) :
						global $post;

						$products_query->the_post();

						$id       = get_the_id();
						$thumb_id = 0;

						$product = wc_get_product( $post );

						if ( has_post_thumbnail() ) {
							$thumb_id = get_post_thumbnail_id();
						} else {
							$product_images = $product->get_gallery_image_ids();

							if ( count( $product_images ) ) {
								$thumb_id = $product_images[0];
							}
						}

						// Product image
						$img = wpb_getImageBySize( array(
							'attach_id'  => $thumb_id,
							'thumb_size' => $size,
							'class'      => 'product-image'
						) );
						?>

                        <div class="product-item product cols-<?php echo $row_clear; ?>">
							<?php echo oxygen_image_placeholder_wrap_element( $img['thumbnail'] ); ?>

                            <div class="lookbook-hover-info">

                                <div class="lookbook-inner-content">

									<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">', '</span>' ); ?>

                                    <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>

									<?php if ( ! oxygen_woocommerce_is_catalog_mode() && ! oxygen_woocommerce_catalog_mode_hide_prices() ) : ?>
                                        <div class="divider"></div>

                                        <div class="price-and-add-to-cart">

											<?php if ( $price_html = $product->get_price_html() ) : ?>
                                                <span class="price"><?php echo $price_html; ?></span>
											<?php endif; ?>


											<?php if ( ! oxygen_woocommerce_is_catalog_mode() ) : ?>

												<?php if ( $product->is_type( 'variable' ) ) : ?>
                                                    <a class="add-to-cart-btn"
                                                       href="<?php echo $product->get_permalink(); ?>">
														<?php _e( 'Select Options', 'oxygen' ); ?>
                                                        <i class="entypo-list-add"></i>
                                                    </a>

												<?php elseif ( $product->is_type( 'grouped' ) ) : ?>
                                                    <a class="add-to-cart-btn"
                                                       href="<?php echo $product->get_permalink(); ?>">
														<?php _e( 'Select Products', 'oxygen' ); ?>
                                                        <i class="entypo-list-add"></i>
                                                    </a>

												<?php elseif ( $product->is_type( 'external' ) ) : ?>
                                                    <a class="add-to-cart-btn"
                                                       href="<?php echo $product->get_product_url(); ?>"
                                                       target="_blank">
														<?php echo $product->single_add_to_cart_text(); ?>
                                                        <i class="entypo-export"></i>
                                                    </a>

												<?php else : ?>
                                                    <a class="add-to-cart-btn add-to-cart add_to_cart_button ajax_add_to_cart"
                                                       data-product_id="<?php echo $id; ?>" href="#">
														<?php _e( 'Add to Cart', 'oxygen' ); ?>
                                                        <i class="entypo-basket"></i>
                                                    </a>
												<?php endif; ?>

											<?php endif; ?>

                                        </div>
									<?php endif; ?>

                                </div>

                                <div class="loading-disabled">
                                    <div class="loader">
                                        <strong><?php _e( 'Adding to cart...', 'oxygen' ); ?></strong>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </div>

                            </div>

                        </div>

					<?php
					endwhile;
					?>
                </div>

			<?php endif; ?>

        </div>

        <script type="text/javascript">
            jQuery( document ).ready( function ( $ ) {
                var $container = $( '#<?php echo $rand_id; ?>' ),
                    $carousel = $container.find( '.lookbook-carousel' );

                if ( $container.hasClass( 'is-fullwidth' ) ) {
                    forceFullWidth( $carousel );
                }

                imagesLoaded( $carousel, function () {
                    $container.removeClass( 'products-hidden' );

                    if ( $container.hasClass( 'is-fullwidth' ) ) {
                        forceFullWidth( $carousel ); // Recall the fullwidth
                    }

                    $carousel.slick( {
                        slide: '.product-item',
                        slidesToShow: <?php echo $row_clear; ?>,
                        slidesToScroll: <?php echo $row_clear; ?>,
                        infinite: false,
                        appendArrows: $container.find( '.pager' ),
                        dots: true,
                        rtl: isRTL(),
                        responsive: [
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 2,
                                    slidesToScroll: 2
                                }
                            },
                            {
                                breakpoint: 520,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1
                                }
                            }
                        ]
                    } );
                } );
            } );
        </script>
		<?php


		$output = ob_get_clean();

		return $output;
	}
}

// Shortcode Options
$opts = array(
	"name"        => "Lookbook Carousel",
	"description" => 'Display shop products on full width.',
	"base"        => "laborator_lookbook_carousel",
	"class"       => "vc_laborator_lookbook_carousel",
	"icon"        => "icon-lab-lookbook-carousel",
	"controls"    => "full",
	"category"    => 'Laborator',
	"params"      => array(


		array(
			"type"        => "loop",
			"heading"     => "Products Query",
			"param_name"  => "products_query",
			'settings'    => array(
				'size'      => array( 'hidden' => false, 'value' => 12 ),
				'order_by'  => array( 'value' => 'date' ),
				'post_type' => array( 'value' => 'product', 'hidden' => false )
			),
			"description" => "Create WordPress loop, to populate products from your site."
		),

		array(
			"type"        => "textfield",
			"heading"     => "Widget title",
			"param_name"  => "title",
			"value"       => "Lookbook",
			"description" => "What text use as widget title. Leave blank if no title is needed."
		),

		array(
			"type"        => "checkbox",
			"heading"     => "Full Width",
			"param_name"  => "full_width",
			"value"       => array( "Yes, please" => true ),
			"description" => "Allow Lookbook container to occopy full width of the browser."
		),

		array(
			"type"        => "dropdown",
			"heading"     => "Columns count",
			"param_name"  => "row_clear",
			"std"         => 4,
			"value"       => array(
				"6 Columns" => 6,
				"5 Columns" => 5,
				"4 Columns" => 4,
				"3 Columns" => 3,
				"2 Columns" => 2,
				"1 Column"  => 1,
			),
			"description" => "Select number of columns you want to paginate products."
		),

		array(
			"type"        => "textfield",
			"heading"     => "Auto Rotate",
			"param_name"  => "auto_rotate",
			"value"       => "5",
			"description" => "You can set automatical rotation of carousel, unit is seconds. Enter 0 to disable."
		),

		array(
			"type"        => "textfield",
			"heading"     => "Image size",
			"param_name"  => "size",
			"value"       => "500x596",
			"description" => "Set the product image size to show. Type: {width}x{height}, if you enter just a number it will resize the image by height."
		),

		array(
			"type"        => "colorpicker",
			"heading"     => "Overlay Color",
			"param_name"  => "overlay_bg",
			"value"       => "rgba(130,2,2,0.85)",
			"description" => "Background color of the layer when product is hovered."
		),

		array(
			"type"        => "textfield",
			"heading"     => "Spacing",
			"param_name"  => "spacing",
			"value"       => "30",
			"description" => "Set elements margin in pixels unit."
		),

		array(
			"type"        => "checkbox",
			"heading"     => "Pager and Pagination",
			"param_name"  => "pager_pagination",
			"std"         => '',
			"value"       => array(
				"Show pager (next, prev)<br />" => 'pager',
				"Show pagination (dots)"        => 'pagination',
			),
			"description" => "Set the visibility of pager and pagination."
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
