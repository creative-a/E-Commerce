<?php
/**
 *	Oxygen WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */
defined( 'ABSPATH' ) || exit;
 
	global $theme_version;
		
	// Close wrapper when header is present
	if ( ! defined( 'NO_HEADER_MENU' ) ) {
		echo '</div>';
	}
	
	// Footer block
	if ( ! defined( 'NO_FOOTER_MENU' ) ) {
		get_template_part('tpls/footer-main');
	}
?>

	</div>

	<?php wp_footer(); ?>
	
	<!-- <?php echo 'ET: ', microtime( true ) - STIME, 's ', $theme_version, ( is_child_theme() ? 'ch' : '' ); ?> -->

</body>
</html>