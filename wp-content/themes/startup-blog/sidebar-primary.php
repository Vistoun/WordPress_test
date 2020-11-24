<?php
// don't output on WooCommerce pages like Cart and Checkout
if ( function_exists( 'is_woocommerce' ) ) {
    if ( is_cart() || is_checkout() || is_account_page() ) {
        return;
    }
}
if ( is_page_template( 'templates/full-width.php' ) ) {
    return;
}
if ( is_active_sidebar( 'primary' ) ) : ?>
    <aside class="sidebar sidebar-primary" id="sidebar-primary" role="complementary">
        <?php dynamic_sidebar( 'primary' ); ?>
    </aside>
<?php endif;