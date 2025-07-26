<?php


$model_slug = isset($_GET['model']) ? sanitize_title($_GET['model']) : '';
$engine_slug = isset($_GET['engine']) ? sanitize_title($_GET['engine']) : '';
$color_slug = isset($_GET['color']) ? sanitize_title($_GET['color']) : '';

if (empty($model_slug) || empty($engine_slug) || empty($color_slug)) {
    // wp_redirect( home_url('/step-1/') ); // আপনার প্রথম ধাপের URL দিন
    // exit;
}
?>

<div class="ecd-form-container">
    <div class="ecd-form-step active" id="time-selection-step">

        <form method="GET" action="<?php echo esc_url(get_permalink()); ?>">
            
            <input type="hidden" name="model" value="<?php echo esc_attr($model_slug); ?>">
            <input type="hidden" name="engine" value="<?php echo esc_attr($engine_slug); ?>">
            <input type="hidden" name="color" value="<?php echo esc_attr($color_slug); ?>">
            
            <input type="hidden" name="step" value="6">

            <h2 class="step-title">When do you want to buy the car?</h2>
            <p class="step-subtitle">Select your preferred time frame.</p>
            
            <div class="options-grid">
                
                <div class="option-wrapper">
                    <input type="radio" id="time-now" name="purchase_time" value="now" checked>
                    <label for="time-now" class="option-card">
                        <span class="option-title">Right now</span>
                        <span class="option-desc">For immediate delivery</span>
                    </label>
                </div>
                
                <div class="option-wrapper">
                    <input type="radio" id="time-3-months" name="purchase_time" value="3_months">
                    <label for="time-3-months" class="option-card">
                        <span class="option-title">Within 3 months</span>
                        <span class="option-desc">For the best deals</span>
                    </label>
                </div>
                
                <div class="option-wrapper">
                    <input type="radio" id="time-6-months" name="purchase_time" value="6_months">
                    <label for="time-6-months" class="option-card">
                        <span class="option-title">Within 6 months</span>
                        <span class="option-desc">For planning</span>
                    </label>
                </div>
                
            </div>
            
            <div class="step-navigation">
                <button type="submit" class="next-step-btn">View Summary →</button>
            </div>

        </form>
    </div>
</div>