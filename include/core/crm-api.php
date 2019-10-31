<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_MZ_API {
	protected $access_token;
	protected $refresh_token;
	protected $download_token;
	protected $expires_in;
	protected $refresh_expires_in;
	protected $token_set_time;
	protected $auth_refresh_called = false;
	protected $session_expired = false;


	protected $crm_user_name;
	protected $crm_password;

	protected $user_access_token;
	protected $user_refresh_token;
	protected $user_download_token;
	protected $user_expires_in;
	protected $user_refresh_expires_in;
	protected $user_token_set_time;
	protected $user_auth_refresh_called = false;
	protected $user_session_expired = false;

	private static $_instance;
	/**
	 * @var EASL_MZ_Request
	 */
	protected $request;

	protected $server_response_delay;

	protected function __construct() {
		$base_uri = untrailingslashit( get_field( 'mz_api_base_url', 'option' ) );

		$this->crm_user_name = get_field( 'mz_api_username', 'option' );
		$this->crm_password  = get_field( 'mz_api_password', 'option' );

		$this->server_response_delay = 10;// 10s

		$this->request = new EASL_MZ_Request( $base_uri );

		$this->load_user_credentials();
	}

	public static function get_instance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function clear_credentials( $is_member = true ) {
		if ( $is_member ) {
			$this->access_token       = '';
			$this->refresh_token      = '';
			$this->download_token     = '';
			$this->expires_in         = '';
			$this->refresh_expires_in = '';
			$this->token_set_time     = '';
		} else {
			$this->user_access_token       = '';
			$this->user_refresh_token      = '';
			$this->user_download_token     = '';
			$this->user_expires_in         = '';
			$this->user_refresh_expires_in = '';
			$this->user_token_set_time     = '';
		}

	}

	public function get_credential_data( $is_member = true ) {
		return array(
			'access_token'       => $this->get_access_token( $is_member ),
			'refresh_token'      => $this->get_refresh_token( $is_member ),
			'download_token'     => $this->get_download_token( $is_member ),
			'expires_in'         => $this->get_expires_in( $is_member ),
			'refresh_expires_in' => $this->get_refresh_expires_in( $is_member ),
			'token_set_time'     => $this->get_token_set_time( $is_member ),
		);
	}

	public function set_credentials( $data = array(), $is_member = true ) {
		if ( $is_member ) {
			if ( isset( $data['access_token'] ) ) {
				$this->access_token = $data['access_token'];
			}
			if ( isset( $data['refresh_token'] ) ) {
				$this->refresh_token = $data['refresh_token'];
			}
			if ( isset( $data['download_token'] ) ) {
				$this->download_token = $data['download_token'];
			}
			if ( isset( $data['expires_in'] ) ) {
				$this->expires_in = intval( $data['expires_in'] );
			}
			if ( isset( $data['refresh_expires_in'] ) ) {
				$this->refresh_expires_in = intval( $data['refresh_expires_in'] );
			}
			if ( isset( $data['token_set_time'] ) ) {
				$this->token_set_time = intval( $data['token_set_time'] );
			}
		} else {
			if ( isset( $data['access_token'] ) ) {
				$this->user_access_token = $data['access_token'];
			}
			if ( isset( $data['refresh_token'] ) ) {
				$this->user_refresh_token = $data['refresh_token'];
			}
			if ( isset( $data['download_token'] ) ) {
				$this->user_download_token = $data['download_token'];
			}
			if ( isset( $data['expires_in'] ) ) {
				$this->user_expires_in = intval( $data['expires_in'] );
			}
			if ( isset( $data['refresh_expires_in'] ) ) {
				$this->user_refresh_expires_in = intval( $data['refresh_expires_in'] );
			}
			if ( isset( $data['token_set_time'] ) ) {
				$this->user_token_set_time = intval( $data['token_set_time'] );
			}
			$this->save_user_credentials();
		}
	}

	public function load_user_credentials() {
		$credentials = get_transient( 'easl_mz_crm_user_credentials' );

		if ( empty( $credentials['access_token'] ) ) {
			$credentials = array();
		}
		$credentials                   = wp_parse_args( $credentials, array(
			'access_token'       => '',
			'expires_in'         => '',
			'refresh_token'      => '',
			'refresh_expires_in' => '',
			'download_token'     => '',
			'token_set_time'     => '',
		) );
		$this->user_access_token       = $credentials['access_token'];
		$this->user_refresh_token      = $credentials['refresh_token'];
		$this->user_download_token     = $credentials['download_token'];
		$this->user_expires_in         = $credentials['expires_in'];
		$this->user_refresh_expires_in = $credentials['refresh_expires_in'];
		$this->user_token_set_time     = $credentials['token_set_time'];
	}

	public function save_user_credentials() {
		$credentials = array(
			'access_token'       => $this->user_access_token,
			'refresh_token'      => $this->user_refresh_token,
			'download_token'     => $this->user_download_token,
			'expires_in'         => $this->user_expires_in,
			'refresh_expires_in' => $this->user_refresh_expires_in,
			'token_set_time'     => $this->user_token_set_time,
		);
		set_transient( 'easl_mz_crm_user_credentials', $credentials, $this->user_refresh_expires_in );
	}

	public function is_session_expired( $is_member = true ) {
		return $is_member ? $this->session_expired : $this->user_session_expired;
	}

	public function is_auth_refresh_called( $is_member = true ) {
		return $is_member ? $this->auth_refresh_called : $this->user_auth_refresh_called;
	}

	public function get_portal( $is_member = true ) {
		return $is_member ? 'portal' : 'base';
	}

	public function get_access_token( $is_member = true ) {
		return $is_member ? $this->access_token : $this->user_access_token;
	}

	public function get_refresh_token( $is_member = true ) {
		return $is_member ? $this->refresh_token : $this->user_refresh_token;
	}

	public function get_download_token( $is_member = true ) {
		return $is_member ? $this->download_token : $this->user_download_token;
	}

	public function get_expires_in( $is_member = true ) {
		return $is_member ? $this->expires_in : $this->user_expires_in;
	}

	public function get_token_set_time( $is_member = true ) {
		return $is_member ? $this->token_set_time : $this->user_token_set_time;
	}

	public function get_refresh_expires_in( $is_member = true ) {
		return $is_member ? $this->refresh_expires_in : $this->user_refresh_expires_in;
	}

	public function get_auth_token( $username, $password, $is_member = true ) {
		$this->clear_credentials( $is_member );
		if ( $is_member ) {
			$this->auth_refresh_called = true;
		} else {
			$this->user_auth_refresh_called = true;
		}
		$request_body = array(
			'grant_type'    => 'password',
			'client_id'     => 'sugar',
			'client_secret' => '',
			'username'      => $username,
			'password'      => $password,
			'platform'      => $this->get_portal( $is_member ),
		);

		$headers = array(
			'Content-Type'  => 'application/json',
			'Cache-Control' => 'no-cache'
		);
		if ( ! $this->post( '/oauth2/token', $is_member, $headers, $request_body ) ) {
			return false;
		}

		$response = $this->request->get_response_body();

		$return_data = array(
			'access_token'       => $response->access_token,
			'expires_in'         => $response->expires_in,
			'refresh_token'      => $response->refresh_token,
			'refresh_expires_in' => $response->refresh_expires_in,
			'download_token'     => $response->download_token,
			'token_set_time'     => time(),
		);

		$this->set_credentials( $return_data, $is_member );

		return true;
	}

	public function get_user_auth_token() {
		$this->get_auth_token( $this->crm_user_name, $this->crm_password, false );
	}

	public function maybe_get_user_auth_token() {
		$get = false;
		if ( ! $this->user_access_token || ! $this->user_expires_in || ! $this->user_token_set_time ) {
			$get = true;
		}
		if ( ( $this->user_token_set_time + $this->user_expires_in - $this->server_response_delay ) < time() ) {
			$get = true;
		}
		if ( $get && $this->crm_user_name && $this->crm_password ) {
			$this->get_auth_token( $this->crm_user_name, $this->crm_password, false );
		}

		return $get;
	}

	public function refresh_auth_token( $is_member = true ) {
		if ( $is_member ) {
			$this->auth_refresh_called = true;
		} else {
			return $this->get_auth_token( $this->crm_user_name, $this->crm_password, false );
		}
		$request_body = array(
			'grant_type'    => 'refresh_token',
			'client_id'     => 'sugar',
			'client_secret' => '',
			'refresh_token' => $this->get_refresh_token( true ),
			'platform'      => $this->get_portal( true ),
		);
		$headers      = array(
			'Content-Type'  => 'application/json',
			'Cache-Control' => 'no-cache'
		);

		$this->clear_credentials( true );

		if ( ! $this->post( '/oauth2/token', true, $headers, $request_body ) ) {
			$this->session_expired = true;
			do_action( 'easl_mz_member_token_expired', true );

			return false;
		}

		$response = $this->request->get_response_body();

		$return_data = array(
			'access_token'       => $response->access_token,
			'expires_in'         => $response->expires_in,
			'refresh_token'      => $response->refresh_token,
			'refresh_expires_in' => $response->refresh_expires_in,
			'download_token'     => $response->download_token,
			'token_set_time'     => time(),
		);

		$this->set_credentials( $return_data, $is_member );

		do_action( 'easl_mz_member_token_refreshed', $return_data, $is_member );

		return true;
	}

	public function maybe_refresh_member_auth_token() {
		if ( $this->auth_refresh_called ) {
			return false;
		}
		if ( ( $this->token_set_time + $this->expires_in - $this->server_response_delay ) < time() ) {

			$this->refresh_auth_token( true );

			return true;
		}

		return false;
	}

	public function maybe_refresh_user_auth_token() {
		if ( $this->user_auth_refresh_called ) {
			return false;
		}
		if ( ( $this->user_token_set_time + $this->user_expires_in - $this->server_response_delay ) < time() ) {

			$this->refresh_auth_token( false );

			return true;
		}

		return false;
	}

	public function maybe_refresh_auth_token( $is_member = true ) {
		return $is_member ? $this->maybe_refresh_member_auth_token() : $this->maybe_refresh_user_auth_token();

	}

	public function reset_password( $email ) {
		$headers = array(
			'Content-Type'  => 'application/json',
			'Cache-Control' => 'no-cache'
		);
		$this->request->reset_headers();
		$this->request->set_request_header( 'Content-Type', 'application/json' );
		$this->request->set_request_header( 'Cache-Control', 'no-cache' );
		$this->request->get( '/portal/password/request', array( 'email' => $email ), array(), false );
		if ( $this->request->get_response_code() == 200 ) {
			return true;
		}

		return false;
	}

	public function get_member_id() {
		$headers = array(
			'Content-Type'  => 'application/json',
			'Cache-Control' => 'no-cache',
			'OAuth-Token'   => $this->get_access_token( true ),
		);
		$result  = $this->get( '/me', true, $headers );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->current_user->id ) ) {
			return false;
		}

		return $response->current_user->id;

	}

	public function change_password( $data, $is_member = true ) {
		$headers = array(
			'Content-Type' => 'application/json',
			'OAuth-Token'  => $this->get_access_token( $is_member ),
		);
		$result  = $this->put( '/me/password', $is_member, $headers, $data );

		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->valid ) ) {
			return false;
		}

		return true;
	}

	public function get_member_details( $member_id, $is_member = true ) {
		$headers = array(
			'Content-Type'  => 'application/json',
			'Cache-Control' => 'no-cache',
			'OAuth-Token'   => $this->get_access_token( $is_member ),
		);
		$result  = $this->get( '/Contacts/' . $member_id, $is_member, $headers );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->id ) ) {
			return false;
		}

		$data = easl_mz_parse_crm_contact_data( $response );

		return $data;
	}

	public function get_membership_details( $member_id ) {
		$headers = array(
			'Content-Type'  => 'application/json',
			'Cache-Control' => 'no-cache',
			'OAuth-Token'   => $this->get_access_token( false ),
		);
		$result  = $this->get( '/easl1_memberships/' . $member_id, false, $headers );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->id ) ) {
			return false;
		}

		$data = easl_mz_parse_crm_membership_data( $response );

		return $data;
	}

	public function get_member_profile_picture( $member_id, $is_member = true ) {
		$this->request->reset_headers();
		$this->request->set_request_header( 'OAuth-Token', $this->get_access_token( $is_member ) );
		$this->request->set_request_header( 'Cache-Control', 'no-cache' );
		$this->request->get( '/Contacts/' . $member_id . '/file/picture', array(), array(), false );

		if ( $this->request->get_response_code() != 200 ) {
			return false;
		}

		$img_base_64 = base64_encode( $this->request->get_response_body() );
		$img_src     = 'data: ' . $this->request->get_request_header( 'content-type' ) . ';base64,' . $img_base_64;

		return $img_src;

	}

	public function get_membership_note_raw( $note_id ) {
		$this->request->reset_headers();
		$this->request->set_request_header( 'OAuth-Token', $this->get_access_token( false ) );
		$this->request->set_request_header( 'Cache-Control', 'no-cache' );
		$this->request->get( '/Notes/' . $note_id . '/file/filename', array(), array(), false );

		if ( $this->request->get_response_code() != 200 ) {
			return false;
		}

		return array(
			'content_disposition' => $this->request->get_response_header( 'content-disposition' ),
			'content_length'      => $this->request->get_response_header( 'content-length' ),
			'content_type'        => $this->request->get_response_header( 'content-type' ),
			'data'                => $this->request->get_response_body()
		);
	}

	public function get_member_profile_picture_raw( $member_id, $is_member = true ) {
		$this->request->reset_headers();
		$this->request->set_request_header( 'OAuth-Token', $this->get_access_token( $is_member ) );
		$this->request->set_request_header( 'Cache-Control', 'no-cache' );
		$this->request->get( '/Contacts/' . $member_id . '/file/picture', array(), array(), false );

		if ( $this->request->get_response_code() != 200 ) {
			return false;
		}

		$image_type = $this->request->get_response_header( 'Content-Type' );

		if ( ! $image_type ) {
			$image_type = 'image/jpeg';
		}

		$image_data = array(
			'type' => $image_type,
			'data' => $this->request->get_response_body()
		);

		return $image_data;

	}

	public function update_member_picture( $member_id, $img_file, $is_member = true ) {
		$headers = array(
			'Content-Type' => 'multipart/form-data',
			'OAuth-Token'  => $this->get_access_token( $is_member ),
		);
		$result  = $this->put( '/Contacts/' . $member_id . '/file/picture', $is_member, $headers, $img_file, 'body', array(), array( 200 ), false );
		if ( ! $result ) {
			return false;
		}

		return true;
	}

	public function get_members( $filter_args = array() ) {
		$headers = array(
			'Content-Type'  => 'application/json',
			'Cache-Control' => 'no-cache',
			'OAuth-Token'   => $this->get_access_token( false ),
		);
		$result  = $this->get( '/Contacts/filter', false, $headers, $filter_args );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->records ) ) {
			return false;
		}
		$members = array();
		foreach ( $response->records as $record ) {
			$members[] = array(
				'id'                         => $record->id,
				'salutation'                 => $record->salutation,
				'first_name'                 => $record->first_name,
				'last_name'                  => $record->last_name,
				'description'                => $record->description,
				'picture'                    => $record->picture,
				'country'                    => $record->primary_address_country,
				'dotb_public_profile'        => $record->dotb_public_profile,
				'dotb_public_profile_fields' => $record->dotb_public_profile_fields,
				'title'                      => $record->title,
				'dotb_job_function'          => $record->dotb_job_function,
				'dotb_job_function_other'    => $record->dotb_job_function_other,
				'department'                 => $record->department,
			);
		}

		return $members;
	}

	public function count_members( $filter_args = array() ) {
		$headers = array(
			'Content-Type'  => 'application/json',
			'Cache-Control' => 'no-cache',
			'OAuth-Token'   => $this->get_access_token( false ),
		);
		$result  = $this->get( '/Contacts/filter/count', false, $headers, $filter_args );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->record_count ) ) {
			return false;
		}

		return $response->record_count;
	}

	public function get_featured_members() {
		$headers = array(
			'Content-Type'  => 'application/json',
			'Cache-Control' => 'no-cache',
			'OAuth-Token'   => $this->get_access_token( false ),
		);
		$data    = array(
			'max_num'  => 5,
			'order_by' => 'date_modified:ASC',
			'fields'   => 'first_name,last_name,salutation,description,picture',

		);
		$result  = $this->get( '/Contacts/filter', false, $headers, $data );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->records ) ) {
			return false;
		}
		$members = array();
		foreach ( $response->records as $record ) {
			$members[] = array(
				'id'          => $record->id,
				'salutation'  => $record->salutation,
				'first_name'  => $record->first_name,
				'last_name'   => $record->last_name,
				'description' => $record->description,
				'picture'     => $record->picture,
			);
		}

		return $members;
	}

	public function update_member_personal_info( $member_id, $data = array(), $is_member = true ) {
		$headers = array(
			'Content-Type' => 'application/json',
			'OAuth-Token'  => $this->get_access_token( $is_member ),
		);
		$result  = $this->put( '/Contacts/' . $member_id, $is_member, $headers, $data );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->id ) ) {
			return false;
		}

		$member_data = easl_mz_parse_crm_contact_data( $response );

		return $member_data;
	}

	public function create_member( $data = array() ) {
		$headers = array(
			'Content-Type' => 'application/json',
			'OAuth-Token'  => $this->get_access_token( false ),
		);
		$result  = $this->post( '/Contacts', false, $headers, $data );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->id ) ) {
			return false;
		}


		return $response->id;
	}

	public function create_membership( $data = array() ) {
		$headers = array(
			'Content-Type' => 'application/json',
			'OAuth-Token'  => $this->get_access_token( false ),
		);

		$result = $this->post( '/easl1_memberships', false, $headers, $data );

		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->id ) ) {
			return false;
		}

		return $response->id;
	}

	public function update_membership( $membership_id, $data = array() ) {
		$headers = array(
			'Content-Type' => 'application/json',
			'OAuth-Token'  => $this->get_access_token( false ),
		);
		$result  = $this->put( '/easl1_memberships/' . $membership_id, false, $headers, $data );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->id ) ) {
			return false;
		}

		return true;
	}

	public function add_membeship_to_member( $member_id, $membership_id ) {
		$headers = array(
			'Content-Type' => 'application/json',
			'OAuth-Token'  => $this->get_access_token( false ),
		);
		$result  = $this->post( "/Contacts/{$member_id}/link/contacts_easl1_memberships_1/{$membership_id}", false, $headers );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();

		return $response->record->dotb_mb_id;
	}

	public function get_members_membership( $member_id ) {
		$headers = array(
			'Content-Type' => 'application/json',
			'OAuth-Token'  => $this->get_access_token( false ),
		);
		$data    = array(
			'fields'   => 'id,category,start_date,end_date',
			'order_by' => 'start_date:DESC,date_entered:DESC'
		);
		$result  = $this->get( "/Contacts/{$member_id}/link/contacts_easl1_memberships_1", false, $headers, $data );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();

		if ( empty( $response->records ) || ( count( $response->records ) < 1 ) ) {
			return false;
		}
		$memberships = array();
		foreach ( $response->records as $record ) {
			$memberships[] = array(
				'id'         => $record->id,
				'category'   => $record->category,
				'start_date' => $record->start_date,
				'end_date'   => $record->end_date,
			);
		}

		return $memberships;
	}

	public function get_members_latest_membership( $member_id ) {
		$headers = array(
			'Content-Type' => 'application/json',
			'OAuth-Token'  => $this->get_access_token( false ),
		);
		$data    = array(
			'max_num'  => 1,
			'fields'   => 'id,status,billing_type,billing_status,start_date,end_date',
			'order_by' => 'start_date:DESC,date_entered:DESC'
		);
		$result  = $this->get( "/Contacts/{$member_id}/link/contacts_easl1_memberships_1", false, $headers, $data );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();

		if ( empty( $response->records[0] ) ) {
			return false;
		}

		$return_data = array(
			'id'             => '',
			'status'         => '',
			'billing_type'   => '',
			'billing_status' => '',
			'start_date'     => '',
			'end_date'       => '',
		);

		if ( isset( $response->records[0]->id ) ) {
			$return_data['id'] = $response->records[0]->id;
		}

		if ( isset( $response->records[0]->status ) ) {
			$return_data['status'] = $response->records[0]->status;
		}

		if ( isset( $response->records[0]->billing_type ) ) {
			$return_data['billing_type'] = $response->records[0]->billing_type;
		}

		if ( isset( $response->records[0]->billing_status ) ) {
			$return_data['billing_status'] = $response->records[0]->billing_status;
		}

		if ( isset( $response->records[0]->start_date ) ) {
			$return_data['start_date'] = $response->records[0]->start_date;
		}

		if ( isset( $response->records[0]->end_date ) ) {
			$return_data['end_date'] = $response->records[0]->end_date;
		}


		return $return_data;
	}

	public function get_membership_notes( $membership_id ) {
		$headers = array(
			'Content-Type' => 'application/json',
			'OAuth-Token'  => $this->get_access_token( false ),
		);
		$data    = array(
			'filter'   => array(
				array( 'portal_flag' => '1' )
			),
			'fields'   => 'id,name,file_mime_type,filename',
			'order_by' => 'date_entered:DESC'
		);
		$result  = $this->get( "/easl1_memberships/{$membership_id}/link/easl1_memberships_activities_1_notes", false, $headers, $data );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();

		if ( empty( $response->records ) || ( count( $response->records ) < 1 ) ) {
			return false;
		}
		$notes = array();
		foreach ( $response->records as $record ) {
			$notes[] = array(
				'id'             => $record->id,
				'name'           => $record->id,
				'file_mime_type' => $record->file_mime_type,
				'filename'       => $record->filename,
			);
		}

		return $notes;
	}

	public function delete_member_account( $member_id ) {
		$headers = array(
			'Content-Type' => 'application/json',
			'OAuth-Token'  => $this->get_access_token( false ),
		);
		$result  = $this->delete( '/Contacts/' . $member_id, false, $headers );
		if ( ! $result ) {
			return false;
		}
		$response = $this->request->get_response_body();
		if ( empty( $response->id ) ) {
			return false;
		}


		return $response->id;
	}

	public function post( $endpoint, $is_member = true, $headers = array(), $data = array(), $data_format = 'body', $cookies = array(), $codes = array( 200 ) ) {
		if ( $this->is_session_expired( $is_member ) ) {
			return false;
		}
		$this->maybe_refresh_auth_token( $is_member );
		$this->request->reset_headers();
		if ( is_array( $headers ) ) {
			foreach ( $headers as $key => $value ) {
				$this->request->set_request_header( $key, $value );
			}
		}
		$this->request->post( $endpoint, $data, $data_format, $cookies );
		if ( $this->request->is_valid_response_code( 401 ) && ! $this->is_auth_refresh_called() ) {
			if ( $this->refresh_auth_token( $is_member ) ) {
				return $this->post( $endpoint, $is_member, $headers, $data, $data_format, $cookies, $codes );
			}

			return false;
		}


		if ( ! $this->request->is_valid_response_code( $codes ) ) {
			return false;
		}

		if ( ! $this->request->get_response_body() ) {
			return false;
		}

		return true;
	}

	public function put( $endpoint, $is_member = true, $headers = array(), $data = array(), $data_format = 'body', $cookies = array(), $codes = array( 200 ), $body_json_encode = true ) {
		if ( $this->is_session_expired( $is_member ) ) {
			return false;
		}
		$this->maybe_refresh_auth_token( $is_member );
		$this->request->reset_headers();
		if ( is_array( $headers ) ) {
			foreach ( $headers as $key => $value ) {
				$this->request->set_request_header( $key, $value );
			}
		}
		$this->request->put( $endpoint, $data, $data_format, $cookies, true, $body_json_encode );

		if ( $this->request->is_valid_response_code( 401 ) && ! $this->is_auth_refresh_called() ) {
			if ( $this->refresh_auth_token( $is_member ) ) {
				return $this->put( $endpoint, $is_member, $headers, $data, $data_format, $cookies, $codes, $body_json_encode );
			}

			return false;
		}


		if ( ! $this->request->is_valid_response_code( $codes ) ) {
			return false;
		}

		if ( ! $this->request->get_response_body() ) {
			return false;
		}

		return true;
	}

	public function delete( $endpoint, $is_member = true, $headers = array(), $data = array(), $data_format = 'body', $cookies = array(), $codes = array( 200 ) ) {
		if ( $this->is_session_expired( $is_member ) ) {
			return false;
		}
		$this->maybe_refresh_auth_token( $is_member );
		$this->request->reset_headers();
		if ( is_array( $headers ) ) {
			foreach ( $headers as $key => $value ) {
				$this->request->set_request_header( $key, $value );
			}
		}
		$this->request->delete( $endpoint, $data, $data_format, $cookies );

		if ( $this->request->is_valid_response_code( 401 ) && ! $this->is_auth_refresh_called() ) {
			if ( $this->refresh_auth_token( $is_member ) ) {
				return $this->delete( $endpoint, $is_member, $headers, $data, $data_format, $cookies, $codes );
			}

			return false;
		}


		if ( ! $this->request->is_valid_response_code( $codes ) ) {
			return false;
		}

		if ( ! $this->request->get_response_body() ) {
			return false;
		}

		return true;
	}

	public function get( $endpoint, $is_member = true, $headers = array(), $data = array(), $cookies = array(), $codes = array( 200 ) ) {
		if ( $this->is_session_expired( $is_member ) ) {
			return false;
		}
		$this->maybe_refresh_auth_token( $is_member );
		$this->request->reset_headers();
		if ( is_array( $headers ) ) {
			foreach ( $headers as $key => $value ) {
				$this->request->set_request_header( $key, $value );
			}
		}
		$this->request->get( $endpoint, $data, $cookies );
		if ( $this->request->is_valid_response_code( 401 ) && ! $this->is_auth_refresh_called() ) {
			if ( $this->refresh_auth_token( $is_member ) ) {
				return $this->get( $endpoint, $is_member, $headers, $data, $cookies, $codes );
			}

			return false;
		}

		if ( ! $this->request->is_valid_response_code( $codes ) ) {
			return false;
		}

		if ( ! $this->request->get_response_body() ) {
			return false;
		}

		return true;
	}

}