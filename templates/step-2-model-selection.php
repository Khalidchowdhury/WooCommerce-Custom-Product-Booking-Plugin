<div class="custom-order-step custom-order-step-2">
    <div class="model-selection-container">
        <h2 class="model-title">Select Your Model</h2>
        <p class="model-subtitle">Choose your preferred vehicle type to continue.</p>
        
        <div class="model-options">
            <?php

            $selected_models = get_field('modal_selections');

            if ($selected_models && is_array($selected_models)) :
                foreach ($selected_models as $post) :
                    setup_postdata($post);
                    global $product;

                    $stock_class = $product->is_in_stock() ? '' : 'out-of-stock';
                    ?>
                    <a href="<?php echo $product->is_in_stock() ? '?step=3&model=' . esc_attr($product->get_slug()) : '#'; ?>" class="model-box <?php echo $stock_class; ?>">
                        
                        <div class="model-image-container">
                            <?php 
                                if ($product->is_on_sale()) {
                                    echo '<span class="sale-badge">Sale!</span>';
                                }
                                if (!$product->is_in_stock()) {
                                    echo '<span class="stock-badge">Out of Stock</span>';
                                }
                                echo $product->get_image('woocommerce_thumbnail'); 
                            ?>
                        </div>

                        <div class="model-name">
                            <div class="model-details">
                                <h3><?php the_title(); ?></h3>
                                <div class="model-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), 10, '...'); ?>
                                </div>
                            </div>
                            <div class="model-price">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                        </div>

                    </a>
                    <?php
                endforeach;
                wp_reset_postdata();
            else :
                echo '<p>No models found. Please select them from the ACF field.</p>';
            endif;
            ?>
        </div>
    </div>
</div>
