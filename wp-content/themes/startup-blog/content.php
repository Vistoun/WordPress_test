<div <?php post_class(); ?>>
	<?php do_action( 'startup_blog_post_before' ); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
			<?php get_template_part( 'content/post-byline' );
			if ( get_theme_mod( 'author_avatars' ) != 'no' ) {
				echo get_avatar( get_the_author_meta( 'ID' ), 42, '', get_the_author() );
			}
			get_template_part( 'content/comments-link' ); ?>
		</div>
		<?php ct_startup_blog_featured_image(); ?>
		<div class="post-content">
			<?php ct_startup_blog_output_last_updated_date(); ?>
			<?php the_content(); ?>
			<?php wp_link_pages( array(
				'before' => '<p class="singular-pagination">' . esc_html__( 'Pages:', 'startup-blog' ),
				'after'  => '</p>',
			) ); ?>
			<?php do_action( 'startup_blog_post_after' ); ?>
		</div>
		<div class="post-meta">
			<?php get_sidebar( 'after-post-content' ); ?>
			<?php get_template_part( 'content/post-author' ); ?>
			<?php get_template_part( 'content/post-categories' ); ?>
			<?php get_template_part( 'content/post-tags' ); ?>
		</div>
	</article>
</div>
<?php comments_template(); ?>