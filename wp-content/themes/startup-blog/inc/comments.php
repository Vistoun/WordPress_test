<?php

//----------------------------------------------------------------------------------
// Update the markup of individual comments
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_startup_blog_customize_comments' ) ) ) {
	function ct_startup_blog_customize_comments( $comment, $args, $depth ) { ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-author">
				<?php echo get_avatar( get_comment_author_email(), 44, '', get_comment_author() ); ?>
				<span class="author-name"><?php comment_author_link(); ?></span>
			</div>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<span
						class="awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'startup-blog' ) ?></span>
					<br/>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<div class="comment-footer">
				<?php comment_reply_link( array_merge( $args, array(
					'depth'     => $depth,
					'max_depth' => $args['max_depth'],
					'before'    => '<i class="fas fa-reply" aria-hidden="true"></i>'
				) ) ); ?>
				<?php edit_comment_link(
					esc_html__( 'Edit', 'startup-blog' ),
					'<i class="fas fa-edit" aria-hidden="true"></i>'
				); ?>
			</div>
		</article>
		<?php
	}
}

//----------------------------------------------------------------------------------
// Update the form fields in the comment form
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_update_fields' ) ) {
	function ct_startup_blog_update_fields( $fields ) {

		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$label     = $req ? '*' : ' ' . esc_html__( '(optional)', 'startup-blog' );
		$aria_req  = $req ? "aria-required='true'" : '';

		$fields['author'] =
			'<p class="comment-form-author">
	            <label for="author">' . esc_html__( "Name", "startup-blog" ) . esc_html( $label ) . '</label>
	            <input id="author" name="author" type="text" placeholder="' . esc_attr__( "Jane Doe", "startup-blog" ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30" ' . esc_html( $aria_req ) . ' />
	        </p>';

		$fields['email'] =
			'<p class="comment-form-email">
	            <label for="email">' . esc_html__( "Email", "startup-blog" ) . esc_html( $label ) . '</label>
	            <input id="email" name="email" type="email" placeholder="' . esc_attr__( "name@email.com", "startup-blog" ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) .
			'" size="30" ' . esc_html( $aria_req ) . ' />
	        </p>';

		$fields['url'] =
			'<p class="comment-form-url">
	            <label for="url">' . esc_html__( "Website", "startup-blog" ) . '</label>
	            <input id="url" name="url" type="url" placeholder="http://google.com" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size="30" />
	            </p>';

		return $fields;
	}
}
add_filter( 'comment_form_default_fields', 'ct_startup_blog_update_fields' );

//----------------------------------------------------------------------------------
// Update the comment textarea field
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_update_comment_field' ) ) {
	function ct_startup_blog_update_comment_field( $comment_field ) {

		// don't filter the WooCommerce review form
		if ( function_exists( 'is_woocommerce' ) ) {
			if ( is_woocommerce() ) {
				return $comment_field;
			}
		}
		$comment_field =
			'<p class="comment-form-comment">
	            <label for="comment">' . esc_html__( "Comment", "startup-blog" ) . '</label>
	            <textarea required id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
	        </p>';

		return $comment_field;
	}
}
add_filter( 'comment_form_field_comment', 'ct_startup_blog_update_comment_field' );

//----------------------------------------------------------------------------------
// Remove comment notes with markup that displays after the comment form
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_remove_comments_notes_after' ) ) {
	function ct_startup_blog_remove_comments_notes_after( $defaults ) {
		$defaults['comment_notes_after'] = '';
		return $defaults;
	}
}
add_action( 'comment_form_defaults', 'ct_startup_blog_remove_comments_notes_after' );