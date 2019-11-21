<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function easl_mz_customizer_panels($panels) {
	$panels['member_zone'] = array(
		'title' => __('Member Zone', 'total-child'),
		'condition' => '__return_true',
	);
	return $panels;
}
add_filter('wpex_customizer_panels', 'easl_mz_customizer_panels', 100);

function easl_mz_customizer_sections($sections) {
	// Logout button 1
	$sections['easl_mz_logout_panel_button1'] = array(
		'title' => __( 'Logout Panel Button 1', 'total' ),
		'panel' => 'wpex_member_zone',
		'settings' => array(
			array(
				'id' => 'mz_logout_button_1_title',
				'transport' => 'partialRefresh',
				'default' => '',
				'control' => array(
					'label' => __('Title', 'total-child'),
					'type' => 'text',
				),
			),
			array(
				'id' => 'mz_logout_button_1_url',
				'transport' => 'partialRefresh',
				'default' => '',
				'control' => array(
					'label' => __('Url', 'total-child'),
					'type' => 'text',
				),
			),
			array(
				'id' => 'mz_logout_button_1_nt',
				'transport' => 'partialRefresh',
				'default' => true,
				'control' => array(
					'label' => __( 'Open in new tab', 'total' ),
					'type' => 'checkbox',
				),
			),
		)
	);
	// Logout button 2
	$sections['easl_mz_logout_panel_button2'] = array(
		'title' => __( 'Logout Panel Button 2', 'total' ),
		'panel' => 'wpex_member_zone',
		'settings' => array(
			array(
				'id' => 'mz_logout_button_2_title',
				'transport' => 'partialRefresh',
				'default' => '',
				'control' => array(
					'label' => __('Title', 'total-child'),
					'type' => 'text',
				),
			),
			array(
				'id' => 'mz_logout_button_2_url',
				'transport' => 'partialRefresh',
				'default' => '',
				'control' => array(
					'label' => __('Url', 'total-child'),
					'type' => 'text',
				),
			),
			array(
				'id' => 'mz_logout_button_2_nt',
				'transport' => 'partialRefresh',
				'default' => true,
				'control' => array(
					'label' => __( 'Open in new tab', 'total' ),
					'type' => 'checkbox',
				),
			),
		)
	);

	// Logout button 3
	$sections['easl_mz_logout_panel_button3'] = array(
		'title' => __( 'Logout Panel Button 3', 'total' ),
		'panel' => 'wpex_member_zone',
		'settings' => array(
			array(
				'id' => 'mz_logout_button_3_title',
				'transport' => 'partialRefresh',
				'default' => '',
				'control' => array(
					'label' => __('Title', 'total-child'),
					'type' => 'text',
				),
			),
			array(
				'id' => 'mz_logout_button_3_url',
				'transport' => 'partialRefresh',
				'default' => '',
				'control' => array(
					'label' => __('Url', 'total-child'),
					'type' => 'text',
				),
			),
			array(
				'id' => 'mz_logout_button_3_nt',
				'transport' => 'partialRefresh',
				'default' => true,
				'control' => array(
					'label' => __( 'Open in new tab', 'total' ),
					'type' => 'checkbox',
				),
			),
		)
	);
	return $sections;
}

add_filter('wpex_customizer_sections', 'easl_mz_customizer_sections');