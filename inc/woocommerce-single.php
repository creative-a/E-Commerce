<?php
/**
 *    Single Product
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Move price below short product description (single product page)
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );

/**
 * Hide Additional Attributes Title
 */
add_filter( 'woocommerce_product_additional_information_heading', '__return_empty_string' );

/**
 * Hide description tab title
 */
add_filter( 'woocommerce_product_description_heading', '__return_empty_string' );

/**
 * Sale, featured and out of stock flashes
 */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'oxygen_woocommerce_single_product_before_images', 'oxygen_woocommerce_show_product_sale_flash', 10 );

/**
 * Single product options
 */
function oxygen_woocommerce_single_product_params() {

	if ( is_single() && is_product() ) {
		$product_image_autoswitch = (int) get_data( 'shop_single_auto_rotate_image' );

		$oxygen_single_product_params = apply_filters( 'oxygen_single_product_params', array(
			'product_image_autoswitch' => 0 == $product_image_autoswitch ? 5 : $product_image_autoswitch
		) );

		printf( '<script>var oxygen_single_product_params = %s</script>', json_encode( $oxygen_single_product_params ) );
	}
}

add_action( 'wp_footer', 'oxygen_woocommerce_single_product_params' );

/**
 * Content single product wrapper for product gallery and summary (start)
 */
if ( ! function_exists( 'oxygen_woocommerce_content_single_product_wrapper_start' ) ) {

	function oxygen_woocommerce_content_single_product_wrapper_start() {
		?>
        <div class="single-product-wrapper">
		<?php
	}
}

add_action( 'woocommerce_before_single_product_summary', 'oxygen_woocommerce_content_single_product_wrapper_start', 5 );

/**
 * Content single product wrapper for product gallery and summary (end)
 */
if ( ! function_exists( 'oxygen_woocommerce_content_single_product_wrapper_end' ) ) {

	function oxygen_woocommerce_content_single_product_wrapper_end() {
		?>
        </div>
		<?php
	}
}

add_action( 'woocommerce_after_single_product_summary', 'oxygen_woocommerce_content_single_product_wrapper_end', 10 );

/**
 * Oxygens's product gallery
 */
if ( ! function_exists( 'oxygen_woocommerce_show_product_images' ) ) {

	function oxygen_woocommerce_show_product_images() {
		get_template_part( 'tpls/woocommerce-product-images' );
	}
}

/**
 * Get product image
 */
if ( ! function_exists( 'oxygen_woocommerce_get_gallery_image' ) ) {

	function oxygen_woocommerce_get_gallery_image( $attachment_id, $main_image = false ) {

		$thumbnail_size = $main_image ? oxygen_woocommerce_get_single_image_size() : oxygen_woocommerce_get_thumbnail_image_size();

		// Image size
		$image_size = apply_filters( 'woocommerce_gallery_image_size', $thumbnail_size );

		// Fullsize image
		$full_size = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$full_src  = wp_get_attachment_image_src( $attachment_id, $full_size );

		$image = oxygen_get_attachment_image( $attachment_id, $image_size, false, array(
			'title'                   => get_data( 'shop_single_lightbox_captions' ) ? get_post_field( 'post_title', $attachment_id ) : '',
			'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
			'class'                   => 'wp-post-image',
			'data-large_image_width'  => $full_src[1],
			'data-large_image_height' => $full_src[2],
		) );

		// Lightbox trigger
		$lightbox_trigger = '';

		if ( $main_image && oxygen_woocommerce_single_product_image_lightbox() ) {
			$lightbox_trigger = oxygen_woocommerce_get_lightbox_trigger_button();
		}

		return sprintf( '<div class="woocommerce-product-gallery__image"><a href="%2$s">%1$s</a>%3$s</div>', $image, $full_src[0], $lightbox_trigger );
	}
}

/**
 * Trigger lightbox button
 */
if ( ! function_exists( 'oxygen_woocommerce_get_lightbox_trigger_button' ) ) {

	function oxygen_woocommerce_get_lightbox_trigger_button() {
		return sprintf( '<button type="button" class="product-gallery-lightbox-trigger lightbox-trigger" title="%s"><i class="glyphicon glyphicon-fullscreen"></i></button>', esc_attr__( 'View product photo in fullscreen', 'oxygen' ) );
	}
}

/**
 *  Double variation image fix
 */
if ( ! function_exists( 'oxygen_woocommerce_variation_remove_featured_image' ) ) {

	function oxygen_woocommerce_variation_remove_featured_image( $variation, $variable ) {

		if ( oxygen_woocommerce_use_custom_product_image_gallery_layout() ) {
			$product_id = $variable->get_id();

			if ( isset( $variation['image_id'] ) && $variation['image_id'] == get_post_thumbnail_id( $product_id ) ) {
				$variation['image_id'] = '';
				$variation['image'] = null;
			}
		}

		return $variation;
	}
}

add_filter( 'woocommerce_available_variation', 'oxygen_woocommerce_variation_remove_featured_image', 1, 2 );

/**
 * Images assigned for product variations
 */
if ( ! function_exists( 'oxygen_woocommerce_available_variation' ) ) {

	function oxygen_woocommerce_available_variation( $variation_atts, $product_variable, $variation ) {
		$attachment_id = $variation->get_image_id();

		$variation_atts['oxygen_image'] = $variation_atts['oxygen_thumbnail'] = array();

		// Product main and thumbmail image
		if ( $attachment_id ) {
			$variation_atts['oxygen_image']     = oxygen_woocommerce_get_gallery_image( $attachment_id, true );
			$variation_atts['oxygen_thumbnail'] = oxygen_woocommerce_get_gallery_image( $attachment_id );
		}

		return $variation_atts;
	}
}

/**
 * Product top navigation (prev-next and rating)
 */
if ( ! function_exists( 'oxygen_woocommerce_single_product_summary_top_nav' ) ) {

	function oxygen_woocommerce_single_product_summary_top_nav() {
		$show_nextprev = get_data( 'shop_single_next_prev' );
		$show_rating = get_data( 'shop_single_product_rating' );
		
		if ( ! $show_nextprev && ! $show_rating ) {
			return;
		}

		?>
        <div class="product-top-nav">
			<?php

			// Next and previous product
			if ( $show_nextprev ) {
				oxygen_woocommerce_product_navigation_prevnext();
			}

			// Product Rating
			if ( $show_rating ) {
				woocommerce_template_single_rating();
			}
			?>
        </div>
		<?php
	}
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_single_product_summary', 'oxygen_woocommerce_single_product_summary_top_nav', 1 );

/**
 * Product navigation (previous and next)
 */
if ( ! function_exists( 'oxygen_woocommerce_product_navigation_prevnext' ) ) {

	function oxygen_woocommerce_product_navigation_prevnext() {

		$shop_next_prev_same_term = apply_filters( 'oxygen_shop_next_prev_same_term', false );

		$prev_post = get_previous_post( $shop_next_prev_same_term, '', 'product_cat' );
		$next_post = get_next_post( $shop_next_prev_same_term, '', 'product_cat' );

		?>
        <div class="nav-links">
            <a href="<?php echo get_permalink( $prev_post ); ?>"
               title="<?php echo esc_attr( get_the_title( $prev_post ) ); ?>"
               class="prev<?php echo ! $prev_post instanceof WP_Post ? ' disable' : ''; ?>">
                <i class="entypo-left-open-mini"></i>
            </a>
            <a href="<?php echo get_permalink( $next_post ); ?>"
               title="<?php echo esc_attr( get_the_title( $next_post ) ); ?>"
               class="next<?php echo ! $next_post instanceof WP_Post ? ' disable' : ''; ?>">
                <i class="entypo-right-open-mini"></i>
            </a>
        </div>
	<?php
	}
}

/**
 * Related products args
 */
function laborator_output_related_products_args( $args ) {
	$args['posts_per_page'] = get_data( 'shop_related_products_per_page' );

	return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'laborator_output_related_products_args' );

/**
 * Related products columns
 */
function oxygen_woocommerce_related_products_columns( $columns ) {

	if ( $columns_set = oxygen_get_number_from_word( get_data( 'shop_related_products_columns' ) ) ) {
		$columns = $columns_set;
	}

	return $columns;
}

add_filter( 'woocommerce_related_products_columns', 'oxygen_woocommerce_related_products_columns' );
add_filter( 'woocommerce_upsells_columns', 'oxygen_woocommerce_related_products_columns' );

/**
 * Single product review user gravatar
 */
if ( ! function_exists( 'oxygen_woocommerce_review_display_gravatar' ) ) {

	function oxygen_woocommerce_review_display_gravatar( $comment ) {

		ob_start();
		woocommerce_review_display_gravatar( $comment );
		$avatar = ob_get_clean();

		echo '<div class="user-avatar">';
		echo oxygen_image_placeholder_wrap_element( $avatar );
		echo '</div>';
	}
}

remove_action( 'woocommerce_review_before', 'woocommerce_review_display_gravatar', 10 );
add_action( 'woocommerce_review_before', 'oxygen_woocommerce_review_display_gravatar', 10 );

/**
 * Share product to social networks
 */
if ( ! function_exists( 'oxygen_woocommerce_share' ) ) {

	function oxygen_woocommerce_share() {
		global $product;

		if ( ! get_data( 'shop_share_product' ) ) {
			return;
		}

		echo '<ul class="share-product">';

		$share_product_networks = get_data( 'shop_share_product_networks' );

		if ( is_array( $share_product_networks ) ) {

			foreach ( $share_product_networks['visible'] as $network_id => $network ) {

				if ( $network_id == 'placebo' ) {
					continue;
				}

				echo '<li>';
				share_story_network_link( $network_id, $product->get_id(), false );
				echo '</li>';
			}

		}

		echo '</ul>';
	}
}

add_action( 'woocommerce_share', 'oxygen_woocommerce_share', 10 );
