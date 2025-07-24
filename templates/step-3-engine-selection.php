<?php
$model_slug = isset($_GET['model']) ? sanitize_title($_GET['model']) : '';
$product = null;
$engine_terms = [];

if ($model_slug) {
    $product_post = get_page_by_path($model_slug, OBJECT, 'product');
    if ($product_post) {
        $product = wc_get_product($product_post->ID);
    }
}

if ($product) {
    $engine_terms = wc_get_product_terms($product->get_id(), 'pa_engine', array('fields' => 'all'));
}
?>

<div class="custom-order-step custom-order-step-3">
    <div class="engine-selection-container">
        <h2 class="engine-title">Select Your Engine</h2>
        <p class="engine-subtitle">
            Choose an engine for: <strong><?php echo esc_html($product ? $product->get_name() : 'Unknown Model'); ?></strong>
        </p>
        <div class="engine-options">
            <?php if (!empty($engine_terms)) : ?>
                <?php foreach ($engine_terms as $term) : ?>
                    <a href="?step=4&model=<?php echo esc_attr($model_slug); ?>&engine=<?php echo esc_attr($term->slug); ?>" class="engine-box">
                        <div class="engine-info">
                            <h3><?php echo esc_html($term->name); ?></h3>
                            <?php if (!empty($term->description)) : ?><p><?php echo esc_html($term->description); ?></p><?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No engine options found for this model.</p>
            <?php endif; ?>
        </div>
    </div>
</div>