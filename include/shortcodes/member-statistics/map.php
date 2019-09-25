<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

return array(
	'name'           => __( 'EASL MZ Member Statistics', 'easl-member-zone' ),
	'base'           => 'easl_mz_member_statistics',
	'category'       => __( 'EASL MZ', 'easl-member-zone' ),
	'description'    => __( 'EASL MZ Member Statistics', 'easl-member-zone' ),
	'icon'           => 'vcex-icon fa fa-book',
	'php_class_name' => 'EASL_VC_MZ_Member_Statistics',
	'params'         => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget title', 'easl-member-zone' ),
			'param_name' => 'title',
			'description' => __( 'Enter text used as widget title (Note: located above content element).', 'easl-member-zone' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Widget Subtitle', 'easl-member-zone' ),
			'param_name' => 'subtitle',
		),
		vc_map_add_css_animation(),
		array(
			'type'        => 'el_id',
			'heading'     => __( 'Element ID', 'js_composer' ),
			'param_name'  => 'el_id',
			'description' => sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'js_composer' ), 'http://www.w3schools.com/tags/att_global_id.asp' ),
		),
		array(
			'type'        => 'textfield',
			'heading'     => __( 'Extra class name', 'js_composer' ),
			'param_name'  => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' ),
		),
		array(
			'type'       => 'css_editor',
			'heading'    => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group'      => __( 'Design Options', 'js_composer' ),
		),
	)
);
