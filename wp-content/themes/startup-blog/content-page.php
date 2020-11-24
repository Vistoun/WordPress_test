<div <?php post_class(); ?>>
	<?php do_action( 'startup_blog_page_before' ); ?>
	<article>
		<?php ct_startup_blog_featured_image(); ?>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
		</div>
		<div class="post-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array(
				'before' => '<p class="singular-pagination">' . esc_html__( 'Pages:', 'startup-blog' ),
				'after'  => '</p>',
			) ); ?>
			<?php do_action( 'startup_blog_page_after' ); ?>
		</div>
		<div class="post-meta">
			<?php get_sidebar( 'after-page-content' ); ?>
		</div>
	</article>
</div>
<?php comments_template(); ?>