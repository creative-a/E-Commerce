<?php
/**
 *	Oxygen WordPress Theme
 *	
 *	Laborator.co
 *	www.laborator.co 
 */
defined( 'ABSPATH' ) || exit;

global $authordata;

if ( ! ( have_comments() || comments_open() ) ) {
	return;
}
?>
<div class="comments" id="comments">
	
	<?php if ( have_comments() ) : ?>
	<h3>
		<?php _e( 'Comments', 'oxygen' ); ?> 
		<span>(<?php echo wp_count_comments( get_the_id() )->approved; ?>)</span>
	</h3>
	
	
	<?php 
		// Comments list
		wp_list_comments( array(
			'callback' => 'oxygen_blog_comment_callback', 
			'end-callback' => 'oxygen_blog_comment_end_callback'
		) );
		
		// Comments pagination
		paginate_comments_links();
	?>
	
	<?php endif; ?>
	
	<?php if ( comments_open() ) : ?>
	<div class="reply_form<?php echo ! have_comments() ? ' form-only' : ''; ?>"><?php 
		comment_form();
	?></div>
	<?php endif; ?>
	
</div>