<?php
/**
 *    Default sidebar
 *
 *    Laborator.co
 *    www.laborator.co
 */

?>
<div id="sidebar" role="complementary">

	<?php
	// Shop sidebar
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product() || is_product_taxonomy() ) ) {
		dynamic_sidebar( 'shop_sidebar' );
	} // Default sidebar
	else {
		dynamic_sidebar();
	}
	?>

</div>