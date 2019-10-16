<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_MZ_Ajax_Handler {

	private static $_instance;

	/**
	 * @var EASL_MZ_API
	 */
	private $api;
	/**
	 * @var EASL_MZ_Session_Handler
	 */
	private $session;
	private $view_path;

	private function __construct() {
		add_action( "wp_ajax_nopriv_easl_mz_load_crm_view", array( $this, 'handle' ) );
		add_action( "wp_ajax_easl_mz_load_crm_view", array( $this, 'handle' ) );
	}

	public static function get_instance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function get_action_name() {
		return 'easl_mz_load_crm_view';
	}

	public function respond( $html = '', $status = 200 ) {
		wp_send_json( array(
			'Status' => $status,
			'Html'   => $html
		) );
	}

	public function respond_field_errors( $errors = array() ) {
		wp_send_json( array(
			'Status' => 400,
			'Errors' => $errors
		) );
	}

	public function respond_file( $file, $data = array(), $status = 200, $extra_data = array() ) {
		if ( ! is_array( $data ) ) {
			$data = (array) $data;
		}
		ob_start();
		extract( $data );
		include $this->view_path . '/' . rtrim( $file, '/' );
		$html = ob_get_clean();
		wp_send_json( array(
			'Status' => $status,
			'Html'   => $html,
			'Data'   => $extra_data,
		) );
	}

	public function handle() {
		if ( empty( $_POST['method'] ) ) {
			$this->respond( 'No method specified!', 405 );
		}
		if ( ! method_exists( $this, $_POST['method'] ) ) {
			$this->respond( 'Specified method does not exists!', 405 );
		}
		$this->session   = easl_mz_get_manager()->getSession();
		$this->api       = easl_mz_get_manager()->getApi();
		$this->view_path = easl_mz_get_manager()->path( 'CRM_VIEWS' );

		call_user_func( array( $this, $_POST['method'] ) );
	}

	public function reset_member_password() {
		if ( empty( $_POST['request_data']['email'] ) ) {
			$this->respond( 'No fields specified!', 405 );
		}

		// TODO - make some think similar to wp_admin_referer
		$reset = $this->api->reset_password( $_POST['request_data']['email'] );
		if ( ! $reset ) {
			$this->respond( 'Error!', 400 );
		}
		$this->respond( 'Success!', 200 );
	}

	public function get_member_card() {
		$current_member_id = $this->session->ge_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			} else {
				$this->session->unset_auth_cookie( true );
				$this->respond( 'Member not found!', 404 );
			}
		}
		$this->api->get_user_auth_token();
		$member_details = $this->api->get_member_details( $current_member_id, false );

		if ( ! $member_details ) {
			$this->session->unset_auth_cookie( true );
			$this->respond( 'Members details not found!', 404 );
		}

		$member_details['profile_picture'] = $this->api->get_member_profile_picture( $current_member_id );

		$this->respond_file( '/member-card/member-card.php', array( 'member' => $member_details ), 200 );

	}

	public function get_members_list() {
		if ( ! easl_mz_is_member_logged_in() ) {
			$this->respond( 'Member not logged in!', 401 );
		}
		$search      = '';
		$country     = '';
		$speciality  = '';
		$letter      = '';
		$page_offset = '';

		if ( isset( $_POST['request_data']['search'] ) ) {
			$search = trim( $_POST['request_data']['search'] );
		}
		if ( isset( $_POST['request_data']['country'] ) ) {
			$country = trim( $_POST['request_data']['country'] );
		}
		if ( isset( $_POST['request_data']['speciality'] ) ) {
			$speciality = trim( $_POST['request_data']['speciality'] );
		}
		if ( isset( $_POST['request_data']['letter'] ) ) {
			$letter = trim( $_POST['request_data']['letter'] );
		}
		if ( isset( $_POST['request_data']['page_offset'] ) ) {
			$page_offset = trim( $_POST['request_data']['page_offset'] );
		}
		$page_offset = absint( $page_offset );
		$page_offset = $page_offset ? $page_offset : 1;
		$num         = 12;
		$filter_args = array(
			'max_num'  => $num,
			'offset'   => ( $page_offset - 1 ) * $num,
			'order_by' => 'date_modified:ASC',
			'fields'   => 'id,name,salutation,first_name,last_name,picture,dotb_public_profile,dotb_public_profile_fields,primary_address_country,description,dotb_easl_specialty'
		);
		$filter      = array();
		$filter[]    = array(
			'$or' => array(
				array(
					'dotb_public_profile' => array(
						'$equals' => 'Yes'
					)
				),
				array(
					'dotb_public_profile' => array(
						'$equals' => 'Yes_Partial'
					)
				),
			)
		);

		if ( $search ) {
			$search_pieces   = explode( ' ', $search );
			$filter_search   = array();
			$filter_search[] = array(
				'first_name' => array(
					'$contains' => $search
				)
			);
			$filter_search[] = array(
				'last_name' => array(
					'$contains' => $search
				)
			);
			if ( count( $search_pieces ) > 1 ) {
				foreach ( $search_pieces as $search_term ) {
					$filter_search[] = array(
						'first_name' => array(
							'$contains' => $search_term
						)
					);
					$filter_search[] = array(
						'last_name' => array(
							'$contains' => $search_term
						)
					);
				}
			}
			$filter[] = array(
				'$or' => $filter_search
			);
		}
		if ( $letter ) {
			$filter[] = array(
				'last_name' => array(
					'$starts' => $letter
				)
			);
		}
		if ( $country ) {
			$filter[] = array(
				'primary_address_country' => $country
			);
		}
		if ( $speciality ) {
			$filter[] = array(
				'dotb_easl_specialty' => array(
					'$contains' => $speciality
				)
			);
		}
		if ( count( $filter ) > 0 ) {
			$filter_args['filter'] = $filter;
		}

		$this->api->get_user_auth_token();
		$members = $this->api->get_members( $filter_args );
		if ( ! $members ) {
			$this->respond( 'Member not found!', 404 );
		}
		$total_found_member = $this->api->count_members( array( 'filter' => $filter ) );

		$data = array(
			'members'            => $members,
			'current_page'       => $page_offset,
			'member_per_page'    => $num,
			'total_found_member' => $total_found_member
		);
		$this->respond_file( '/member-directory/member-directory.php', $data, 200 );
	}

	public function get_member_details() {
		if ( ! easl_mz_is_member_logged_in() ) {
			$this->respond( 'Member not logged in!', 401 );
		}
		$member_id = '';

		if ( isset( $_POST['request_data']['member_id'] ) ) {
			$member_id = trim( $_POST['request_data']['member_id'] );
		}
		if ( ! $member_id ) {
			$this->respond( 'Member not found!', 404 );
		}
		$member_details = $this->api->get_member_details( $member_id, false );
		if ( ! $member_details ) {
			$this->respond( 'Member not found!', 404 );
		}
		$this->respond_file( '/member-profile-details.php', array( 'member' => $member_details ), 200 );
	}

	public function get_featured_member() {
		$this->api->maybe_get_user_auth_token();
		$featured_members = $this->api->get_featured_members();
		if ( ! $featured_members ) {
			$this->respond( 'Member not found!', 404 );
		}
		$this->respond_file( '/featured-member/featured-member.php', array( 'members' => $featured_members ), 200 );
	}

	public function get_membership_form() {
		$current_member_id = $this->session->ge_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			}
		}
		if ( ! $current_member_id ) {
			$this->respond( 'Member not found!', 404 );
		}
		$member_details = $this->api->get_member_details( $current_member_id );
		if ( ! $member_details ) {
			$this->respond( 'Member ' . $current_member_id . ' not found!', 404 );
		}

		$member_details['profile_picture']   = $this->api->get_member_profile_picture( $current_member_id );
		$member_details['latest_membership'] = $this->api->get_members_latest_membership( $current_member_id );

		$extra_data = array();

		$membership_expiring = easl_mz_get_membership_expiring( array(
			'dotb_mb_current_end_date' => $member_details['dotb_mb_current_end_date'],
			'dotb_mb_id'               => $member_details['dotb_mb_id'],
			'latest_membership'        => $member_details['latest_membership'],
			'first_name'               => $member_details['first_name'],
			'last_name'                => $member_details['last_name'],
			'dotb_mb_current_status'   => $member_details['dotb_mb_current_status'],
		) );
		if ( $membership_expiring ) {
			$extra_data['banner'] = $membership_expiring;
		}


		$this->respond_file( '/memeber-details/memeber-details.php', array( 'member' => $member_details ), 200, $extra_data );
	}

	public function get_membership_banner() {
		$current_member_id = $this->session->ge_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			}
		}
		if ( ! $current_member_id ) {
			$this->respond( 'Member not found!', 404 );
		}
		$member_details = $this->api->get_member_details( $current_member_id );
		if ( ! $member_details ) {
			$this->respond( 'Member ' . $current_member_id . ' not found!', 404 );
		}

		$member_details['latest_membership'] = $this->api->get_members_latest_membership( $current_member_id );

		$membership_expiring = easl_mz_get_membership_expiring( array(
			'dotb_mb_current_end_date' => $member_details['dotb_mb_current_end_date'],
			'dotb_mb_id'               => $member_details['dotb_mb_id'],
			'latest_membership'        => $member_details['latest_membership'],
			'first_name'               => $member_details['first_name'],
			'last_name'                => $member_details['last_name'],
			'dotb_mb_current_status'   => $member_details['dotb_mb_current_status'],
		) );
		if ( ! $membership_expiring ) {
			$this->respond( '', 400 );
		}
		$this->respond( $membership_expiring, 200 );
	}

	public function get_new_membership_form() {
		$current_member_id = $this->session->ge_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			}
		}
		if ( ! $current_member_id ) {
			$this->respond( 'Member not found!', 404 );
		}
		$member_details = $this->api->get_member_details( $current_member_id );
		if ( ! $member_details ) {
			$this->respond( 'Member ' . $current_member_id . ' not found!', 404 );
		}

		$member_details['latest_membership'] = $this->api->get_members_latest_membership( $current_member_id );
		$extra_data                          = array();

		$membership_expiring = easl_mz_get_membership_expiring( array(
			'dotb_mb_current_end_date' => $member_details['dotb_mb_current_end_date'],
			'dotb_mb_id'               => $member_details['dotb_mb_id'],
			'latest_membership'        => $member_details['latest_membership'],
			'first_name'               => $member_details['first_name'],
			'last_name'                => $member_details['last_name'],
			'dotb_mb_current_status'   => $member_details['dotb_mb_current_status'],
		) );
		if ( $membership_expiring ) {
			$extra_data['banner'] = $membership_expiring;
		}

		$renew    = 'no';
		$messages = false;
		if ( isset( $_POST['request_data']['renew'] ) ) {
			$renew = $_POST['request_data']['renew'];
		}
		if ( isset( $_POST['request_data']['messages'] ) ) {
			$messages = $_POST['request_data']['messages'];
		}
		$this->respond_file( '/new-membership-form/new-membership-form.php', array(
			'member'   => $member_details,
			'renew'    => $renew,
			'messages' => $messages
		), 200, $extra_data );
	}

	public function update_member_profile() {
		if ( empty( $_POST['request_data'] ) ) {
			$this->respond( 'No fields specified!', 405 );
		}
		$request_data = array();
		parse_str( $_POST['request_data'], $request_data );
		if ( empty( $request_data['id'] ) ) {
			$this->respond( 'No member specified!', 405 );
		}
		$member_id = $request_data['id'];

		$current_member_id = $this->session->ge_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			}
		}
		if ( ! $current_member_id || ( $current_member_id != $member_id ) ) {
			$this->respond( 'Member not found!', 404 );
		}

		$errors = easl_mz_validate_member_data( $request_data );

		if ( count( $errors ) > 0 ) {
			$this->respond_field_errors( $errors );
		}
		if ( ! isset( $request_data['dotb_public_profile'] ) ) {
			$request_data['dotb_public_profile'] = 'No';
		}
		if ( $request_data['dotb_public_profile'] == 'No' ) {
			$request_data['dotb_public_profile_fields'] = '';
		}

		$request_data['description'] = wp_unslash($request_data['description']);

		unset( $request_data['id'] );
		$updated = $this->api->update_member_personal_info( $member_id, $request_data );
		if ( ! $updated ) {
			$this->respond( 'Error!', 405 );
		}
		$this->respond( 'Your profile updated successfully!', 200 );
	}

	public function change_member_password() {
		if ( empty( $_POST['request_data'] ) ) {
			$this->respond( 'No fields specified!', 405 );
		}

		$errors       = array();
		$request_data = $_POST['request_data'];

		if ( empty( $request_data['old_password'] ) ) {
			$errors['old_password'] = 'Mandatory field.';
		}
		if ( empty( $request_data['new_password'] ) ) {
			$errors['new_password'] = 'Mandatory field.';
		}
		if ( empty( $request_data['new_password2'] ) ) {
			$errors['new_password2'] = 'Mandatory field.';
		}

		if ( $request_data['new_password2'] !== $request_data['new_password'] ) {
			$errors['new_password2'] = 'Must be same as password.';
		}
		if ( count( $errors ) > 0 ) {
			$this->respond_field_errors( $errors );
		}

		$api_args = array(
			'old_password' => $request_data['old_password'],
			'new_password' => $request_data['new_password']
		);

		$updated = $this->api->change_password( $api_args );
		if ( ! $updated ) {
			$this->respond( 'Error!', 405 );
		}
		$this->respond( 'Password changed successfully!', 200 );
	}

	public function create_member_profile() {
		if ( empty( $_POST['request_data'] ) ) {
			$this->respond( 'No fields specified!', 405 );
		}
		$request_data = array();
		parse_str( $_POST['request_data'], $request_data );
		if ( empty( $request_data ) ) {
			$this->respond( 'No fields specified!', 405 );
		}
		$errors = easl_mz_validate_member_data( $request_data );
		if ( ! $errors ) {
			$errors = array();
		}
		if ( empty( $request_data['password'] ) ) {
			$errors['password'] = 'Mandatory field.';
		}
		if ( empty( $request_data['password2'] ) ) {
			$errors['password2'] = 'Mandatory field.';
		}
		$password  = $request_data['password'];
		$password2 = $request_data['password2'];
		unset( $request_data['password'] );
		unset( $request_data['password2'] );

		if ( $password !== $password2 ) {
			$errors['password2'] = 'Must be same as password.';
		}
		if ( count( $errors ) > 0 ) {
			$this->respond_field_errors( $errors );
		}

		$request_data['description'] = wp_unslash($request_data['description']);

		$request_data['portal_name']      = $request_data['email1'];
		$request_data['portal_password']  = $password;
		$request_data['portal_password1'] = $password;
		$request_data['portal_active']    = true;


		$this->api->get_user_auth_token();

		$created_member_id = $this->api->create_member( $request_data );

		if ( ! $created_member_id ) {
			$this->respond( 'Error!', 405 );
		}

		$membership_page = easl_member_new_membership_form_url( false );
		if ( ! $membership_page ) {
			$membership_page = get_field( 'member_dashboard_url', 'option' );
		}
		if ( ! $membership_page ) {
			$membership_page = get_site_url();
		}

		$auth_response_status = $this->api->get_auth_token( $request_data['portal_name'], $password, true );
		if ( ! $auth_response_status ) {
			$this->respond_file( 'member-login/basic-login-form.php', array( 'redirect_url' => $membership_page ), 201 );
		}
		// Member authenticated
		$this->session->set_auth_cookie( $request_data['portal_name'], $this->api->get_credential_data( true ) );
		$this->session->add_data( 'member_id', $created_member_id );
		$this->session->save_session_data();
		$this->respond( $membership_page, 200 );
	}

	public function delete_current_member() {
		if ( empty( $_POST['request_data'] ) ) {
			$this->respond( 'No fields specified!', 405 );
		}
		$request_data = $_POST['request_data'];
		if ( empty( $request_data['id'] ) ) {
			$this->respond( 'No member specified!', 405 );
		}
		$member_id = $request_data['id'];

		$current_member_id = $this->session->ge_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			}
		}
		if ( ! $current_member_id || ( $current_member_id != $member_id ) ) {
			$this->respond( 'Member not found!', 404 );
		}
		$this->api->maybe_get_user_auth_token();
		$deleted = $this->api->delete_member_account( $member_id );
		if ( ! $deleted ) {
			$this->respond( 'Error!', 400 );
		}

		$this->session->unset_auth_cookie();
		$this->api->clear_credentials();

		do_action( 'easl_mz_member_logged_out' );

		$this->respond( 'Your account deleted successfully!', 200 );
	}
}