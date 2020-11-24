<?php
// TRT: This template is used for search results pages so the author and date is not displayed
?>
<div <?php post_class(); ?>>
	<?php do_action( 'startup_blog_archive_post_before' ); ?>
	<article>
		<div class='post-header'>
			<?php do_action( 'startup_blog_sticky_post_status' ); ?>
			<h2 class='post-title'>
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
			</h2>
		</div>
		<?php ct_startup_blog_featured_image(); ?>
		<div class="post-content">
			<?php echo wp_kses_post( ct_startup_blog_excerpt() ); ?>
		</div>
	</article>
	<?php do_action( 'startup_blog_archive_post_after' ); ?>
</div>