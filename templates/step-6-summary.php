<?php

$model_slug = isset($_GET['model']) ? sanitize_title($_GET['model']) : '';
$engine_slug = isset($_GET['engine']) ? sanitize_title($_GET['engine']) : '';
$color_slug = isset($_GET['color']) ? sanitize_title($_GET['color']) : '';

$purchase_time_slug = isset($_GET['purchase_time']) ? sanitize_key($_GET['purchase_time']) : '';


$timeframe_options = [
    'now' => 'Right now',
    '3_months' => 'Within 3 months',
    '6_months' => 'Within 6 months',
];
$timeframe_text = isset($timeframe_options[$purchase_time_slug]) ? $timeframe_options[$purchase_time_slug] : 'Not Selected';



$matching_builds = [];
$parent_product = null;

if ($model_slug && $engine_slug && $color_slug) {
    $product_post = get_page_by_path($model_slug, OBJECT, 'product');
    if ($product_post) {
        $parent_product = wc_get_product($product_post->ID);
        
        if ($parent_product && $parent_product->is_type('variable')) {
            $available_variations = $parent_product->get_available_variations();
            
            foreach ($available_variations as $variation_data) {
                $variation_attributes = $variation_data['attributes'];
                
                if (
                    isset($variation_attributes['attribute_pa_engine']) && $variation_attributes['attribute_pa_engine'] == $engine_slug &&
                    isset($variation_attributes['attribute_pa_color']) && $variation_attributes['attribute_pa_color'] == $color_slug
                ) {
                    $matching_builds[] = wc_get_product($variation_data['variation_id']);
                }
            }
        }
    }
}
?>

<!-- =================================== -->
<!--      HTML Output                    -->
<!-- =================================== -->

<div class="custom-order-step custom-order-step-5">
    <div class="summary-container">
        <h2 class="summary-title">Available Builds</h2>
        <p class="summary-subtitle">
            Based on your selections, here are the builds ready for reservation.
        </p>

        <div class="build-listings">
            <?php if (!empty($matching_builds) && $parent_product) : ?>
                <?php foreach ($matching_builds as $build) : ?>
                    <div class="build-card">
                        <div class="build-image">
                            <?php echo $build->get_image(); ?>
                        </div>
                        <div class="build-details">
                            <div class="spec-summary">
                                <h3><?php echo $parent_product->get_name(); ?></h3>
                                <ul>
                                    <?php $engine_term = get_term_by('slug', $engine_slug, 'pa_engine'); ?>
                                    <li><strong>Engine:</strong> <?php echo $engine_term ? esc_html($engine_term->name) : 'N/A'; ?></li>
                                    
                                    <?php $color_term = get_term_by('slug', $color_slug, 'pa_color'); ?>
                                    <li><strong>Color:</strong> <?php echo $color_term ? esc_html($color_term->name) : 'N/A'; ?></li>
                                    
                                    <li><strong>Timeframe:</strong> <?php echo esc_html($timeframe_text); ?></li>
                                </ul>
                            </div>
                            <div class="production-status">
                                <?php 
                                if ($build->get_description()) {
                                    echo wpautop($build->get_description()); 
                                }
                                ?>
                            </div>
                            <div class="build-actions">
                                <div class="build-price">
                                    <?php echo $build->get_price_html(); ?>
                                </div>
                                <?php
                                $checkout_url = add_query_arg([
                                    'add-to-cart' => $build->get_id(),
                                    'time' => $purchase_time_slug, 
                                ], wc_get_checkout_url());
                                ?>
                                <a href="<?php echo esc_url($checkout_url); ?>" class="checkout-btn">
                                    Reserve & Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="no-builds-found">Sorry, no in-progress builds match your exact selections at this time. Please try a different combination or contact us.</p>
            <?php endif; ?>
        </div>
    </div>
</div>