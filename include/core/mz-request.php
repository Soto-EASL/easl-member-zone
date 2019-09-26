<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_MZ_Request {
	protected $base_uri;
	protected $response_code;
	protected $response_body;

	protected $request_headers;
	protected $response_headers;

	public function __construct( $base_uri ) {
		$this->base_uri = $base_uri;
	}

	public function reset_headers() {
		$this->request_headers  = array();
		$this->response_headers = array();
	}

	public function set_request_header( $key, $value ) {
		$this->request_headers[ $key ] = $value;
	}

	public function response_headers( $key, $value ) {
		$this->request_headers[ $key ] = $value;
	}

	public function reset_response() {
		$this->response_code    = false;
		$this->response_headers = array();
		$this->response_body    = array();
	}

	public function get_response_code() {
		return $this->response_code;
	}

	public function get_response_headers() {
		return $this->response_headers;
	}

	public function get_request_header( $key ) {
		isset( $this->response_headers[ $key ] ) ? $this->response_headers[ $key ] : '';
	}

	public function get_response_body() {
		return $this->response_body;
	}

	public function is_valid_response_code( $codes = array() ) {
		if ( is_int( $codes ) ) {
			$codes = array( $codes );
		}
		if ( in_array( $this->response_code, $codes ) ) {
			return true;
		}

		return false;
	}

	public function post( $endpoint, $data = array(), $data_format = 'body', $cookies = array(), $parse_json = true ) {
		$url  = $this->base_uri . $endpoint;
		$args = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'body'        => json_encode( $data ),
			'data_format' => $data_format,
			'headers'     => $this->request_headers,
			'cookies'     => $cookies
		);
		$this->reset_response();
		$response               = wp_remote_post( $url, $args );
		$this->response_code    = wp_remote_retrieve_response_code( $response );
		$this->response_headers = wp_remote_retrieve_headers( $response );

		$body = wp_remote_retrieve_body( $response );
		if ( $body ) {
			$this->response_body = $parse_json ? json_decode( $body ) : $body;
		}
	}

	public function put( $endpoint, $data = array(), $data_format = 'body', $cookies = array(), $parse_json = true, $body_json_encode = true ) {
		$url  = $this->base_uri . $endpoint;
		$args = array(
			'method'      => 'PUT',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'body'        => $body_json_encode ? json_encode( $data ) : $data,
			'data_format' => $data_format,
			'headers'     => $this->request_headers,
			'cookies'     => $cookies
		);
		$this->reset_response();
		$response            = wp_remote_request( $url, $args );
		$this->response_code = wp_remote_retrieve_response_code( $response );
		$this->response_headers = wp_remote_retrieve_headers( $response );

		$body = wp_remote_retrieve_body( $response );

		if ( $body ) {
			$this->response_body = $parse_json ? json_decode( $body ) : $body;
		}
	}

	public function delete( $endpoint, $data = array(), $data_format = 'body', $cookies = array(), $parse_json = true ) {
		$url  = $this->base_uri . $endpoint;
		$args = array(
			'method'      => 'DELETE',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'body'        => json_encode( $data ),
			'data_format' => $data_format,
			'headers'     => $this->request_headers,
			'cookies'     => $cookies
		);
		$this->reset_response();
		$response            = wp_remote_request( $url, $args );
		$this->response_code = wp_remote_retrieve_response_code( $response );
		$this->response_headers = wp_remote_retrieve_headers( $response );

		$body = wp_remote_retrieve_body( $response );

		if ( $body ) {
			$this->response_body = $parse_json ? json_decode( $body ) : $body;
		}
	}

	public function get( $endpoint, $data = array(), $cookies = array(), $parse_json = true ) {
		$url  = $this->base_uri . $endpoint;
		$url  = add_query_arg( $data, $url );
		$args = array(
			'timeout'     => 10,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'headers'     => $this->request_headers,
			'cookies'     => $cookies,
		);
		$this->reset_response();

		$response = wp_remote_get( $url, $args );

		$this->response_code = wp_remote_retrieve_response_code( $response );
		$this->response_headers = wp_remote_retrieve_headers( $response );

		$body = wp_remote_retrieve_body( $response );

		if ( $body ) {
			$this->response_body = $parse_json ? json_decode( $body ) : $body;
		}
	}

	public function raw_request($endpoint, $args){
		$url  = $this->base_uri . $endpoint;
		return wp_remote_request($url, $args);
	}
}