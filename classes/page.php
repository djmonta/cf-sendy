<?php

/**
 * Class CF_Sendy_Page
 *
 * Create admin page
 * @package CF-Sendy
 * @author    Sachiko Miyamoto (email : monta@inforichjapan.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 Sachiko Miyamoto for INFORICH Inc.
 */
class CF_Sendy_Page {


	/**
	 * Root for view directory
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $view_dir;

	/**
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * CF_Sendy_Page constructor.
	 *
	 * @param string $view_dir Directory path for views
	 * @param string $url URL for assets
	 */
	public function __construct( $view_dir, $url  ) {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		$this->view_dir = $view_dir;
		$this->url = $url;
	}

	/**
	 * Create admin page view
	 *
	 * @since 0.1.0
	 */
	public function display() {
		add_submenu_page(
			'caldera-forms',
			__( 'Sendy Settings', 'cf-sendy'),
			__( 'Sendy Settings', 'cf-sendy'),
			'manage_options',
			'cf-sendy',
			[ $this, 'render' ]
		);
	}

	/**
	 * Redner admin page view
	 *
	 * @since 0.1.0
	 */
	public function render() {
		ob_start();
		include  $this->view_dir . '/admin.php';
		echo ob_get_clean();

	}

	/**
	 * Register scripts
	 *
	 * @uses "admin_enqueue_scripts"
	 *
	 * @param string $hook Current hook
	 */
	public function register_scripts( $hook ){
		if( 'caldera-forms_page_cf-sendy' == $hook ){
			wp_enqueue_style( 'caldera-forms-admin-styles', CFCORE_URL . 'assets/css/admin.css', array(), CFCORE_VER );
			wp_enqueue_script( 'cf-sendy', $this->url . '/admin.js', array( 'jquery' ) );
		}

	}

}
