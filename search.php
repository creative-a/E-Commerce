<?php
/**
 *    Oxygen WordPress Theme
 *
 *    Laborator.co
 *    www.laborator.co
 */
defined( 'ABSPATH' ) || exit;

global $wp_query;

// Header
get_header();

?>
    <div class="row">
        <div class="col-md-12">

            <div class="search-results-header">
                <div class="row">
                    <div class="col-sm-8">

                        <div class="results-text">
                            <h3>
								<?php echo sprintf( _n( 'Showing <span>%d</span> result for', 'Showing <span>%d</span> results for:', $wp_query->found_posts, 'oxygen' ), $wp_query->found_posts ); ?>
                                <span class="search-text">&quot;<?php echo get_search_query( true ); ?>&quot;</span>
                            </h3>

                            <p>
								<?php _e( "Didn't find what you were looking for?", 'oxygen' ); ?>
                                <a href="#" id="search-again"><?php _e( 'Search again', 'oxygen' ); ?></a>
                            </p>
                        </div>

                    </div>

                    <div class="col-sm-4">

                        <form action="<?php echo home_url(); ?>" method="get" class="search search-box"
                              enctype="application/x-www-form-urlencoded">

                            <button type="submit" class="search-submit">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>

                            <input type="text" class="search_input" name="s" alt=""
                                   placeholder="<?php _e( 'Search...', 'oxygen' ); ?>" value=""/>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <section class="search-results">

        <div class="row">
			<?php
			// Search results
			if ( have_posts() ) :

				// Loop search results
				$i = 0;
				while ( have_posts() ) : the_post();

					?>
                    <div class="col-sm-4">

                        <article class="search-entry">

                            <a href="<?php the_permalink(); ?>" class="thumb">
								<?php

								if ( has_post_thumbnail() ) :
									echo oxygen_get_attachment_image( get_post_thumbnail_id(), 'shop-thumb-2' );
                                elseif ( file_exists( sprintf( '%s/assets/images/search-type-%s.png', get_template_directory(), get_post_type() ) ) ) :
									echo oxygen_image_placeholder_wrap_element( sprintf( '<img src="%s/assets/images/search-type-%s.png" class="plc" width="170" height="208" />', get_template_directory_uri(), get_post_type() ) );
								endif;
								?>
                            </a>

                            <a href="<?php the_permalink(); ?>" class="title">
								<?php the_title(); ?>
                                <span><?php echo get_post_type_object( get_post_type() )->labels->singular_name; ?></span>
                            </a>

                        </article>

                    </div>
					<?php

					// Clearer
					echo ++ $i % 3 == 0 ? '<div class="clear"></div>' : '';

				endwhile;

			endif;
			?>
        </div>
		<?php
		// Pagination
		the_posts_pagination( array(
			'mid_size' => 5
		) );
		?>

    </section>
<?php

// Footer
get_footer();