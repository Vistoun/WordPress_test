<?php

//----------------------------------------------------------------------------------
//	Add social profile fields to user profile menu. Social icons are output in the author bio's after posts.
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'startup_blog_add_social_profile_settings' ) ) ) {
	function startup_blog_add_social_profile_settings( $user ) {

		$user_id = get_current_user_id();
		if ( ! current_user_can( 'edit_posts', $user_id ) ) {
			return false;
		}
		$social_sites = ct_startup_blog_social_array();
		?>
		<table class="form-table">
			<tr>
				<th>
					<h3><?php esc_html_e( 'Social Profiles', 'startup-blog' ); ?></h3>
				</th>
			</tr>
			<?php
			foreach ( $social_sites as $key => $social_site ) {

				$label = ucfirst( $key );

				if ( $key == 'rss' ) {
					$label = __('RSS', 'startup-blog');
				} elseif ( $key == 'soundcloud' ) {
					$label = __('SoundCloud', 'startup-blog');
				} elseif ( $key == 'slideshare' ) {
					$label = __('SlideShare', 'startup-blog');
				} elseif ( $key == 'codepen' ) {
					$label = __('CodePen', 'startup-blog');
				} elseif ( $key == 'stumbleupon' ) {
					$label = __('StumbleUpon', 'startup-blog');
				} elseif ( $key == 'deviantart' ) {
					$label = __('DeviantArt', 'startup-blog');
				} elseif ( $key == 'hacker-news' ) {
					$label = __('Hacker News', 'startup-blog');
				} elseif ( $key == 'whatsapp' ) {
					$label = __('WhatsApp', 'startup-blog');
				} elseif ( $key == 'qq' ) {
					$label = __('QQ', 'startup-blog');
				} elseif ( $key == 'vk' ) {
					$label = __('VK', 'startup-blog');
				} elseif ( $key == 'wechat' ) {
					$label = __('WeChat', 'startup-blog');
				} elseif ( $key == 'tencent-weibo' ) {
					$label = __('Tencent Weibo', 'startup-blog');
				} elseif ( $key == 'paypal' ) {
					$label = __('PayPal', 'startup-blog');
				} elseif ( $key == 'email-form' ) {
					$label = __('Contact Form', 'startup-blog');
				} elseif ( $key == 'google-wallet' ) {
					$label = __('Google Wallet', 'startup-blog');
				} elseif ( $key == 'ok-ru' ) {
					$label = __('OK.ru', 'startup-blog');
				} elseif ( $key == 'stack-overflow' ) {
					$label = __('Stack Overflow', 'startup-blog');
				}
				?>
				<tr>
					<th>
						<?php if ( $key == 'email' ) : ?>
							<label
								for="<?php echo esc_attr( $key ); ?>-profile"><?php esc_html_e( 'Email Address', 'startup-blog' ); ?></label>
						<?php else : ?>
							<label
								for="<?php echo esc_attr( $key ); ?>-profile"><?php echo esc_html( $label ); ?></label>
						<?php endif; ?>
					</th>
					<td>
						<?php if ( $key == 'email' ) { ?>
							<input type='text' id='<?php echo esc_attr( $key ); ?>-profile' class='regular-text'
							       name='<?php echo esc_attr( $key ); ?>-profile'
							       value='<?php echo is_email( get_the_author_meta( $social_site, $user->ID ) ); ?>'/>
						<?php } elseif ( $key == 'skype' ) { ?>
							<input type='url' id='<?php echo esc_attr( $key ); ?>-profile' class='regular-text'
							       name='<?php echo esc_attr( $key ); ?>-profile'
							       value='<?php echo esc_url( get_the_author_meta( $social_site, $user->ID ), array(
								       'http',
								       'https',
								       'skype'
							       ) ); ?>'/>
						<?php } else { ?>
							<input type='url' id='<?php echo esc_attr( $key ); ?>-profile' class='regular-text'
							       name='<?php echo esc_attr( $key ); ?>-profile'
							       value='<?php echo esc_url( get_the_author_meta( $social_site, $user->ID ) ); ?>'/>
						<?php } ?>
					</td>
				</tr>
			<?php } ?>
		</table>
		<?php
	}
}
add_action( 'show_user_profile', 'startup_blog_add_social_profile_settings' );
add_action( 'edit_user_profile', 'startup_blog_add_social_profile_settings' );

//----------------------------------------------------------------------------------
//	Save the user's social profile links
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'startup_blog_save_social_profiles' ) ) ) {
	function startup_blog_save_social_profiles( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		$social_sites = ct_startup_blog_social_array();

		foreach ( $social_sites as $key => $social_site ) {
			if ( $key == 'email' ) {
				// if email, only accept 'mailto' protocol
				if ( isset( $_POST["$key-profile"] ) ) {
					update_user_meta( $user_id, $social_site, sanitize_email( wp_unslash( $_POST["$key-profile"] ) ) );
				}
			} elseif ( $key == 'skype' ) {
				// accept skype protocol
				if ( isset( $_POST["$key-profile"] ) ) {
					update_user_meta( $user_id, $social_site, esc_url_raw( wp_unslash( $_POST["$key-profile"] ), array(
						'http',
						'https',
						'skype'
					) ) );
				}
			} else {
				if ( isset( $_POST["$key-profile"] ) ) {
					update_user_meta( $user_id, $social_site, esc_url_raw( wp_unslash( $_POST["$key-profile"] ) ) );
				}
			}
		}
	}
}
add_action( 'personal_options_update', 'startup_blog_save_social_profiles' );
add_action( 'edit_user_profile_update', 'startup_blog_save_social_profiles' );