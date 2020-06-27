<?php
/**
 *    Loop Product
 *
 *    Laborator.co
 *    www.laborator.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Remove loop product link
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

/**
 * Remove thumbnail, add to cart, title and other hooks attached in product loop item
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

/**
 * Change positions for catalog ordering and results count
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

if ( get_data( 'shop_sorting_show' ) ) {
	add_action( 'woocommerce_before_main_content', 'woocommerce_catalog_ordering', 41 );
	add_action( 'woocommerce_before_main_content', 'woocommerce_result_count', 42 );
}

/**
 * Loop product wrapper start
 */
if ( ! function_exists( 'oxygen_woocommerce_loop_product_wrapper_start' ) ) {

	function oxygen_woocommerce_loop_product_wrapper_start() {
		echo '<div class="product-wrapper">';
	}
}

add_action( 'woocommerce_before_shop_loop_item', 'oxygen_woocommerce_loop_product_wrapper_start', 1 );

/**
 * Loop product wrapper end
 */
if ( ! function_exists( 'oxygen_woocommerce_loop_product_wrapper_end' ) ) {

	function oxygen_woocommerce_loop_product_wrapper_end() {
		echo '</div>';
	}
}

add_action( 'woocommerce_after_shop_loop_item', 'oxygen_woocommerce_loop_product_wrapper_end', 100 );

/**
 * Sale, featured and out of stock flashes
 */
if ( ! function_exists( 'oxygen_woocommerce_show_product_sale_flash' ) ) {

	function oxygen_woocommerce_show_product_sale_flash() {
		global $post, $product;

		$featured_key = 'shop_featured_product_ribbon_show';
		$oos_key      = 'shop_oos_ribbon_show';
		$sale_key     = 'shop_sale_ribbon_show';

		if ( is_product() && $product->get_id() == get_queried_object_id() ) {
			$featured_key = 'shop_single_featured_ribbon_show';
			$oos_key      = 'shop_single_oos_ribbon_show';
			$sale_key     = 'shop_single_sale_ribbon_show';
		}

		// Featured product
		if ( $product->is_featured() && '0' !== get_data( $featured_key ) ) {
			echo apply_filters( 'woocommerce_featured_flash', '<div class="sale_tag product-featured">
				<div class="ribbon">
					<strong class="ribbon-content">
						<span>' . esc_html__( 'Featured', 'woocommerce' ) . '</span>
					</strong>
				</div>
			</div>', $post, $product );
		} // Out of stock product
		else if ( false == $product->is_in_stock() && '0' !== get_data( $oos_key ) ) {
			echo apply_filters( 'woocommerce_out_of_stock_flash', '<div class="sale_tag stock-out">
				<div class="ribbon">
					<strong class="ribbon-content">
						<span>' . esc_html__( 'Out of stock', 'woocommerce' ) . '</span>
					</strong>
				</div>
			</div>', $post, $product );
		} // On sale product
		else if ( $product->is_on_sale() && '0' !== get_data( $sale_key ) ) {
			echo apply_filters( 'woocommerce_sale_flash', '<div class="sale_tag sale">
				<div class="ribbon">
					<strong class="ribbon-content">
						<span>' . esc_html__( 'Sale', 'woocommerce' ) . '</span>
					</strong>
				</div>
			</div>', $post, $product );
		}
	}
}

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'oxygen_woocommerce_show_product_sale_flash', 10 );

/**
 * Product loop thumbnail with other information inside
 */
if ( ! function_exists( 'oxygen_woocommerce_loop_item_thumbnail' ) ) {

	function oxygen_woocommerce_loop_item_thumbnail() {
		global $post, $product;

		$post_thumbnail_id = get_post_thumbnail_id();
		$product_images    = $product->get_gallery_image_ids();

		// Remove featured image from gallery
		if ( has_post_thumbnail() && in_array( $post_thumbnail_id, $product_images ) ) {
			unset( $product_images[ array_search( $post_thumbnail_id, $product_images ) ] );
		}

		// Classes
		$classes = array( 'product-images' );

		// Has gallery
		$has_gallery = count( $product_images ) > 0;

		if ( $has_gallery ) {
			$has_gallery =
			$classes[] = 'has-gallery';
		}

		$item_preview_type = get_data( 'shop_item_preview_type' );
		$item_preview_type = 'Second Image on Hover' == $item_preview_type ? 'second-image' : ( 'None' == $item_preview_type ? 'none' : 'gallery' );

		$classes[] = "preview-type-{$item_preview_type}";

		// Loop product wrapper
		?>
        <div <?php oxygen_class_attr( $classes ); ?>>

            <a href="<?php the_permalink(); ?>" class="featured-image">
				<?php
				$image_size   = apply_filters( 'single_product_archive_thumbnail_size', 'woocommerce_thumbnail' );
				$thumbnail_id = has_post_thumbnail() ? $post_thumbnail_id : array_shift( $product_images );

				if ( $thumbnail_id ) {
					echo oxygen_get_attachment_image( $thumbnail_id, $image_size );
				} else {
					// @ToDo Show placeholder image
				}

				if ( $product_images && in_array( $item_preview_type, array( 'second-image', 'gallery' ) ) ) {

					if ( 'second-image' == $item_preview_type ) {
						$thumbnail_id = array_shift( $product_images );
						echo oxygen_get_attachment_image( $thumbnail_id, $image_size, null, null, 'secondary-image' );
					} else {
						foreach ( $product_images as $thumbnail_id ) {
							echo oxygen_get_attachment_image( $thumbnail_id, $image_size, null, null, 'secondary-image gallery-image' );
						}
					}
				}
				?>
            </a>
			<?php

			// Gallery navigation
			if ( $has_gallery && 'gallery' == $item_preview_type ) :
				?>
                <a href="#" class="product-images--navigation product-images--prev"></a>
                <a href="#" class="product-images--navigation product-images--next"></a>
			<?php
			endif;


			// Wishlist link
			if ( oxygen_is_yith_wishlist_supported() ) :

				oxygen_yith_wcwl_add_to_wishlist();

			endif;

			// Quick view
			if ( oxygen_woocommerce_quickview_enabled() ) :

				?>
                <div class="quick-view">
                <a href="#">
                    <i class="entypo-popup"></i>
					<?php esc_html_e( 'Quick View', 'oxygen' ); ?>
                </a>
                </div><?php

			endif;

			// Rating
			oxygen_woocommerce_show_product_rating();
			?>
        </div>
		<?php

		// Adding to cart incicator
		?>
        <div class="adding-to-cart">
            <div class="loader">
                <strong><?php esc_html_e( 'Adding to cart', 'oxygen' ); ?></strong>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
		<?php
	}
}

add_action( 'woocommerce_before_shop_loop_item_title', 'oxygen_woocommerce_loop_item_thumbnail', 10 );

/**
 * Display product title with link wrapping
 */
if ( ! function_exists( 'oxygen_woocommerce_template_loop_product_title' ) ) {

	function oxygen_woocommerce_template_loop_product_title() {
		woocommerce_template_loop_product_link_open();
		woocommerce_template_loop_product_title();
		woocommerce_template_loop_product_link_close();
	}
}

/**
 * Item Title
 */
if ( ! function_exists( 'oxygen_woocommerce_loop_item_title' ) ) {

	function oxygen_woocommerce_loop_item_title() {
		global $product;

		?>
        <div class="product-description">

			<?php
			// Title
			oxygen_woocommerce_template_loop_product_title();

			/**
			 * Hooks after the title
			 *
			 * @hooked oxygen_woocommerce_product_loop_categories - 10
			 * @hooked oxygen_woocommerce_product_description_divider - 20
			 * @hooked woocommerce_template_loop_price - 30
			 * @hooked woocommerce_template_loop_add_to_cart - 40
			 */
			do_action( 'oxygen_woocommerce_after_loop_item_title' );
			?>

        </div>
		<?php
	}
}

add_action( 'woocommerce_shop_loop_item_title', 'oxygen_woocommerce_loop_item_title' );

/**
 * Product categories in products loop
 */
if ( ! function_exists( 'oxygen_woocommerce_product_loop_categories' ) ) {

	function oxygen_woocommerce_product_loop_categories() {
		global $product;

		?>
        <div class="product-categories">
			<?php the_terms( $product->get_id(), 'product_cat' ); ?>
        </div>
		<?php
	}
}

/**
 * Product description divider
 */
if ( ! function_exists( 'oxygen_woocommerce_product_description_divider' ) ) {

	function oxygen_woocommerce_product_description_divider() {
		if ( oxygen_woocommerce_is_catalog_mode() && oxygen_woocommerce_catalog_mode_hide_prices() ) {
			return;
		} else if ( ! oxygen_woocommerce_show_product_loop_price() && ! oxygen_woocommerce_show_product_loop_add_to_cart() ) {
			return;
		}

		echo '<div class="divider"></div>';
	}
}

add_action( 'oxygen_woocommerce_after_loop_item_title', 'oxygen_woocommerce_product_description_divider', 20 );

/**
 * Loop add to cart link
 */
if ( ! function_exists( 'oxygen_woocommerce_loop_add_to_cart_link' ) ) {

	function oxygen_woocommerce_loop_add_to_cart_link( $link ) {
		global $product;

		if ( $product && preg_match( "#<a(?<link>.*)>(?<content>.*?)<\/a>#", $link, $matches ) ) {

			// Simple product
			if ( $product->is_type( 'simple' ) ) {
				$icon = sprintf( '<i data-toggle="tooltip" data-placement="bottom" title="%s" class="glyphicon glyphicon-plus-sign"></i><i class="action-taken glyphicon glyphicon-ok-sign"></i>', esc_attr( $matches['content'] ) );

				return sprintf( '<a%s>%s</a>', $matches['link'], $icon );
			} // Grouped or variable product
			else if ( $product->is_type( 'grouped' ) || $product->is_type( 'variable' ) ) {
				$icon = sprintf( '<i data-toggle="tooltip" data-placement="bottom" title="%s" class="entypo-list-add"></i>', esc_attr( $matches['content'] ) );

				return sprintf( '<a%s>%s</a>', $matches['link'], $icon );
			} // External product
			else if ( $product->is_type( 'external' ) ) {
				$icon = sprintf( '<i data-toggle="tooltip" data-placement="bottom" title="%s" class="entypo-export"></i>', esc_attr( $matches['content'] ) );

				return sprintf( '<a%s>%s</a>', $matches['link'], $icon );
			}
		}

		return $link;
	}
}

add_filter( 'woocommerce_loop_add_to_cart_link', 'oxygen_woocommerce_loop_add_to_cart_link', 10 );

/**
 * Product Quickview entry
 */
if ( ! function_exists( 'oxygen_woocommerce_product_quickview_entry' ) ) {

	function oxygen_woocommerce_product_quickview_entry() {

		// Enqueue slick carousel
		oxygen_enqueue_slick_carousel();

		// Product item
		ob_start();
		get_template_part( 'tpls/woocommerce-product-quickview' );
		$product_quickview = str_replace( '</script>', '&lt;/script&gt;', ob_get_clean() );

		// Use as template
		echo '<script type="text/template" class="product-quickview-template">';
		echo $product_quickview;
		echo '</script>';
	}
}

/**
 * Product Quickvew sale flash
 */
add_action( 'oxygen_woocommerce_quickview_product_images', 'oxygen_woocommerce_show_product_sale_flash', 10 );

/**
 * Product Quickvew images
 */
if ( ! function_exists( 'oxygen_woocommerce_quickview_display_product_images' ) ) {

	function oxygen_woocommerce_quickview_display_product_images() {
		global $post, $product;

		// Product images
		$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
		$attachment_ids    = $product->get_gallery_image_ids();

		if ( $post_thumbnail_id ) {
			array_unshift( $attachment_ids, $post_thumbnail_id );
		}

		// Unique attachment ids
		$attachment_ids = array_unique( $attachment_ids );

		// Image size
		$image_size = apply_filters( 'oxygen_woocommerce_product_quickview_image', 'woocommerce_single' );

		?>
        <div class="product-images">

			<?php
			/**
			 * Main product images
			 */
			foreach ( $attachment_ids as $attachment_id ) :

				$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );

				?>
                <a href="<?php echo esc_url( $link ); ?>" class="product-image">
					<?php
					echo oxygen_get_attachment_image( $attachment_id, $image_size );
					?>
                </a>
			<?php

			endforeach;
			?>

        </div>
		<?php
	}
}

add_action( 'oxygen_woocommerce_quickview_product_images', 'oxygen_woocommerce_quickview_display_product_images', 20 );

/**
 * Product quickview view product button
 */
if ( ! function_exists( 'oxygen_woocommerce_product_quickview_view_product' ) ) {

	function oxygen_woocommerce_product_quickview_view_product() {
		global $product;

		$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );

		?>
        <form class="cart">
            <a href="<?php echo esc_url( $link ); ?>" class="button view-product">
                <i class="entypo-eye"></i>
				<?php esc_html_e( 'View Product', 'oxygen' ); ?>
            </a>
        </form>
		<?php
	}
}

/**
 * Product title as h3.
 */
if ( ! function_exists( 'kalium_woocommerce_template_single_title_h3' ) ) {

    function kalium_woocommerce_template_single_title_h3() {
		the_title( '<h3 class="product_title entry-title">', '</h3>' );
    }
}

/**
 * Product quick view summary
 */
add_action( 'oxygen_woocommerce_quickview_product_summary', 'kalium_woocommerce_template_single_title_h3', 10 );
add_action( 'oxygen_woocommerce_quickview_product_summary', 'woocommerce_template_single_rating', 20 );
add_action( 'oxygen_woocommerce_quickview_product_summary', 'woocommerce_template_single_excerpt', 30 );
add_action( 'oxygen_woocommerce_quickview_product_summary', 'woocommerce_template_single_price', 40 );
add_action( 'oxygen_woocommerce_quickview_product_summary', 'oxygen_woocommerce_product_quickview_view_product', 50 );
add_action( 'oxygen_woocommerce_quickview_product_summary', 'woocommerce_template_single_sharing', 60 );

/**
 * Get current using Wishlist ID
 */
function oxygen_yith_wishlist_get_default_wishlist_id() {
	static $wishlist_id;

	// Use the current wishlist
	if ( isset( $wishlist_id ) ) {
		return $wishlist_id;
	}

	// default wishlist id
	$default_wishlists = is_user_logged_in() ? YITH_WCWL()->get_wishlists( array( 'is_default' => true ) ) : false;

	if ( ! empty( $default_wishlists ) ) {
		$default_wishlist = $default_wishlists[0]['ID'];
	} else {
		$default_wishlist = false;
	}

	$wishlist_id = $default_wishlist;

	return $default_wishlist;
}

/**
 * Ordering dropdown for products loop
 */
if ( ! function_exists( 'oxygen_woocommerce_shop_loop_ordering_dropdown' ) ) {

	function oxygen_woocommerce_shop_loop_ordering_dropdown( $catalog_orderby_options, $orderby ) {

		$selected = '';
		$options  = '';

		foreach ( $catalog_orderby_options as $id => $name ) {
			$atts = '';

			if ( $orderby == $id ) {
				$selected = $name;
				$atts     = ' class="active"';
			}

			$options .= sprintf( '<li role="presentation"%3$s><a href="#%1$s">%2$s</a></li>', $id, esc_html( $name ), $atts );
		}

		?>
        <div class="woocommerce-ordering--dropdown form-group sort">

            <div class="dropdown">

                <button class="dropdown-toggle" type="button" data-toggle="dropdown">
                    <span><?php echo esc_html( $selected ); ?></span>
                    <i class="caret"></i>
                </button>

                <ul class="dropdown-menu fade" role="menu">

					<?php
					/**
					 * Ordering options
					 */
					echo $options;
					?>

                </ul>

            </div>
        </div>
		<?php
	}
}

add_action( 'oxygen_woocommerce_shop_loop_ordering', 'oxygen_woocommerce_shop_loop_ordering_dropdown', 10, 2 );

/**
 * Shop categories
 */
if ( ! function_exists( 'oxygen_woocommerce_maybe_show_product_categories' ) ) {

	function oxygen_woocommerce_maybe_show_product_categories() {
		wc_set_loop_prop( 'loop', 0 );

		$categories = woocommerce_maybe_show_product_subcategories( '' );

		if ( trim( $categories ) ) {
			$classes   = array( 'products', 'shop-categories' );
			$classes[] = 'columns-' . oxygen_woocommerce_get_category_columns();

			printf( '<ul %s>%s</ul>', oxygen_class_attr( $classes, false ), $categories );
		}

	}
}

remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );
add_filter( 'woocommerce_before_shop_loop', 'oxygen_woocommerce_maybe_show_product_categories' );

/**
 * Category title
 */
if ( ! function_exists( 'oxygen_woocommerce_template_loop_category_entry' ) ) {

	function oxygen_woocommerce_template_loop_category_entry( $category ) {
		ob_start();
		woocommerce_subcategory_thumbnail( $category );
		$category_image = ob_get_clean();

		?>
        <div class="category-wrapper">
			<?php

			// Category image
			echo oxygen_image_placeholder_wrap_element( $category_image );

			static $animation_delay = 0;
			$animation_delay += 100;
			?>
            <h2 class="woocommerce-loop-category__title">
				<span class="title wow flipInX" data-wow-delay="<?php echo $animation_delay; ?>"><?php
					echo esc_html( $category->name );

					// Count
					if ( get_data( 'shop_category_count' ) && $category->count ) {
						echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . esc_html( $category->count ) . ')</mark>', $category );
					}
					?></span>
            </h2>

        </div>
		<?php
	}
}

remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );

add_action( 'woocommerce_shop_loop_subcategory_title', 'oxygen_woocommerce_template_loop_category_entry', 10 );
