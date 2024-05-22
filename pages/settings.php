<?php
	$twilio_number = get_option( 'twilio_number', '' );
	$accountSID = get_option( 'twilio_account_sid', '' );
	$authToken = get_option( 'twilio_auth_token', '' );
?>

<div class="wrap about-wrap">
	<h2 class="nav-tab-wrapper">
		<a href="?page=twilio" class="nav-tab nav-tab-active">Twilio Credentials Settings</a>
	</h2>
	<form action="<?php echo admin_url('options-general.php?page=twilio'); ?>">
		<input type="hidden" name="page" value="twilio">
		<input type="hidden" name="action" value="update">
		<?php wp_nonce_field(); ?>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row"><label for="twilio_number">Twilio Number</label></th>
					<td>
						<input name="twilio_number" type="text" value="<?php echo $twilio_number; ?>" class="regular-text" placeholder="+13362522164">
						<p class="description">Country code + 10-digit Twilio phone number (i.e. +13362522164)</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="accountSID">Account SID</label></th>
					<td>
						<input name="accountSID" type="text" value="<?php echo $accountSID; ?>" class="regular-text">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="authToken">Auth Token</label></th>
					<td><input name="authToken" type="password" value="<?php echo $authToken; ?>" class="regular-text"></td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
		</p>
	</form>
</div>
