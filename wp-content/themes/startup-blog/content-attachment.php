<div <?php post_class(); ?>>
	<?php do_action( 'startup_blog_attachment_before' ); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
		</div>
		<div class="post-content">
			<?php
			$image = wp_get_attachment_image($post->ID, 'full');
			$image_meta = wp_prepare_attachment_for_js($post->ID);
			?>
			<div class="attachment-container">
				<?php echo wp_kses( $image, array(
					'img' => array(
						'width' => array(),
						'height' => array(),
						'src' => array(),
						'class' => array(),
						'alt' => array(),
						'srcset' => array(),
						'sizes' => array(),
					) ) ); ?>
				<span class="attachment-caption">
					<?php echo wp_kses_post( $image_meta['caption'] ); ?>
				</span>
			</div>
			<?php echo wp_kses_post( wpautop( $image_meta['description'] ) ); ?>
		</div>
	</article>
	<?php do_action( 'startup_blog_attachment_after' ); ?>
</div>
<?php comments_template(); ?>