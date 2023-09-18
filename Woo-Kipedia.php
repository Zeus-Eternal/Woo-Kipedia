<?php
/*
Plugin Name: Woo-Kipedia
Description: Display WooCommerce Product pages as Wiki's with Shortcode
Version: 0.2.5
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
    register_setting('woo-kipedia-settings-group', 'woo-kipedia-link-as-button', 'sanitize_text_field'); // New option
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
               class="nav-tab <?php echo isset($_GET['tab']) && $_GET['tab'] === 'info' ? 'nav-tab-active' : ''; ?>">Info</a>
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
                                   value="<?php echo esc_attr(get_option('woo-kipedia-max-description-words', 500)); ?>"/>
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
                        <th scope="row">Show Read More Button</th>
                        <td>
                            <label for="woo-kipedia-show-read-more">
                                <input type="checkbox" id="woo-kipedia-show-read-more"
                                       name="woo-kipedia-show-read-more" value="yes"
                                    <?php checked(get_option('woo-kipedia-show-read-more', 'yes'), 'yes'); ?> />
                                Show Read More
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Read More Button Text</th>
                        <td><input type="text" name="woo-kipedia-read-more-text"
                                   value="<?php echo esc_attr(get_option('woo-kipedia-read-more-text', 'Read Woo-Ki')); ?>"/>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Link as Button</th>
                        <td>
                            <label for="woo-kipedia-link-as-button">
                                <input type="checkbox" id="woo-kipedia-link-as-button"
                                       name="woo-kipedia-link-as-button" value="yes"
                                    <?php checked(get_option('woo-kipedia-link-as-button', 'no'), 'yes'); ?> />
                                Link as Button
                            </label>
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
                    <!-- Shortcode descriptions here -->
                </div>
            <?php } ?>
            <?php submit_button(); ?>
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
        <li><code>per_page</code> (default: 20) - Number of products to display per page.</li>
        <li><code>order_by</code> (default: 'date') - Order products by 'date', 'title', or 'rand' (random).</li>
        <li><code>order</code> (default: 'asc') - Sort products in 'asc' (ascending) or 'desc' (descending) order.</li>
        <li><code>max_description_words</code> (default: 500) - Maximum number of words to display in the short description.</li>
        <li><code>show_price</code> (default: 'yes') - Show product prices ('yes' or 'no').</li>
        <li><code>show_thumbnail</code> (default: 'yes') - Show product thumbnail images ('yes' or 'no').</li>
        <li><code>show_read_more</code> (default: 'yes') - Show the "Read More" link or button ('yes' or 'no').</li>
        <li><code>read_more_text</code> (default: 'Read Woo-Ki') - Text for the "Read More" link or button.</li>
        <li><code>link_as_button</code> (default: 'no') - Display the "Read More" link as a button ('yes' or 'no').</li>
    </ul>
    <?php
    return ob_get_clean();
}

// Enqueue CSS styles
function woo_kipedia_enqueue_styles() {
    wp_enqueue_style('woo-kipedia-styles', plugin_dir_url(__FILE__) . 'styles.css');
}

add_action('wp_enqueue_scripts', 'woo_kipedia_enqueue_styles');
