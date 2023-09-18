<?php
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
                    <!-- Other settings fields go here -->
                </table>
            <?php } ?>
            <?php if (isset($_GET['tab']) && $_GET['tab'] === 'info') { ?>
                <div class="woo-kipedia-info">
                    <!-- Information about the plugin goes here -->
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
