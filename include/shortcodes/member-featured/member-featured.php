<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_VC_MZ_Member_Featured extends EASL_MZ_Shortcode {
	public function get_featured_members() {
		$featured_members = array(
			array(
				'name'         => 'Dr Laura Zimmerman',
				'image'        => 'https://easl.websitestage.co.uk/wp-content/uploads/2018/09/AnnareinKerbert_EASL.png',
				'intro'        => 'Reason for being featured, lorem ipsum dolor sit amet, consectetur adipiscing elit, nullam pellentes.',
				'profile_link' => '#',
			),
			array(
				'name'         => 'Jan Masek',
				'image'        => 'https://easl.websitestage.co.uk/wp-content/uploads/2018/09/Masek-photo.png',
				'intro'        => 'Reason for being featured, lorem ipsum dolor sit amet, consectetur adipiscing elit, nullam pellentes.',
				'profile_link' => '#',
			),
			array(
				'name'         => 'Wenshi Wang',
				'image'        => 'https://easl.websitestage.co.uk/wp-content/uploads/2018/09/Wenshi-Wang.jpg',
				'intro'        => 'Reason for being featured, lorem ipsum dolor sit amet, consectetur adipiscing elit, nullam pellentes.',
				'profile_link' => '#',
			),
		);

		return $featured_members;
	}
}