<?php
/**
 *	Oxygen WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */
defined( 'ABSPATH' ) || exit;

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2, user-scalable=yes">

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

	<?php
		// Header cart
		if ( oxygen_is_shop_supported() ) :
			get_template_part( 'tpls/header-cart' );
		endif; 
	?>

	<?php if ( ! defined( 'NO_HEADER_MENU' ) ) : ?>
	<div class="wrapper">

		<?php
		// Check is slider is assigned for this page url
		define( 'HAS_SLIDER', ( $revslider_id = oxygen_get_page_revslider_id() ) );

		// Hooks before the header
		do_action( 'oxygen_before_header' );

		// Menu
		if ( HEADER_TYPE == 1 ) {
			get_sidebar( 'menu' );
		}
		// Header types 2-4
		else if ( in_array( HEADER_TYPE, array( 2, 3, 4 ) ) ) {
			get_sidebar( 'menu-top' );

			// Slider
			if ( HAS_SLIDER ) {
				
				if ( is_search() ) {
					echo "<div style='height: 30px;'></div>";
				} else if ( function_exists( 'putRevSlider' ) ) {
					echo putRevSlider( $revslider_id );
				}
			}
		}
		?>

		<div class="main<?php echo HEADER_TYPE == 1 && HAS_SLIDER ? ' hide-breadcrumb' : ''; ?>">

			<?php get_template_part( 'tpls/breadcrumb' ); ?>

			<?php
			// Slider
			if ( HEADER_TYPE == 1 && HAS_SLIDER ) :

				?>
				<div class="rev-slider-container row">
					<?php echo putRevSlider( $revslider_id ); ?>
				</div>
				<?php

			endif;
			?>

	<?php endif; ?>
