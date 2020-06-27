<?php
/**
 *    Cross Sells
 *
 *    Laborator.co
 *    www.laborator.co
 */

global $post, $product;

?>
<li class="type-cross-sells">

	<?php if ( has_post_thumbnail() ) : ?>
        <div class="product-image">

			<?php if ( $product->is_visible() ) : ?>
            <a href="<?php the_permalink(); ?>">
				<?php endif; ?>

				<?php echo oxygen_image_placeholder_wrap_element( $product->get_image() ); ?>

				<?php if ( $product->is_visible() ) : ?>
            </a>
		<?php endif; ?>

        </div>
	<?php endif; ?>

    <div class="product-details">

        <h4 class="product-title">
			<?php
			if ( $product->is_visible() ) :
				echo sprintf( '<a href="%s">%s</a>', esc_url( $product->get_permalink() ), esc_html( $product->get_title() ) );
			else :
				echo esc_html( $product->get_title() );
			endif;
			?>
        </h4>

        <div class="price">
			<?php echo $product->get_price_html(); ?>
        </div>

    </div>

    <div class="product-add-to-cart">
        <i class="action-taken glyphicon glyphicon-ok-sign"></i>
		<?php woocommerce_template_loop_add_to_cart(); ?>
    </div>

</li>