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

	public function respond_file( $file, $data = array(), $status = 200 ) {
		if ( ! is_array( $data ) ) {
			$data = (array) $data;
		}
		ob_start();
		extract( $data );
		include $this->view_path . '/' . rtrim( $file, '/' );
		$html = ob_get_clean();
		wp_send_json( array(
			'Status' => $status,
			'Html'   => $html
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
			}
		}
		if ( ! $current_member_id ) {
			$this->respond( 'Member not found!', 404 );
		}
		$member_details = wp_cache_get( 'easl_mz_api_member' . $current_member_id, 'easlmz_cache_group' );
		if ( ! $member_details ) {
			$member_details = $this->api->get_member_details( $current_member_id );
		}

		if ( ! $member_details ) {
			$this->respond( 'Member not found!', 404 );
		}
		$this->respond_file( '/member-card/member-card.php', array( 'member' => $member_details ), 200 );

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
		$this->respond_file( '/memeber-details/memeber-details.php', array( 'member' => $member_details ), 200 );
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

		$request_data['portal_name']     = $request_data['email1'];
		$request_data['portal_password'] = $password;


		$this->api->maybe_get_user_auth_token();

		$created_member_id = $this->api->create_member( $request_data );

		if ( ! $created_member_id ) {
			$this->respond( 'Error!', 405 );
		}

		$membership_page = get_field( 'membership_plan_url', 'option' );
		if ( ! $membership_page ) {
			$membership_page = get_field( 'member_dashboard_url', 'option' );
		}
		if ( ! $membership_page ) {
			$membership_page = get_site_url();
		}

		$auth_response_status = $this->api->get_auth_token( $request_data['portal_name'], $password, true );
		if ( ! $auth_response_status ) {
			$this->respond_file( 'member-login/login-form.php', array( 'redirect_url' => $membership_page ), 201 );
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