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

class WPBakeryShortCode_laborator_products_carousel extends WPBakeryShortCode {

	/**
	 * Ids to exclude in products query
	 */
	private $exclude_ids = array();

	/**
	 * Tax query
	 */
	private $tax_query = array();

	/**
	 * Shortcode content
	 */
	public function content( $atts, $content = null ) {

		// Atts
		$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

		// Default query args
		oxygen_vc_loop_param_set_default_value( $atts['products_query'], 'post_type', 'product' );
		oxygen_vc_loop_param_set_default_value( $atts['products_query'], 'size', '12' );

		// Shortcode atts
		extract( shortcode_atts( array(
			'products_query'        => '',
			'product_types_to_show' => '',
			'row_clear'             => '',
			'auto_rotate'           => '',
			'el_class'              => '',
			'css'                   => '',
		), $atts ) );


		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'lab_wpb_products_carousel wpb_content_element products-hidden ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'] );

		// Generate query using WC_Shortcode_Products class
		$query_args = oxygen_vc_query_builder( $products_query );

		$atts = array(
			'columns' => $row_clear
		);

		$type = 'products';

		// Items per page
		if ( ! empty( $query_args['posts_per_page'] ) ) {
			$atts['limit'] = $query_args['posts_per_page'];
		}

		// Order column
		if ( ! empty( $query_args['orderby'] ) ) {
			$atts['orderby'] = $query_args['orderby'];
		}

		// Order direction
		if ( ! empty( $query_args['order'] ) ) {
			$atts['order'] = $query_args['order'];
		}

		// Tax Query
		if ( ! empty( $query_args['tax_query'] ) ) {
			$tax_query = $categories = array();

			foreach ( $query_args['tax_query'] as $i => $tax ) {

				if ( is_numeric( $i ) && ! empty( $tax['taxonomy'] ) ) {
					// Product Categories
					if ( 'product_cat' == $tax['taxonomy'] ) {
						if ( 'NOT IN' == strtoupper( $tax['operator'] ) ) {
							$tax_query[] = $tax;
						} else {
							foreach ( $tax['terms'] as $term_id ) {
								if ( $term = get_term( $term_id, 'product_cat' ) ) {
									$categories[] = $term->slug;
								}
							}
						}
					} // Other terms
					else {
						$tax_query[] = $tax;
					}
				}
			}

			// Categories
			$atts['category'] = implode( ',', $categories );

			// Add tax query to products query
			if ( count( $tax_query ) ) {
				$this->tax_query = $tax_query;
				add_filter( 'woocommerce_shortcode_products_query', array( $this, 'addTaxQuery' ), 100, 3 );
			}
		}

		// Include post ids
		if ( ! empty( $query_args['post__in'] ) ) {
			$atts['ids'] = implode( ',', $query_args['post__in'] );
		}

		// Exclude post ids
		if ( ! empty( $query_args['post__not_in'] ) ) {
			$this->exclude_ids = $query_args['post__not_in'];
			add_filter( 'woocommerce_shortcode_products_query', array( $this, 'excludeIds' ), 100, 3 );
		}

		// Featured items only
		if ( 'only_featured' == $product_types_to_show ) {
			$atts['visibility'] = 'featured';
			$type               = 'featured_products';
		}

		// On sale products
		if ( 'only_on_sale' == $product_types_to_show ) {
			$type = 'sale_products';
		}

		// Products per row on mobile
		$ppr_mobile = 'two' === get_data( 'shop_products_mobile_two_per_row' ) ? 2 : 1;

		// Get products
		$shortcode = new WC_Shortcode_Products( $atts, $type );

		// Enqueue Slick Carousel
		oxygen_enqueue_slick_carousel();

		// Slides in medium screen sizes
		$carousel_slides_md = apply_filters( 'oxygen_woocommerce_products_carousel_slides_md', min( 3, $row_clear ) );

		// Element id
		$rand_id = "el_" . time() . mt_rand( 10000, 99999 );

		ob_start();

		?>
        <div class="<?php echo $css_class; ?>" id="<?php echo esc_attr( $rand_id ); ?>">

            <div class="products-loading">
                <div class="loader">
                    <strong><?php esc_html_e( 'Loading products...', 'oxygen' ); ?></strong>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

			<?php

			/**
			 * Show products
			 */
			echo $shortcode->get_content();

			?>
        </div>

        <script type="text/javascript">
			jQuery( document ).ready( function ( $ ) {
				var $container = $( '#<?php echo $rand_id; ?>' ),
					$products = $container.find( '.products' ),
					totalProducts = $products.find( '.product' ).length;

				$container.removeClass( 'products-hidden' );

				$products.slick( {
					slide: '.type-product',
					slidesToShow: <?php echo absint( $row_clear ); ?>,
					swipeToSlide: true,
					infinite: false,
					rtl: isRTL(),
					<?php if ( absint( $auto_rotate ) > 0 ) : ?>
					autoplay: true,
					autoplaySpeed: <?php echo absint( $auto_rotate * 1000 ); ?>,
					<?php endif; ?>
					responsive: [
						{
							breakpoint: 992,
							settings: {
								slidesToShow: <?php echo absint( $carousel_slides_md ); ?>,
								slidesToScroll: <?php echo absint( $carousel_slides_md ); ?>,
							}
						},
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
								slidesToShow: <?php echo esc_js( $ppr_mobile ); ?>,
								slidesToScroll: <?php echo esc_js( $ppr_mobile ); ?>,
							}
						}
					]
				} );

				var nextReset = false,
					prevReset = true;

				$products.on( 'click', '.slick-arrow', function ( ev ) {
					var isNext = jQuery( this ).hasClass( 'slick-next' ),
						currentSlide = $products.slick( 'slickCurrentSlide' ),
						maxSlide = totalProducts - $products.slick( 'slickGetOption', 'slidesToShow' );

					// Reset to last slide
					if ( prevReset && 0 == currentSlide ) {
						$products.slick( 'slickGoTo', maxSlide );
						prevReset = false;
					}

					if ( 0 == currentSlide ) {
						prevReset = true;
					}

					// Reset to first slide
					if ( nextReset && currentSlide == maxSlide ) {
						$products.slick( 'slickGoTo', 0 );
						nextReset = false;
					}

					if ( currentSlide == maxSlide ) {
						nextReset = true;
					}
				} );
			} );
        </script>
		<?php


		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	/**
	 * Exclude Ids from query
	 */
	public function excludeIds( $query, $atts, $type ) {

		if ( empty( $query['post__not_in'] ) ) {
			$query['post__not_in'] = array();
		}

		// Exclude ids
		$query['post__not_in'] = array_merge( $query['post__not_in'], $this->exclude_ids );

		// Remove filter after execution
		remove_filter( 'woocommerce_shortcode_products_query', array( $this, 'excludeIds' ), 100, 3 );

		return $query;
	}

	/**
	 * Add tax query
	 */
	public function addTaxQuery( $query, $atts, $type ) {
		$tax_query_default = array(
			'field'    => 'term_id',
			'taxonomy' => '',
			'operator' => 'IN',
			'terms'    => array()
		);

		if ( empty( $query['tax_query'] ) ) {
			$query['tax_query'] = array(
				'relation' => 'AND'
			);
		}

		foreach ( $this->tax_query as $tax_query ) {
			$query['tax_query'][] = array_merge( $tax_query_default, $tax_query );
		}

		// Remove filter after execution
		$this->tax_query = array();
		add_filter( 'woocommerce_shortcode_products_query', array( $this, 'addTaxQuery' ), 100, 3 );

		return $query;
	}
}

// Shortcode Options
$opts = array(
	"name"        => 'Products Carousel',
	"description" => 'Display shop products with Touch Carousel.',
	"base"        => "laborator_products_carousel",
	"class"       => "vc_laborator_products_carousel",
	"icon"        => "icon-lab-products-carousel",
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
			"description" => "Create WordPress loop, to populate products from your site.",
		),

		array(
			"type"        => "dropdown",
			"heading"     => "Filter Products by Type",
			"param_name"  => "product_types_to_show",
			"value"       => array(
				"Show all types of products from the above query"   => '',
				"Show only featured products from the above query." => 'only_featured',
				"Show only products on sale from the above query."  => 'only_on_sale',
			),
			"description" => "Based on layout columns you use, select number of columns to wrap the product.",
		),

		array(
			"type"        => "dropdown",
			"heading"     => "Columns count",
			"param_name"  => "row_clear",
			"value"       => array(
				"6 Columns" => 6,
				"5 Columns" => 5,
				"4 Columns" => 4,
				"3 Columns" => 3,
				"2 Columns" => 2,
				"1 Column"  => 1,
			),
			"description" => "Based on layout columns you use, select number of columns to wrap the product.",
		),

		array(
			"type"        => "textfield",
			"heading"     => "Auto Rotate",
			"param_name"  => "auto_rotate",
			"value"       => "5",
			"description" => "You can set automatical rotation of carousel, unit is seconds. Enter 0 to disable.",
		),

		array(
			"type"        => "textfield",
			"heading"     => "Extra class name",
			"param_name"  => "el_class",
			"value"       => "",
			"description" => "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.",
		),

		array(
			"type"       => "css_editor",
			"heading"    => 'Css',
			"param_name" => "css",
			"group"      => 'Design options',
		)
	)
);

// Add & init the shortcode
if ( function_exists( 'vc_map' ) ) {
	vc_map( $opts );
} else {
	wpb_map( $opts );
}
