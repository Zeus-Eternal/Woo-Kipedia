<?php
/*
Plugin Name: Woo-Kipedia
Description: Display WooCommerce Product pages as Wikis with Shortcodes
Version: 0.1.6
Author: Zeus The Eternal
*/

// Register settings page
function woo_kipedia_register_settings() {
    add_menu_page(
        'Woo-Kipedia Settings',
        'Woo-Kipedia',
        'manage_options',
        'woo-kipedia-settings',
        'woo_kipedia_settings_page'
    );

    add_action('admin_init', 'woo_kipedia_register_settings_fields');
}

add_action('admin_menu', 'woo_kipedia_register_settings');

// Register settings fields
function woo_kipedia_register_settings_fields() {
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-per-page', 'woo_kipedia_sanitize_per_page');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-order-by', 'sanitize_text_field');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-order', 'woo_kipedia_sanitize_order');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-max-description-words', 'woo_kipedia_sanitize_max_description_words');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-show-price', 'sanitize_text_field');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-show-thumbnail', 'sanitize_text_field');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-show-read-more', 'sanitize_text_field');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-read-more-text', 'sanitize_text_field');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-button-text', 'sanitize_text_field');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-button-font-size', 'woo_kipedia_sanitize_font_size');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-link-as-button', 'sanitize_text_field');
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-wooki-page', 'absint');
}

// Sanitization callback functions
function woo_kipedia_sanitize_per_page($input) {
    return absint($input);
}

function woo_kipedia_sanitize_order($input) {
    return in_array($input, array('asc', 'desc')) ? $input : 'asc';
}

function woo_kipedia_sanitize_max_description_words($input) {
    return absint($input);
}

function woo_kipedia_sanitize_font_size($input) {
    return preg_match('/^\d+(\.\d{1,2})?$/', $input) ? $input : '';
}

// Settings page content
function woo_kipedia_settings_page() {
    ?>
    <div class="wrap">
        <h1>Woo-Kipedia Settings</h1>
        <h2 class="nav-tab-wrapper">
            <a href="?page=woo-kipedia-settings"
               class="nav-tab <?php echo empty($_GET['tab']) ? 'nav-tab-active' : ''; ?>">General Settings</a>
            <a href="?page=woo-kipedia-settings&tab=usage"
               class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] === 'usage' ? 'nav-tab-active' : ''; ?>">Usage Instructions</a>
            <a href="?page=woo-kipedia-settings&tab=info"
               class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] === 'info' ? 'nav-tab-active' : ''; ?>">Plugin Info</a>
        </h2>
        <form method="post" action="options.php">
            <?php settings_fields('woo-kipedia-settings-group'); ?>
            <?php do_settings_sections('woo-kipedia-settings-group'); ?>
            <?php if (empty($_GET['tab']) || $_GET['tab'] === 'general') { ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Products Per Page</th>
                        <td><input type="number" name="woo-kipedia-per-page"
                                   value="<?php echo esc_attr(get_option('woo-kipedia-per-page', 20)); ?>"/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Order By</th>
                        <td>
                            <select name="woo-kipedia-order-by">
                                <option value="date" <?php selected(get_option('woo-kipedia-order-by'), 'date'); ?>>Date</option>
                                <option value="title" <?php selected(get_option('woo-kipedia-order-by'), 'title'); ?>>Title</option>
                                <option value="price" <?php selected(get_option('woo-kipedia-order-by'), 'price'); ?>>Price</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Order</th>
                        <td>
                            <select name="woo-kipedia-order">
                                <option value="asc" <?php selected(get_option('woo-kipedia-order'), 'asc'); ?>>Ascending</option>
                                <option value="desc" <?php selected(get_option('woo-kipedia-order'), 'desc'); ?>>Descending</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Max Description Words</th>
                        <td><input type="number" name="woo-kipedia-max-description-words"
                                   value="<?php echo esc_attr(get_option('woo-kipedia-max-description-words', 500)); ?>"/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Show Price</th>
                        <td>
                            <label><input type="radio" name="woo-kipedia-show-price" value="yes"
                                          <?php checked(get_option('woo-kipedia-show-price'), 'yes'); ?>>Yes</label>
                            <label><input type="radio" name="woo-kipedia-show-price" value="no"
                                          <?php checked(get_option('woo-kipedia-show-price'), 'no'); ?>>No</label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Show Thumbnail</th>
                        <td>
                            <label><input type="radio" name="woo-kipedia-show-thumbnail" value="yes"
                                          <?php checked(get_option('woo-kipedia-show-thumbnail'), 'yes'); ?>>Yes</label>
                            <label><input type="radio" name="woo-kipedia-show-thumbnail" value="no"
                                          <?php checked(get_option('woo-kipedia-show-thumbnail'), 'no'); ?>>No</label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Show Read More</th>
                        <td>
                            <label><input type="radio" name="woo-kipedia-show-read-more" value="yes"
                                          <?php checked(get_option('woo-kipedia-show-read-more'), 'yes'); ?>>Yes</label>
                            <label><input type="radio" name="woo-kipedia-show-read-more" value="no"
                                          <?php checked(get_option('woo-kipedia-show-read-more'), 'no'); ?>>No</label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Read More Text</th>
                        <td><input type="text" name="woo-kipedia-read-more-text"
                                   value="<?php echo esc_attr(get_option('woo-kipedia-read-more-text', 'Read Woo-Ki')); ?>"/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Link as Button</th>
                        <td>
                            <label><input type="radio" name="woo-kipedia-link-as-button" value="yes"
                                          <?php checked(get_option('woo-kipedia-link-as-button'), 'yes'); ?>>Yes</label>
                            <label><input type="radio" name="woo-kipedia-link-as-button" value="no"
                                          <?php checked(get_option('woo-kipedia-link-as-button'), 'no'); ?>>No</label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Select Wooki Page</th>
                        <td>
                            <select name="woo-kipedia-wooki-page">
                                <option value="">Select a page</option>
                                <?php
                                $pages = get_pages();
                                foreach ($pages as $page) {
                                    $selected = selected(get_option('woo-kipedia-wooki-page'), $page->ID, false);
                                    echo "<option value='{$page->ID}' {$selected}>{$page->post_title}</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <!-- Add a button to save settings -->
                <tr valign="top">
                    <th scope="row"></th>
                    <td>
                        <?php wp_nonce_field('woo-kipedia-nonce', 'woo-kipedia-security'); ?>
                        <button type="button" id="woo-kipedia-save-settings" class="button button-primary">Save Settings</button>
                    </td>
                </tr>
            <?php } ?>
            <?php if (isset($_GET['tab']) && $_GET['tab'] === 'usage') {
                echo woo_kipedia_shortcode_usage_instructions();
            } elseif (isset($_GET['tab']) && $_GET['tab'] === 'info') {
                echo woo_kipedia_plugin_info();
            } ?>
        </form>
    </div>
    <?php
}

// Shortcode for displaying WooCommerce products
function woo_kipedia_shortcode($atts) {
    $atts = shortcode_atts(array(
        'per_page' => get_option('woo-kipedia-per-page', 20),
        'order_by' => get_option('woo-kipedia-order-by', 'date'),
        'order' => get_option('woo-kipedia-order', 'asc'),
        'max_description_words' => get_option('woo-kipedia-max-description-words', 500),
        'show_price' => get_option('woo-kipedia-show-price', 'yes'),
        'show_thumbnail' => get_option('woo-kipedia-show-thumbnail', 'yes'),
        'show_read_more' => get_option('woo-kipedia-show-read-more', 'yes'),
        'read_more_text' => get_option('woo-kipedia-read-more-text', 'Read Woo-Ki'),
        'link_as_button' => get_option('woo-kipedia-link-as-button', 'no'),
        'wooki_page' => get_option('woo-kipedia-wooki-page', ''), // New attribute
    ), $atts);

    // Retrieve the content of the selected Wooki page
    if (!empty($atts['wooki_page'])) {
        $wooki_content = get_post_field('post_content', $atts['wooki_page']);
    } else {
        $wooki_content = 'No Wooki page selected.';
    }

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
        // Display the Wooki page content
        echo '<div class="wooki-content">' . $wooki_content . '</div>';

        // Add code here to display WooCommerce product information
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
                        <?php if ($atts['link_as_button'] === 'yes') { ?>
                            <div class="product-button">
                                <a href="<?php the_permalink(); ?>"
                                   class="woo-kipedia-button"><?php echo esc_html($atts['read_more_text']); ?></a>
                            </div>
                        <?php } else { ?>
                            <div class="product-read-more">
                                <a href="<?php the_permalink(); ?>"><?php echo esc_html($atts['read_more_text']); ?></a>
                            </div>
                        <?php } ?>
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
        <li><code>per_page</code> - Number of products to display per page.</li>
        <li><code>order_by</code> - Order products by 'date', 'title', or 'price'.</li>
        <li><code>order</code> - Order products in 'asc' (ascending) or 'desc' (descending) order.</li>
        <li><code>max_description_words</code> - Maximum number of words to display in the product description.</li>
        <li><code>show_price</code> - Whether to show the product price ('yes' or 'no').</li>
        <li><code>show_thumbnail</code> - Whether to show the product thumbnail ('yes' or 'no').</li>
        <li><code>show_read_more</code> - Whether to show the 'Read More' link ('yes' or 'no').</li>
        <li><code>read_more_text</code> - Text for the 'Read More' link.</li>
        <li><code>link_as_button</code> - Whether to display the 'Read More' link as a button ('yes' or 'no').</li>
        <li><code>wooki_page</code> - Select a page to display as Wooki content.</li>
    </ul>
    <?php
    return ob_get_clean();
}

// Plugin info
function woo_kipedia_plugin_info() {
    ob_start();
    ?>
    <h2>Woo-Kipedia Plugin Information</h2>
    <p>Author: Zeus The Eternal</p>
    <p>Version: 0.1.6</p>
    <p>Description: Display WooCommerce Product pages as Wikis with Shortcodes</p>
    <?php
    return ob_get_clean();
}

// Enqueue CSS styles
function woo_kipedia_enqueue_styles() {
    wp_enqueue_style('woo-kipedia-styles', plugin_dir_url(__FILE__) . 'styles.css');
}

add_action('wp_enqueue_scripts', 'woo_kipedia_enqueue_styles');

// Enqueue the JavaScript file
function woo_kipedia_enqueue_admin_scripts() {
    wp_enqueue_script('woo-kipedia-admin', plugin_dir_url(__FILE__) . 'assets/js/woo-kipedia-admin.js', array('jquery'), '1.0', true);

    // Pass Ajax URL to script.js
    wp_localize_script('woo-kipedia-admin', 'wooKipediaAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('woo-kipedia-nonce'),
    ));
}

add_action('admin_enqueue_scripts', 'woo_kipedia_enqueue_admin_scripts');

// Ajax handler to save settings
function woo_kipedia_save_settings() {
    // Check nonce for security
    check_ajax_referer('woo-kipedia-nonce', 'security');

    // Process and save settings here
    $per_page = sanitize_text_field($_POST['per_page']);
    // Save other settings here

    // Example: Update per_page option
    update_option('woo-kipedia-per-page', $per_page);

    // Return a success response
    wp_send_json_success();
}

add_action('wp_ajax_woo_kipedia_save_settings', 'woo_kipedia_save_settings');
add_action('wp_ajax_nopriv_woo_kipedia_save_settings', 'woo_kipedia_save_settings');
