<?php
global $post;
$button_text = get_theme_mod('slider_button_text');
?>
<li class="<?php echo esc_attr( $classes ); ?>">
	<div class="content-container">
		<div class="max-width">
			<div class="title">
				<?php
				$custom_title = get_post_meta( $post->ID, 'startup_blog_title', true);
				echo $custom_title ? esc_html( $custom_title ) : esc_html( get_the_title() );
				?>
			</div>
			<?php
			// echo'ing get_the_excerpt() instead of using the_excerpt() to avoid plugins adding content via filters.
			// Ex. Jetpack will add social sharing buttons into the slide when using the_excerpt(): http://pics.competethemes.com/l3AM
			echo wp_kses_post( wpautop( get_the_excerpt() ) ); 
			if ( get_theme_mod( 'slider_button_display' ) != 'no' ) : ?>
				<a class="read-more" href="<?php the_permalink(); ?>">
					<?php
					if ( $button_text == '' ) {
						esc_html_e( 'Read more', 'startup-blog');
					} else {
						echo esc_html( $button_text );
					}
					?>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<div class="image-container" style="background-image: url('<?php the_post_thumbnail_url(); ?>');"></div>
</li>