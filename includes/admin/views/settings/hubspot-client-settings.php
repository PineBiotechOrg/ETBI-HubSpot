<?php 
	
	$client_id = get_option('etbi_hubspot_options_client_id');
	$client_secret = get_option('etbi_hubspot_options_client_secret');
	$redirect_uri = menu_page_url( 'etbi_hubspot_client' );

	$hubspot_client = ETBI_Hubspot_Client::instance();

	$auth_url = $hubspot_client->get_auth_url( $redirect_uri );

	$auth_code = get_option( 'etbi_hubspot_options_authorization_code', '' );

	$access_token = get_option( 'etbi_hubspot_options_access_token', '' );
	$refresh_token = get_option( 'etbi_hubspot_options_refresh_token', '' );
	$expires_in = get_option( 'etbi_hubspot_options_expires_in', '' );
	
?>

<div class="wrap">
	<h1>Hubspot Client</h1>

  <form method="post" action="options.php">
	  <?php settings_fields( 'etbi_hubspot' ); ?>

	  <table>
		  <tr valign="top">
			  <th scope="row"><label for="etbi_hubspot_options_client_id">Client ID</label></th>
			  <td><input type="text" id="etbi_hubspot_options_client_id" name="etbi_hubspot_options_client_id" value="<?php echo $client_id; ?>" placeholder="Client ID"  /></td>
		  </tr>
		  <tr valign="top">
			  <th scope="row"><label for="etbi_hubspot_options_client_secret">Client Secret</label></th>
			  <td><input type="password" id="etbi_hubspot_options_client_secret" name="etbi_hubspot_options_client_secret" value="<?php echo esc_attr( $client_secret ); ?>" placeholder="Client Secret" /></td>
		  </tr>
		  <tr valign="top" class="hidden">
			  <th scope="row"><label for="redirect_uri">Redirect URI</label></th>
			  <td><input type="hidden" id="redirect_uri" name="redirect_uri" value="<?php esc_attr( $redirect_uri ); ?>" /></td>
		  </tr>
		  <tr valign="top" class="hidden">
			  <th scope="row"><label for="authorization_code">Auth Code</label></th>
			  <td><input type="hidden" id="authorization_code" name="authorization_code" value="<?php echo esc_attr( etbi_get_auth_code() ); ?>" /></td>
		  </tr>
		  <tr valign="top" class="hidden">
			  <th scope="row"><label for="access_token">Access Token</label></th>
			  <td><input type="hidden" id="access_token" name="access_token" value="<?php echo esc_attr( $access_token ); ?>" /></td>
		  </tr>
		  <tr valign="top" class="hidden">
			  <th scope="row"><label for="refresh_token">Refresh Token</label></th>
			  <td><input type="hidden" id="refresh_token" name="refresh_token" value="<?php echo esc_attr( $refresh_token ); ?>" /></td>
		  </tr>

		  <tr valign="top" class="hidden">
			  <th scope="row"><label for="expires_in">Expires In</label></th>
			  <td><input type="hidden" id="expires_in" name="expires_in" value="<?php echo esc_attr( $expires_in ); ?>" /></td>
		  </tr>
	  </table>

	  <?php  submit_button(); ?>
  </form>
</div>