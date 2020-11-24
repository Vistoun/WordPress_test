<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
	<?php wp_head(); ?>
</head>

<body id="<?php print esc_attr( get_stylesheet() ); ?>" <?php body_class(); ?>>
<?php do_action( 'startup_blog_body_top' ); ?>
<?php 
if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open();
	} else {
			do_action( 'wp_body_open' );
} ?>
<a class="skip-content" href="#main"><?php esc_html_e( 'Press "Enter" to skip to content', 'startup-blog' ); ?></a>
<div id="overflow-container" class="overflow-container">
	<?php do_action( 'startup_blog_before_header' ); ?>
	<?php
		// Elementor `header` location
		if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) :
	?>
	<header class="site-header" id="site-header" role="banner">
		<?php do_action( 'startup_blog_header_opening' ); ?>
		<div class="secondary-header">
			<?php
			ct_startup_blog_social_icons_output();
			if ( has_nav_menu( 'secondary' ) ) : ?>
			<button id="toggle-navigation-secondary" class="toggle-navigation-secondary" aria-expanded="false">
				<span class="screen-reader-text"><?php esc_html_e( 'open menu', 'startup-blog' ); ?></span>
				<span class="icon">+</span>
			</button>
			<div id="menu-secondary-container" class="menu-secondary-container">
				<?php get_template_part( 'menu', 'secondary' ); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php do_action( 'startup_blog_after_secondary_header' ); ?>
		<div class="primary-header">
			<div class="max-width">
				<div id="title-container" class="title-container">
					<?php get_template_part( 'logo' ) ?>
					<?php if ( get_bloginfo( 'description' ) && get_theme_mod( 'tagline' ) != 'footer' && get_theme_mod( 'tagline' ) != 'no' ) {
						echo '<p class="tagline">' . esc_html( get_bloginfo( 'description' ) ) . '</p>';
					} ?>
				</div>
				<button id="toggle-navigation" class="toggle-navigation" name="toggle-navigation" aria-expanded="false">
					<span class="screen-reader-text"><?php esc_html_e( 'open menu', 'startup-blog' ); ?></span>
					<?php echo ct_startup_blog_svg_output( 'toggle-navigation' ); ?>
				</button>
				<div id="menu-primary-container" class="menu-primary-container">
					<?php get_template_part( 'menu', 'primary' ); ?>
					<?php get_template_part( 'content/search-bar' ); ?>
				</div>
			</div>
		</div>
		<?php do_action( 'startup_blog_header_closing' ); ?>
	</header>
	<?php endif; ?>
	<?php ct_startup_blog_slider(); ?>
	<?php do_action( 'startup_blog_after_header' ); ?>
	<div class="main-content-container">
		<div class="max-width">
			<?php if ( get_theme_mod( 'sidebar' ) == 'before' ) {
				get_sidebar( 'primary' );
			} ?>
			<section id="main" class="main" role="main">
				<?php do_action( 'startup_blog_main_top' );
				if ( function_exists( 'yoast_breadcrumb' ) ) {
					yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
				}
