<?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */

global $wp_query;

$blog_title          = get_the_title( get_queried_object() );
$sidebar_position    = get_data( 'blog_sidebar_position' );
$pagination_position = get_data( 'blog_pagination_position' );

// Browsing categories
if ( is_category() ) {
	$cat_slug      = isset( $wp_query->query['category_name'] ) ? $wp_query->query['category_name'] : $wp_query->query['cat'];
	$category_name = get_term_by( ( is_numeric( $cat_slug ) ? 'id' : 'slug' ), basename( $cat_slug ), 'category' )->name;
	$blog_title    = sprintf( __( 'Category - %s', 'oxygen' ), $category_name );
} // Browsing tags
else if ( is_tag() ) {
	$tag_name   = get_term_by( 'slug', $wp_query->query['tag'], 'post_tag' )->name;
	$blog_title = sprintf( __( ' Tag - %s', 'oxygen' ), $tag_name );
} // Browsing author
else if ( is_author() ) {
	global $authordata;
	$blog_title = sprintf( __( 'Author - %s', 'oxygen' ), $authordata->display_name );
}
?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-head-title"><?php echo $blog_title; ?></h1>
    </div>
</div>


<div class="row">

    <div class="col-md-<?php echo $sidebar_position == 'Hide' ? 12 : 9; ?><?php echo $sidebar_position == 'Left' ? ' blog-content-right' : ''; ?>">

        <div class="blog">

			<?php
			// Posts loop
			while ( have_posts() ) : the_post();

				get_template_part( 'tpls/blog-post' );

			endwhile;

			// Pagination
			the_posts_pagination( array(
				'mid_size' => 5
			) );
			?>

        </div>

    </div>


	<?php if ( $sidebar_position == 'Right' || $sidebar_position == 'Left' ) : ?>
        <div class="col-md-3<?php echo $sidebar_position == 'Left' ? ' blog-left-sidebar' : ''; ?>">
			<?php get_template_part( 'tpls/blog-sidebar' ); ?>
        </div>
	<?php endif; ?>

</div>