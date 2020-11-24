<?php

function ct_startup_blog_register_theme_page() {
	add_theme_page( 
		sprintf( esc_html__( '%s Dashboard', 'startup-blog' ), wp_get_theme() ), 
		sprintf( esc_html__( '%s Dashboard', 'startup-blog' ), wp_get_theme() ), 
		'edit_theme_options', 
		'startup-blog-options', 
		'ct_startup_blog_options_content'
	);
}
add_action( 'admin_menu', 'ct_startup_blog_register_theme_page' );

function ct_startup_blog_options_content() {

	$pro_url = 'https://www.competethemes.com/startup-blog-pro/?utm_source=wp-dashboard&utm_medium=Dashboard&utm_campaign=Startup%20Blog%20Pro%20-%20Dashboard';
	?>
	<div id="startup-blog-dashboard-wrap" class="wrap startup-blog-dashboard-wrap">
		<h2><?php printf( esc_html__( '%s Dashboard', 'startup-blog' ), wp_get_theme() ); ?></h2>
		<?php do_action( 'ct_startup_blog_theme_options_before' ); ?>
		<div class="main">
			<?php if ( defined( 'STARTUP_BLOG_PRO_FILE' ) ) : ?>
			<div class="thanks-upgrading" style="background-image: url(<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/bg-texture.png'; ?>)">
				<h3>Thanks for upgrading!</h3>
				<p>You can find the new features in the Customizer</p>
			</div>
			<?php endif; ?>
			<?php if ( !defined( 'STARTUP_BLOG_PRO_FILE' ) ) : ?>
			<div class="getting-started">
				<h3>Get Started with Startup Blog</h3>
				<p>Follow this step-by-step guide to customize your website with Startup Blog:</p>
				<a href="https://www.competethemes.com/help/getting-started-startup-blog/" target="_blank">Read the Getting Started Guide</a>
			</div>
			<div class="pro">
				<h3>Customize More with Startup Blog Pro</h3>
				<p>Add 8 new customization features to your site with the <a href="<?php echo $pro_url; ?>" target="_blank">Startup Blog Pro</a> plugin.</p>
				<ul class="feature-list">
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/layouts.png'; ?>" />
						</div>
						<div class="text">
							<h4>New Layouts</h4>
							<p>New layouts help your content look and perform its best. You can switch to new layouts effortlessly from the Customizer, or from specific posts or pages.</p>
							<p>Startup Blog Pro adds 6 new layouts.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/post-templates.png'; ?>" />
						</div>
						<div class="text">
							<h4>Post Templates</h4>
							<p>Beautiful post templates help you share your story with style. Switch any post to one of four new templates.</p>
							<p>Startup Blog Pro adds four post templates and a default template setting.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/custom-colors.png'; ?>" />
						</div>
						<div class="text">
							<h4>Custom Colors</h4>
							<p>Custom colors let you match the color of your site with your brand. Point-and-click to select a color, and watch your site update instantly.</p>
							<p>With 61 color controls, you can change the color of any element on your site.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/fonts.png'; ?>" />
						</div>
						<div class="text">
							<h4>New Fonts</h4>
							<p>Stylish new fonts add character and charm to your content. Select and instantly preview fonts from the Customizer.</p>
							<p>Since Startup Blog Pro is powered by Google Fonts, it comes with 728 fonts for you to choose from.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/font-sizes.png'; ?>" />
						</div>
						<div class="text">
							<h4>Font Sizes</h4>
							<p>Change the size of the fonts used throughout Startup Blog. Optimize the reading experience for the custom fonts you choose.</p>
							<p>Startup Blog Pro has font size controls including mobile and desktop specific settings.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/featured-videos.png'; ?>" />
						</div>
						<div class="text">
							<h4>Featured Videos</h4>
							<p>Featured Videos are an easy way to share videos in place of Featured Images. Instantly embed a Youtube video by copying and pasting its URL into an input.</p>
							<p>Startup Blog Pro auto-embeds videos from Youtube, Vimeo, DailyMotion, Flickr, Animoto, TED, Blip, Cloudup, FunnyOrDie, Hulu, Vine, WordPress.tv, and VideoPress.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/featured-image-size.png'; ?>" />
						</div>
						<div class="text">
							<h4>Featured Image Size</h4>
							<p>Set each Featured Image to the perfect size. You can change the aspect ratio for all Featured Images and individual Featured Images with ease.</p>
							<p>Startup Blog Pro includes twelve different aspect ratios for your Featured Images.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/footer-text.png'; ?>" />
						</div>
						<div class="text">
							<h4>Custom Footer Text</h4>
							<p>Custom footer text lets you further brand your site. Just start typing to add your own text to the footer.</p>
							<p>The footer text supports plain text and full HTML for adding links.</p>
						</div>
					</li>
				</ul>
				<p><a href="<?php echo $pro_url; ?>" target="_blank">Click here</a> to view Startup Blog Pro now, and see what it can do for your site.</p>
			</div>
			<div class="pro-ad" style="background-image: url(<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/bg-texture.png'; ?>)">
				<h3>Add Incredible Flexibility to Your Site</h3>
				<p>Start customizing with Startup Blog Pro today</p>
				<a href="<?php echo $pro_url; ?>" target="_blank">View Startup Blog Pro</a>
			</div>
			<?php endif; ?>
		</div>
		<div class="sidebar">
			<div class="dashboard-widget">
				<h4>More Amazing Resources</h4>
				<ul>
					<li><a href="https://www.competethemes.com/documentation/startup-blog-support-center/" target="_blank">Startup Blog Support Center</a></li>
					<li><a href="https://wordpress.org/support/theme/startup-blog" target="_blank">Support Forum</a></li>
					<li><a href="https://www.competethemes.com/help/startup-blog-changelog/" target="_blank">Changelog</a></li>
					<li><a href="https://www.competethemes.com/help/startup-blog-css-snippets/" target="_blank">CSS Snippets</a></li>
					<li><a href="https://www.competethemes.com/help/child-theme-startup-blog/" target="_blank">Starter child theme</a></li>
					<li><a href="https://www.competethemes.com/help/startup-blog-demo-data/" target="_blank">Startup Blog demo data</a></li>
					<li><a href="<?php echo $pro_url; ?>" target="_blank">Startup Blog Pro</a></li>
				</ul>
			</div>
			<div class="dashboard-widget">
				<h4>User Reviews</h4>
				<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/reviews.png'; ?>" />
				<p>Users are loving Startup Blog! <a href="https://wordpress.org/support/theme/startup-blog/reviews/?filter=5#new-post" target="_blank">Click here</a> to leave your own review</p>
			</div>
			<div class="dashboard-widget">
				<h4>Reset Customizer Settings</h4>
				<p><b>Warning:</b> Clicking this buttin will erase the Startup Blog theme's current settings in the Customizer.</p>
				<form method="post">
					<input type="hidden" name="startup_blog_reset_customizer" value="startup_blog_reset_customizer_settings"/>
					<p>
						<?php wp_nonce_field( 'startup_blog_reset_customizer_nonce', 'startup_blog_reset_customizer_nonce' ); ?>
						<?php submit_button( 'Reset Customizer Settings', 'delete', 'delete', false ); ?>
					</p>
				</form>
			</div>
		</div>
		<?php do_action( 'ct_startup_blog_theme_options_after' ); ?>
	</div>
<?php }