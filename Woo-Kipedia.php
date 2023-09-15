<?php
/*
Plugin Name: Woo-Kipedia
Description: Integrate WooCommerce products into Wiki pages.
Version: 0.1.0
Author: Zeus Eternal
*/

// Display WooCommerce products and "Add to Cart" button
function display_product_and_cart_button() {
    // Check if the user is logged in
    if (is_user_logged_in()) {
        // User is logged in, display the "Add to Cart" button
        global $product;
        echo apply_filters('woocommerce_loop_add_to_cart_link', sprintf(
            '<a href="%s" data-quantity="1" class="button %s" %s>Add to cart</a>',
            esc_url($product->add_to_cart_url()),
            esc_attr(isset($class) ? $class : 'button'),
            isset($attributes) ? wc_implode_html_attributes($attributes) : ''
        ), $product);
    } else {
        // User is not logged in, you can optionally display a message or take other actions
        echo 'Please log in to add products to your cart.';
    }
}

// Add a shortcode to display products and the "Add to Cart" button on Wiki pages
function wiki_product_shortcode($atts) {
    ob_start();
    display_product_and_cart_button();
    return ob_get_clean();
}
add_shortcode('wiki_product', 'wiki_product_shortcode');
