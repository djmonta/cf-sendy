<?php
/**
 * Processor config UI for Sendy for Caldera Forms
 *
 * @package   cf_sendy
 * @author    Sachiko Miyamoto (email : monta@inforichjapan.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 Sachiko Miyamoto for INFORICH Inc.
 */
$client = cf_sendy_client_setup();
$availability = $client->can_send();
?>
<?php if( false == $availability ) : ?>
<div id="cf-sendy-setup">
	<h3>
		<?php esc_html_e( 'Settings are incorrect.', 'cf-sendy' ); ?>
	</h3>

	<div class="caldera-config-group">
		<label for="cf-sendy-settings" id="cf-sendy-settings-label">
			<?php esc_html_e('Sendy Settings', 'cf-sendy'); ?>
		</label>
		<a href="<?php echo esc_url( cf_sendy_get_settings_url() ); ?>" id="cf-sendy-settings" class="button" aria-describedby="cf-sendy-settings-desc" title="<?php esc_attr_e( 'Click to set API Key and Install URL.', 'cf-sendy');?>">
			<?php esc_html_e('Sendy Settings', 'cf-sendy' ); ?>
		</a>
		<p class="description" id="cf-sendy-settings-desc">
			<?php esc_html_e( 'Please set API Key and Install URL from Sendy Settings.', 'cf-sendy' ); ?>
		</p>
	</div>
</div>
<?php endif; ?>

<div id="cf-sendy-settings">

	<div class="caldera-config-group">
		<label for="list_id">
			<?php _e( 'Sendy List ID', 'cf-sendy' ); ?>
		</label>
		<div class="caldera-config-field">
			<input type="text" class="block-input field-config required" id="list_id" name="{{_name}}[list_id]" value="{{list_id}}">
		</div>
			<p class="description" id="list_id-desc">
				<?php _e( 'List ID to add a subscribe to.', 'cf-sendy' ); ?>
			</p>
	</div>

	<div class="caldera-config-group">
		<label for="email_address">
			<?php _e( 'Email Address', 'cf-sendy' ); ?>
		</label>
		<div class="caldera-config-field">
			<input type="text" class="block-input field-config required magic-tag-enabled" id="email" name="{{_name}}[email]" value="{{email}}" required="true">
		</div>
	</div>

	<div class="caldera-config-group">
		<label for="name">
			<?php _e( 'Name', 'cf-sendy' ); ?>
		</label>
		<div class="caldera-config-field">
			<input type="text" class="block-input field-config magic-tag-enabled" id="name" name="{{_name}}[name]" value="{{name}}" >
		</div>
	</div>

	<div class="caldera-config-group">
		<label for="company_name">
			<?php _e( 'Company Name', 'cf-sendy' ); ?>
		</label>
		<div class="caldera-config-field">
			<input type="text"  class="block-input field-config magic-tag-enabled" id="company_name" name="{{_name}}[company_name]" value="{{company_name}}">
		</div>
	</div>

	<div class="caldera-config-group">
		<label for="success_message">
			<?php _e( 'Success Message', 'cf-sendy' ); ?>
		</label>
		<div class="caldera-config-field">
			<input type="text"  class="block-input field-config magic-tag-enabled" id="success_message" name="{{_name}}[success_message]" value="{{success_message}}">
		</div>
		<p class="description">
			<?php _e( "Message to show on successful subscription. If empty, a generic message will be shown.", 'cf-sendy' ); ?>
		</p>
	</div>

</div>

