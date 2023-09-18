// woo-kipedia-admin.js
jQuery(document).ready(function($) {
    // Ajax call to save settings
    $('#woo-kipedia-save-settings').on('click', function() {
        var data = {
            action: 'woo_kipedia_save_settings',
            per_page: $('#woo-kipedia-per-page').val(),
            // Add other settings here
        };

        $.post(ajaxurl, data, function(response) {
            // Handle success or error here
            if (response.success) {
                alert('Settings saved successfully');
            } else {
                alert('Error saving settings');
            }
        });
    });
});
