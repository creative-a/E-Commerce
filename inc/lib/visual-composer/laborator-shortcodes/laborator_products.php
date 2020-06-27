<?php
/**
 *    Products Shortcode for Visual Composer
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

class WPBakeryShortCode_laborator_products extends WPBakeryShortCode {

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

		// Default query args
		oxygen_vc_loop_param_set_default_value( $atts['products_query'], 'size', '12' );

		// Extract shortcode attributes
		extract( shortcode_atts( array(
			'products_query'        => '',
			'product_types_to_show' => '',
			'row_clear'             => '4',
			'el_class'              => '',
			'css'                   => '',
		), $atts ) );

		// CSS Class
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'lab_wpb_products wpb_content_element ' . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'] );

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

		// Get products
		$shortcode = new WC_Shortcode_Products( $atts, $type );

		return sprintf( '<div class="%s">%s</div>', $css_class, $shortcode->get_content() );
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
		$this->tax_query = array();
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
		add_filter( 'woocommerce_shortcode_products_query', array( $this, 'addTaxQuery' ), 100, 3 );

		return $query;
	}
}

// Shortcode Options
$opts = array(
	"name"        => "Products",
	"description" => 'Display shop products on custom query.',
	"base"        => "laborator_products",
	"class"       => "vc_laborator_products",
	"icon"        => "icon-lab-products",
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
			"type"        => "dropdown",
			"heading"     => "Filter Products by Type",
			"param_name"  => "product_types_to_show",
			"value"       => array(
				"Show all types of products from the above query"   => '',
				"Show only featured products from the above query." => 'only_featured',
				"Show only products on sale from the above query."  => 'only_on_sale',
			),
			"description" => "Based on layout columns you use, select number of columns to wrap the product."
		),

		array(
			"type"        => "dropdown",
			"heading"     => "Columns",
			"param_name"  => "row_clear",
			'std'         => '4',
			"value"       => array(
				"6 products per row" => 6,
				"5 products per row" => 5,
				"4 products per row" => 4,
				"3 products per row" => 3,
				"2 products per row" => 2,
				"1 product per row"  => 1,
			),
			"description" => "Based on layout columns you use, select when the product items will be cleared to new row."
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