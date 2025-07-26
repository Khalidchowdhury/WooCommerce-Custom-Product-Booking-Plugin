<?php
/*
Plugin Name: ecdautodesign
Plugin URI: https://example.com
Description: Custom car order multi-step form.
Version: 2.1.0 FINAL
Author: MD. Khalid Chowdhury
License: wpdevspro
Text Domain: ecdautodesign
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// =========================================================================
//  SECTION 1: PLUGIN SETUP (Template Loading and Styles)
// =========================================================================

add_action('wp_enqueue_scripts', function () {
    if (is_page_template('page-custom-order.php')) {
        wp_enqueue_style('custom-order-css', plugin_dir_url(__FILE__) . 'assets/css/custom-order.css');
    }
});

add_filter('theme_page_templates', function ($templates) {
    $templates['page-custom-order.php'] = 'Custom Order Page';
    return $templates;
});

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


// =========================================================================
//  SECTION 2: CORE FUNCTIONALITY (Save and Display Data Correctly)
// =========================================================================

/**
 * Step 1: Save the selected 'time' from the URL to the cart item data.
 */
add_filter('woocommerce_add_cart_item_data', 'ecd_save_timeframe_to_cart_item', 10, 2);
function ecd_save_timeframe_to_cart_item($cart_item_data, $product_id) {
    if (isset($_REQUEST['time']) && !empty($_REQUEST['time'])) {
        $cart_item_data['ecd_purchase_time'] = sanitize_key($_REQUEST['time']);
    }
    return $cart_item_data;
}


/**
 * Step 2: Save the delivery time to the order as HIDDEN meta data.
 * The underscore prefix '_' hides the data from all customer-facing views.
 */
add_action('woocommerce_checkout_create_order_line_item', 'ecd_save_timeframe_to_order_item', 10, 4);
function ecd_save_timeframe_to_order_item($item, $cart_item_key, $values, $order) {
    if (isset($values['ecd_purchase_time'])) {
        $time_slug = $values['ecd_purchase_time'];
        $timeframe_options = [
            'now'      => 'Deliver Right Now',
            '3_months' => 'Deliver within 3 months',
            '6_months' => 'Deliver within 6 months',
        ];
        $timeframe_text = isset($timeframe_options[$time_slug]) ? $timeframe_options[$time_slug] : '';

        if ($timeframe_text) {
            // The underscore hides this data from the frontend.
            $item->add_meta_data('_delivery_timeframe', $timeframe_text, true);
        }
    }
}


/**
 * **NEW - Step 3**: Un-hide our custom meta data ONLY in the admin order view.
 * This tells WooCommerce to show our hidden field in the admin area.
 */
add_filter('woocommerce_hidden_order_itemmeta', 'ecd_show_hidden_timeframe_in_admin', 10, 1);
function ecd_show_hidden_timeframe_in_admin($hidden_meta) {
    // Find our key '_delivery_timeframe' and remove it from the "hidden" list.
    $key_to_show = '_delivery_timeframe';
    $key_index = array_search($key_to_show, $hidden_meta);

    if (false !== $key_index) {
        unset($hidden_meta[$key_index]);
    }
    return $hidden_meta;
}


/**
 * **NEW - Step 4**: Clean up the display label for our custom meta data.
 * This changes the ugly "_delivery_timeframe" to a beautiful "Delivery".
 */
add_filter('woocommerce_order_item_display_meta_key', 'ecd_clean_timeframe_meta_key', 10, 3);
function ecd_clean_timeframe_meta_key($display_key, $meta, $item) {
    // If the key is our hidden key...
    if ('_delivery_timeframe' === $meta->key) {
        // ...change its display name to a clean, readable label.
        $display_key = 'Delivery';
    }
    return $display_key;
}