<?php
/**
 * Caldera Forms Sendy Admin view
 *
 * @package CF-Sendy
 * @author    Sachiko Miyamoto (email : monta@inforichjapan.com)
 * @license   GPL-2.0+
 * @link
 * @copyright 2017 Sachiko Miyamoto for INFORICH Inc.
 */
?>
<style>
	.caldera-editor-header {
		margin-top: 1.5em;
	}
	.caldera-editor-logo {
		display: inline-block;
	}
	h1 {
		display: inline-block;
		margin: 0 0 0 10px;
		vertical-align: -2px;
	}
	label {
		display: inline-block;
		width: 150px;
	}
	.block-input {
		width: 350px;
	}
	.card {
    margin-top: 40px;
    padding: 0 2em 1em;
	}
	.caldera-config-group {
		margin: 2em auto 1em;
	}
	p.cf-sendy-notice {
		display: inline-block;
		padding: 4px;
		border-radius: 4px;
	}
	p.cf-sendy-success {
		background: #a3bf61;
		color: #fff;
	}
	p.cf-sendy-error {
		background: #ff0000;
		color: #fff;
	}
	.cf-sendy-notice-wrap{
	}
</style>
<div class="caldera-editor-header">
	<div class="caldera-editor-logo"><span class="dashicons-cf-logo"></span></div>
	<h1><?php esc_html_e( 'Caldera Forms: Sendy', 'cf-sendy' ); ?></h1>
	</ul>
</div>
<div class="cf-sendy-admin-page-wrap card">

	<form id="cf-sendy-admin-form">
		<input name="action" value="cf_sendy_admin_save" type="hidden" />
		<?php wp_nonce_field( 'cf_sendy_admin_save', 'cf-sendy-nonce', false ); ?>
		<div class="caldera-config-group">
			<label for="cf-sendy-api-key">
				<?php esc_html_e( 'API Key', 'cf-sendy' ); ?>
			</label>
			<input id="cf-sendy-api-key" value="<?php echo esc_attr( CF_Sendy_API_Key::get() ); ?>" class="block-input field-config required" name="cf-sendy-api-key" />
		</div>
		<div class="caldera-config-group">
			<label>
				<?php echo esc_html( __( 'Sendy Install URL', 'cf-sendy' ) ); ?>
			</label>
			<input id="cf-sendy-install-url" value="<?php echo esc_attr( CF_Sendy_Install_Url::get() );?>" class="block-input field-config required" name="cf-sendy-install-url" placeholder="http://" />
		</div>
		<div class="caldera-config-group">
			<?php submit_button( __( 'Save', 'cf-sendy' ) ); ?>
			<p class="spinner" id="cf-sendy-spinner" aria-hidden="true"></p>
		</div>
		<div class="cf-sendy-notice-wrap">
			<p id="cf-sendy-not-saved" class="error alert cf-sendy-notice cf-sendy-error" style="display: none;visibility: hidden" aria-hidden="true">
				<?php esc_html_e( 'Settings could not be saved. Please refresh the page and try again', 'cf-sendy' ); ?>
			</p>
			<p id="cf-sendy-saved" class="error alert cf-sendy-success cf-sendy-notice" style="display: none;visibility: hidden" aria-hidden="true">
				<?php esc_html_e( 'Settings Saved', 'cf-sendy' ); ?>
			</p>
		</ul>

	</form>
	<div class="caldera-config-group">
		<p id="cf-sendy-link">
			<?php printf( '<a href="%s" title="%s" target="_blank">%s</a>', 'hhttps://sendy.co/api', 'Sendy API Documents', 'Click here to read the API documents.' ); ?>
		</p>
	</div>
</div>

<script>
	
jQuery( document ).ready( function ($) {
	$( '#cf-sendy-admin-form' ).on( 'submit', function(e){
		e.preventDefault();

		$spinner = $( '#cf-sendy-spinner' );
		$spinner.show().attr( 'aria-hidden', false ).css( 'visibility', 'visible' );
		$( '.cf-sendy-notice' ).hide().attr( 'aria-hidden', true ).css( 'visibility', 'none' );
	   

		data = $(this).serialize();
		console.log( data );
		$.post({
			url: ajaxurl,
			data:data,
			success: function (r) {
				if( 0 == r ){
					fail($);
				}else{
					$spinner.hide().attr( 'aria-hidden', true ).css( 'visibility', 'none' );
					$( '#cf-sendy-saved' ).show().attr( 'aria-hidden', false ).css( 'visibility', 'visible' );
				}

			},
			error: function(){
				fail($);
			}
		});

		function fail($) {
			$('#cf-sendy-not-saved').show().attr('aria-hidden', false).css('visibility', 'visible');
			$spinner.hide().attr('aria-hidden', true).css('visibility', 'none');
		}
	});
});

</script>