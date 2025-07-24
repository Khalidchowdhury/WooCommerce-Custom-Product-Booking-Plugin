<?php

$model_slug = isset($_GET['model']) ? sanitize_title($_GET['model']) : '';
$engine_slug = isset($_GET['engine']) ? sanitize_title($_GET['engine']) : '';
$product = null;
$color_terms = [];

if ($model_slug) {
    $product_post = get_page_by_path($model_slug, OBJECT, 'product');
    if ($product_post) {
        $product = wc_get_product($product_post->ID);
    }
}

if ($product) {
    $color_terms = wc_get_product_terms($product->get_id(), 'pa_color', array('fields' => 'all'));
}
?>

<div class="custom-order-step custom-order-step-4">
    <div class="color-selection-container">
        <h2 class="color-title">Select Your Color</h2>
        <p class="color-subtitle">
            Choose a color for: <strong><?php echo esc_html($product ? $product->get_name() : 'Unknown Model'); ?></strong>
        </p>

        <div class="color-options">
            <?php if (!empty($color_terms)) : ?>
                <?php foreach ($color_terms as $term) : ?>
                    <a href="?step=5&model=<?php echo esc_attr($model_slug); ?>&engine=<?php echo esc_attr($engine_slug); ?>&color=<?php echo esc_attr($term->slug); ?>" class="color-box">
                        <div class="color-info">
                            <h3><?php echo esc_html($term->name); ?></h3>
                            <?php if (!empty($term->description)) : ?>
                                <p><?php echo esc_html($term->description); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No color options found for this model. Please ensure the 'color' attribute is set for this product in WooCommerce.</p>
            <?php endif; ?>
        </div>
    </div>
</div>