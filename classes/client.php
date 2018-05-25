<?php
/**
 * Class CF_Sendy_Client
 *
 * @package   CF-Sendy
 * @author    Sachiko Miyamoto (email : monta@inforichjapan.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 Sachiko Miyamoto for INFORICH Inc.
 */
class CF_Sendy_Client {

	/**
	 * Sendy API Key
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $api_key;

	/**
	 * Sendy Install URL
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $install_url;

	/**
	 * CF_Sendy_Client constructor.
	 *
	 * @param int|string $entry_id Entry ID
	 * @param string $form_id Form ID
	 */
	public function __construct( $api_key, $install_url ) {
		$this->api_key = $api_key;
		$this->install_url = $install_url;
	}

	/**
	 * Check if sending should be possible
	 *
	 * @since 0.1.0
	 *
	 * @return bool
	 */
	public function can_send(){
		if( empty( $this->api_key ) || empty( $this->install_url ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Send data to remote API
	 *
	 * @since 0.1.0
	 * 
	 * @param string $uri Request URI
	 * @param array $args Request Args
	 * 
	 * @return bool
	 */
	public function send( $uri, $args ){
		/**
		 * Filter the URL we are sending request to communicate API
		 *
		 * @since 0.1.0
		 *
		 * @param string $url The API URL
		 *//* not for use*/
		$install_url = apply_filters( '_cf_sendy_install_url', get_option( '_cf_sendy_install_url', '') );
		$url = trailingslashit( $install_url ) . $uri;
		$request_query = $this->buildData($args);

		$r = wp_remote_post( $url, array(
			'body' => $request_query
		) );

		if( 200 == wp_remote_retrieve_response_code( $r ) || 201 == wp_remote_retrieve_response_code( $r ) ){
			$body = wp_remote_retrieve_body( $r );
			return $body;
		} else {
			wp_die( wp_remote_retrieve_body( $r  ) );
			return false;
		}

	}

	/**
	 * buildData
	 *
	 * Creates a string of data for either post or get requests.
	 * @param mixed $data       Array of key value pairs
	 * @access public
	 * @return void
	 */
	protected function buildData($data) {
		ksort($data);
		$params = array();
		foreach ($data as $key => $value) {
			$params[] = $key.'='.$this->encode($value);
		}
		return implode('&', $params);
	}

	/**
	 * encode
	 *
	 * Short-cut for utf8_encode / rawurlencode
	 * @param mixed $data   Data to encode
	 * @access protected
	 * @return void         Encoded data
	 */
	protected function encode($data) {
		return rawurlencode($data);
	}

	/**
	 * Create request arguments for subscribe to List API call
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function addSubscriber( array $subscriber_data, $list_id ) {

		$uri = 'subscribe';

		$request_args =  array(
			'email' => $subscriber_data['email'],
			'name' => $subscriber_data['name'],
			'CompanyName' => $subscriber_data['company_name'],
			'list' => $list_id,
			'boolean' => true
		);

		$response = $this->send( $uri, $request_args );

		// TODO: Handle API error?
		/* API Responses
			Success: true
			Error: Some fields are missing.
			Error: Invalid email address.
			Error: Invalid list ID.
			Error: Already subscribed.
		*/
		return $response;

	}

	/**
	 * Create request arguments for subscriber count to API call
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	public function subscriberCount( $list_id ) {

		$uri = 'api/subscribers/active-subscriber-count.php';

		$request_args =  array(
			'api_key' => $this->api_key,
			'list_id' => $list_id
		);

		return $this->send( $uri, $request_args );

	}

}
