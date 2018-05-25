<?php

/**
 * Class CF_Sendy_API_Key
 *
 * DB abstraction for API key settings
 */
class CF_Sendy_API_Key extends CF_Sendy_Settings{

	/**
	 * @inheritDoc
	 */
	protected static $key_option = '_cf_sendy_api_key';

	/**
	 * @inheritDoc
	 */
	public static function save( $value ){
		$value = strip_tags( $value );
		$stored  = update_option( self::$key_option, $value );
		return $stored;

	}
}