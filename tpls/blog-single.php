<?php
/**
 *	Oxygen WordPress Theme
 *
 *	Laborator.co
 *	www.laborator.co
 */
$show_sidebar = apply_filters( 'oxygen_single_blog_sidebar', true );
?>

<div class="row">

	<div class="<?php echo $show_sidebar ? 'col-lg-9' : 'col-lg-12'; echo $show_sidebar && 'Left' == get_data( 'blog_sidebar_position' ) ? ' pull-right-md' : ''; ?>">

		<!--blog01-->
		<div class="blog">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'tpls/blog-post-single' );

			endwhile;
			?>

		</div>

	</div>

	<?php if ( $show_sidebar ) : ?>
	<div class="col-lg-3">
		<?php get_template_part( 'tpls/blog-sidebar' ); ?>
	</div>
	<?php endif; ?>

</div>