<div <?php post_class(); ?>>
	<?php do_action( 'startup_blog_archive_post_before' ); ?>
	<article>
		<div class='post-header'>
			<?php do_action( 'startup_blog_sticky_post_status' ); ?>
			<h2 class='post-title'>
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
			</h2>
			<?php get_template_part( 'content/post-byline' );
			if ( get_theme_mod( 'author_avatars' ) != 'no' ) {
				echo get_avatar( get_the_author_meta( 'ID' ), 42, '', get_the_author() );
			}
		    get_template_part( 'content/comments-link' ); ?>
		</div>
		<?php ct_startup_blog_featured_image(); ?>
		<div class="post-content">
			<?php echo wp_kses_post( ct_startup_blog_excerpt() ); ?>
		</div>
	</article>
	<?php do_action( 'startup_blog_archive_post_after' ); ?>
</div>