<?php
/**
 * Plugin Name: Sendy for Caldera Forms
 * Plugin URI:  https://calderawp.com
 * Description: Sendy newsletter integration for Caldera Forms
 * Version: 0.1.0
 * Author:      Sachiko Miyamoto for INFORICH Inc.
 * Author URI:  https://CalderaWP.com
 * License:     GPLv2+
 * Text Domain: cf-sendy
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2016 Sachiko Miyamoto (email : monta@inforichjapan.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define constants
 */
define( 'CF_SENDY_VER', '0.1.0' );
define( 'CF_SENDY_URL',     plugin_dir_url( __FILE__ ) );
define( 'CF_SENDY_PATH',    plugin_dir_path( __FILE__ ) );

/**
 * Make plugin go
 *
 * @since 0.1.0
 */
add_action( 'init', 'cf_sendy_init', 5 );

// add filter to register addon with Caldera Forms
add_filter('caldera_forms_get_form_processors', 'cf_sendy_register');

//show success notices
add_filter( 'caldera_forms_render_notices', 'cf_sendy_maybe_notices' );

// pull in the functions file
include CF_SENDY_PATH . 'includes/functions.php';


/**
 * Initialize plugin
 *
 * @uses "init"
 *
 * @since 0.1.0
 */
function cf_sendy_init() {
	if( class_exists( 'Caldera_Forms_Autoloader' ) ){
			//register autoloader
			Caldera_Forms_Autoloader::add_root( 'CF_Sendy', CF_SENDY_PATH . 'classes' );

			if( current_user_can( Caldera_Forms::get_manage_cap() ) ){
				add_action( 'wp_ajax_cf_sendy_admin_save', 'cf_sendy_save_key_ajax_cb' );
			}

			//load admin
			if( is_admin() ){
				$page = new CF_Sendy_Page( CF_SENDY_PATH . 'views',  CF_SENDY_URL );
				$menu = new CF_Sendy_Menu( $page );
				$menu->init();
			}

	}

}
