<?php
/*
Plugin Name: Custom Order Plugins
Plugin URI: https://example.com
Description: Custom car order multi-step form.
Version: 2.1.1 FINAL (Modified)
Author: MD. Khalid Chowdhury
License: wpdevspro
Text Domain: ecdautodesign
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include the ACF fields 
require_once plugin_dir_path(__FILE__) . 'includes/acf-fields.php';

/**
 *
 * Plugin Basic Setup 
 *
 */
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



/**
 *
 * Save Delivery Time to Cart Item Data
 *
 */
add_filter('woocommerce_add_cart_item_data', 'ecd_save_timeframe_to_cart_item', 10, 2);
function ecd_save_timeframe_to_cart_item($cart_item_data, $product_id) {
    if (isset($_REQUEST['time']) && !empty($_REQUEST['time'])) {
        $cart_item_data['ecd_purchase_time'] = sanitize_key($_REQUEST['time']);
    }
    return $cart_item_data;
}


/**
 *
 * In Cart Page, Show Delivery Time Notice Under Product Name
 *
 */
add_filter('woocommerce_cart_item_name', 'ecd_display_delivery_time_under_product_name', 10, 3);
function ecd_display_delivery_time_under_product_name($product_name, $cart_item, $cart_item_key) {
    if (isset($cart_item['ecd_purchase_time'])) {
        $time_slug = $cart_item['ecd_purchase_time'];
        $timeframe_options = [
            'now'      => 'Deliver Right Now',
            '3_months' => 'Deliver within 3 months',
            '6_months' => 'Deliver within 6 months',
        ];
        $timeframe_text = isset($timeframe_options[$time_slug]) ? $timeframe_options[$time_slug] : '';

        if ($timeframe_text) {
            $delivery_time_display = 'Delivery: ' . $timeframe_text;
            $product_name .= '<div class="ecd-delivery-time-meta" style="font-size: 0.9em; color: #555;">' . esc_html($delivery_time_display) . '</div>';
        }
    }
    return $product_name;
}




/**
 *
 * Save Delivery Time for Admin Panel
 *
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
            $item->add_meta_data('_delivery_timeframe', $timeframe_text, true);
        }
    }
}



/**
 *
 * Hide Public and Show Only Admin Panel Delivery Time 
 *
 */
add_filter('woocommerce_hidden_order_itemmeta', 'ecd_show_hidden_timeframe_in_admin', 10, 1);
function ecd_show_hidden_timeframe_in_admin($hidden_meta) {
    $key_to_show = '_delivery_timeframe';
    $key_index = array_search($key_to_show, $hidden_meta);

    if (false !== $key_index) {
        unset($hidden_meta[$key_index]);
    }
    return $hidden_meta;
}


/**
 *
 * Clean Display Delivery Time Key in Admin
 *
 */
add_filter('woocommerce_order_item_display_meta_key', 'ecd_clean_timeframe_meta_key', 10, 3);
function ecd_clean_timeframe_meta_key($display_key, $meta, $item) {
    if ('_delivery_timeframe' === $meta->key) {
        $display_key = 'Delivery';
    }
    return $display_key;
}

