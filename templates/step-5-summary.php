<?php

$model_slug = isset($_GET['model']) ? sanitize_title($_GET['model']) : '';
$engine_slug = isset($_GET['engine']) ? sanitize_title($_GET['engine']) : '';
$color_slug = isset($_GET['color']) ? sanitize_title($_GET['color']) : '';

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

<div class="custom-order-step custom-order-step-5">
    <div class="summary-container">
        <h2 class="summary-title">Available Builds</h2>
        <p class="summary-subtitle">
            Based on your selections, here are the builds ready for reservation.
        </p>

        <div class="build-listings">
            <?php if (!empty($matching_builds)) : ?>
                <?php foreach ($matching_builds as $build) : ?>
                    <div class="build-card">
                        <div class="build-image">
                            <?php echo $build->get_image(); ?>
                        </div>
                        <div class="build-details">
                            <div class="spec-summary">
                                <h3><?php echo $parent_product->get_name(); ?></h3>
                                <ul>
                                    <li><strong>Engine:</strong> <?php echo get_term_by('slug', $engine_slug, 'pa_engine')->name; ?></li>
                                    <li><strong>Color:</strong> <?php echo get_term_by('slug', $color_slug, 'pa_color')->name; ?></li>
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
                                <a href="<?php echo esc_url( wc_get_checkout_url() . '?add-to-cart=' . $build->get_id() ); ?>" class="checkout-btn">
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