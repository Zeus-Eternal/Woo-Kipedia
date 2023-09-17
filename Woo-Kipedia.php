<?php
/*
Plugin Name: Woo-Kipedia
Description: Display WooCommerce products and related information using shortcodes.
Version: 0.2.1
Author: Zeus Eternal
*/
defined('ABSPATH') || die('Direct access not allowed.');

// Add a menu item to the admin panel
function woo_kipedia_admin_menu() {
    add_menu_page(
        'Woo-Kipedia Settings',
        'Woo-Kipedia',
        'manage_options',
        'woo-kipedia-settings',
        'woo_kipedia_settings_page',
        'dashicons-admin-generic'
    );
}
add_action('admin_menu', 'woo_kipedia_admin_menu');

// Callback function to render the settings page
function woo_kipedia_settings_page() {
    $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';

    ?>
    <div class="wrap">
        <h2 class="nav-tab-wrapper">
            <a href="?page=woo-kipedia-settings&tab=general"
               class="nav-tab <?php echo $active_tab === 'general' ? 'nav-tab-active' : ''; ?>">General Settings</a>
            <a href="?page=woo-kipedia-settings&tab=usage"
               class="nav-tab <?php echo $active_tab === 'usage' ? 'nav-tab-active' : ''; ?>">Usage Instructions</a>
        </h2>

        <?php if ($active_tab === 'general') { ?>
            <form method="post" action="options.php">
                <?php
                settings_fields('woo-kipedia-settings-group');
                do_settings_sections('woo-kipedia-settings');
                submit_button();
                ?>
            </form>
        <?php } elseif ($active_tab === 'usage') { ?>
            <div class="woo-kipedia-usage-instructions">
                <?php echo woo_kipedia_shortcode_usage_instructions(); ?>
            </div>
        <?php } ?>
    </div>
    <?php
}

// Updated woo_kipedia_products_shortcode function (used for [woo-kipedia] shortcode)
function woo_kipedia_products_shortcode($atts) {
    if (class_exists('WooCommerce')) {
        ob_start();

        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        $products_per_page = get_option('woo-kipedia-per-page', 20);
        $order_by = get_option('woo-kipedia-order-by', 'menu_order');
        $order = get_option('woo-kipedia-order', 'ASC');
        $filter_by_letter = isset($_GET['az']) ? sanitize_text_field($_GET['az']) : '';
        $max_description_words = get_option('woo-kipedia-max-description-words', 20);

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $products_per_page,
            'paged' => $paged,
            'orderby' => $order_by,
            'order' => $order,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'name',
                    'terms' => $filter_by_letter,
                    'operator' => 'LIKE',
                ),
            ),
        );

        $products_query = new WP_Query($args);

        if ($products_query->have_posts()) {
            while ($products_query->have_posts()) {
                $products_query->the_post();
                wc_get_template_part('content', 'product');

                // Display the short description with a word limit
                $short_description = get_the_excerpt();
                $short_description_words = explode(' ', $short_description);
                $short_description_words = array_slice($short_description_words, 0, $max_description_words);
                $short_description = implode(' ', $short_description_words);
                echo "<p class='short-description'>$short_description</p>";
            }

            // Numeric navigation
            echo '<div class="numeric-navigation">';
            echo paginate_links(array(
                'total' => $products_query->max_num_pages,
                'current' => $paged,
            ));
            echo '</div>';
        } else {
            echo 'No products found.';
        }

        wp_reset_postdata();

        return ob_get_clean();
    } else {
        // WooCommerce is not active, display a message
        return 'WooCommerce is not installed.';
    }
}

// Add settings fields for sorting, per_page, and max_description_words
function woo_kipedia_register_settings() {
    add_settings_section(
        'woo-kipedia-general-settings',
        'General Settings',
        'woo_kipedia_general_settings_callback',
        'woo-kipedia-settings'
    );

    add_settings_field(
        'woo-kipedia-per-page',
        'Products Per Page',
        'woo_kipedia_per_page_callback',
        'woo-kipedia-settings',
        'woo-kipedia-general-settings'
    );

    add_settings_field(
        'woo-kipedia-order-by',
        'Sort Products By',
        'woo_kipedia_order_by_callback',
        'woo-kipedia-settings',
        'woo-kipedia-general-settings'
    );

    add_settings_field(
        'woo-kipedia-order',
        'Sort Order',
        'woo_kipedia_order_callback',
        'woo-kipedia-settings',
        'woo-kipedia-general-settings'
    );

    // Register the new setting field for maximum word count in short description
    add_settings_field(
        'woo-kipedia-max-description-words',
        'Max Short Description Words',
        'woo_kipedia_max_description_words_callback',
        'woo-kipedia-settings',
        'woo-kipedia-general-settings'
    );

    register_setting('woo-kipedia-settings-group', 'woo-kipedia-per-page', 'woo_kipedia_sanitize_per_page');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-order-by', 'sanitize_text_field');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-order', 'sanitize_text_field');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-max-description-words', 'absint');
}
add_action('admin_init', 'woo_kipedia_register_settings');

// Sanitize the "Products Per Page" option
function woo_kipedia_sanitize_per_page($input) {
    return absint($input);
}

// Settings callbacks
function woo_kipedia_general_settings_callback() {
    echo 'General settings for the Woo-Kipedia plugin.';
}

function woo_kipedia_per_page_callback() {
    $per_page = get_option('woo-kipedia-per-page', 20);
    echo "<input type='number' name='woo-kipedia-per-page' value='$per_page' />";
}

function woo_kipedia_order_by_callback() {
    $order_by = get_option('woo-kipedia-order-by', 'menu_order');
    $options = array(
        'menu_order' => 'Default Sorting',
        'title' => 'Title',
        'date' => 'Date Created',
        'price' => 'Price',
    );

    echo "<select name='woo-kipedia-order-by'>";
    foreach ($options as $value => $label) {
        $selected = ($order_by === $value) ? 'selected' : '';
        echo "<option value='$value' $selected>$label</option>";
    }
    echo "</select>";
}

function woo_kipedia_order_callback() {
    $order = get_option('woo-kipedia-order', 'ASC');
    $options = array(
        'ASC' => 'Ascending',
        'DESC' => 'Descending',
    );

    echo "<select name='woo-kipedia-order'>";
    foreach ($options as $value => $label) {
        $selected = ($order === $value) ? 'selected' : '';
        echo "<option value='$value' $selected>$label</option>";
    }
    echo "</select>";
}

// Callback for the max short description words setting
function woo_kipedia_max_description_words_callback() {
    $max_description_words = get_option('woo-kipedia-max-description-words', 20);
    echo "<input type='number' name='woo-kipedia-max-description-words' value='$max_description_words' />";
}

// Add a link to the settings page from the plugins list
function woo_kipedia_settings_link($links) {
    $settings_link = '<a href="admin.php?page=woo-kipedia-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'woo_kipedia_settings_link');

// Function to display usage instructions
function woo_kipedia_shortcode_usage_instructions() {
    ob_start();
    ?>
    <h3>Woo-Kipedia Shortcode Usage</h3>
    <p>To display WooCommerce products using the Woo-Kipedia plugin, you can use the following shortcodes:</p>
    <h4>Display All Products</h4>
    <p>Use the <code>[woo-kipedia]</code> shortcode to display all products. You can also specify the number of products per page, sorting options, and the maximum number of words for the short description in the plugin settings.</p>
    <pre>[woo-kipedia]</pre>

    <h4>Display a Single Product by ID</h4>
    <p>Use the <code>[woo-kipedia-product id="PRODUCT_ID"]</code> shortcode to display a single product by its ID. Replace <code>PRODUCT_ID</code> with the actual product ID.</p>
    <pre>[woo-kipedia-product id="123"]</pre>

    <h4>Display a Random Product</h4>
    <p>Use the <code>[woo-kipedia-product-random]</code> shortcode to display a random product from your WooCommerce store.</p>
    <pre>[woo-kipedia-product-random]</pre>

    <h4>Display the Most Popular Product</h4>
    <p>Use the <code>[woo-kipedia-product-popular]</code> shortcode to display the most popular (most bought) product from your store.</p>
    <pre>[woo-kipedia-product-popular]</pre>

    <h4>Display Product SKU</h4>
    <p>Use the <code>[woo-kipedia-product-sku id="PRODUCT_ID"]</code> shortcode to display the SKU of a product by its ID.</p>
    <pre>[woo-kipedia-product-sku id="123"]</pre>

    <h4>Display Product Categories</h4>
    <p>Use the <code>[woo-kipedia-product-categories id="PRODUCT_ID"]</code> shortcode to display the categories of a product by its ID.</p>
    <pre>[woo-kipedia-product-categories id="123"]</pre>

    <h4>Display Product Tags</h4>
    <p>Use the <code>[woo-kipedia-product-tags id="PRODUCT_ID"]</code> shortcode to display the tags of a product by its ID.</p>
    <pre>[woo-kipedia-product-tags id="123"]</pre>

    <h4>Display Product Attributes</h4>
    <p>Use the <code>[woo-kipedia-product-attributes id="PRODUCT_ID"]</code> shortcode to display the attributes of a product by its ID.</p>
    <pre>[woo-kipedia-product-attributes id="123"]</pre>

    <h4>Display Product Reviews</h4>
    <p>Use the <code>[woo-kipedia-product-reviews id="PRODUCT_ID"]</code> shortcode to display the reviews of a product by its ID.</p>
    <pre>[woo-kipedia-product-reviews id="123"]</pre>

    <h4>Display Product Categories List</h4>
    <p>Use the <code>[woo-kipedia-product-categories-list]</code> shortcode to display a list of all product categories.</p>
    <pre>[woo-kipedia-product-categories-list]</pre>

    <h4>Display Category Navigation</h4>
    <p>Use the <code>[woo-kipedia-category-az-navigation]</code> shortcode to display alphabetical navigation links for product categories, allowing users to filter products by category names starting with a specific letter.</p>
    <pre>[woo-kipedia-category-az-navigation]</pre>

    <h4>Check If WooCommerce is Active</h4>
    <p>Use the <code>[woo-kipedia-check-woocommerce]</code> shortcode to check if WooCommerce is installed and active.</p>
    <pre>[woo-kipedia-check-woocommerce]</pre>

    <?php
    return ob_get_clean();
}

// Shortcode for displaying WooCommerce products
add_shortcode('woo-kipedia', 'woo_kipedia_products_shortcode');

// Shortcodes for displaying single product, random product, popular product, SKU, categories, tags, attributes, and reviews
add_shortcode('woo-kipedia-product', 'woo_kipedia_single_product_shortcode');
add_shortcode('woo-kipedia-product-random', 'woo_kipedia_random_product_shortcode');
add_shortcode('woo-kipedia-product-popular', 'woo_kipedia_popular_product_shortcode');
add_shortcode('woo-kipedia-product-sku', 'woo_kipedia_product_sku_shortcode');
add_shortcode('woo-kipedia-product-categories', 'woo_kipedia_product_categories_shortcode');
add_shortcode('woo-kipedia-product-tags', 'woo_kipedia_product_tags_shortcode');
add_shortcode('woo-kipedia-product-attributes', 'woo_kipedia_product_attributes_shortcode');
add_shortcode('woo-kipedia-product-reviews', 'woo_kipedia_product_reviews_shortcode');

// Shortcode for displaying a list of product categories
add_shortcode('woo-kipedia-product-categories-list', 'woo_kipedia_product_categories_list_shortcode');

// Shortcode for displaying category navigation
add_shortcode('woo-kipedia-category-az-navigation', 'woo_kipedia_category_az_navigation_shortcode');

// Shortcode for checking if WooCommerce is active
add_shortcode('woo-kipedia-check-woocommerce', 'woo_kipedia_check_woocommerce_shortcode');

// Shortcode for displaying a single product by ID
function woo_kipedia_single_product_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);

    if (class_exists('WooCommerce')) {
        if (!empty($atts['id'])) {
            ob_start();
            wc_get_template_part('content', 'single-product', $atts['id']);
            return ob_get_clean();
        } else {
            return 'Please provide a product ID.';
        }
    } else {
        return 'WooCommerce is not installed.';
    }
}

// Shortcode for displaying a random product
function woo_kipedia_random_product_shortcode() {
    if (class_exists('WooCommerce')) {
        ob_start();
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 1,
            'orderby' => 'rand',
        );
        $products_query = new WP_Query($args);
        if ($products_query->have_posts()) {
            while ($products_query->have_posts()) {
                $products_query->the_post();
                wc_get_template_part('content', 'single-product');
            }
        }
        wp_reset_postdata();
        return ob_get_clean();
    } else {
        return 'WooCommerce is not installed.';
    }
}

// Shortcode for displaying the most popular product
function woo_kipedia_popular_product_shortcode() {
    if (class_exists('WooCommerce')) {
        ob_start();
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 1,
            'meta_key' => 'total_sales',
            'orderby' => 'meta_value_num',
        );
        $products_query = new WP_Query($args);
        if ($products_query->have_posts()) {
            while ($products_query->have_posts()) {
                $products_query->the_post();
                wc_get_template_part('content', 'single-product');
            }
        }
        wp_reset_postdata();
        return ob_get_clean();
    } else {
        return 'WooCommerce is not installed.';
    }
}

// Shortcode for displaying the SKU of a product by its ID
function woo_kipedia_product_sku_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);

    if (class_exists('WooCommerce')) {
        if (!empty($atts['id'])) {
            $product = wc_get_product($atts['id']);
            if ($product) {
                return 'SKU: ' . $product->get_sku();
            } else {
                return 'Product not found.';
            }
        } else {
            return 'Please provide a product ID.';
        }
    } else {
        return 'WooCommerce is not installed.';
    }
}

// Shortcode for displaying the categories of a product by its ID
function woo_kipedia_product_categories_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);

    if (class_exists('WooCommerce')) {
        if (!empty($atts['id'])) {
            $product = wc_get_product($atts['id']);
            if ($product) {
                $categories = get_the_terms($product->get_id(), 'product_cat');
                if ($categories) {
                    $category_names = array();
                    foreach ($categories as $category) {
                        $category_names[] = $category->name;
                    }
                    return implode(', ', $category_names);
                } else {
                    return 'No categories found for this product.';
                }
            } else {
                return 'Product not found.';
            }
        } else {
            return 'Please provide a product ID.';
        }
    } else {
        return 'WooCommerce is not installed.';
    }
}

// Shortcode for displaying the tags of a product by its ID
function woo_kipedia_product_tags_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);

    if (class_exists('WooCommerce')) {
        if (!empty($atts['id'])) {
            $product = wc_get_product($atts['id']);
            if ($product) {
                $tags = get_the_terms($product->get_id(), 'product_tag');
                if ($tags) {
                    $tag_names = array();
                    foreach ($tags as $tag) {
                        $tag_names[] = $tag->name;
                    }
                    return implode(', ', $tag_names);
                } else {
                    return 'No tags found for this product.';
                }
            } else {
                return 'Product not found.';
            }
        } else {
            return 'Please provide a product ID.';
        }
    } else {
        return 'WooCommerce is not installed.';
    }
}

// Shortcode for displaying the attributes of a product by its ID
function woo_kipedia_product_attributes_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);

    if (class_exists('WooCommerce')) {
        if (!empty($atts['id'])) {
            $product = wc_get_product($atts['id']);
            if ($product) {
                $attributes = $product->get_attributes();
                if (!empty($attributes)) {
                    ob_start();
                    foreach ($attributes as $attribute) {
                        $attribute_label = wc_attribute_label($attribute->get_name());
                        $attribute_value = $attribute->get_options();
                        echo "<p><strong>$attribute_label:</strong> " . implode(', ', $attribute_value) . "</p>";
                    }
                    return ob_get_clean();
                } else {
                    return 'No attributes found for this product.';
                }
            } else {
                return 'Product not found.';
            }
        } else {
            return 'Please provide a product ID.';
        }
    } else {
        return 'WooCommerce is not installed.';
    }
}

// Shortcode for displaying the reviews of a product by its ID
function woo_kipedia_product_reviews_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => '',
    ), $atts);

    if (class_exists('WooCommerce')) {
        if (!empty($atts['id'])) {
            $product = wc_get_product($atts['id']);
            if ($product) {
                ob_start();
                comments_template();
                return ob_get_clean();
            } else {
                return 'Product not found.';
            }
        } else {
            return 'Please provide a product ID.';
        }
    } else {
        return 'WooCommerce is not installed.';
    }
}

// Shortcode for displaying a list of product categories
function woo_kipedia_product_categories_list_shortcode() {
    if (class_exists('WooCommerce')) {
        $args = array(
            'taxonomy' => 'product_cat',
            'title_li' => '',
            'hide_empty' => false,
        );
        return wp_list_categories($args);
    } else {
        return 'WooCommerce is not installed.';
    }
}

// Shortcode for displaying alphabetical navigation links for product categories
function woo_kipedia_category_az_navigation_shortcode() {
    if (class_exists('WooCommerce')) {
        $categories = get_terms('product_cat', array('hide_empty' => false));
        $az_letters = range('A', 'Z');
        ob_start();
        ?>
        <div class="az-navigation">
            <ul class="az-nav-list">
                <?php foreach ($az_letters as $letter) { ?>
                    <li><a href="?az=<?php echo $letter; ?>"><?php echo $letter; ?></a></li>
                <?php } ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    } else {
        return 'WooCommerce is not installed.';
    }
}

// Shortcode for checking if WooCommerce is active
function woo_kipedia_check_woocommerce_shortcode() {
    if (class_exists('WooCommerce')) {
        return 'WooCommerce is active.';
    } else {
        return 'WooCommerce is not installed.';
    }
}

