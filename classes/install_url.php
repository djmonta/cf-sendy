<?php

/**
 * Class CF_Sendy_Install_URL
 *
 * DB abstraction for Install URL settings
 */
class CF_Sendy_Install_URL extends CF_Sendy_Settings{

	/**
	 * @inheritDoc
	 */
	protected static $key_option = '_cf_sendy_install_url';

	/**
	 * @inheritDoc
	 */
	public static function save( $value ){
		$value = strip_tags( $value );
		$stored  = update_option( self::$key_option, $value );
		return $stored;

	}
}