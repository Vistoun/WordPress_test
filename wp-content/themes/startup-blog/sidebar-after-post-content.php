<?php if ( is_active_sidebar( 'after-post-content' ) ) : ?>
	<aside class="sidebar sidebar-after-post-content" id="sidebar-after-post-content" role="complementary">
		<?php dynamic_sidebar( 'after-post-content' ); ?>
	</aside>
<?php endif;