<?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */

?>
<div class="woocommerce product-quickview">

	<?php
	/**
	 * Hooks: oxygen_woocommerce_quickview_before
	 */
	do_action( 'oxygen_woocommerce_quickview_before' );
	?>

    <div class="product-quickview--column product-quickview--images">

		<?php
		/**
		 * Hooks: oxygen_woocommerce_quickview_product_images
		 *
		 * @hooked oxygen_woocommerce_show_product_sale_flash - 10
		 * @hooked oxygen_woocommerce_quickview_display_product_images - 20
		 */
		do_action( 'oxygen_woocommerce_quickview_product_images' );
		?>

    </div>

    <div class="product-quickview--column product-quickview--summary">

        <div class="summary">

			<?php
			/**
			 * Hooks: oxygen_woocommerce_quickview_product_summary
			 */
			do_action( 'oxygen_woocommerce_quickview_product_summary' );
			?>

        </div>

    </div>

	<?php
	/**
	 * Hooks: oxygen_woocommerce_quickview_after
	 */
	do_action( 'oxygen_woocommerce_quickview_after' );
	?>

</div>