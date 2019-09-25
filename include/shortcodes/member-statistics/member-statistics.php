<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_MZ_Member_Statistics extends EASL_MZ_Shortcode {

	public function enqueue_map_scripts(){
		wp_enqueue_script('mz-map-script', easl_mz_get_asset_url('/js/map.js'), array('jquery'), time(), true);
	}
}