<?php
/**
 *	Oxygen WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */

global $post, $authordata;

$posts_url = get_author_posts_url( $authordata->ID );
$user_url = $authordata->user_url;

$thumbnails = get_data( 'blog_single_thumbnails' );
$show_category = get_data( 'blog_category' );
$show_tags = get_data( 'blog_tags' );
$author_info = get_data( 'blog_author_info' );
$post_date = get_data( 'blog_post_date' );
$autoswitch = get_data( 'blog_gallery_autoswitch' );

if ( ! $user_url ) {
	$user_url = $posts_url;
}

$author_link = '<a href="' . $user_url . '">' . get_the_author() . '</a>';

$post_slider_images = oxygen_get_field( 'post_slider_images' );

$autoswitch = is_numeric( $autoswitch ) ? $autoswitch : 5;

// Featured image size
$blog_thumb_height = get_data( 'blog_thumbnail_height' );
$featured_image_size = apply_filters( 'oxygen_blog_single_featured_image_size', array( 870, $blog_thumb_height ? $blog_thumb_height : 0 ) );
?>
<div class="single_post">

	<?php if ( $thumbnails && is_array( $post_slider_images ) && count( $post_slider_images ) ) : ?>
	<div class="post_img nivox post-slider-images" data-autoswitch="<?php echo $autoswitch * 1000; ?>">
		
		<div class="loading">
			<?php _e( 'Loading images...', 'oxygen' ); ?>
		</div>
		<?php
			
			// Enqueue slick slider
			oxygen_enqueue_slick_carousel();
			
			foreach ( $post_slider_images as $i => $attachment_id ) :
			
				if ( is_array( $attachment_id ) ) {
					$attachment_id = $attachment_id['id'];
				}
				
				$attachment = get_post( $attachment_id );
				$image = wp_get_attachment_image_src( $attachment->ID, 'original' );
				$caption = $attachment->post_excerpt;
				
				$link = $attachment->_wp_attachment_image_alt;
				
				?><a href="<?php echo strstr( $link, 'http' ) ? $link : $image[0]; ?>" title="<?php echo esc_attr( $caption ); ?>" class="hidden" data-lightbox-gallery="post-gallery">
						<?php echo oxygen_get_attachment_image( $attachment_id, $featured_image_size ); ?>
				</a><?php
				
			endforeach;
		?>
	</div>
	<?php
		// Show featured image
		elseif ( $thumbnails && has_post_thumbnail() ) :
            $attachment_id = get_post_thumbnail_id( get_the_id() );
			$attachment = wp_get_attachment_image_src( $attachment_id, 'original' );

			?>
			<div class="post_img nivo">		
				<a href="<?php echo is_array( $attachment ) ? $attachment[0] : site_url(); ?>">
					<?php echo oxygen_get_attachment_image( $attachment_id, $featured_image_size ); ?>
				</a>
			</div>
			<?php 
				
		endif; 
	?>
									
	<div class="post_details">
		
		<h1><?php the_title(); ?></h1>
		
		<h3><?php
			// Category
			if ( $show_category && has_category() ) {
				_e( 'In ', 'oxygen' ); 
				the_category( ', ' );
			}
			
			// Post date
			if ( $post_date ) { 
				echo sprintf( ' <strong>' . __( 'on %s', 'oxygen' ) . '</strong>', get_the_time( 'F d, Y - H:i' ) );
				echo has_tag() ? ',' : '';
			}
			
			// Tags
			if ( $show_tags && has_tag() ) { 
				the_tags( __( 'Tags', 'oxygen' ) . ' ' ); 
			} 
		?></h3>
		
		<hr>
		
		<div class="post-content">
			<?php 
				// Content
				the_content(); 
				
				// Post pagination
				wp_link_pages( array(
					'before' => '<div class="post-pagination">' . __( 'Pages:', 'oxygen' ), 
					'after' => '</div>',
					'pagelink' => '<span>%</span>'
				) );
			?>
		</div>
								
		
		<?php if ( $author_info ) : ?>
		<div class="author_post">
			<div class="row">
			
				<div class="col-sm-2 col-xs-4">
					<div class="author_img">
						<a href="<?php echo $user_url; ?>"><?php echo get_avatar( $authordata->ID ); ?></a>
					</div>
				</div>

				<div class="col-sm-10 col-xs-8 mobile-padding">
				
					<span class="author_text">
						<?php echo sprintf( __( 'About the author: %s', 'oxygen' ), $author_link ); ?>
					</span>
					
					<p class="author_about">
						<?php echo $authordata->description ? nl2br( $authordata->description ) : __( 'No other information about this author.', 'oxygen' ); ?>
					</p>
				</div>
			
			</div>
		</div>
		<?php endif; ?>
	
	</div>

</div>
<?php 
	
// Share post
if ( get_data( 'blog_share_story' ) ) : 

	?><div class="share-post">
		<h3><?php _e( 'Share This Story', 'oxygen' ); ?>:</h3>
		
		<div class="share-post-links">	
			
			<?php 
				$share_story_networks = get_data( 'blog_share_story_networks' );
				
				foreach ( $share_story_networks['visible'] as $network_id => $network ) {
					
					if ( $network_id == 'placebo' ) {
						continue;
					}
					
					share_story_network_link( $network_id, get_the_id() );
				}
			?>
			
		</div>
		
	</div><?php 
		
endif; 

// Comments
comments_template();
