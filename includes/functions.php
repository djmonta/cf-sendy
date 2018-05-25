<?php
/**
 * Functions for Sendy for Caldera Forms
 *
 * @package   cf-sendy
 * @author    Sachiko Miyamoto (email : monta@inforichjapan.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 Sachiko Miyamoto for INFORICH Inc.
 */

/**
 * Load processor
 *
 * @since 0.1.0
 *
 * @uses "caldera_forms_pre_load_processors" action
 *//* not for use
function cf_sendy_load() {
	if( ! class_exists( 'Caldera_Forms_Processor_Newsletter' ) ){
		return;
	}
	
	cf_sendy_register_autload();
	new CF_Sendy_Processor( cf_sendy_register(), cf_sendy_fields(), 'cf-sendy' );
	
}*/

/**
 * Registers the Sendy Processor
 *
 * @since 0.1.0
 * @param array		$processors		Array of current registered processors
 *
 * @return array	array of regestered processors
 */
function cf_sendy_register($processors){

	$processors['sendy'] = array(
		"name"				=>	__('Sendy', 'cf-sendy'),
		"description"		=>	__( 'Add Sendy optins to your form.', 'cf-sendy'),
		"icon"				=>	CF_SENDY_URL . "icon.png",
		"author"			=>	"Sachiko Miyamoto",
		"author_url"		=>	"https://CalderaWP.com",
		"pre_processor"		=>	'cf_sendy_process',
		"template"			=>	CF_SENDY_PATH . "includes/config.php",

	);

	return $processors;

}


/**
 * Validate the process if possible, and if not return errors.
 *
 * @since 0.1.0
 *
 * @param array $config Processor config
 * @param array $form Form config
 * @param string $proccesid Unique ID for this instance of the processor
 *
 * @return array Return if errors, do not return if not
 */
function cf_sendy_process( array $config, array $form, $transdata ){
	$client = cf_sendy_client_setup();
	$client_set = $client->can_send();
	if( ! $client_set ){
		return array(
			'type' => 'error',
			'note' => esc_html__( 'Sendy is not authorized', 'cf-sendy' )
		);

	}

	$_message = Caldera_Forms::do_magic_tags( $config[ 'success_message' ] );
	if ( ! empty( $_message ) ) {
		$message = $_message;
	} else {
		$message = __( 'Thank you. Message was sent.', 'cf-sendy' );
	}

	$subscriber_data = array(
		'email' => Caldera_Forms::do_magic_tags( $config[ 'email'] ),
		'name' => Caldera_Forms::do_magic_tags( $config[ 'name'] ),
		'company_name' => Caldera_Forms::do_magic_tags( $config[ 'company_name'] ),
	);
	if( ! isset( $subscriber_data[ 'email' ] ) || ! is_email( $subscriber_data[ 'email' ] ) ) {
		return array(
			'type' =>'error',
			'note' => __( "Invalid email address.", 'cf-sendy' )
		);
	} else {
		$api_response = $client->addSubscriber( $subscriber_data, $config['list_id'] );
		if ( is_string( $api_response ) && $api_response == 'true' ) {
			$subscribed = true;
			Caldera_Forms::set_submission_meta( 'sendy', $subscribed, $form, $transdata );
			$cf_sendy_notice = array(
				'type'=>'success',
				'note' => $message
			);
		} else {
			$cf_sendy_notice = array(
				'type'=>'success',
				'note' => $message
			);
		}
	}

}

function cf_sendy_client_setup() {
	$api_key = get_option( '_cf_sendy_api_key', '' );
	$install_url = get_option( '_cf_sendy_install_url', '' );
	$client = new CF_Sendy_Client( $api_key, $install_url );
	return $client;
}

function subscribe( $client, $subscriber_data, $list_id ) {
	$response = $client->addSubscriber( $subscriber_data, $list_id);
	return $response;
}

/**
 * Get the URL for login and get auth code
 *
 * @since 0.1.0
 *
 * @return string
 */
function cf_sendy_get_settings_url() {
	return admin_url( 'admin.php?page=cf-sendy-settings' );
}

/**
 * Save settings via AJAX
 *
 * @uses "wp_ajax_cf_sendy_admin_save" action
 *
 * @since 0.1.0
 */
function cf_sendy_save_key_ajax_cb(){

	if( isset( $_POST[ 'cf-sendy-nonce' ] ) ){
		if( wp_verify_nonce( $_POST[ 'cf-sendy-nonce' ], 'cf_sendy_admin_save' ) ){
			if ( isset( $_POST[ 'cf-sendy-api-key' ] ) ) {
				$saved_api_key = CF_Sendy_API_Key::save( $_POST[ 'cf-sendy-api-key' ] );
			} else {
				CF_Sendy_API_Key::save( '' );
			}
			if( isset( $_POST[ 'cf-sendy-install-url' ] ) ){
				$saved_install_url = CF_Sendy_Install_URL::save( $_POST[ 'cf-sendy-install-url' ] );
			} else {
				CF_Sendy_Install_URL::save( '' );
			}

			status_header( 200 );
			wp_send_json_success();


		}else{
			status_header( 403 );
			wp_send_json_error();
		}
	}

	status_header( 400 );
	wp_send_json_error();

}

/**
 * Add our success notices if needed.
 *
 * @since 1.0.1
 *
 * @uses "caldera_forms_render_notices"
 *
 * @param $notices
 *
 * @return array
 */
function cf_sendy_maybe_notices( $notices ) {
	global $cf_sendy_notice;
	if ( is_array( $cf_sendy_notice ) && ! empty( $cf_sendy_notice ) ) {
		$notices[ $cf_sendy_notice[ 'type' ] ][ 'note' ] =  $cf_sendy_notice[ 'note' ];
	}

	return $notices;

}

/**
 * Sendy for Caldera Forms config
 *
 * @since 0.1.0
 *
 * @return array	Processor configuration
 *//* not for use
function cf_sendy_config(){

	return array(
		"name"				=>	__( 'Sendy for Caldera Forms', 'cf-sendy'),
		"description"		=>	__( 'Sendy for Caldera Forms', 'cf-sendy'),
		"icon"				=>	CF_SENDY_URL . "icon.png",
		"author"			=>	'Sachiko Miyamoto',
		"author_url"		=>	'https://CalderaWP.com',
		"template"			=>	CF_SENDY_PATH . "includes/config.php",

	);
}*/

/**
 * Config for lists field
 *
 * @since 0.1.0
 *
 * @return array
 *//* not for use
function cf_sendy_lists_field_config(){
	return array(
		'id'       => 'cf-sendy-list_id',
		'label'    => __( 'List ID', 'cf-sendy' ),
		'desc'     => __( 'List ID to add subscriber to.', 'cf-sendy' ),
		'type'     => 'text',
		'required' => true,
		'extra_classes' => 'block-input',
		'magic' => false
	);
}*/

/**
 * Get UI fileds config
 *
 * @since 0.1.0
 *
 * @return array
 *//* not for use
function cf_sendy_fields(){

	$fields = array(
		cf_sendy_lists_field_config(),
		array(
			'id'       => 'cf-sendy-list_id-hidden',
			'type'     => 'hidden',
			'required' => true,
			'magic' => false,
			'label' => __( 'List', 'cf-sendy' )
		),
		array(
			'id'       => 'cf-sendy-email',
			'label'    => __( 'Email Address', 'cf-sendy' ),
			'desc'     => __( 'Subscriber email address.', 'cf-sendy' ),
			'type'     => 'advanced',
			'allow_types' => array( 'email' ),
			'required' => true,
			'magic' => false
		),
		array(
			'id'            => 'cf-sendy-name',
			'label'         => __( 'Name', 'cf-sendy' ),
			'type'          => 'text',
			'desc'          => __( 'Subscriber name.', 'cf-sendy' ),
			'required'      => true,
			'allowed_types' => 'name',
		),
		array(
			'id'    => 'cf-sendy-tags',
			'label' => __( 'Tags', 'cf-sendy' ),
			'desc'  => __( 'Comma separated list of tags.', 'cf-sendy' ),
			'type'  => 'text',
			'required' => false,
		),
		array(
			'id'    => 'cf-sendy-misc_notes',
			'label' => __( 'Miscellaneous notes', 'cf-sendy' ),
			'type'  => 'text',
			'required' => false,
		),
		array(
			'id'   => 'cf-sendy-add_tracking',
			'label' => __( 'Add Tracking', 'cf-sendy' ),
			'type'  => 'text',
			'desc' => sprintf( '<a href="%s" target="_blank" title="%s">%s</a> %s.',
				'description-url',
				esc_html__( 'Sendy ad tracking documentation', 'cf-sendy' ),
				esc_html__( 'Value for ad tracking field in Sendy.', 'cf-sendy' ),
				esc_html__( 'To pass UTM tags use {get:*} magic tags, such as {get:utm_campaign}', 'cf-sendy' )
			),
			'required' => false,
			'desc_escaped' => true
		)
	);
*/
	/**
	 * Filter admin UI field configs
	 *
	 * @since 0.1.0
	 *
	 * @param array $fields The fields
	 *//* not for use
	return apply_filters( 'cf_sendy_fields', $fields );*/
/*}*/

/**
 * Initializes the licensing system
 *
 * @uses "admin_init" action
 *
 * @since 0.1.0
 *//* not for use
function cf_sendy_init_license(){
	if ( ! function_exists( 'caldera_warnings_dismissible_notice' ) ) {
		include_once CF_SENDY_PATH . 'vendor/autoload.php';
	}
	$plugin = array(
		'name'		=>	'Sendy for Caldera Forms',
		'slug'		=>	'sendy-for-caldera-forms',
		'url'		=>	'https://calderawp.com/',
		'version'	=>	CF_SENDY_VER,
		'key_store'	=>  'CF_SENDY_license',
		'file'		=>  CF_SENDY_CORE,
	);

	new \calderawp\licensing_helper\licensing( $plugin );

}*/

/**
 * Add our example form
 *
 * @uses "caldera_forms_get_form_templates"
 *
 * @since 0.1.0
 *
 * @param array $forms Example forms.
 *
 * @return array
 *//* not for use
function cf_sendy_example_form( $forms ) {
	$forms['cf_sendy']	= array(
		'name'	=>	__( 'Contact form with Sendy signup.', 'cf-sendy' ),
		'template'	=>	include CF_SENDY_PATH . 'includes/templates/example.php'
	);

	return $forms;

}*/

