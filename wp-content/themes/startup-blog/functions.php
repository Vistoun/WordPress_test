<?php

//----------------------------------------------------------------------------------
//	Include all required files
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/comments.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/customizer.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/last-updated-meta-box.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/review.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/scripts.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/social-icons.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/user-profile.php' );

//----------------------------------------------------------------------------------
//	Include review request
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'dnh/handler.php' );
new WP_Review_Me( array(
		'days_after' => 14,
		'type'       => 'theme',
		'slug'       => 'startup-blog',
		'message'    => __( 'Hey! Sorry to interrupt, but you\'ve been using Startup Blog for a little while now. If you\'re happy with this theme, could you take a minute to leave a review? <i>You won\'t see this notice again after closing it.</i>', 'startup-blog' )
	)
);

//----------------------------------------------------------------------------------
//	Set content width variable
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_startup_blog_set_content_width' ) ) ) {
	function ct_startup_blog_set_content_width() {
		if ( ! isset( $content_width ) ) {
			$content_width = 780;
		}
	}
}
add_action( 'after_setup_theme', 'ct_startup_blog_set_content_width', 0 );

//----------------------------------------------------------------------------------
//	Add theme support for various features, register menus, load text domain
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_startup_blog_theme_setup' ) ) ) {
	function ct_startup_blog_theme_setup() {

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );
		// Support for infinite scroll in jetpack
		add_theme_support( 'infinite-scroll', array(
			'container' => 'loop-container',
			'footer'    => 'overflow-container',
			'render'    => 'ct_startup_blog_infinite_scroll_render'
		) );
		add_theme_support( 'custom-logo', array(
			'height'      => 60,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true
		) );
		add_theme_support( 'custom-header', array(
			'width' 			=> '1200',
			'height' 		  => '360',
			'header-text' => false,
			'flex-height' => true,
			'flex-width'  => true
		) );
		// TRT Note: this is added so users can customize the excerpt if they add pages to the slider
		add_post_type_support( 'page', array('excerpt', 'custom-fields') );
		
		// WooCommerce support
		add_theme_support( 'woocommerce' );
		// Support WooCommerce image gallery features 
		add_theme_support( 'wc-product-gallery-zoom' ); 
		add_theme_support( 'wc-product-gallery-lightbox' ); 
		add_theme_support( 'wc-product-gallery-slider' );

		// Gutenberg - full images
		add_theme_support( 'align-full' );

		// Gutenberg - add support for editor styles
		add_theme_support('editor-styles');

		// Gutenberg - modify the font sizes
		add_theme_support( 'editor-font-sizes', array(
			array(
					'name' => __( 'small', 'startup-blog' ),
					'shortName' => __( 'S', 'startup-blog' ),
					'size' => 12,
					'slug' => 'small'
			),
			array(
					'name' => __( 'regular', 'startup-blog' ),
					'shortName' => __( 'M', 'startup-blog' ),
					'size' => 16,
					'slug' => 'regular'
			),
			array(
					'name' => __( 'large', 'startup-blog' ),
					'shortName' => __( 'L', 'startup-blog' ),
					'size' => 28,
					'slug' => 'large'
			),
			array(
					'name' => __( 'larger', 'startup-blog' ),
					'shortName' => __( 'XL', 'startup-blog' ),
					'size' => 38,
					'slug' => 'larger'
			)
		) );

		register_nav_menus( array(
			'primary'   => esc_html__( 'Primary', 'startup-blog' ),
			'secondary' => esc_html__( 'Secondary', 'startup-blog' )
		) );

		load_theme_textdomain( 'startup-blog', get_template_directory() . '/languages' );

		// image size for Featured Images in two column layouts
		add_image_size( 'two-columns', 640 );
	}
}
add_action( 'after_setup_theme', 'ct_startup_blog_theme_setup', 10 );

//-----------------------------------------------------------------------------
// Load custom stylesheet for the post editor
//-----------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_add_editor_styles' ) ) {
	function ct_startup_blog_add_editor_styles() {
		add_editor_style( 'styles/editor-style.css' );
	}
}
add_action( 'admin_init', 'ct_startup_blog_add_editor_styles' );

//----------------------------------------------------------------------------------
//	Register widget areas
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_startup_blog_register_widget_areas' ) ) ) {
	function ct_startup_blog_register_widget_areas() {

		register_sidebar( array(
			'name'          => esc_html__( 'Primary Sidebar', 'startup-blog' ),
			'id'            => 'primary',
			'description'   => esc_html__( 'Widgets in this area will be shown in the sidebar next to the main content.', 'startup-blog' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'After Post Content', 'startup-blog' ),
			'id'            => 'after-post-content',
			'description'   => esc_html__( 'Widgets in this area will be shown on posts after the content.', 'startup-blog' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'After Page Content', 'startup-blog' ),
			'id'            => 'after-page-content',
			'description'   => esc_html__( 'Widgets in this area will be shown on pages after the content.', 'startup-blog' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'Footer', 'startup-blog' ),
			'id'            => 'footer-area',
			'description'   => esc_html__( 'Widgets in this area will be shown in the footer.', 'startup-blog' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
	}
}
add_action( 'widgets_init', 'ct_startup_blog_register_widget_areas' );

//----------------------------------------------------------------------------------
//	Output excerpt/content
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_excerpt' ) ) {
	function ct_startup_blog_excerpt() {
		global $post;
		$show_full_post = get_theme_mod( 'full_post' );
		$ismore         = strpos( $post->post_content, '<!--more-->' );
		if ( $show_full_post === 'yes' || $ismore ) {
			return wpautop( get_the_content() );
		} else {
			return wpautop( get_the_excerpt() );
		}
	}
}

//----------------------------------------------------------------------------------
//	Update excerpt length. Allow user input from Customizer.
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_custom_excerpt_length' ) ) {
	function ct_startup_blog_custom_excerpt_length( $length ) {

		$new_excerpt_length = get_theme_mod( 'excerpt_length' );

		if ( ! empty( $new_excerpt_length ) && $new_excerpt_length != 30 ) {
			return $new_excerpt_length;
		} elseif ( $new_excerpt_length === 0 ) {
			return 0;
		} else {
			return 30;
		}
	}
}
add_filter( 'excerpt_length', 'ct_startup_blog_custom_excerpt_length', 99 );

//----------------------------------------------------------------------------------
// Add plain ellipsis for automatic excerpts (removes "[]")
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_excerpt_ellipsis' ) ) {
	function ct_startup_blog_excerpt_ellipsis() {
		return '&#8230;';
	}
}
add_filter( 'excerpt_more', 'ct_startup_blog_excerpt_ellipsis', 10 );

//----------------------------------------------------------------------------------
// Removes the "(more...)" link that will only appear if a more tag is used
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_remove_read_more_link' ) ) {
	function ct_startup_blog_remove_read_more_link() {
		return '';
	}
}
add_filter( 'the_content_more_link', 'ct_startup_blog_remove_read_more_link', 9999 ); // more tags

//----------------------------------------------------------------------------------
// Don't scroll to text after clicking a "more tag" link
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_remove_more_link_scroll' ) ) {
	function ct_startup_blog_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'ct_startup_blog_remove_more_link_scroll' );

//----------------------------------------------------------------------------------
// Output the Featured Image
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_featured_image' ) ) {
	function ct_startup_blog_featured_image() {

		global $post;
		$featured_image = '';

		if ( has_post_thumbnail( $post->ID ) ) {
			$size = 'full';
			$two_column_layouts = array( 'two-right', 'two-left', 'two-narrow', 'two-wide' );
			if ( in_array( get_theme_mod( 'layout' ), $two_column_layouts ) ) {
				$size = 'two-columns';
			}

			if ( is_singular() ) {
				$featured_image = '<div class="featured-image">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>';
			} else {
				$featured_image = '<div class="featured-image"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . get_the_post_thumbnail( $post->ID, $size ) . '</a></div>';
			}
		}

		$featured_image = apply_filters( 'ct_startup_blog_featured_image', $featured_image );

		if ( $featured_image ) {
			echo $featured_image;
		}
	}
}

/*
 * WP will apply the ".menu-primary-items" class & id to the containing <div> instead of <ul>
 * making styling confusing. This simple wrapper adds a unique class to make styling easier.
 */
if ( ! function_exists( 'ct_startup_blog_wp_page_menu' ) ) {
	function ct_startup_blog_wp_page_menu() {
		wp_page_menu( array(
				"menu_class" => "menu-unset",
				"depth"      => - 1
			)
		);
	}
}

//----------------------------------------------------------------------------------
// Add a label to "sticky" posts on archive pages
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_sticky_post_marker' ) ) {
	function ct_startup_blog_sticky_post_marker() {
		if ( is_sticky() && !is_archive() && !is_search() ) {
			echo '<div class="sticky-status"><span>' . esc_html__( "Featured", "startup-blog" ) . '</span></div>';
		}
	}
}
add_action( 'startup_blog_sticky_post_status', 'ct_startup_blog_sticky_post_marker' );

//----------------------------------------------------------------------------------
// Reset Customizer settings added by Startup Blog. Button added in theme-options.php.
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_reset_customizer_options' ) ) {
	function ct_startup_blog_reset_customizer_options() {

		if ( !isset( $_POST['startup_blog_reset_customizer'] ) || 'startup_blog_reset_customizer_settings' !== $_POST['startup_blog_reset_customizer'] ) {
			return;
		}

		if ( ! wp_verify_nonce( wp_unslash( $_POST['startup_blog_reset_customizer_nonce'] ), 'startup_blog_reset_customizer_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$mods_array = array(
			'slider_posts_or_pages',
			'slider_recent_posts',
			'slider_post_category',
			'slider_pages',
			'slider_display',
			'slider_arrow_navigation',
			'slider_dot_navigation',
			'slider_button_text',
			'slider_sticky',
			'slider_time',
			'slider_auto_rotate',
			'color_primary',
			'color_secondary',
			'color_background',
			'layout',
			'tagline',
			'post_byline_date',
			'post_byline_author',
			'author_avatars',
			'comment_link',
			'author_box',
			'sidebar',
			'full_post',
			'excerpt_length',
			'last_updated'
		);

		$social_sites = ct_startup_blog_social_array();

		// add social site settings to mods array
		foreach ( $social_sites as $social_site => $value ) {
			$mods_array[] = $social_site;
		}

		$mods_array = apply_filters( 'ct_startup_blog_mods_to_remove', $mods_array );

		foreach ( $mods_array as $theme_mod ) {
			remove_theme_mod( $theme_mod );
		}

		$redirect = admin_url( 'themes.php?page=startup-blog-options' );
		$redirect = add_query_arg( 'startup_blog_status', 'deleted', $redirect );

		// safely redirect
		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'admin_init', 'ct_startup_blog_reset_customizer_options' );

//----------------------------------------------------------------------------------
// Notice to let users know when their Customizer settings have been reset
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_delete_settings_notice' ) ) {
	function ct_startup_blog_delete_settings_notice() {

		if ( isset( $_GET['startup_blog_status'] ) ) {
			if ( $_GET['startup_blog_status'] == 'deleted' ) {
				?>
				<div class="updated">
					<p><?php esc_html_e( 'Customizer settings deleted.', 'startup-blog' ); ?></p>
				</div>
				<?php
			}
		}
	}
}
add_action( 'admin_notices', 'ct_startup_blog_delete_settings_notice' );

//----------------------------------------------------------------------------------
// Add body classes for styling purposes
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_body_class' ) ) {
	function ct_startup_blog_body_class( $classes ) {

		global $post;
		$full_post       = get_theme_mod( 'full_post' );
		$sidebar_display = get_theme_mod( 'sidebar' );
		$layout          = get_theme_mod( 'layout' );

		if ( $full_post == 'yes' ) {
			$classes[] = 'full-post';
		}
		if ( $sidebar_display == 'no' ) {
			$classes[] = 'hide-sidebar';
		}
		// don't add layout classes if PRO plugin is active
		if ( !defined( 'STARTUP_BLOG_PRO_FILE' ) ) {
			$classes[] = esc_attr( $layout );
		}

		return $classes;
	}
}
add_filter( 'body_class', 'ct_startup_blog_body_class' );

//----------------------------------------------------------------------------------
// Add a shared class for post divs on archive and single pages
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_post_class' ) ) {
	function ct_startup_blog_post_class( $classes ) {
		$classes[] = 'entry';
		return $classes;
	}
}
add_filter( 'post_class', 'ct_startup_blog_post_class' );

//----------------------------------------------------------------------------------
// Used to get messy SVG HTML out of content markup. Currently one SVG used for the mobile menu icon
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_svg_output' ) ) {
	function ct_startup_blog_svg_output( $type ) {

		$svg = '';
		if ( $type == 'toggle-navigation' ) {
			$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="21" viewBox="0 0 30 21" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-265.000000, -78.000000)" fill="#333333"><g transform="translate(265.000000, 78.000000)"><rect x="0" y="0" width="30" height="3" rx="1.5"/><rect x="0" y="9" width="30" height="3" rx="1.5"/><rect x="0" y="18" width="30" height="3" rx="1.5"/></g></g></g></svg>';
		}
		return $svg;
	}
}

//----------------------------------------------------------------------------------
// Add meta elements for the charset, viewport, and template
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_add_meta_elements' ) ) {
	function ct_startup_blog_add_meta_elements() {

		$meta_elements = '';

		$meta_elements .= sprintf( '<meta charset="%s" />' . "\n", esc_attr( get_bloginfo( 'charset' ) ) );
		$meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

		$theme    = wp_get_theme( get_template() );
		$template = sprintf( '<meta name="template" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );
		$meta_elements .= $template;

		echo $meta_elements;
	}
}
add_action( 'wp_head', 'ct_startup_blog_add_meta_elements', 1 );

//----------------------------------------------------------------------------------
// Get the right template for Jetpack infinite scroll
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_infinite_scroll_render' ) ) {
	function ct_startup_blog_infinite_scroll_render() {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'content', 'archive' );
		}
	}
}

//----------------------------------------------------------------------------------
// Template routing function. Setup to follow DRY coding patterns. 
// Using index.php file only instead of duplicating loop in page.php, post.php, etc.
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_get_content_template' ) ) {
	function ct_startup_blog_get_content_template() {

		// Get bbpress.php for all bbpress pages
		if ( function_exists( 'is_bbpress' ) ) {
			if ( is_bbpress() ) {
				get_template_part( 'content/bbpress' );
				return;
			} 
		}
		if ( is_home() || is_archive() ) {
			get_template_part( 'content-archive', get_post_type() );
		} else {
			get_template_part( 'content', get_post_type() );
		}
	}
}

//----------------------------------------------------------------------------------
// Allow Skype URIs to be used. Used for the Skype social icon in Customizer 
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_allow_skype_protocol' ) ) {
	function ct_startup_blog_allow_skype_protocol( $protocols ) {
		$protocols[] = 'skype';
		return $protocols;
	}
}
add_filter( 'kses_allowed_protocols', 'ct_startup_blog_allow_skype_protocol' );

//----------------------------------------------------------------------------------
// Add class to primary menu if all menu items are single-tiered.
// Then mobile menu items can be styled horizontally instead of vertically
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_primary_dropdown_check' ) ) {
	function ct_startup_blog_primary_dropdown_check( $item_output, $item, $depth, $args ) {

		if ( $args->theme_location == 'primary' ) {

			if ( in_array( 'menu-item-has-children', $item->classes ) ) {
				if ( strpos( $args->menu_class, 'hierarchical' ) == false ) {
					$args->menu_class .= ' hierarchical';
				}
			}
		}
		return $item_output;
	}
}
add_filter( 'walker_nav_menu_start_el', 'ct_startup_blog_primary_dropdown_check', 10, 4 );

//----------------------------------------------------------------------------------
// Filters the_archive_title() like this: "Category: Business" => "Business" 
// the_archive_title() used in content/archive-header.php
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_modify_archive_titles' ) ) {
	function ct_startup_blog_modify_archive_titles( $title ) {

		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = get_the_author();
		} elseif ( is_month() ) {
			$title = single_month_title( ' ' );
		}
		// is_year() and is_day() neglected b/c there is no analogous function for retrieving the page title

		return $title;
	}
}
add_filter( 'get_the_archive_title', 'ct_startup_blog_modify_archive_titles' );

//----------------------------------------------------------------------------------
// Add paragraph tags for author bio displayed in content/archive-header.php.
// the_archive_description includes paragraph tags for tag and category descriptions, but not the author bio. 
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_modify_archive_descriptions' ) ) {
	function ct_startup_blog_modify_archive_descriptions( $description ) {

		if ( is_author() ) {
			$description = wpautop( $description );
		}
		return $description;
	}
}
add_filter( 'get_the_archive_description', 'ct_startup_blog_modify_archive_descriptions' );

//----------------------------------------------------------------------------------
// Return CSS based on the user's Customizer selected colors.
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_override_colors' ) ) {
	function ct_startup_blog_override_colors() {

		$color_css       = '';
		$primary_color   = get_theme_mod( 'color_primary' );
		$secondary_color = get_theme_mod( 'color_secondary' );
		$bg_color        = get_theme_mod( 'color_background' );

		if ( $primary_color == '' ) {
			$primary_color = '#20a4e6';
		}
		if ( $secondary_color == '' ) {
			$secondary_color = '#17e6c3';
		}
		if ( $bg_color == '' ) {
			$bg_color = '#f0f5f8';
		}
		// Update all instances of the default blue color being used
		if ( $primary_color != '#20a4e6' ) {
			$color_css .= "a,a:link,a:visited,.menu-primary-items a:hover,.menu-primary-items a:active,.menu-primary-items a:focus,.menu-primary-items li.current-menu-item > a,.menu-secondary-items li.current-menu-item a,.menu-secondary-items li.current-menu-item a:link,.menu-secondary-items li.current-menu-item a:visited,.menu-secondary-items a:hover,.menu-secondary-items a:active,.menu-secondary-items a:focus,.toggle-navigation-secondary:hover,.toggle-navigation-secondary:active,.toggle-navigation-secondary.open,.widget li a:hover,.widget li a:active,.widget li a:focus,.widget_recent_comments li a,.widget_recent_comments li a:link,.widget_recent_comments li a:visited,.post-comments-link a:hover,.post-comments-link a:active,.post-comments-link a:focus,.post-title a:hover,.post-title a:active,.post-title a:focus {
			  color: $primary_color;
			}";
			$color_css .= "@media all and (min-width: 50em) { .menu-primary-items li.menu-item-has-children:hover > a,.menu-primary-items li.menu-item-has-children:hover > a:after,.menu-primary-items a:hover:after,.menu-primary-items a:active:after,.menu-primary-items a:focus:after,.menu-secondary-items li.menu-item-has-children:hover > a,.menu-secondary-items li.menu-item-has-children:hover > a:after,.menu-secondary-items a:hover:after,.menu-secondary-items a:active:after,.menu-secondary-items a:focus:after {
			  color: $primary_color;
			} }";
			$color_css .= "input[type=\"submit\"],.comment-pagination a:hover,.comment-pagination a:active,.comment-pagination a:focus,.site-header:before,.social-media-icons a:hover,.social-media-icons a:active,.social-media-icons a:focus,.pagination a:hover,.pagination a:active,.pagination a:focus,.featured-image > a:after,.entry:before,.post-tags a,.widget_calendar #prev a:hover,.widget_calendar #prev a:active,.widget_calendar #prev a:focus,.widget_calendar #next a:hover,.widget_calendar #next a:active,.widget_calendar #next a:focus,.bb-slider .image-container:after,.sticky-status span,.overflow-container .hero-image-header:before {
				background: $primary_color;
			}";
			$color_css .= ".woocommerce .single_add_to_cart_button, .woocommerce .checkout-button, .woocommerce .place-order .button {
				background: $primary_color !important;
			}";
			$color_css .= "@media all and (min-width: 50em) { .menu-primary-items ul:before,.menu-secondary-items ul:before {
				background: $primary_color;
			} }";
			$color_css .= "blockquote,.widget_calendar #today, .woocommerce-message, .woocommerce-info {
				border-color: $primary_color;
			}";
			$color_css .= ".toggle-navigation:hover svg g,.toggle-navigation.open svg g {
				fill: $primary_color;
			}";
			$color_css .= ".site-title a:hover,.site-title a:active,.site-title a:focus {
				color: $primary_color;
			}";
	
			// Create translucent variation and apply to hovers
			$red                 = hexdec( substr( $primary_color, 1, 2 ) );
			$green               = hexdec( substr( $primary_color, 3, 2 ) );
			$blue                = hexdec( substr( $primary_color, 5, 2 ) );
			$primary_color_hover = "rgba($red, $green, $blue, 0.6)";
			
			$color_css .= "a:hover,a:active,a:focus,.widget_recent_comments li a:hover,.widget_recent_comments li a:active,.widget_recent_comments li a:focus {
			  color: $primary_color_hover;
			}";
			$color_css .= "input[type=\"submit\"]:hover,input[type=\"submit\"]:active,input[type=\"submit\"]:focus,.post-tags a:hover,.post-tags a:active,.post-tags a:focus {
			  background: $primary_color_hover;
			}";
		}
		// Update gradients if either color has changed
		if ( $primary_color != '#20a4e6' || $secondary_color != '#17e6c3' ) {

			if ( is_rtl() ) {	
				$color_css .= ".rtl .site-header:before,.rtl .featured-image > a:after,.rtl .entry:before,.rtl .bb-slider .image-container:after,.rtl .overflow-container .hero-image-header:before {
					background-image: -webkit-linear-gradient(left, $secondary_color, $primary_color);
					background-image: linear-gradient(to right, $secondary_color, $primary_color);
				}";
				$color_css .= "@media all and (min-width: 50em) { .rtl .menu-primary-items ul:before,.rtl .menu-secondary-items ul:before {
					background-image: -webkit-linear-gradient(left, $secondary_color, $primary_color);
					background-image: linear-gradient(to right, $secondary_color, $primary_color);
				} }";
			} else {
				$color_css .= ".site-header:before,.featured-image > a:after,.entry:before,.bb-slider .image-container:after,.overflow-container .hero-image-header:before {
					background-image: -webkit-linear-gradient(left, $primary_color, $secondary_color);
					background-image: linear-gradient(to right, $primary_color, $secondary_color);
				}";
				$color_css .= "@media all and (min-width: 50em) { .menu-primary-items ul:before,.menu-secondary-items ul:before {
					background-image: -webkit-linear-gradient(left, $primary_color, $secondary_color);
					background-image: linear-gradient(to right, $primary_color, $secondary_color);
				} }";
			}
			
		}
		if ( $bg_color != '#f0f5f8' ) {
			$color_css .= "body {background: $bg_color;}";
		}
		return $color_css;
	}
}

//----------------------------------------------------------------------------------
// Output the user's custom colors
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_output_color_css' ) ) {
	function ct_startup_blog_output_color_css() {
		if ( !is_rtl() ) {
			$color_css = ct_startup_blog_override_colors();
			if ( !empty($color_css) ) {
				wp_add_inline_style( 'ct-startup-blog-style', ct_startup_blog_sanitize_css( $color_css ) );
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_startup_blog_output_color_css', 20 );

//----------------------------------------------------------------------------------
// Output differently for RTL b/c RTL stylesheets have no handle (not enqueued) and the <link>
// element is output so late
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_output_color_css_rtl' ) ) {
	function ct_startup_blog_output_color_css_rtl() {
		if ( is_rtl() ) {
			$color_css = ct_startup_blog_override_colors();
			if ( !empty($color_css) ) {
				echo '<style id="ct-startup-blog-style-inline-css" type="text/css">'. ct_startup_blog_sanitize_css( $color_css ) .'</style>';
			}
		}
	}
}
add_action( 'wp_head', 'ct_startup_blog_output_color_css_rtl', 99 );

//----------------------------------------------------------------------------------
// Sanitize CSS then convert "&gt;" back into ">" character so direct descendant CSS selectors work
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_sanitize_css' ) ) {
	function ct_startup_blog_sanitize_css( $css ) {
		$css = wp_kses( $css, '' );
		$css = str_replace( '&gt;', '>', $css );

		return $css;
	}
}

//----------------------------------------------------------------------------------
// Create and output the slider setup in the Customizer
//----------------------------------------------------------------------------------
if ( ! function_exists( 'ct_startup_blog_slider' ) ) {
	function ct_startup_blog_slider() {

		// Decide if slider should be displayed based on user's Customizer settings
		$display = get_theme_mod( 'slider_display' );
		if ( ( $display == 'homepage' || $display == '' ) && ( !is_front_page() || is_paged() ) ) {
			return;
		}
		if ( $display == 'blog' && ( !is_home() || is_paged() ) ) {
			return;
		}
		if ( $display == 'no' ) {
			return;
		}

		// Setup variables needed to get content
		$counter        = 1;
		$nav_counter    = 1;
		$display_arrows = get_theme_mod( 'slider_arrow_navigation' );
		$display_dots   = get_theme_mod( 'slider_dot_navigation' );
		$post_type      = get_theme_mod( 'slider_posts_or_pages' );
		$pages          = get_theme_mod( 'slider_pages' );
		$num_posts      = get_theme_mod( 'slider_recent_posts' );
		$args           = array();
		if ( $num_posts == '' ) {
			$num_posts = 5;
		}
		// prepare query args
		if ( $post_type == 'pages' ) {
			$args['post_type'] = 'page';
			$args['post__in']  = explode( '|', $pages );
			$args['orderby']      = 'post__in';
		} else {
			$args['posts_per_page'] = $num_posts;
			$post_category          = get_theme_mod( 'slider_post_category' );
			if ( $post_category != '' && $post_category != 'all' ) {
				$args['cat'] = $post_category;
			}
			if ( get_theme_mod( 'slider_sticky' ) == 'no' ) {
				$args['ignore_sticky_posts'] = true;
			}
		}

		$the_query = new WP_Query( $args );

		// Loop through posts
		if ( $the_query->have_posts() ) {
			echo '<div id="bb-slider" class="bb-slider">';
			echo '<ul id="bb-slide-list" class="slide-list">';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$classes = 'slide slide-' . esc_attr( $counter );
				if ( $counter == 1 ) {
					$classes .= ' current';
				}
				// Getting template this way instead of using get_template_part() so variables are accessible
				include( locate_template( 'content-slide.php', false, false ) );
				$counter ++;
			}
			echo '</ul>';
			if ( $display_arrows != 'no' ) {
				echo '<div class="arrow-navigation">';
					echo '<a id="bb-slider-left" class="left slide-nav" href="#"><i class="fas fa-angle-left"></i></a>';
					echo '<a id="bb-slider-right" class="right slide-nav" href="#"><i class="fas fa-angle-right"></i></a>';
				echo '</div>';
			}
			if ( $display_dots != 'no' ) {
				echo '<ul id="dot-navigation" class="dot-navigation">';
				while ( $nav_counter <= $the_query->post_count ) {
					$dot_class = 'dot ' . $nav_counter;
					if ( $nav_counter == 1 ) {
						$dot_class .= ' current';
					}
					echo '<li class="' . esc_attr( $dot_class ) . '"><a href="#"></a></li>';
					$nav_counter ++;
				}
				echo '</ul>';
			}
			echo '</div>';
			wp_reset_postdata();
		}
	}
}

//----------------------------------------------------------------------------------
// Providing a fallback title on the off-chance a post is untitled so it remains clickable on the blog.
// Copying "(title)" which WordPress uses in the admin dashboard.
//----------------------------------------------------------------------------------
function ct_startup_blog_no_missing_titles( $title, $id = null ) {
	if ( $title == '' ) {
		$title = esc_html__( '(title)', 'startup-blog' );
	}
	return $title;
}
add_filter( 'the_title', 'ct_startup_blog_no_missing_titles', 10, 2 );

//----------------------------------------------------------------------------------
// Turn of partial refresh for logo, so title shows up when logo is removed
//----------------------------------------------------------------------------------
function ct_startup_blog_update_logo_refresh( $wp_customize ) {
	$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
}
add_action( 'customize_register', 'ct_startup_blog_update_logo_refresh', 20 );

//----------------------------------------------------------------------------------
// Output the markup for the optional scroll-to-top arrow 
//----------------------------------------------------------------------------------
function ct_startup_blog_scroll_to_top_arrow() {
	$setting = get_theme_mod('scroll_to_top');
	
	if ( $setting == 'yes' ) {
		echo '<button id="scroll-to-top" class="scroll-to-top"><span class="screen-reader-text">'. esc_html__('Scroll to the top', 'startup-blog') .'</span><i class="fas fa-arrow-up"></i></button>';
	}
}
add_action( 'startup_blog_body_bottom', 'ct_startup_blog_scroll_to_top_arrow');

//----------------------------------------------------------------------------------
// Output the "Last Updated" date on posts
//----------------------------------------------------------------------------------
function ct_startup_blog_output_last_updated_date() {
	
	global $post;

	if ( get_the_modified_date() != get_the_date() ) {
		$updated_post = get_post_meta( $post->ID, 'ct_startup_blog_last_updated', true );
		$updated_customizer = get_theme_mod( 'last_updated' );
		if ( 
			( $updated_customizer == 'yes' && ($updated_post != 'no') )
			|| $updated_post == 'yes' 
			) {
				echo '<p class="last-updated">'. esc_html__("Last updated on", "startup-blog") . ' ' . get_the_modified_date() . ' </p>';
			}
	}
}

//----------------------------------------------------------------------------------
// Add support for Elementor headers & footers
//----------------------------------------------------------------------------------
function ct_startup_blog_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_location( 'header' );
	$elementor_theme_manager->register_location( 'footer' );
}
add_action( 'elementor/theme/register_locations', 'ct_startup_blog_register_elementor_locations' ); 

//----------------------------------------------------------------------------------
// Output the Header Image
//----------------------------------------------------------------------------------
if ( !function_exists('ct_startup_blog_output_header_image') ) {
	function ct_startup_blog_output_header_image() {
		if ( ! empty( get_custom_header()->attachment_id ) ) : ?>
			<img class="header-image" src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" />
		<?php endif; 
	}	
}

//----------------------------------------------------------------------------------
// Hook header image based on user's position selection
//----------------------------------------------------------------------------------
if ( !function_exists('ct_startup_blog_header_image_output_position') ) {
	function ct_startup_blog_header_image_output_position() {
		if ( ! empty( get_custom_header()->attachment_id ) ) {
			$position = get_theme_mod('header_image_position');
			if ( empty($position) || $position == 'top' ) {
				add_action( 'startup_blog_header_opening', 'ct_startup_blog_output_header_image' );
			} else if ( $position == 'middle' ) {
				add_action( 'startup_blog_after_secondary_header' , 'ct_startup_blog_output_header_image' );
			} else if ( $position == 'bottom' ) {
				add_action( 'startup_blog_header_closing' , 'ct_startup_blog_output_header_image' );
			}
		}
	}	
}
add_action( 'wp_head', 'ct_startup_blog_header_image_output_position' );


//----------------------------------------------------------------------------------
// Output standard post pagination
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_startup_blog_pagination' ) ) ) {
  function ct_startup_blog_pagination() {

    // Never output pagination on bbpress pages
    if ( function_exists( 'is_bbpress' ) ) {
      if ( is_bbpress() ) {
        return;
      } 
    }
    // Output pagination if Jetpack not installed, otherwise check if infinite scroll is active before outputting
    if ( !class_exists( 'Jetpack' ) ) {
      the_posts_pagination( array(
        'mid_size' => 1,
				'prev_text' => '',
				'next_text' => ''
      ) );
    } elseif ( !Jetpack::is_module_active( 'infinite-scroll' ) ) {
      the_posts_pagination( array(
        'mid_size' => 1,
				'prev_text' => '',
				'next_text' => ''
      ) );
    }
  }
}