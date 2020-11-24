<?php

/* Add customizer panels, sections, settings, and controls */
add_action( 'customize_register', 'ct_startup_blog_add_customizer_content' );

function ct_startup_blog_add_customizer_content( $wp_customize ) {

	//----------------------------------------------------------------------------------
	// Reorder default sections
	//----------------------------------------------------------------------------------
	$wp_customize->get_section( 'title_tagline' )->priority = 2;
	$wp_customize->get_section( 'header_image' )->priority = 15;

	//----------------------------------------------------------------------------------
	// Make sure Front Page setting exists before moving. (Doesn't show if user has no published pages)
	//----------------------------------------------------------------------------------
	if ( is_object( $wp_customize->get_section( 'static_front_page' ) ) ) {
		$wp_customize->get_section( 'static_front_page' )->priority = 3;
	}

	//----------------------------------------------------------------------------------
	// Add postMessage support for site title and tagline
	//----------------------------------------------------------------------------------
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	//----------------------------------------------------------------------------------
	// Repeater control for users to add pages to the slider
	//----------------------------------------------------------------------------------
	class ct_startup_blog_repeater_control extends WP_Customize_Control {
		public $type = 'repeater';
		public function render_content(){
			$pages = get_pages();
			?>
			<label class="customize_repeater">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<p><?php echo wp_kses_post($this->description); ?></p>
				<input type="hidden" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($this->value()); ?>" class="customize_repeater_value_field" data-customize-setting-link="<?php echo esc_attr($this->id); ?>"/>
				<select id="blueprint-page-select" class="customize_repeater_page_select">
					<?php
					// Hidden dropdown where all pages are listed. Duplicated via JS for use.
					echo '<option value="">'. esc_html( "Select a page", "startup-blog" ) .'</option>';
					foreach ( $pages as $page ) {
						echo '<option value="'. absint( $page->ID ) .'">'. esc_html( $page->post_title ) .'</option>';
					}
					?>
				</select>
				<div class="customize_repeater_fields">
					<div class="set">
						<select class="customize_repeater_page_select">
							<?php
							// Adding select here so it's available if nothing is saved yet
							echo '<option value="">'. esc_html( "Select a page", "startup-blog" ) .'</option>';
							foreach ( $pages as $page ) {
								echo '<option value="'. absint( $page->ID ) .'">'. esc_html( $page->post_title ) .'</option>';
							}
							?>
						</select>
					</div>
				</div>
				<a href="#" class="button button-primary customize_repeater_add_field"><?php esc_html_e('Add a Page', 'startup-blog') ?></a>
			</label>
			<?php
		}
	}

	//----------------------------------------------------------------------------------
	// Help section to instruct users how to add content to slides in slider. No actual input added.
	//----------------------------------------------------------------------------------
	class ct_startup_blog_slider_help extends WP_Customize_Control {
		public function render_content() {
			$link           = 'https://www.competethemes.com/help/customize-slider-startup-blog/';
			$featured_image = trailingslashit( get_template_directory_uri() ) . 'assets/images/featured-image.png';
			$excerpt_box    = trailingslashit( get_template_directory_uri() ) . 'assets/images/excerpt-box.png';
			echo '<hr>';
			echo '<p>';
				echo __( 'Add a <a href="#" class="featured-image-link"><i class="fas fa-search-plus"></i> Featured Image</a> to any post/page to display a background image in the slider. Use the <a href="#" class="excerpt-box-link"><i class="fas fa-search-plus"></i> Excerpt box</a> to craft a custom excerpt for any slide.', 'startup-blog' );
				echo '<img class="featured-image" src="'. esc_url( $featured_image ) .'" />';
				echo '<img class="excerpt-box" src="'. esc_url( $excerpt_box ) .'" />';
			echo '</p>';
		}
	}

	//----------------------------------------------------------------------------------
	// Add panels
	//----------------------------------------------------------------------------------
	if ( method_exists( 'WP_Customize_Manager', 'add_panel' ) ) {

		$wp_customize->add_panel( 'ct_startup_blog_slider_panel', array(
			'priority'    => 20,
			'title'       => __( 'Slider', 'startup-blog' ),
			'description' => __( 'Use these settings to add a slider to the header.', 'startup-blog' )
		) );
	}

	//----------------------------------------------------------------------------------
	// Header Image: adding new options (section registered by Core)
	//----------------------------------------------------------------------------------
	// setting
	$wp_customize->add_setting( 'header_image_position', array(
		'default'           => 'top',
		'sanitize_callback' => 'ct_startup_blog_sanitize_header_image_positions'
	) );
	// control
	$wp_customize->add_control( 'header_image_position', array(
		'label'    => __( 'Where should the header image display?', 'startup-blog' ),
		'section'  => 'header_image',
		'settings' => 'header_image_position',
		'type'     => 'radio',
		'choices'  => array(
			'top' 	 => __( 'Above everything', 'startup-blog' ),
			'middle' => __( 'Below the social icons', 'startup-blog' ),
			'bottom' => __( 'Below the header', 'startup-blog' )
		)
	) );

	//----------------------------------------------------------------------------------
	// Panel: Slider. Section: Content
	//----------------------------------------------------------------------------------
	$wp_customize->add_section( 'startup_blog_slider_content', array(
		'title'       => __( 'Content', 'startup-blog' ),
		'panel'       => 'ct_startup_blog_slider_panel',
		'priority'    => 1
	) );
	// setting
	$wp_customize->add_setting( 'slider_posts_or_pages', array(
		'default'           => 'posts',
		'sanitize_callback' => 'ct_startup_blog_sanitize_posts_or_pages',
	) );
	// control
	$wp_customize->add_control( 'slider_posts_or_pages', array(
		'label'    => __( 'Use posts or pages?', 'startup-blog' ),
		'section'  => 'startup_blog_slider_content',
		'settings' => 'slider_posts_or_pages',
		'type'     => 'radio',
		'choices'  => array(
			'posts' => __( 'Posts', 'startup-blog' ),
			'pages' => __( 'Pages', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'slider_recent_posts', array(
		'default'           => '5',
		'sanitize_callback' => 'absint'
	) );
	// control
	$wp_customize->add_control( 'slider_recent_posts', array(
		'label'    => __( 'Number of posts in the slider', 'startup-blog' ),
		'section'  => 'startup_blog_slider_content',
		'settings' => 'slider_recent_posts',
		'type'     => 'number'
	) );
	// setting
	$wp_customize->add_setting( 'slider_post_category', array(
		'default'           => 'all',
		'sanitize_callback' => 'ct_startup_blog_sanitize_post_categories'
	) );
	$categories_array = array( 'all' => __('All', 'startup-blog') );
	foreach ( get_categories() as $category ) {
		$categories_array[$category->term_id] = $category->name;
	}
	// control
	$wp_customize->add_control( 'slider_post_category', array(
		'label'    => __( 'Post category', 'startup-blog' ),
		'section'  => 'startup_blog_slider_content',
		'settings' => 'slider_post_category',
		'type'     => 'select',
		'choices' => $categories_array
	) );
	// setting
	$wp_customize->add_setting( 'slider_pages', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field', // 99|103|7
	));
	// control
	$wp_customize->add_control(new ct_startup_blog_repeater_control( $wp_customize, 'slider_pages', array(
		'label'    		=> __( 'Add pages to the slider', 'startup-blog' ),
		'settings'		=> 'slider_pages',
		'section'  		=> 'startup_blog_slider_content',
	)));
	// setting
	$wp_customize->add_setting( 'slider_help', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	));
	// control
	$wp_customize->add_control(new ct_startup_blog_slider_help( $wp_customize, 'slider_help', array(
		'settings'		=> 'slider_help',
		'section'  		=> 'startup_blog_slider_content',
	)));

	//----------------------------------------------------------------------------------
	// Panel: Slider. Section: Style
	//----------------------------------------------------------------------------------
	$wp_customize->add_section( 'startup_blog_slider_settings', array(
		'title'    => __( 'Style', 'startup-blog' ),
		'panel'    => 'ct_startup_blog_slider_panel',
		'priority' => 2
	) );
	// setting
	$wp_customize->add_setting( 'slider_display', array(
		'default'           => 'homepage',
		'sanitize_callback' => 'ct_startup_blog_sanitize_slider_display'
	) );
	// control
	$wp_customize->add_control( 'slider_display', array(
		'label'    => __( 'Display slider on:', 'startup-blog' ),
		'section'  => 'startup_blog_slider_settings',
		'settings' => 'slider_display',
		'type'     => 'radio',
		'choices' => array(
			'homepage'  => __( 'Homepage', 'startup-blog' ),
			'blog'      => __( 'Blog', 'startup-blog' ),
			'all-pages' => __( 'All Pages', 'startup-blog' ),
			'no'        => __( 'Do not display', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'slider_arrow_navigation', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'slider_arrow_navigation', array(
		'label'    => __( 'Display arrow navigation?', 'startup-blog' ),
		'section'  => 'startup_blog_slider_settings',
		'settings' => 'slider_arrow_navigation',
		'type'     => 'radio',
		'choices' => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'slider_dot_navigation', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'slider_dot_navigation', array(
		'label'    => __( 'Display dot navigation?', 'startup-blog' ),
		'section'  => 'startup_blog_slider_settings',
		'settings' => 'slider_dot_navigation',
		'type'     => 'radio',
		'choices' => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'slider_button_display', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'slider_button_display', array(
		'label'    => __( 'Display button?', 'startup-blog' ),
		'section'  => 'startup_blog_slider_settings',
		'settings' => 'slider_button_display',
		'type'     => 'radio',
		'choices' => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'slider_button_text', array(
		'default'           => __( 'Read more', 'startup-blog'),
		'sanitize_callback' => 'ct_startup_blog_sanitize_text'
	) );
	// control
	$wp_customize->add_control( 'slider_button_text', array(
		'label'    => __( 'Button text', 'startup-blog' ),
		'section'  => 'startup_blog_slider_settings',
		'settings' => 'slider_button_text',
		'type'     => 'text'
	) );
	// setting
	$wp_customize->add_setting( 'slider_auto_rotate', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'slider_auto_rotate', array(
		'label'    => __( 'Automatically rotate through slides?', 'startup-blog' ),
		'section'  => 'startup_blog_slider_settings',
		'settings' => 'slider_auto_rotate',
		'type'     => 'radio',
		'choices' => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'slider_time', array(
		'default'           => 5,
		'sanitize_callback' => 'absint'
	) );
	// control
	$wp_customize->add_control( 'slider_time', array(
		'label'    => __( 'How many seconds between slides?', 'startup-blog' ),
		'section'  => 'startup_blog_slider_settings',
		'settings' => 'slider_time',
		'type'     => 'number'
	) );
	// setting
	$wp_customize->add_setting( 'slider_sticky', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'slider_sticky', array(
		'label'    => __( 'Include "sticky" posts?', 'startup-blog' ),
		'section'  => 'startup_blog_slider_settings',
		'settings' => 'slider_sticky',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );

	//----------------------------------------------------------------------------------
	// Section: Colors
	//----------------------------------------------------------------------------------
	$wp_customize->add_section( 'startup_blog_colors', array(
		'title'    => __( 'Colors', 'startup-blog' ),
		'priority' => 20
	) );
	// setting
	$wp_customize->add_setting( 'color_primary', array(
		'default'           => '#20a4e6',
		'sanitize_callback' => 'sanitize_hex_color'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'color_primary', array(
			'label'       => __( 'Primary Color', 'startup-blog' ),
			'section'     => 'startup_blog_colors',
			'settings'    => 'color_primary'
		)
	) );
	// setting
	$wp_customize->add_setting( 'color_secondary', array(
		'default'           => '#17e6c3',
		'sanitize_callback' => 'sanitize_hex_color'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'color_secondary', array(
			'label'       => __( 'Secondary Color', 'startup-blog' ),
			'section'     => 'startup_blog_colors',
			'settings'    => 'color_secondary'
		)
	) );
	// setting
	$wp_customize->add_setting( 'color_background', array(
		'default'           => '#f0f5f8',
		'sanitize_callback' => 'sanitize_hex_color'
	) );
	// control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'color_background', array(
			'label'       => __( 'Background Color', 'startup-blog' ),
			'section'     => 'startup_blog_colors',
			'settings'    => 'color_background'
		)
	) );

	//----------------------------------------------------------------------------------
	// Section: Layout
	//----------------------------------------------------------------------------------
	$wp_customize->add_section( 'startup_blog_layout', array(
		'title'       => __( 'Layout', 'startup-blog' ),
		'priority'    => 25,
	) );
	// setting
	$wp_customize->add_setting( 'layout', array(
		'default'           => 'right-sidebar',
		'sanitize_callback' => 'ct_startup_blog_sanitize_layout'
	) );
	// control
	$wp_customize->add_control( 'layout', array(
		'label'    => __( 'Choose a Layout', 'startup-blog' ),
		'section'  => 'startup_blog_layout',
		'settings' => 'layout',
		'type'     => 'radio',
		'choices'  => array(
			'right-sidebar' => __( 'Right sidebar', 'startup-blog' ),
			'left-sidebar'  => __( 'Left sidebar', 'startup-blog' )
		)
	) );

	//----------------------------------------------------------------------------------
	// Section: Social Media Icons
	//----------------------------------------------------------------------------------
	$wp_customize->add_section( 'ct_startup_blog_social_media_icons', array(
		'title'       => __( 'Social Media Icons', 'startup-blog' ),
		'priority'    => 30,
		'description' => __( 'Add the URL for each of your social profiles.', 'startup-blog' )
	) );

	// get the social sites array
	$social_sites = ct_startup_blog_social_array();

	// set a priority used to order the social sites
	$priority = 5;

	// create a setting and control for each social site
	foreach ( $social_sites as $social_site => $value ) {
		// if email icon
		if ( $social_site == 'email' ) {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'sanitize_callback' => 'ct_startup_blog_sanitize_email'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'    => __( 'Email Address', 'startup-blog' ),
				'section'  => 'ct_startup_blog_social_media_icons',
				'priority' => $priority
			) );
		} else if ( $social_site == 'phone' ) {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'sanitize_callback' => 'ct_startup_blog_sanitize_phone'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'    => __( 'Phone', 'startup-blog' ),
				'section'     => 'ct_startup_blog_social_media_icons',
				'priority'    => $priority,
				'type'        => 'text'
			) );
		} else {

			$label = ucfirst( $social_site );

			if ( $social_site == 'rss' ) {
				$label = __('RSS', 'startup-blog');
			} elseif ( $social_site == 'soundcloud' ) {
				$label = __('SoundCloud', 'startup-blog');
			} elseif ( $social_site == 'slideshare' ) {
				$label = __('SlideShare', 'startup-blog');
			} elseif ( $social_site == 'codepen' ) {
				$label = __('CodePen', 'startup-blog');
			} elseif ( $social_site == 'stumbleupon' ) {
				$label = __('StumbleUpon', 'startup-blog');
			} elseif ( $social_site == 'deviantart' ) {
				$label = __('DeviantArt', 'startup-blog');
			} elseif ( $social_site == 'hacker-news' ) {
				$label = __('Hacker News', 'startup-blog');
			} elseif ( $social_site == 'whatsapp' ) {
				$label = __('WhatsApp', 'startup-blog');
			} elseif ( $social_site == 'qq' ) {
				$label = __('QQ', 'startup-blog');
			} elseif ( $social_site == 'vk' ) {
				$label = __('VK', 'startup-blog');
			} elseif ( $social_site == 'wechat' ) {
				$label = __('WeChat', 'startup-blog');
			} elseif ( $social_site == 'tencent-weibo' ) {
				$label = __('Tencent Weibo', 'startup-blog');
			} elseif ( $social_site == 'paypal' ) {
				$label = __('PayPal', 'startup-blog');
			} elseif ( $social_site == 'email-form' ) {
				$label = __('Contact Form', 'startup-blog');
			} elseif ( $social_site == 'google-wallet' ) {
				$label = __('Google Wallet', 'startup-blog');
			} elseif ( $social_site == 'ok-ru' ) {
				$label = __('OK.ru', 'startup-blog');
			} elseif ( $social_site == 'stack-overflow' ) {
				$label = __('Stack Overflow', 'startup-blog');
			} elseif ( $social_site == 'artstation' ) {
				$label = __('ArtStation', 'startup-blog');
			}

			if ( $social_site == 'skype' ) {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'ct_startup_blog_sanitize_skype'
				) );
				// control
				$wp_customize->add_control( $social_site, array(
					'type'        => 'url',
					'label'       => $label,
					// translators: %s = link to article
					'description' => sprintf( __( 'Accepts Skype link protocol (<a href="%s" target="_blank">learn more</a>)', 'startup-blog' ), 'https://www.competethemes.com/blog/skype-links-wordpress/' ),
					'section'     => 'ct_startup_blog_social_media_icons',
					'priority'    => $priority
				) );
			} else {
				// setting
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'esc_url_raw'
				) );
				// control
				$wp_customize->add_control( $social_site, array(
					'type'     => 'url',
					'label'    => $label,
					'section'  => 'ct_startup_blog_social_media_icons',
					'priority' => $priority
				) );
			}
		}
		// increment the priority for next site
		$priority = $priority + 5;
	}

	//----------------------------------------------------------------------------------
	// Section: Show/Hide Elements
	//----------------------------------------------------------------------------------
	$wp_customize->add_section( 'startup_blog_show_hide', array(
		'title'    => __( 'Show/Hide Elements', 'startup-blog' ),
		'priority' => 25
	) );
	// setting
	$wp_customize->add_setting( 'tagline', array(
		'default'           => 'header-footer',
		'sanitize_callback' => 'ct_startup_blog_sanitize_tagline_settings'
	) );
	// control
	$wp_customize->add_control( 'tagline', array(
		'label'    => __( 'Show the tagline?', 'startup-blog' ),
		'section'  => 'startup_blog_show_hide',
		'settings' => 'tagline',
		'type'     => 'radio',
		'choices'  => array(
			'header-footer' => __( 'Yes, in the header & footer', 'startup-blog' ),
			'header'        => __( 'Yes, in the header', 'startup-blog' ),
			'footer'        => __( 'Yes, in the footer', 'startup-blog' ),
			'no'            => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'post_byline_date', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'post_byline_date', array(
		'label'    => __( 'Show date in post byline?', 'startup-blog' ),
		'section'  => 'startup_blog_show_hide',
		'settings' => 'post_byline_date',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'post_byline_author', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'post_byline_author', array(
		'label'    => __( 'Show author name in post byline?', 'startup-blog' ),
		'section'  => 'startup_blog_show_hide',
		'settings' => 'post_byline_author',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'author_avatars', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'author_avatars', array(
		'label'    => __( 'Show post author avatars?', 'startup-blog' ),
		'section'  => 'startup_blog_show_hide',
		'settings' => 'author_avatars',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'comment_link', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'comment_link', array(
		'label'    => __( 'Show comment icon link?', 'startup-blog' ),
		'section'  => 'startup_blog_show_hide',
		'settings' => 'comment_link',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'author_box', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'author_box', array(
		'label'    => __( 'Show author box after posts?', 'startup-blog' ),
		'section'  => 'startup_blog_show_hide',
		'settings' => 'author_box',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'post_categories', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'post_categories', array(
		'label'    => __( 'Show categories after the post?', 'startup-blog' ),
		'section'  => 'startup_blog_show_hide',
		'settings' => 'post_categories',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'post_tags', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'post_tags', array(
		'label'    => __( 'Show tags after the post?', 'startup-blog' ),
		'section'  => 'startup_blog_show_hide',
		'settings' => 'post_tags',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'sidebar', array(
		'default'           => 'after',
		'sanitize_callback' => 'ct_startup_blog_sanitize_sidebar_settings'
	) );
	// control
	$wp_customize->add_control( 'sidebar', array(
		'label'    => __( 'Show sidebar on mobile devices?', 'startup-blog' ),
		'section'  => 'startup_blog_show_hide',
		'settings' => 'sidebar',
		'type'     => 'radio',
		'choices'  => array(
			'after'  => __( 'Yes, after main content', 'startup-blog' ),
			'before' => __( 'Yes, before main content', 'startup-blog' ),
			'no'     => __( 'No', 'startup-blog' )
		)
	) );

	//----------------------------------------------------------------------------------
	// Section: Blog
	//----------------------------------------------------------------------------------
	$wp_customize->add_section( 'startup_blog_blog', array(
		'title'    => __( 'Blog', 'startup-blog' ),
		'priority' => 50
	) );
	// setting
	$wp_customize->add_setting( 'full_post', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control
	$wp_customize->add_control( 'full_post', array(
		'label'    => __( 'Show full posts on blog?', 'startup-blog' ),
		'section'  => 'startup_blog_blog',
		'settings' => 'full_post',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
	// setting
	$wp_customize->add_setting( 'excerpt_length', array(
		'default'           => '30',
		'sanitize_callback' => 'absint'
	) );
	// control
	$wp_customize->add_control( 'excerpt_length', array(
		'label'    => __( 'Excerpt word count', 'startup-blog' ),
		'section'  => 'startup_blog_blog',
		'settings' => 'excerpt_length',
		'type'     => 'number'
	) );

	//----------------------------------------------------------------------------------
	// Section: Additional Options
	//----------------------------------------------------------------------------------
	$wp_customize->add_section( 'ct_startup_blog_additional_options', array(
		'title'    => __( 'Additional Options', 'startup-blog' ),
		'priority' => 55
	) );
	// setting - last updated
	$wp_customize->add_setting( 'last_updated', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control - last updated
	$wp_customize->add_control( 'last_updated', array(
		'label'    => __( 'Display the date each post was last updated?', 'startup-blog' ),
		'section'  => 'ct_startup_blog_additional_options',
		'settings' => 'last_updated',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );

	//----------------------------------------------------------------------------------
	// Section: Scroll-to-top Arrow
	//----------------------------------------------------------------------------------
	$wp_customize->add_section( 'startup_blog_scroll_to_top', array(
		'title'    => __( 'Scroll-to-Top Arrow', 'startup-blog' ),
		'priority' => 55
	) );
	// setting - scroll-to-top arrow
	$wp_customize->add_setting( 'scroll_to_top', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_startup_blog_sanitize_yes_no_settings'
	) );
	// control - scroll-to-top arrow
	$wp_customize->add_control( 'scroll_to_top', array(
		'label'    => __( 'Display Scroll-to-top arrow?', 'startup-blog' ),
		'section'  => 'startup_blog_scroll_to_top',
		'settings' => 'scroll_to_top',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'startup-blog' ),
			'no'  => __( 'No', 'startup-blog' )
		)
	) );
}

//----------------------------------------------------------------------------------
// Sanitize email.
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_email( $input ) {
	return sanitize_email( $input );
}

//----------------------------------------------------------------------------------
// Sanitize yes/no settings
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_yes_no_settings( $input ) {

	$valid = array(
		'yes' => __( 'Yes', 'startup-blog' ),
		'no'  => __( 'No', 'startup-blog' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

//----------------------------------------------------------------------------------
// Sanitize text
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

//----------------------------------------------------------------------------------
// Sanitize Skype URI
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_skype( $input ) {
	return esc_url_raw( $input, array( 'http', 'https', 'skype' ) );
}

//----------------------------------------------------------------------------------
// Sanitize tagline settings
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_tagline_settings( $input ) {

	$valid = array(
		'header-footer' => __( 'Yes, in the header & footer', 'startup-blog' ),
		'header'        => __( 'Yes, in the header', 'startup-blog' ),
		'footer'        => __( 'Yes, in the footer', 'startup-blog' ),
		'no'            => __( 'No', 'startup-blog' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

//----------------------------------------------------------------------------------
// Sanitize sidebar settings
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_sidebar_settings( $input ) {

	$valid = array(
		'after'  => __( 'Yes, after main content', 'startup-blog' ),
		'before' => __( 'Yes, before main content', 'startup-blog' ),
		'no'     => __( 'No', 'startup-blog' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

//----------------------------------------------------------------------------------
// Sanitize slider display settings
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_slider_display( $input ) {

	$valid = array(
		'homepage'  => __( 'Homepage', 'startup-blog' ),
		'blog'      => __( 'Blog', 'startup-blog' ),
		'all-pages' => __( 'All Pages', 'startup-blog' ),
		'no'        => __( 'Do not display', 'startup-blog' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

//----------------------------------------------------------------------------------
// Sanitize post categories setting
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_post_categories( $input ) {

	$categories_array = array( 'all' => 'All' );
	foreach ( get_categories() as $category ) {
		$categories_array[$category->term_id] = $category->name;
	}

	return array_key_exists( $input, $categories_array ) ? $input : '';
}

//----------------------------------------------------------------------------------
// Sanitize layout settings
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_layout( $input ) {

	/* TRT Note: Also allowing layouts only included in the premium plugin.
	 * Needs to be done this way b/c sanitize_callback cannot be updated
	 * via get_setting() */
	$valid = array(
		'right-sidebar' => __( 'Right sidebar', 'startup-blog' ),
		'left-sidebar'  => __( 'Left sidebar', 'startup-blog' ),
		'narrow'        => __( 'No sidebar - Narrow', 'startup-blog' ),
		'wide'          => __( 'No sidebar - Wide', 'startup-blog' ),
		'two-right'     => __( 'Two column - Right sidebar', 'startup-blog' ),
		'two-left'      => __( 'Two column - Left sidebar', 'startup-blog' ),
		'two-narrow'    => __( 'Two column - No Sidebar - Narrow', 'startup-blog' ),
		'two-wide'      => __( 'Two column - No Sidebar - Wide', 'startup-blog' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

//----------------------------------------------------------------------------------
// Sanitize posts or pages settings
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_posts_or_pages( $input ) {

	$valid = array(
		'posts' => __( 'Posts', 'startup-blog' ),
		'pages' => __( 'Pages', 'startup-blog' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

//----------------------------------------------------------------------------------
// Sanitize phone social icon
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_phone( $input ) {
	if ( $input != '' ) {
		return esc_url_raw( 'tel:' . $input, array( 'tel' ) );
	} else {
		return '';
	}
}

//----------------------------------------------------------------------------------
// Sanitize header image positions
//----------------------------------------------------------------------------------
function ct_startup_blog_sanitize_header_image_positions( $input ) {

	$valid = array(
		'top' 	 => __( 'Above everything', 'startup-blog' ),
		'middle' => __( 'Below the social icons', 'startup-blog' ),
		'bottom' => __( 'Below the header', 'startup-blog' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_startup_blog_customize_preview_js() {
	if ( !defined( 'STARTUP_BLOG_PRO_FILE' ) && !(isset($_GET['mailoptin_optin_campaign_id']) || isset($_GET['mailoptin_email_campaign_id'])) ) {
		$url = 'https://www.competethemes.com/startup-blog-pro/?utm_source=wp-dashboard&utm_medium=Customizer&utm_campaign=Startup%20Blog%20Pro%20-%20Customizer';
		$content = "<script>jQuery('#customize-info').prepend('<div class=\"upgrades-ad\"><a href=\"". $url ."\" target=\"_blank\">Customize Colors with Startup Blog Pro <span>&rarr;</span></a></div>')</script>";
		echo apply_filters('ct_startup_blog_customizer_ad', $content);
	}
}
add_action('customize_controls_print_footer_scripts', 'ct_startup_blog_customize_preview_js');