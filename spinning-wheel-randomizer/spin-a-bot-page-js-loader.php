<?php
/**
 * Plugin Name: Spinabot Page JS Loader
 * Description: Loads the Spin-a-Bot wheel script only on the /spin-a-bot/ page.
 * Version: 1.0
 * Author: Teresa Kaylor
 */

add_action('wp_enqueue_scripts', function () {
    if (is_page('spin-a-bot')) {
        wp_enqueue_script(
            'spinabot-wheel',
            plugin_dir_url(__FILE__) . 'js/spinabot-wheel.js',
            array(),
            null,
            true
        );
    }
});
