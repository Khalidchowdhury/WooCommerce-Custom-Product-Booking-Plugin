<?php
/*
Plugin Name: ecdautodesign
Plugin URI: https://example.com
Description: Custom car order multi-step form.
Version: 1.0.0
Author: Your Name
License: GPL2
Text Domain: ecdautodesign
*/

// Enqueue styles for the template
add_action('wp_enqueue_scripts', function () {
    if (is_page_template('page-custom-order.php')) {
        wp_enqueue_style('custom-order-css', plugin_dir_url(__FILE__) . 'assets/css/custom-order.css');
    }
});

// Register the template so it appears in dropdown
add_filter('theme_page_templates', function ($templates) {
    $templates['page-custom-order.php'] = 'Custom Order Page';
    return $templates;
});

// Force WordPress to load the template from plugin when selected
add_filter('template_include', function ($template) {
    $page_template = get_page_template_slug(get_queried_object_id());

    if ($page_template === 'page-custom-order.php') {
        $plugin_template = plugin_dir_path(__FILE__) . 'templates/page-custom-order.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }

    return $template;
});
