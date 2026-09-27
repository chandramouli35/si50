<?php
/**
 * SecondInnings50 - Premium PayU Payment Gateway Pipeline
 * Handles form generation, hash calculation, and callback listeners.
 *
 * @package SecondInnings50
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Check if user is a premium membership status
 */
function si50_is_premium( $user_id ) {
	// Administrator is always premium
	if ( user_can( $user_id, 'manage_options' ) ) {
		return true;
	}
	$status = get_user_meta( $user_id, 'si50_membership_status', true );
	return ( 'premium' === $status );
}

/**
 * Retrieve active PayU Credential Options
 */
function si50_get_payu_credentials() {
	return array(
		'key'      => si50_get_payu_key(),
		'salt'     => si50_get_payu_salt(),
		'base_url' => si50_get_payu_base_url(),
	);
}

/**
 * Render Payment Gate Section UI helper (PayU Form)
 */
function si50_render_payment_gate_section( $user_id ) {
	$m_gender = get_user_meta( $user_id, 'si50_gender', true );
	$is_premium = si50_is_premium( $user_id );

	if ( $is_premium ) {
		return;
	}

	// Female Membership is FREE
	if ( 'Female' === $m_gender ) {
		return;
	}

	$creds = si50_get_payu_credentials();
	$userdata = get_userdata( $user_id );
	
	$txnid = 'si50_tx_' . $user_id . '_' . time();
	$amount = '699.00';
	$productinfo = 'SecondInnings50 One-Time Membership';
	$firstname = get_user_meta( $user_id, 'si50_fullname', true ) ?: $userdata->display_name;
	$email = $userdata->user_email;
	$phone = get_user_meta( $user_id, 'si50_phone', true );
	
	$surl = home_url( '/?si50_action=payu_callback' );
	$furl = home_url( '/?si50_action=payu_callback' );

	// Generate Hash: key|txnid|amount|productinfo|firstname|email|||||||||||salt
	$hash_string = $creds['key'] . '|' . $txnid . '|' . $amount . '|' . $productinfo . '|' . $firstname . '|' . $email . '|||||||||||' . $creds['salt'];
	$hash = strtolower( hash( 'sha512', $hash_string ) );

	$payu_url = rtrim( $creds['base_url'], '/' ) . '/_payment';
	?>
	<div id="payment-section" class="card" style="border: 2px solid var(--color-gold) !important; background-color: #FFFFFF !important; margin-bottom: 24px; padding: 20px; border-radius: var(--radius-md); text-align: center; box-shadow: 0 4px 15px rgba(197, 160, 89, 0.1);">
		<span style="font-size: 2.5rem; display: block; margin-bottom: 10px;">💳</span>
		<h3 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-forest); margin: 0 0 10px 0;">
			<?php esc_html_e( 'Secure Your Membership', 'secondinnings50' ); ?>
		</h3>
		<p style="color: var(--color-charcoal-muted); font-size: var(--fs-sm); max-width: 500px; margin: 0 auto 16px auto; line-height: 1.5;">
			<?php esc_html_e( '₹699 One-Time Membership Fee.', 'secondinnings50' ); ?><br>
			<strong><?php esc_html_e( 'Female Membership is FREE.', 'secondinnings50' ); ?></strong><br><br>
			<span style="color: var(--color-terracotta); font-weight: 700;"><?php esc_html_e( 'No Hidden Charges. No Recurring Fees.', 'secondinnings50' ); ?></span>
		</p>
		
		<div style="font-size: 1.5rem; font-weight: 800; color: var(--color-terracotta); margin-bottom: 16px;">
			₹699 <span style="font-size: 0.85rem; font-weight: 600; color: var(--color-charcoal-muted);"><?php esc_html_e( 'One-Time Fee', 'secondinnings50' ); ?></span>
		</div>

		<form action="<?php echo esc_url( $payu_url ); ?>" method="post" name="payuForm" id="payuForm">
			<input type="hidden" name="key" value="<?php echo esc_attr( $creds['key'] ); ?>" />
			<input type="hidden" name="hash" value="<?php echo esc_attr( $hash ); ?>" />
			<input type="hidden" name="txnid" value="<?php echo esc_attr( $txnid ); ?>" />
			<input type="hidden" name="amount" value="<?php echo esc_attr( $amount ); ?>" />
			<input type="hidden" name="firstname" value="<?php echo esc_attr( $firstname ); ?>" />
			<input type="hidden" name="email" value="<?php echo esc_attr( $email ); ?>" />
			<input type="hidden" name="phone" value="<?php echo esc_attr( $phone ); ?>" />
			<input type="hidden" name="productinfo" value="<?php echo esc_attr( $productinfo ); ?>" />
			<input type="hidden" name="surl" value="<?php echo esc_url( $surl ); ?>" />
			<input type="hidden" name="furl" value="<?php echo esc_url( $furl ); ?>" />
			
			<button type="submit" class="btn btn-gold" style="font-weight: 700; padding: 12px 30px; font-size: 1.1rem; width: 100%; max-width: 300px;">
				<?php esc_html_e( 'Pay Securely via PayU', 'secondinnings50' ); ?>
			</button>
		</form>

		<div style="margin-top: 15px; font-size: 0.85rem; color: var(--color-charcoal-muted);">
			By proceeding, you agree to our <a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>" target="_blank" style="text-decoration: underline; color: inherit;">Terms & Conditions</a>, <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" target="_blank" style="text-decoration: underline; color: inherit;">Privacy Policy</a>, and <a href="<?php echo esc_url( home_url( '/refund-policy/' ) ); ?>" target="_blank" style="text-decoration: underline; color: inherit;">Refund Policy</a>.
		</div>
	</div>
	<?php
}

/**
 * PayU Callback Listener
 */
function si50_payu_callback_listener() {
	if ( isset( $_GET['si50_action'] ) && 'payu_callback' === $_GET['si50_action'] ) {
		$creds = si50_get_payu_credentials();
		$salt = $creds['salt'];

		$status      = isset( $_POST["status"] ) ? sanitize_text_field( $_POST["status"] ) : '';
		$firstname   = isset( $_POST["firstname"] ) ? sanitize_text_field( $_POST["firstname"] ) : '';
		$amount      = isset( $_POST["amount"] ) ? sanitize_text_field( $_POST["amount"] ) : '';
		$txnid       = isset( $_POST["txnid"] ) ? sanitize_text_field( $_POST["txnid"] ) : '';
		$posted_hash = isset( $_POST["hash"] ) ? sanitize_text_field( $_POST["hash"] ) : '';
		$key         = isset( $_POST["key"] ) ? sanitize_text_field( $_POST["key"] ) : '';
		$productinfo = isset( $_POST["productinfo"] ) ? sanitize_text_field( $_POST["productinfo"] ) : '';
		$email       = isset( $_POST["email"] ) ? sanitize_email( $_POST["email"] ) : '';

		// Additional fields might be empty, but PayU expects them in reverse hash if present in request
		$udf1 = isset( $_POST["udf1"] ) ? $_POST["udf1"] : '';
		$udf2 = isset( $_POST["udf2"] ) ? $_POST["udf2"] : '';
		$udf3 = isset( $_POST["udf3"] ) ? $_POST["udf3"] : '';
		$udf4 = isset( $_POST["udf4"] ) ? $_POST["udf4"] : '';
		$udf5 = isset( $_POST["udf5"] ) ? $_POST["udf5"] : '';

		$retHashSeq = $salt . '|' . $status . '||||||' . $udf5 . '|' . $udf4 . '|' . $udf3 . '|' . $udf2 . '|' . $udf1 . '|' . $email . '|' . $firstname . '|' . $productinfo . '|' . $amount . '|' . $txnid . '|' . $key;
		
		$hash = strtolower( hash( "sha512", $retHashSeq ) );

		if ( $hash != $posted_hash ) {
			// Invalid signature
			error_log( 'SecondInnings50 PayU Error: Signature mismatch on callback.' );
			wp_die( esc_html__( 'Invalid transaction signature. Please contact support.', 'secondinnings50' ) );
		} else {
			// Signature is valid. Extract user_id from txnid (format: si50_tx_{user_id}_{timestamp})
			$parts = explode( '_', $txnid );
			$user_id = isset( $parts[2] ) ? intval( $parts[2] ) : 0;

			if ( $user_id ) {
				if ( $status === "success" ) {
					// 1. Mark user as Pending Review and Premium
					update_user_meta( $user_id, 'si50_vetting_status', 'pending_review' );
					update_user_meta( $user_id, 'si50_membership_status', 'premium' );
					update_user_meta( $user_id, 'si50_payment_status', 'paid' );
					update_user_meta( $user_id, 'si50_payment_mode', 'PayU' );
					update_user_meta( $user_id, 'si50_payu_txnid', $txnid );
					
					si50_log_activity( $user_id, 'payment_verified', sprintf( 'PayU Signature Verified. TXN: %s. Upgraded to Premium and submitted application.', $txnid ) );

					// 2. Send Admin WhatsApp Alert
					$admin_users = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
					$admin_phone = '';
					if ( ! empty( $admin_users ) ) {
						$admin_phone = get_user_meta( $admin_users[0]->ID, 'si50_phone', true );
					}
					if ( empty( $admin_phone ) ) {
						$admin_phone = get_option( 'si50_admin_whatsapp_number', '' );
					}
					if ( ! empty( $admin_phone ) ) {
						$name = get_user_meta( $user_id, 'si50_fullname', true );
						$city_state = get_user_meta( $user_id, 'si50_city_state', true );
						$admin_msg = sprintf(
							esc_html__( "🔔 New Application Received! %s from %s has just paid and submitted their profile.", 'secondinnings50' ),
							$name,
							$city_state
						);
						si50_send_whatsapp_alert( $admin_phone, $admin_msg );
					}

					// 3. Send HTML notification email to site administrator
					si50_email_trigger_new_registration( $user_id );

					// 4. Automatically authenticate the applicant
					wp_set_current_user( $user_id );
					wp_set_auth_cookie( $user_id );

					// 5. Redirect to success page
					wp_safe_redirect( home_url( '/application-received/' ) );
					exit;
				} else {
					// Failure
					si50_log_activity( $user_id, 'payment_failed', sprintf( 'PayU transaction failed. TXN: %s.', $txnid ) );
					wp_safe_redirect( home_url( '/join-application/?payment=failed' ) );
					exit;
				}
			}
		}
		
		wp_safe_redirect( home_url( '/' ) );
		exit;
	}
}
add_action( 'template_redirect', 'si50_payu_callback_listener' );
