<?php
/**
 * Template Name: Custom Order Page
 */

get_header(); 

$current_step = isset($_GET['step']) ? sanitize_text_field($_GET['step']) : '1';

switch ($current_step) {
    case '1':
        include plugin_dir_path(__FILE__) . '../templates/step-1-welcome.php';
        break;

    case '2':
        include plugin_dir_path(__FILE__) . '../templates/step-2-model-selection.php';
        break;

    case '3':
        include plugin_dir_path(__FILE__) . '../templates/step-3-engine-selection.php';
        break;

    case '4':
        include plugin_dir_path(__FILE__) . '../templates/step-4-color-selection.php';
        break;

    case '5':
        include plugin_dir_path(__FILE__) . '../templates/step-5-time-pciker.php';
        break;

    case '6':
        include plugin_dir_path(__FILE__) . '../templates/step-6-summary.php';
        break;



        

        default:
        echo '<p>Invalid step.</p>';
        break;
}

get_footer();
