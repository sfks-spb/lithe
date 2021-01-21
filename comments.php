<?php

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
*/

if ( post_password_required() ) {
	return;
}

if ( $comments ): ?>

	<div class="comments" id="comments">

        <?php
            wp_list_comments();
        ?>

    </div>

<?php endif; ?>

<?php


if ( comments_open() || pings_open() ) {

	comment_form(
		array(
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h2>',
		)
	);

    ?>

    <span class="comment-consent">

        <?php printf( __( 'By clicking "Post Comment" you\'re agreeing to our %1$s.', 'lithe' ), '<a href="' . esc_attr( get_home_url( null, 'about/privacy' ) ) . '">' . __( 'privacy policy', 'lithe' ) . '</a>' ); ?>

    </span>

    <?php
} elseif ( is_single() ) {

	?>

	<div class="comment-respond" id="respond">

		<p class="comments-closed"><?php _e( 'Comments are closed.', 'lithe' ); ?></p>

	</div>

	<?php
}
