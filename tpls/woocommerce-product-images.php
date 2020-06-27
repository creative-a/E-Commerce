<?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */

global $post, $product;

// Enqueue Slick Carousel
oxygen_enqueue_slick_carousel();

// Product images
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$attachment_ids    = $product->get_gallery_image_ids();

if ( $post_thumbnail_id ) {
	array_unshift( $attachment_ids, $post_thumbnail_id );
}

// Unique attachment ids
$attachment_ids = array_unique( $attachment_ids );

// Classes
$classes = array( 'product-gallery' );

if ( oxygen_woocommerce_single_product_image_zoom() ) {
	$classes[] = 'zoom';
}

if ( oxygen_woocommerce_single_product_image_lightbox() ) {
	$classes[] = 'lightbox';
}

?>
<div <?php oxygen_class_attr( $classes ); ?>>

	<?php
	/**
	 * Hooks: oxygen_woocommerce_single_product_before_images
	 *
	 * @hooked
	 */
	do_action( 'oxygen_woocommerce_single_product_before_images' );
	?>

    <div class="product-images">

		<?php
		/**
		 * Main product images
		 */
		foreach ( $attachment_ids as $attachment_id ) :

			$html = oxygen_woocommerce_get_gallery_image( $attachment_id, true );

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );

		endforeach;
		?>

    </div>

    <div class="product-thumbnails">

		<?php
		/**
		 * Thumbnails
		 */
		foreach ( $attachment_ids as $attachment_id ) :

			$html = oxygen_woocommerce_get_gallery_image( $attachment_id );

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );

		endforeach;
		?>

    </div>

</div>
