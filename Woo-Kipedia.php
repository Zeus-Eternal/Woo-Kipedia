   ?>
    <div class="wrap">
        <h1>Woo-Kipedia Settings</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=woo-kipedia-settings"
               class="nav-tab <?php echo empty($_GET['tab']) ? 'nav-tab-active' : ''; ?>">General Settings</a>
            <a href="?page=woo-kipedia-settings&tab=info"
               class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] === 'info' ? 'nav-tab-active' : ''; ?>">Info</a>
            <a href="?page=woo-kipedia-settings&tab=usage"
               class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] === 'usage' ? 'nav-tab-active' : ''; ?>">Usage Instructions</a>
        </h2>
        <form method="post" action="options.php">
            <?php settings_fields('woo-kipedia-settings-group'); ?>
            <?php do_settings_sections('woo-kipedia-settings-group'); ?>
            <?php if (empty($_GET['tab']) || $_GET['tab'] === 'general') { ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Products Per Page</th>
                        <td><input type="number" name="woo-kipedia-per-page"
                                   value="<?php echo esc_attr(get_option('woo-kipedia-per-page', 10)); ?>"/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Order By</th>
                        <td>
                            <select name="woo-kipedia-order-by">
                                <option value="date" <?php selected(get_option('woo-kipedia-order-by', 'date'), 'date'); ?>>
                                    Date
                                </option>
                                <option value="title" <?php selected(get_option('woo-kipedia-order-by', 'date'), 'title'); ?>>
                                    Title
                                </option>
                                <option value="rand" <?php selected(get_option('woo-kipedia-order-by', 'date'), 'rand'); ?>>
                                    Random
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Sort Order</th>
                        <td>
                            <select name="woo-kipedia-order">
                                <option value="asc" <?php selected(get_option('woo-kipedia-order', 'asc'), 'asc'); ?>>
                                    Ascending
                                </option>
                                <option value="desc" <?php selected(get_option('woo-kipedia-order', 'asc'), 'desc'); ?>>
                                    Descending
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Max Short Description Words</th>
                        <td><input type="number" name="woo-kipedia-max-description-words"
                                   value="<?php echo esc_attr(get_option('woo-kipedia-max-description-words', 20)); ?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Show/Hide Prices</th>
                        <td>
                            <label for="woo-kipedia-show-price">
                                <input type="checkbox" id="woo-kipedia-show-price" name="woo-kipedia-show-price"
                                       value="yes" <?php checked(get_option('woo-kipedia-show-price', 'yes'), 'yes'); ?> />
                                Show Prices
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Show/Hide Thumbnail Images</th>
                        <td>
                            <label for="woo-kipedia-show-thumbnail">
                                <input type="checkbox" id="woo-kipedia-show-thumbnail"
                                       name="woo-kipedia-show-thumbnail" value="yes"
                                    <?php checked(get_option('woo-kipedia-show-thumbnail', 'yes'), 'yes'); ?> />
                                Show Thumbnail Images
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Show/Hide Read More Button</th>
                        <td>
                            <label for="woo-kipedia-show-read-more">
                                <input type="checkbox" id="woo-kipedia-show-read-more"
                                       name="woo-kipedia-show-read-more" value="yes"
                                    <?php checked(get_option('woo-kipedia-show-read-more', 'yes'), 'yes'); ?> />
                                Show Read More Button
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Read More Button Text</th>
                        <td><input type="text" name="woo-kipedia-read-more-text"
                                   value="<?php echo esc_attr(get_option('woo-kipedia-read-more-text', 'Read More')); ?>"/>
                        </td>
                    </tr>
                </table>
            <?php } ?>
            <?php if (isset($_GET['tab']) && $_GET['tab'] === 'info') { ?>
                <div class="woo-kipedia-info">
                    <h3>About Woo-Kipedia Plugin</h3>
                    <p>
                        Woo-Kipedia is a WordPress plugin that allows you to display WooCommerce products and related information using shortcodes. It provides various shortcodes to help you showcase products and their details on your website.
                    </p>
                    <p>
                        <strong>Plugin Name:</strong> Woo-Kipedia<br>
                        <strong>Version:</strong> 1.0<br>
                        <strong>Author:</strong> Your Name
                    </p>
                    <p>
                        For more information and support, visit the <a href="https://example.com/woo-kipedia-plugin"
                                                                      target="_blank">Woo-Kipedia Plugin Page</a>.
                    </p>
                </div>
            <?php } ?>
            <?php if (isset($_GET['tab']) && $_GET['tab'] === 'usage') { ?>
                <div class="woo-kipedia-usage-instructions">
                    <?php echo woo_kipedia_shortcode_usage_instructions(); ?>
                    <h3>Shortcodes for Displaying Product Information</h3>
                    <p>Use the following shortcodes to display various WooCommerce product information:</p>
                    <div class="shortcode-section">
                        <h4>[woo-kipedia-product]</h4>
                        <p>Display a single product by specifying its ID.</p>
                        <p>Example usage: <code>[woo-kipedia-product id="123"]</code></p>
                    </div>
                    <div class="shortcode-section">
                        <h4>[woo-kipedia-product-random]</h4>
                        <p>Display a random product.</p>
                        <p>Example usage: <code>[woo-kipedia-product-random]</code></p>
                    </div>
                    <div class="shortcode-section">
                        <h4>[woo-kipedia-product-popular]</h4>
                        <p>Display popular products.</p>
                        <p>Example usage: <code>[woo-kipedia-product-popular]</code></p>
                    </div>
                    <div class="shortcode-section">
                        <h4>[woo-kipedia-product-sku]</h4>
                        <p>Display a product by specifying its SKU (Stock Keeping Unit).</p>
                        <p>Example usage: <code>[woo-kipedia-product-sku sku="SKU123"]</code></p>
                    </div>
                    <div class="shortcode-section">
                        <h4>[woo-kipedia-product-categories]</h4>
                        <p>Display products from specific categories by specifying their category IDs.</p>
                        <p>Example usage: <code>[woo-kipedia-product-categories categories="1,2,3"]</code></p>
                    </div>
                    <div class="shortcode-section">
                        <h4>[woo-kipedia-product-tags]</h4>
                        <p>Display products with specific tags by specifying their tag slugs.</p>
                        <p>Example usage: <code>[woo-kipedia-product-tags tags="tag1,tag2,tag3"]</code></p>
                    </div>
                    <div class="shortcode-section">
                        <h4>[woo-kipedia-product-attributes]</h4>
                        <p>Display products with specific attributes and their values.</p>
                        <p>Example usage: <code>[woo-kipedia-product-attributes attributes="color:red,size:large"]</code></p>
                    </div>
                    <div class="shortcode-section">
                        <h4>[woo-kipedia-product-reviews]</h4>
                        <p>Display product reviews for a specific product by specifying its ID.</p>
                        <p>Example usage: <code>[woo-kipedia-product-reviews id="123"]</code></p>
                    </div>
                </div>
            <?php } ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Shortcode for displaying WooCommerce products
function woo_kipedia_shortcode($atts) {
    // Shortcode attributes
    $atts = shortcode_atts(array(
        'per_page' => get_option('woo-kipedia-per-page', 10),
        'order_by' => get_option('woo-kipedia-order-by', 'date'),
        'order' => get_option('woo-kipedia-order', 'asc'),
        'max_description_words' => get_option('woo-kipedia-max-description-words', 20),
        'show_price' => get_option('woo-kipedia-show-price', 'yes'),
        'show_thumbnail' => get_option('woo-kipedia-show-thumbnail', 'yes'),
        'show_read_more' => get_option('woo-kipedia-show-read-more', 'yes'),
        'read_more_text' => get_option('woo-kipedia-read-more-text', 'Read More'),
    ), $atts);

    // Query WooCommerce products
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $atts['per_page'],
        'orderby' => $atts['order_by'],
        'order' => $atts['order'],
    );

    $products = new WP_Query($args);

    ob_start();

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            global $product;
            ?>

            <div class="product">
                <?php if ($atts['show_thumbnail'] === 'yes') { ?>
                    <div class="product-thumbnail">
                        <?php echo $product->get_image(); ?>
                    </div>
                <?php } ?>

                <div class="product-info">
                    <h2><?php the_title(); ?></h2>
                    <div class="product-description">
                        <?php
                        $short_description = get_the_excerpt();
                        $words = explode(' ', $short_description);
                        if (count($words) > $atts['max_description_words']) {
                            echo implode(' ', array_slice($words, 0, $atts['max_description_words'])) . '...';
                        } else {
                            echo $short_description;
                        }
                        ?>
                    </div>
                    <?php if ($atts['show_price'] === 'yes') { ?>
                        <div class="product-price">
                            <?php echo $product->get_price_html(); ?>
                        </div>
                    <?php } ?>
                    <?php if ($atts['show_read_more'] === 'yes') { ?>
                        <div class="product-read-more">
                            <a href="<?php the_permalink(); ?>"><?php echo esc_html($atts['read_more_text']); ?></a>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php
        }

        // Add pagination
        echo '<div class="woo-kipedia-pagination">';
        echo paginate_links(array(
            'total' => $products->max_num_pages,
        ));
        echo '</div>';
    } else {
        echo 'No products found';
    }

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('woo-kipedia', 'woo_kipedia_shortcode');

// Usage instructions for the plugin
function woo_kipedia_shortcode_usage_instructions() {
    ob_start();
    ?>
    <h2>Woo-Kipedia Shortcode Usage Instructions</h2>
    <p>Use the following shortcode to display WooCommerce products with Woo-Kipedia:</p>
    <pre>[woo-kipedia]</pre>
    <p>This shortcode supports the following attributes:</p>
    <ul>
        <li><code>per_page</code> (default: 10) - Number of products to display per page.</li>
        <li><code>order_by</code> (default: 'date') - Order products by 'date', 'title', or 'rand' (random).</li>
        <li><code>order</code> (default: 'asc') - Sort products in 'asc' (ascending) or 'desc' (descending) order.</li>
        <li><code>max_description_words</code> (default: 20) - Maximum number of words to display in the short description.</li>
        <li><code>show_price</code> (default: 'yes') - Show or hide product prices. Use 'yes' to show, 'no' to hide.</li>
        <li><code>show_thumbnail</code> (default: 'yes') - Show or hide product thumbnail images. Use 'yes' to show, 'no' to hide.</li>
        <li><code>show_read_more</code> (default: 'yes') - Show or hide the "Read More" button. Use 'yes' to show, 'no' to hide.</li>
        <li><code>read_more_text</code> (default: 'Read More') - Text for the "Read More" button.</li>
    </ul>
    <?php
    return ob_get_clean();
}

// Shortcodes for displaying single product, random product, popular product, SKU, categories, tags, attributes, and reviews
add_shortcode('woo-kipedia-product', 'woo_kipedia_single_product_shortcode');
add_shortcode('woo-kipedia-product-random', 'woo_kipedia_random_product_shortcode');
add_shortcode('woo-kipedia-product-popular', 'woo_kipedia_popular_product_shortcode');
add_shortcode('woo-kipedia-product-sku', 'woo_kipedia_product_sku_shortcode');
add_shortcode('woo-kipedia-product-categories', 'woo_kipedia_product_categories_shortcode');
add_shortcode('woo-kipedia-product-tags', 'woo_kipedia_product_tags_shortcode');
add_shortcode('woo-kipedia-product-attributes', 'woo_kipedia_product_attributes_shortcode');
add_shortcode('woo-kipedia-product-reviews', 'woo_kipedia_product_reviews_shortcode');

// Extend the Usage Tab with documentation for the new shortcodes
function woo_kipedia_extend_usage_tab($content) {
    $extended_content = woo_kipedia_shortcode_usage_instructions();

    // Document the new shortcodes
    $extended_content .= '<h3>Shortcodes for Displaying Product Information</h3>';
    $extended_content .= '<p>Use the following shortcodes to display various WooCommerce product information:</p>';
    $extended_content .= '<div class="shortcode-section">';
    $extended_content .= '<h4>[woo-kipedia-product]</h4>';
    $extended_content .= '<p>Display a single product by specifying its ID.</p>';
    $extended_content .= '<p>Example usage: <code>[woo-kipedia-product id="123"]</code></p>';
    $extended_content .= '</div>';
    $extended_content .= '<div class="shortcode-section">';
    $extended_content .= '<h4>[woo-kipedia-product-random]</h4>';
    $extended_content .= '<p>Display a random product.</p>';
    $extended_content .= '<p>Example usage: <code>[woo-kipedia-product-random]</code></p>';
    $extended_content .= '</div>';
    $extended_content .= '<div class="shortcode-section">';
    $extended_content .= '<h4>[woo-kipedia-product-popular]</h4>';
    $extended_content .= '<p>Display popular products.</p>';
    $extended_content .= '<p>Example usage: <code>[woo-kipedia-product-popular]</code></p>';
    $extended_content .= '</div>';
    $extended_content .= '<div class="shortcode-section">';
    $extended_content .= '<h4>[woo-kipedia-product-sku]</h4>';
    $extended_content .= '<p>Display a product by specifying its SKU (Stock Keeping Unit).</p>';
    $extended_content .= '<p>Example usage: <code>[woo-kipedia-product-sku sku="SKU123"]</code></p>';
    $extended_content .= '</div>';
    $extended_content .= '<div class="shortcode-section">';
    $extended_content .= '<h4>[woo-kipedia-product-categories]</h4>';
    $extended_content .= '<p>Display products from specific categories by specifying their category IDs.</p>';
    $extended_content .= '<p>Example usage: <code>[woo-kipedia-product-categories categories="1,2,3"]</code></p>';
    $extended_content .= '</div>';
    $extended_content .= '<div class="shortcode-section">';
    $extended_content .= '<h4>[woo-kipedia-product-tags]</h4>';
    $extended_content .= '<p>Display products with specific tags by specifying their tag slugs.</p>';
    $extended_content .= '<p>Example usage: <code>[woo-kipedia-product-tags tags="tag1,tag2,tag3"]</code></p>';
    $extended_content .= '</div>';
    $extended_content .= '<div class="shortcode-section">';
    $extended_content .= '<h4>[woo-kipedia-product-attributes]</h4>';
    $extended_content .= '<p>Display products with specific attributes and their values.</p>';
    $extended_content .= '<p>Example usage: <code>[woo-kipedia-product-attributes attributes="color:red,size:large"]</code></p>';
    $extended_content .= '</div>';
    $extended_content .= '<div class="shortcode-section">';
    $extended_content .= '<h4>[woo-kipedia-product-reviews]</h4>';
    $extended_content .= '<p>Display product reviews for a specific product by specifying its ID.</p>';
    $extended_content .= '<p>Example usage: <code>[woo-kipedia-product-reviews id="123"]</code></p>';
    $extended_content .= '</div>';

    return $content . $extended_content;
}

add_filter('woo-kipedia-settings-tab-usage', 'woo_kipedia_extend_usage_tab');
