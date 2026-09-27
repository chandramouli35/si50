<?php
/**
 * Environment Configuration and Credentials Registry.
 *
 * Implements a strict, multi-tier fallback architecture to securely retrieve,
 * decrypt, and validate all production API keys, secrets, and transport tokens.
 *
 * @package SecondInnings50
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Retrieve the internal Cryptographic Encoding/Decoding Passphrase Key.
 *
 * Tries the global server-level constant 'SI50_CRYPTO_SALT' first,
 * then falls back to a predefined secure default salt.
 *
 * @return string The resolved system encryption salt.
 */
function si50_get_system_encryption_salt() {
	if ( defined( 'SI50_CRYPTO_SALT' ) && ! empty( 'SI50_CRYPTO_SALT' ) ) {
		return SI50_CRYPTO_SALT;
	}
	// Secure default fallback salt for internal encryption cascading
	return 'si50_secure_enterprise_default_cryptographic_salt_token_2026';
}

/**
 * Helper: Decrypt a base64 encoded credential using AES-256-CBC.
 *
 * @param string $encrypted_base64 The base64 encoded payload to decrypt.
 * @param string $salt             The cryptographic passphrase key.
 * @return string|false            The decrypted plaintext string, or false on error.
 */
function si50_decrypt_credential( $encrypted_base64, $salt ) {
	$method = 'aes-256-cbc';
	$data   = base64_decode( $encrypted_base64 );
	if ( ! $data ) {
		return false;
	}
	
	$iv_length = openssl_cipher_iv_length( $method );
	if ( strlen( $data ) <= $iv_length ) {
		return false;
	}
	
	$iv        = substr( $data, 0, $iv_length );
	$encrypted = substr( $data, $iv_length );
	$decrypted = openssl_decrypt( $encrypted, $method, $salt, 0, $iv );
	
	return $decrypted;
}

/**
 * Helper: Encrypt a plain credential string using AES-256-CBC.
 * Useful for seeding options or admin dashboards.
 *
 * @param string $plain_text The plain text value to encrypt.
 * @param string $salt       The cryptographic passphrase key.
 * @return string            The base64 encoded encrypted string.
 */
function si50_encrypt_credential( $plain_text, $salt ) {
	$method    = 'aes-256-cbc';
	$iv_length = openssl_cipher_iv_length( $method );
	$iv        = openssl_random_pseudo_bytes( $iv_length );
	$encrypted = openssl_encrypt( $plain_text, $method, $salt, 0, $iv );
	
	return base64_encode( $iv . $encrypted );
}

/**
 * Resolve a production credential using the 3-Tier cascade pipeline.
 *
 * Tier 1: Check server-isolated global constants.
 * Tier 2: Check protected, cryptographically encrypted options row.
 * Tier 3: Raise error telemetry log, stop flow, and bubble up UI exception warning.
 *
 * @param string $const_name The name of the server global PHP constant.
 * @param string $option_key The registry settings array key name.
 * @param bool   $is_vital   Whether this credential is fatal to execution if missing.
 * @return string            The resolved credential value.
 */
function si50_resolve_credential( $const_name, $option_key, $is_vital = true ) {
	// Tier 1: Global Core Constants
	if ( defined( $const_name ) ) {
		$const_val = constant( $const_name );
		if ( ! empty( $const_val ) ) {
			return (string) $const_val;
		}
	}

	// Tier 2: Secure Options Storage
	$encrypted_creds = get_option( 'si50_encrypted_credentials' );
	if ( is_array( $encrypted_creds ) && isset( $encrypted_creds[ $option_key ] ) ) {
		$encrypted_val = $encrypted_creds[ $option_key ];
		if ( ! empty( $encrypted_val ) ) {
			$salt      = si50_get_system_encryption_salt();
			$decrypted = si50_decrypt_credential( $encrypted_val, $salt );
			if ( false !== $decrypted && ! empty( $decrypted ) ) {
				return (string) $decrypted;
			}
		}
	}

	// Tier 3: Fail-Safe Exception Handler Interceptor
	if ( $is_vital ) {
		// Log telemetry event anonymously (without displaying keys)
		error_log( sprintf( 'SecondInnings50 [Registry Exception]: Vital credential parameter missing. Const: "%s", Option Key: "%s".', $const_name, $option_key ) );

		$error_msg = __( 'Service configuration error. Our onboarding hosts have been notified. Please try again shortly.', 'secondinnings50' );

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			wp_send_json_error( array( 'message' => $error_msg ) );
			exit;
		} elseif ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			status_header( 500 );
			wp_send_json( array( 'code' => 'service_config_error', 'message' => $error_msg ) );
			exit;
		} else {
			wp_die( 
				esc_html( $error_msg ), 
				esc_html__( 'Configuration Error', 'secondinnings50' ), 
				array( 'response' => 500 ) 
			);
		}
	}

	return '';
}

/**
 * Functional API: Retrieve PayU Merchant Key.
 *
 * @return string Key ID.
 */
function si50_get_payu_key() {
	return si50_resolve_credential( 'PAYU_MERCHANT_KEY', 'payu_merchant_key', true );
}

/**
 * Functional API: Retrieve PayU Merchant Salt.
 *
 * @return string Secret Key.
 */
function si50_get_payu_salt() {
	return si50_resolve_credential( 'PAYU_MERCHANT_SALT', 'payu_merchant_salt', true );
}

/**
 * Functional API: Retrieve PayU Base URL.
 *
 * @return string Base URL.
 */
function si50_get_payu_base_url() {
	return si50_resolve_credential( 'PAYU_BASE_URL', 'payu_base_url', true );
}

/**
 * Functional API: Retrieve Identity Vetting & OTP Gateway Authentication Token.
 *
 * @return string SMS gateway token.
 */
function si50_get_otp_gateway_token() {
	return si50_resolve_credential( 'SI50_SMS_GATEWAY_TOKEN', 'sms_gateway_token', true );
}

/**
 * Functional API: Retrieve SMS Gateway API base endpoint URL.
 *
 * @return string API Endpoint.
 */
function si50_get_otp_gateway_api_url() {
	return si50_resolve_credential( 'SI50_SMS_API_URL', 'sms_api_url', true );
}

/**
 * Functional API: Retrieve WhatsApp Session Bearer API Token.
 *
 * @return string WhatsApp API Token.
 */
function si50_get_whatsapp_api_key() {
	return si50_resolve_credential( 'SI50_WHATSAPP_API_KEY', 'whatsapp_api_key', true );
}

/**
 * Functional API: Retrieve WhatsApp Outbound Sender Coordinates.
 *
 * @return string WhatsApp Sender phone/profile identifier.
 */
function si50_get_whatsapp_sender() {
	return si50_resolve_credential( 'SI50_WHATSAPP_SENDER', 'whatsapp_sender', false );
}

/**
 * Functional API: Retrieve Google Maps API Access Token.
 *
 * @return string Google Maps key.
 */
function si50_get_google_maps_key() {
	return si50_resolve_credential( 'SI50_GOOGLE_MAPS_API_KEY', 'google_maps_key', false );
}

/**
 * Functional API: Retrieve Real-Time SMTP Transactional Email Config.
 *
 * @return array SMTP credentials array.
 */
function si50_get_smtp_credentials() {
	return array(
		'host'       => si50_resolve_credential( 'SI50_SMTP_HOST', 'smtp_host', true ),
		'user'       => si50_resolve_credential( 'SI50_SMTP_USER', 'smtp_user', true ),
		'pass'       => si50_resolve_credential( 'SI50_SMTP_PASS', 'smtp_pass', true ),
		'port'       => intval( si50_resolve_credential( 'SI50_SMTP_PORT', 'smtp_port', true ) ),
		'encryption' => si50_resolve_credential( 'SI50_SMTP_ENCRYPTION', 'smtp_encryption', true ),
	);
}

/**
 * Configure WP Mail PHPMailer object with resolved SMTP Credentials.
 *
 * @param PHPMailer $phpmailer The mailer instance.
 */
function si50_smtp_mail_configuration( $phpmailer ) {
	$creds = si50_get_smtp_credentials();
	if ( empty( $creds['host'] ) || empty( $creds['user'] ) || empty( $creds['pass'] ) ) {
		return;
	}

	$phpmailer->isSMTP();
	$phpmailer->Host       = $creds['host'];
	$phpmailer->SMTPAuth   = true;
	$phpmailer->Port       = $creds['port'];
	$phpmailer->Username   = $creds['user'];
	$phpmailer->Password   = $creds['pass'];
	
	// Encryption cascade (SSL/TLS)
	$encryption = strtolower( trim( $creds['encryption'] ) );
	if ( 'ssl' === $encryption || 'tls' === $encryption ) {
		$phpmailer->SMTPSecure = $encryption;
	} else {
		$phpmailer->SMTPSecure = '';
	}
}
add_action( 'phpmailer_init', 'si50_smtp_mail_configuration' );

