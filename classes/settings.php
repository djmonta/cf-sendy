<?php

/**
 * Class CF_Sendy_Settings
 *
 * Base container for settings.
 *
 * This should really be in CF core
 *
 * @package CF-Sendy
 * @author    Sachiko Miyamoto (email : monta@inforichjapan.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 Sachiko Miyamoto for INFORICH Inc.
 */
abstract class CF_Sendy_Settings {

	/**
	 * Name of option key to save in
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected static $key_option;

	/**
	 * Get saved value
	 *
	 * @since 0.1.0
	 *
	 * @return mixed|void
	 */
	public static function get(){
		return get_option( static::$key_option, '' );
	}

	/**
	 * Save new value
	 *
	 * @since 0.1.0
	 *
	 * @param string $value
	 *
	 * @return bool
	 */
	public static function save( $value ){

		return update_option( static::$key_option, $value );

	}

}