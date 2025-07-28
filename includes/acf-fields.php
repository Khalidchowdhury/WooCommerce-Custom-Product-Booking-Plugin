<?php



add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_6884fafca07e2',
	'title' => 'Custom Order Plugins Options',
	'fields' => array(
		array(
			'key' => 'field_6884fafd22b80',
			'label' => 'Insurance Fee',
			'name' => 'insurance_fee',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '50',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'min' => '',
			'max' => '',
			'allow_in_bindings' => 1,
			'placeholder' => 'Enter Your Insurance Free',
			'step' => '',
			'prepend' => '',
			'append' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'product',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );

	acf_add_local_field_group( array(
	'key' => 'group_6887755361915',
	'title' => 'Custom Ordre Modal Selection',
	'fields' => array(
		array(
			'key' => 'field_6887755481e36',
			'label' => 'Modal selections',
			'name' => 'modal_selections',
			'aria-label' => '',
			'type' => 'post_object',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array(
				0 => 'product',
			),
			'post_status' => array(
				0 => 'publish',
			),
			'taxonomy' => array(
				0 => 'product_cat:all-products',
			),
			'return_format' => 'object',
			'multiple' => 1,
			'allow_null' => 0,
			'allow_in_bindings' => 1,
			'bidirectional' => 0,
			'ui' => 1,
			'bidirectional_target' => array(
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_template',
				'operator' => '==',
				'value' => 'page-custom-order.php',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
) );
} );

