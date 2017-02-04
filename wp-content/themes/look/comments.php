<?php

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			look_title_comments ();
			?>
		</h2>


		<ol class="comment-list">
			<?php
			wp_list_comments( 'type=comment&callback=look_comment' );
			?>
		</ol><!-- .comment-list -->
        <?php look_comment_nav(); ?>

	<?php endif;  ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'look' ); ?></p>
<?php endif; ?>
<?php
	$args = array(
		'comment_notes_before' => '',
		'fields' => '
			<p class="comment-notes">Your email address will not be published.</p>
			<div class="row">
				<div class="comment-form-author col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<input placeholder="' . esc_attr__( 'Your name*', 'look' ) . '" type="text" required="required" size="30" value="" name="author" id="author">
				</div>
				<div class="comment-form-email col-lg-6 col-md-6 col-sm-6 col-xs-12">
					<input placeholder="' . esc_attr__( 'Your email*', 'look' ) . '" type="email" required="required" size="30" value="" name="email" id="email">
				</div>
			</div>
			<div class="row">
				<div class="comment-form-url col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<input placeholder="' . esc_attr__( 'Website', 'look' ) . '" type="url" size="30" value="" name="url" id="url">
				</div>
			</div>
		',

		// change the title of the reply section
		'title_reply'=> __( 'Leave your comment', 'look' ),

		// remove "Text or HTML to be displayed after the set of comment fields"
		'comment_notes_after' => '',

		// redefine your own textarea (the comment body)
		'comment_field' => '<div class="row"><div class="comment-form-comment col-lg-12 col-md-12 col-sm-12 col-xs-12"><textarea rows="6" placeholder="' . esc_attr__( 'Your comment', 'look' ) . '" name="comment" aria-required="true"></textarea></div></div>',

		// change the title of send button 
		'label_submit'=> __( 'Post comment', 'look' ),
	);

	comment_form( $args );
?>

</div><!-- .comments-area -->
