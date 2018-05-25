<?php

/**
 * Class CF_Sendy_Menu
 *
 * Create admin menu
 *
 * @package CF-Sendy
 * @author    Sachiko Miyamoto (email : monta@inforichjapan.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 Sachiko Miyamoto for INFORICH Inc.
 */
class CF_Sendy_Menu {

	/**
	 * Page instance
	 *
	 * @since 0.1.0
	 *
	 * @var CF_Sendy_Page
	 */
	private $page;

	/**
	 * CF_Sendy_Menu constructor.
	 *
	 * @param CF_Sendy_Page $page
	 */
	public function __construct( CF_Sendy_Page $page ) {
		$this->page = $page;

	}

	/**
	 * Hook to admin_menu to create view
	 *
	 * @since 0.1.0
	 */
	public function init(){
		add_action( 'admin_menu', array( $this->page, 'display' ) );

	}


}

