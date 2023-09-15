<?php
/*
Plugin Name: Woo-Kipedia
Description: Display all WooCommerce products using a shortcode.
Version: 1.0.0
Author: Zeus Eternal
*/

// Shortcode for displaying all WooCommerce products
function woo_kipedia_product_shortcode($atts) {
    if (class_exists('WooCommerce')) {
        ob_start();

        $products = wc_get_products(array(
            'status' => 'publish',
            'limit' => -1,
        ));

        if ($products) {
            foreach ($products as $product) {
                echo "<h2>{$product->get_name()}</h2>";
                echo "<p>Price: {$product->get_price()}</p>";

                if ($product->get_description()) {
                    echo "<div class='product-description'>{$product->get_description()}</div>";
                }

                if ($product->get_short_description()) {
                    echo "<div class='short-description'>{$product->get_short_description()}</div>";
                }

                echo apply_filters('woocommerce_loop_add_to_cart_link', sprintf(
                    '<a href="%s" class="button %s" %s>Add to Cart</a>',
                    esc_url($product->add_to_cart_url()),
                    esc_attr(isset($class) ? $class : 'button'),
                    isset($attributes) ? wc_implode_html_attributes($attributes) : ''
                ), $product);

                echo "<div class='product-comments'>" . comments_template() . "</div>";
            }
        } else {
            echo 'No products found.';
        }

        return ob_get_clean();
    } else {
        // WooCommerce is not active, display a message
        return 'WooCommerce is not installed.';
    }
}
add_shortcode('woo-kipedia-product', 'woo_kipedia_product_shortcode');
