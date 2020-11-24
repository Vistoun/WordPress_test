<?php
// don't output on WooCommerce pages like Cart and Checkout
if ( function_exists( 'is_woocommerce' ) ) {
	if ( is_cart() || is_checkout() || is_account_page() ) {
		return;
	}
}
if ( is_active_sidebar( 'after-page-content' ) ) : ?>
	<aside class="sidebar sidebar-after-page-content" id="sidebar-after-page-content" role="complementary">
		<?php dynamic_sidebar( 'after-page-content' ); ?>
	</aside>
<?php endif;