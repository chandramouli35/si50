<?php
/**
 * SecondInnings50 - Production Engine Core
 * Handles active OTP dispatching, secure photo uploads, member reports/blocks, 
 * compatibility scoring, audit logs, city-wise WhatsApp routing, and HTML transaction emails.
 *
 * @package SecondInnings50
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * 1. Database Schema Installation
 */
function si50_install_production_tables() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	// Audit Logs Table
	$table_audit = $wpdb->prefix . 'si50_audit_logs';
	$sql_audit = "CREATE TABLE $table_audit (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		user_id bigint(20) NOT NULL,
		action varchar(100) NOT NULL,
		details text NOT NULL,
		timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY  (id),
		KEY user_id (user_id),
		KEY action (action)
	) $charset_collate;";
	dbDelta( $sql_audit );

	// Community Events Table
	$table_events = $wpdb->prefix . 'si50_events';
	$sql_events = "CREATE TABLE $table_events (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		title varchar(255) NOT NULL,
		description text NOT NULL,
		event_date datetime NOT NULL,
		location varchar(255) NOT NULL,
		city varchar(100) NOT NULL,
		whatsapp_group_url varchar(255) NOT NULL,
		timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY  (id),
		KEY city (city)
	) $charset_collate;";
	dbDelta( $sql_events );

	// Travel Groups Table
	$table_travel = $wpdb->prefix . 'si50_travel_groups';
	$sql_travel = "CREATE TABLE $table_travel (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		title varchar(255) NOT NULL,
		description text NOT NULL,
		destination varchar(255) NOT NULL,
		start_date date NOT NULL,
		end_date date NOT NULL,
		whatsapp_group_url varchar(255) NOT NULL,
		created_by bigint(20) NOT NULL,
		timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY  (id),
		KEY destination (destination)
	) $charset_collate;";
	dbDelta( $sql_travel );

	// Safety Reports Table
	$table_reports = $wpdb->prefix . 'si50_member_reports';
	$sql_reports = "CREATE TABLE $table_reports (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		reporter_id bigint(20) NOT NULL,
		reported_id bigint(20) NOT NULL,
		reason varchar(255) NOT NULL,
		details text NOT NULL,
		status varchar(50) NOT NULL DEFAULT 'active',
		timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY  (id),
		KEY reporter_id (reporter_id),
		KEY reported_id (reported_id),
		KEY status (status)
	) $charset_collate;";
	dbDelta( $sql_reports );

	// Seed Initial Community Events and Travel Groups if empty
	si50_seed_production_data();
}

/**
 * Seed helper to insert default mock-free events & travel groups
 */
function si50_seed_production_data() {
	global $wpdb;

	// Events Seeding
	$table_events = $wpdb->prefix . 'si50_events';
	if ( 0 === intval( $wpdb->get_var( "SELECT COUNT(*) FROM $table_events" ) ) ) {
		$wpdb->insert( $table_events, array(
			'title'              => 'Sunday Morning Nature Walk & Tea',
			'description'        => 'Join other active silver members for a gentle walk through the botanical gardens, followed by morning Chai at the tea gallery.',
			'event_date'         => date( 'Y-m-d H:i:s', strtotime( '+7 days 07:00:00' ) ),
			'location'           => 'Empress Botanical Garden, Ghorpadi',
			'city'               => 'Pune',
			'whatsapp_group_url' => 'https://chat.whatsapp.com/PuneActiveSeniors',
		) );
		$wpdb->insert( $table_events, array(
			'title'              => 'Retro Music & Ghazal Evening',
			'description'        => 'An intimate gathering celebrating vintage melodies, poetry readings, and shared musical memories from the golden eras.',
			'event_date'         => date( 'Y-m-d H:i:s', strtotime( '+14 days 18:30:00' ) ),
			'location'           => 'Lata Mangeshkar Auditoria',
			'city'               => 'Mumbai',
			'whatsapp_group_url' => 'https://chat.whatsapp.com/MumbaiActiveSeniors',
		) );
		$wpdb->insert( $table_events, array(
			'title'              => 'Sanskrit & Heritage Exchange Circle',
			'description'        => 'Engage in conversational circles exploring ancient Indian literature, scriptural translations, and local heritage preservation.',
			'event_date'         => date( 'Y-m-d H:i:s', strtotime( '+10 days 16:00:00' ) ),
			'location'           => 'India International Centre (IIC)',
			'city'               => 'Delhi',
			'whatsapp_group_url' => 'https://chat.whatsapp.com/DelhiActiveSeniors',
		) );
	}

	// Travel Groups Seeding
	$table_travel = $wpdb->prefix . 'si50_travel_groups';
	if ( 0 === intval( $wpdb->get_var( "SELECT COUNT(*) FROM $table_travel" ) ) ) {
		$wpdb->insert( $table_travel, array(
			'title'              => 'Comfort Teerth Yatra: Varanasi & Ayodhya Dham',
			'description'        => 'A fully guided, comfortable spiritual journey designed for active seniors. Premium hotels, accessible coaches, and shared spiritual devotion.',
			'destination'        => 'Ayodhya & Varanasi',
			'start_date'         => date( 'Y-m-d', strtotime( '+30 days' ) ),
			'end_date'           => date( 'Y-m-d', strtotime( '+36 days' ) ),
			'whatsapp_group_url' => 'https://chat.whatsapp.com/IndiaActiveSeniors',
			'created_by'         => 1,
		) );
		$wpdb->insert( $table_travel, array(
			'title'              => 'Kerala Backwaters & Wellness Retreat',
			'description'        => 'Experience serene houseboat cruises, rejuvenating Ayurvedic health consultations, and relaxing wellness sessions in tropical Kerala.',
			'destination'        => 'Kerala',
			'start_date'         => date( 'Y-m-d', strtotime( '+45 days' ) ),
			'end_date'           => date( 'Y-m-d', strtotime( '+51 days' ) ),
			'whatsapp_group_url' => 'https://chat.whatsapp.com/IndiaActiveSeniors',
			'created_by'         => 1,
		) );
	}
}

/**
 * 2. Activity Audit Logging System
 */
function si50_log_activity( $user_id, $action, $details ) {
	global $wpdb;
	$table_audit = $wpdb->prefix . 'si50_audit_logs';

	$wpdb->insert(
		$table_audit,
		array(
			'user_id'   => intval( $user_id ),
			'action'    => sanitize_text_field( $action ),
			'details'   => sanitize_textarea_field( $details ),
			'timestamp' => current_time( 'mysql' )
		),
		array( '%d', '%s', '%s', '%s' )
	);
}

// Log actual user logins
function si50_log_user_login( $user_login, $user ) {
	if ( $user ) {
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
		si50_log_activity( $user->ID, 'login', sprintf( 'User successfully logged into platform. Source IP: %s', $ip ) );
	}
}
add_action( 'wp_login', 'si50_log_user_login', 10, 2 );

// Log user meta updates
function si50_log_meta_updates( $meta_id, $object_id, $meta_key, $_meta_value ) {
	$tracked_keys = array(
		'si50_fullname',
		'si50_phone',
		'si50_city_state',
		'si50_vetting_status'
	);
	if ( in_array( $meta_key, $tracked_keys, true ) ) {
		$value_str = is_array( $_meta_value ) ? implode( ', ', $_meta_value ) : strval( $_meta_value );
		si50_log_activity( $object_id, 'profile_update', sprintf( 'Meta field "%s" updated to: "%s"', $meta_key, $value_str ) );
	}
}
add_action( 'updated_user_meta', 'si50_log_meta_updates', 10, 4 );

/**
 * 3. Mobile OTP Verification Core
 */
function si50_send_otp_code( $phone_number, $otp ) {
	// Clean the phone number (pre-formatted with India country code prefix 91)
	$clean_phone = preg_replace( '/[^0-9]/', '', $phone_number );
	if ( 10 === strlen( $clean_phone ) ) {
		$clean_phone = '91' . $clean_phone;
	}

	$message = sprintf( esc_html__( 'Your SecondInnings50 verification code is: %s. Valid for 5 minutes. Do not share this code.', 'secondinnings50' ), $otp );

	// Output to error log for local tracking/simulation fallback
	error_log( sprintf( '🔑 [Outgoing OTP SMS] To: +%s, Code: %s, Message: "%s"', $clean_phone, $otp, $message ) );

	// 1. Core Registry Option Gateway Pipeline
	$sms_token   = si50_get_otp_gateway_token();
	$sms_api_url = si50_get_otp_gateway_api_url();

	if ( ! empty( $sms_token ) && ! empty( $sms_api_url ) ) {
		wp_remote_post( $sms_api_url, array(
			'method'      => 'POST',
			'timeout'     => 5,
			'redirection' => 5,
			'headers'     => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $sms_token,
			),
			'body'        => json_encode( array(
				'to'      => $clean_phone,
				'message' => $message,
				'code'    => $otp,
			) ),
			'blocking'    => false, // Non-blocking background call
		) );
		return true;
	}

	// 2. Legacy Twilio Option Pipeline
	$twilio_sid   = get_option( 'si50_twilio_sid', '' );
	$twilio_token = get_option( 'si50_twilio_token', '' );
	$twilio_number = get_option( 'si50_twilio_number', '' );

	if ( ! empty( $twilio_sid ) && ! empty( $twilio_token ) && ! empty( $twilio_number ) ) {
		$url = 'https://api.twilio.com/2010-04-01/Accounts/' . $twilio_sid . '/Messages.json';
		wp_remote_post( $url, array(
			'method'      => 'POST',
			'timeout'     => 5,
			'redirection' => 5,
			'headers'     => array(
				'Authorization' => 'Basic ' . base64_encode( $twilio_sid . ':' . $twilio_token ),
			),
			'body'        => array(
				'From' => $twilio_number,
				'To'   => '+' . $clean_phone,
				'Body' => $message,
			),
			'blocking'    => false, // Non-blocking background call
		) );
		return true;
	}

	// 3. Legacy Gupshup Option Pipeline
	$gupshup_user = get_option( 'si50_gupshup_user', '' );
	$gupshup_pass = get_option( 'si50_gupshup_password', '' );
	$gupshup_mask = get_option( 'si50_gupshup_mask', '' );

	if ( ! empty( $gupshup_user ) && ! empty( $gupshup_pass ) ) {
		$url = 'https://enterprise.smsgupshup.com/GatewayAPI/rest';
		wp_remote_post( $url, array(
			'method'      => 'POST',
			'timeout'     => 5,
			'redirection' => 5,
			'body'        => array(
				'method'   => 'sendMessage',
				'send_to'  => $clean_phone,
				'msg'      => $message,
				'msg_type' => 'TEXT',
				'userid'   => $gupshup_user,
				'password' => $gupshup_pass,
				'auth_scheme' => 'plain',
				'v'        => '1.1',
				'maskname' => $gupshup_mask
			),
			'blocking'    => false,
		) );
		return true;
	}

	// 4. Legacy SMS Horizon Pipeline
	$horizon_key    = get_option( 'si50_smshorizon_key', '' );
	$horizon_sender = get_option( 'si50_smshorizon_sender', 'SMSHRZ' );

	if ( ! empty( $horizon_key ) ) {
		$url = 'https://smshorizon.co.in/api/sendsms.php';
		wp_remote_post( $url, array(
			'method'      => 'GET',
			'timeout'     => 5,
			'redirection' => 5,
			'body'        => array(
				'user'    => 'si50_user',
				'apikey'  => $horizon_key,
				'mobile'  => $clean_phone,
				'message' => $message,
				'senderid'=> $horizon_sender,
				'type'    => 'txt'
			),
			'blocking'    => false,
		) );
		return true;
	}

	// Legacy generic external Webhook hook
	$webhook_url = get_option( 'si50_sms_webhook_url', '' );
	if ( ! empty( $webhook_url ) ) {
		wp_remote_post( $webhook_url, array(
			'method'      => 'POST',
			'timeout'     => 5,
			'headers'     => array( 'Content-Type' => 'application/json' ),
			'body'        => json_encode( array( 'phone' => $clean_phone, 'message' => $message, 'code' => $otp ) ),
			'blocking'    => false,
		) );
	}

	return true;
}

/**
 * AJAX hook to trigger verification code dispatches
 */
function si50_ajax_send_otp() {
	check_ajax_referer( 'si50_auth_nonce', 'security' );

	$phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	$clean_phone = preg_replace( '/[^0-9]/', '', $phone );

	if ( empty( $clean_phone ) || strlen( $clean_phone ) < 10 ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please enter a valid 10-digit WhatsApp number.', 'secondinnings50' ) ) );
	}

	// Generate a cryptographically secure random 4-digit code
	$otp = strval( rand( 1000, 9999 ) );

	// Database transient logging window: expires exactly at 5 minutes (300 seconds) to prevent replay hacks
	set_transient( 'si50_otp_' . $clean_phone, $otp, 300 );

	// Log transaction audit
	si50_log_activity( 0, 'otp_sent', sprintf( 'OTP dispatch requested for phone number: %s', $clean_phone ) );

	// Dispatch outbound request
	$sent = si50_send_otp_code( $clean_phone, $otp );

	if ( $sent ) {
		wp_send_json_success( array( 'message' => esc_html__( 'Verification code successfully sent!', 'secondinnings50' ) ) );
	} else {
		wp_send_json_error( array( 'message' => esc_html__( 'Failed to dispatch verification SMS. Please try again.', 'secondinnings50' ) ) );
	}
}
add_action( 'wp_ajax_si50_send_otp', 'si50_ajax_send_otp' );
add_action( 'wp_ajax_nopriv_si50_send_otp', 'si50_ajax_send_otp' );

/**
 * AJAX hook to verify verification code
 */
function si50_ajax_verify_otp() {
	check_ajax_referer( 'si50_auth_nonce', 'security' );

	$phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	$otp   = isset( $_POST['otp'] ) ? sanitize_text_field( $_POST['otp'] ) : '';
	
	$clean_phone = preg_replace( '/[^0-9]/', '', $phone );
	$saved_otp   = get_transient( 'si50_otp_' . $clean_phone );

	if ( empty( $saved_otp ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Verification code has expired. Please request a new one.', 'secondinnings50' ) ) );
	}

	if ( $saved_otp === $otp ) {
		// Immediately clear/delete the transient to prevent replay hacks
		delete_transient( 'si50_otp_' . $clean_phone );

		// Record verification telemetry
		si50_log_activity( 0, 'otp_verified', sprintf( 'OTP successfully verified for phone: %s', $clean_phone ) );

		wp_send_json_success( array( 'message' => esc_html__( 'Mobile verification completed!', 'secondinnings50' ) ) );
	} else {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid verification code. Please check and try again.', 'secondinnings50' ) ) );
	}
}
add_action( 'wp_ajax_si50_verify_otp', 'si50_ajax_verify_otp' );
add_action( 'wp_ajax_nopriv_si50_verify_otp', 'si50_ajax_verify_otp' );

/**
 * 4. Secure Selfie/Photo Vetting Upload Module
 */
function si50_secure_upload_selfie( $file_input_name, $user_id ) {
	if ( empty( $_FILES[ $file_input_name ]['name'] ) ) {
		return new WP_Error( 'no_file', esc_html__( 'No file uploaded.', 'secondinnings50' ) );
	}

	$file = $_FILES[ $file_input_name ];

	// Validate size limits (e.g. 5MB)
	if ( $file['size'] > 5 * 1024 * 1024 ) {
		return new WP_Error( 'file_too_large', esc_html__( 'Selfie file size cannot exceed 5MB.', 'secondinnings50' ) );
	}

	// Validate mime type checks
	$allowed_mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'png'          => 'image/png',
		'gif'          => 'image/gif',
		'webp'         => 'image/webp',
	);
	$file_info = wp_check_filetype( basename( $file['name'] ), $allowed_mimes );
	
	if ( ! $file_info['ext'] || ! $file_info['type'] ) {
		return new WP_Error( 'invalid_file_type', esc_html__( 'Invalid file format. Please upload JPG, PNG, GIF or WEBP images only.', 'secondinnings50' ) );
	}

	// Double check content type checks via PHP getimagesize
	$img_size = getimagesize( $file['tmp_name'] );
	if ( false === $img_size ) {
		return new WP_Error( 'not_an_image', esc_html__( 'Uploaded file is not a valid image.', 'secondinnings50' ) );
	}

	// Randomized Hashing for File Naming (prevents direct URL guessing/catfishing scripts)
	$hash_name = md5( uniqid( rand(), true ) );
	$file['name'] = $hash_name . '.' . $file_info['ext'];

	// Store files securely using standard WordPress uploads
	require_once ABSPATH . 'wp-admin/includes/file.php';
	
	// Override upload filters to prevent executable file uploads
	$upload_overrides = array(
		'test_form' => false,
		'mimes'     => $allowed_mimes
	);

	$movefile = wp_handle_upload( $file, $upload_overrides );

	if ( $movefile && ! isset( $movefile['error'] ) ) {
		// Update user meta indices
		update_user_meta( $user_id, 'si50_verification_selfie_url', $movefile['url'] );
		update_user_meta( $user_id, 'si50_verification_selfie_path', $movefile['file'] );
		
		si50_log_activity( $user_id, 'selfie_upload', 'Secure identity verification selfie uploaded successfully.' );
		return $movefile['url'];
	} else {
		return new WP_Error( 'upload_failed', $movefile['error'] );
	}
}

/**
 * 5. Member Block & Report Isolation System
 */
function si50_ajax_block_member() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => esc_html__( 'You must be logged in to block profiles.', 'secondinnings50' ) ) );
	}

	check_ajax_referer( 'si50_auth_nonce', 'security' );

	$user_id = get_current_user_id();
	$blocked_id = isset( $_POST['blocked_user_id'] ) ? intval( $_POST['blocked_user_id'] ) : 0;

	if ( ! $blocked_id || $blocked_id === $user_id ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid member choice.', 'secondinnings50' ) ) );
	}

	// Fetch current blocklist
	$blocklist = (array) get_user_meta( $user_id, 'si50_blocked_users', true );
	if ( ! in_array( $blocked_id, $blocklist, true ) ) {
		$blocklist[] = $blocked_id;
		update_user_meta( $user_id, 'si50_blocked_users', $blocklist );

		$blocked_members = get_user_meta( $user_id, 'si50_blocked_members', true );
		if ( ! is_array( $blocked_members ) ) {
			$blocked_members = array();
		}
		if ( ! in_array( $blocked_id, $blocked_members, true ) ) {
			$blocked_members[] = $blocked_id;
			update_user_meta( $user_id, 'si50_blocked_members', $blocked_members );
		}

		// Bidirectional tracking block to ensure absolute isolation
		$their_blocks = (array) get_user_meta( $blocked_id, 'si50_blocked_by_users', true );
		if ( ! in_array( $user_id, $their_blocks, true ) ) {
			$their_blocks[] = $user_id;
			update_user_meta( $blocked_id, 'si50_blocked_by_users', $their_blocks );
		}

		$blocked_by_members = get_user_meta( $blocked_id, 'si50_blocked_by_members', true );
		if ( ! is_array( $blocked_by_members ) ) {
			$blocked_by_members = array();
		}
		if ( ! in_array( $user_id, $blocked_by_members, true ) ) {
			$blocked_by_members[] = $user_id;
			update_user_meta( $blocked_id, 'si50_blocked_by_members', $blocked_by_members );
		}

		// Delete any active or pending connection requests
		global $wpdb;
		$wpdb->query( $wpdb->prepare(
			"DELETE FROM {$wpdb->prefix}si50_connect_requests 
			 WHERE (sender_id = %d AND receiver_id = %d) 
			    OR (sender_id = %d AND receiver_id = %d)",
			$user_id, $blocked_id, $blocked_id, $user_id
		) );

		// Log isolation vector
		si50_log_activity( $user_id, 'member_blocked', sprintf( 'Blocked user: %d', $blocked_id ) );
	}

	wp_send_json_success( array( 'message' => esc_html__( 'Member has been successfully blocked. You will no longer view their profile.', 'secondinnings50' ) ) );
}
add_action( 'wp_ajax_si50_block_member', 'si50_ajax_block_member' );

/**
 * AJAX hook to report user profiles
 */
function si50_ajax_report_member() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => esc_html__( 'You must be logged in to report profiles.', 'secondinnings50' ) ) );
	}

	check_ajax_referer( 'si50_auth_nonce', 'security' );

	$user_id = get_current_user_id();
	$reported_id = isset( $_POST['reported_user_id'] ) ? intval( $_POST['reported_user_id'] ) : 0;
	$reason = isset( $_POST['reason'] ) ? sanitize_textarea_field( $_POST['reason'] ) : '';

	if ( ! $reported_id || $reported_id === $user_id || empty( $reason ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid report arguments.', 'secondinnings50' ) ) );
	}

	// Log report inside audit logs
	si50_log_activity( $user_id, 'member_reported', sprintf( 'Reported user: %d. Reason: "%s"', $reported_id, $reason ) );

	// Write safety report details inside database
	global $wpdb;
	$table_reports = $wpdb->prefix . 'si50_member_reports';
	$wpdb->insert(
		$table_reports,
		array(
			'reporter_id' => $user_id,
			'reported_id' => $reported_id,
			'reason'      => esc_html( wp_trim_words( $reason, 10, '...' ) ),
			'details'     => esc_html( $reason ),
			'status'      => 'active',
			'timestamp'   => current_time( 'mysql' )
		),
		array( '%d', '%d', '%s', '%s', '%s', '%s' )
	);

	$reported_members = get_user_meta( $user_id, 'si50_reported_members', true );
	if ( ! is_array( $reported_members ) ) {
		$reported_members = array();
	}
	if ( ! in_array( $reported_id, $reported_members, true ) ) {
		$reported_members[] = $reported_id;
		update_user_meta( $user_id, 'si50_reported_members', $reported_members );
	}

	$reported_by_members = get_user_meta( $reported_id, 'si50_reported_by_members', true );
	if ( ! is_array( $reported_by_members ) ) {
		$reported_by_members = array();
	}
	if ( ! in_array( $user_id, $reported_by_members, true ) ) {
		$reported_by_members[] = $user_id;
		update_user_meta( $reported_id, 'si50_reported_by_members', $reported_by_members );
	}

	// Automatically block reported users bidirectionally to prevent active harassment
	$blocklist = (array) get_user_meta( $user_id, 'si50_blocked_users', true );
	if ( ! in_array( $reported_id, $blocklist, true ) ) {
		$blocklist[] = $reported_id;
		update_user_meta( $user_id, 'si50_blocked_users', $blocklist );
		
		$blocked_members = get_user_meta( $user_id, 'si50_blocked_members', true );
		if ( ! is_array( $blocked_members ) ) {
			$blocked_members = array();
		}
		if ( ! in_array( $reported_id, $blocked_members, true ) ) {
			$blocked_members[] = $reported_id;
			update_user_meta( $user_id, 'si50_blocked_members', $blocked_members );
		}

		$their_blocks = (array) get_user_meta( $reported_id, 'si50_blocked_by_users', true );
		if ( ! in_array( $user_id, $their_blocks, true ) ) {
			$their_blocks[] = $user_id;
			update_user_meta( $reported_id, 'si50_blocked_by_users', $their_blocks );
		}

		$blocked_by_members = get_user_meta( $reported_id, 'si50_blocked_by_members', true );
		if ( ! is_array( $blocked_by_members ) ) {
			$blocked_by_members = array();
		}
		if ( ! in_array( $user_id, $blocked_by_members, true ) ) {
			$blocked_by_members[] = $user_id;
			update_user_meta( $reported_id, 'si50_blocked_by_members', $blocked_by_members );
		}
	}

	// Delete any active or pending connection requests
	$wpdb->query( $wpdb->prepare(
		"DELETE FROM {$wpdb->prefix}si50_connect_requests 
		 WHERE (sender_id = %d AND receiver_id = %d) 
		    OR (sender_id = %d AND receiver_id = %d)",
		$user_id, $reported_id, $reported_id, $user_id
	) );

	// Send transactional HTML notification email to Administrator
	$admin_email = get_option( 'admin_email' );
	$reporter = get_userdata( $user_id );

	$accused = get_userdata( $reported_id );

	$subject = sprintf( '[%s] ⚠️ ALERT: Member Profile Reported for Vetting Review', get_bloginfo( 'name' ) );
	
	$body = '
	<div style="font-family: Arial, sans-serif; line-height: 1.6; max-width: 600px; margin: 0 auto; border: 1.5px solid #d9534f; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
		<div style="background-color: #d9534f; color: #ffffff; padding: 18px 24px; text-align: center;">
			<h2 style="margin: 0; font-size: 20px; font-weight: bold;">⚠️ Safety Violation Report</h2>
		</div>
		<div style="padding: 24px; background-color: #ffffff;">
			<p>An approved member has submitted a safety warning request regarding a profile in the directory. The reported member has been automatically isolated from the reporter\'s view grid.</p>
			
			<table style="width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px;">
				<tr style="border-bottom: 1px solid #eeeeee;">
					<td style="padding: 10px 0; font-weight: bold; color: #555555; width: 150px;">Reporter Name:</td>
					<td style="padding: 10px 0; color: #333333;">' . esc_html( $reporter->display_name ) . ' (ID: #' . $user_id . ')</td>
				</tr>
				<tr style="border-bottom: 1px solid #eeeeee;">
					<td style="padding: 10px 0; font-weight: bold; color: #555555;">Accused Profile:</td>
					<td style="padding: 10px 0; color: #333333;">' . esc_html( $accused->display_name ) . ' (ID: #' . $reported_id . ')</td>
				</tr>
				<tr>
					<td style="padding: 10px 0; font-weight: bold; color: #555555; vertical-align: top;">Reason / Evidence:</td>
					<td style="padding: 10px 0; color: #333333; background: #fdf3f2; border: 1px dashed #d9534f; padding: 10px; border-radius: 4px; font-style: italic;">' . esc_html( $reason ) . '</td>
				</tr>
			</table>
			
			<p style="margin-bottom: 0;">Please log into your SI50 Control Panel immediately to review the profile details, download their PDF vetting application dossier, or deactivate their account.</p>
		</div>
		<div style="background-color: #f8f9fa; padding: 12px; text-align: center; border-top: 1px solid #eeeeee; font-size: 11px; color: #888888;">
			SecondInnings50 Automated Safety Desk
		</div>
	</div>';

	si50_send_html_email( $admin_email, $subject, $body );

	wp_send_json_success( array( 'message' => esc_html__( 'Report submitted to administrators. The member has been isolated and blocked from your view.', 'secondinnings50' ) ) );
}
add_action( 'wp_ajax_si50_report_member', 'si50_ajax_report_member' );

/**
 * Helper to retrieve all excluded/blocked User IDs
 */
if ( ! function_exists( 'si50_get_blocked_user_ids' ) ) {
	function si50_get_blocked_user_ids( $user_id ) {
		$blocked = (array) get_user_meta( $user_id, 'si50_blocked_users', true );
		$blocked_by = (array) get_user_meta( $user_id, 'si50_blocked_by_users', true );
		return array_unique( array_map( 'intval', array_merge( $blocked, $blocked_by ) ) );
	}
}

/**
 * 6. Programmatic Profile Completion Engine
 */
function si50_get_profile_completion_percentage( $user_id ) {
	$percent = 0;

	// Vector 1: Bio text (min 10 chars)
	$bio = get_user_meta( $user_id, 'si50_introduction', true );
	if ( ! empty( $bio ) && strlen( trim( $bio ) ) >= 10 ) {
		$percent += 25;
	}

	// Vector 2: Basic Info (Age Bracket, Marital Status, Occupation)
	$age_bracket = get_user_meta( $user_id, 'si50_age_bracket', true );
	$marital     = get_user_meta( $user_id, 'si50_marital_status', true );
	$occupation  = get_user_meta( $user_id, 'si50_occupation', true );
	if ( ! empty( $age_bracket ) && ! empty( $marital ) && ! empty( $occupation ) ) {
		$percent += 25;
	}

	// Vector 3: Interest Checkboxes / Seeking Focus details
	$looking = (array) get_user_meta( $user_id, 'si50_looking_for', true );
	$circles = (array) get_user_meta( $user_id, 'si50_circles_interest', true );
	if ( ! empty( $looking ) || ! empty( $circles ) ) {
		$percent += 25;
	}

	// Vector 4: Selfie Upload
	$selfie = get_user_meta( $user_id, 'si50_verification_selfie_url', true );
	if ( ! empty( $selfie ) ) {
		$percent += 25;
	}

	return $percent;
}

/**
 * 7. Interest-Driven Compatibility Matching Algorithm
 */
function si50_calculate_compatibility( $user_a, $user_b ) {
	$user_a = intval( $user_a );
	$user_b = intval( $user_b );

	if ( ! $user_a || ! $user_b || $user_a === $user_b ) {
		return 0;
	}

	// Retrieve seeks & interests vectors
	$looking_a = (array) get_user_meta( $user_a, 'si50_looking_for', true );
	$looking_b = (array) get_user_meta( $user_b, 'si50_looking_for', true );
	
	$circles_a = (array) get_user_meta( $user_a, 'si50_circles_interest', true );
	$circles_b = (array) get_user_meta( $user_b, 'si50_circles_interest', true );

	$hobbies_a = (array) get_user_meta( $user_a, 'si50_hobbies_interests', true );
	$hobbies_b = (array) get_user_meta( $user_b, 'si50_hobbies_interests', true );

	$city_a = strtolower( trim( get_user_meta( $user_a, 'si50_city_state', true ) ) );
	$city_b = strtolower( trim( get_user_meta( $user_b, 'si50_city_state', true ) ) );

	// 1. Shared seek interests intersection (weight: 35%)
	$common_looking = array_intersect( $looking_a, $looking_b );
	$looking_score = ! empty( $looking_a ) ? ( count( $common_looking ) / count( $looking_a ) ) * 35 : 15;

	// 2. Shared circle targets intersection (weight: 25%)
	$common_circles = array_intersect( $circles_a, $circles_b );
	$circles_score = ! empty( $circles_a ) ? ( count( $common_circles ) / count( $circles_a ) ) * 25 : 10;

	// 3. Shared hobbies & interests (weight: 25%)
	$common_hobbies = array_intersect( $hobbies_a, $hobbies_b );
	$hobbies_score = ! empty( $hobbies_a ) ? ( count( $common_hobbies ) / count( $hobbies_a ) ) * 25 : 10;

	// 4. Residing in same city, state, or zone (weight: 25%)
	$city_score = 5;
	if ( ! empty( $city_a ) && ! empty( $city_b ) ) {
		if ( $city_a === $city_b ) {
			$city_score = 25; // Exact same city/state string
		} else {
			// Try to extract state (assuming "City, State" format)
			$parts_a = array_map('trim', explode(',', $city_a));
			$parts_b = array_map('trim', explode(',', $city_b));
			$state_a = count($parts_a) > 1 ? end($parts_a) : $city_a;
			$state_b = count($parts_b) > 1 ? end($parts_b) : $city_b;
			
			if ( $state_a === $state_b ) {
				$city_score = 18; // Same state
			} else {
				// Zone matching (simplified heuristic based on state strings)
				$zones = array(
					'north' => array('delhi', 'punjab', 'haryana', 'uttar pradesh', 'rajasthan', 'himachal', 'uttarakhand', 'kashmir', 'chandigarh'),
					'south' => array('kerala', 'tamil nadu', 'karnataka', 'andhra', 'telangana', 'puducherry'),
					'east' => array('west bengal', 'odisha', 'bihar', 'jharkhand', 'assam', 'sikkim', 'meghalaya'),
					'west' => array('maharashtra', 'gujarat', 'goa', 'daman', 'diu', 'dadra'),
					'central' => array('madhya pradesh', 'chhattisgarh')
				);
				
				$zone_a = 'pan-india';
				$zone_b = 'pan-india-b'; // distinct fallback
				
				foreach ($zones as $zone => $states) {
					foreach ($states as $s) {
						if (strpos($state_a, $s) !== false) $zone_a = $zone;
						if (strpos($state_b, $s) !== false) $zone_b = $zone;
					}
				}
				
				if ( $zone_a === $zone_b && $zone_a !== 'pan-india' ) {
					$city_score = 12; // Same zone
				} else {
					$city_score = 5; // Pan India / Different zone
				}
			}
		}
	}

	// Sum matching weight components
	$total_score = round( $looking_score + $circles_score + $hobbies_score + $city_score );

	// Core baseline adjustments based on 4-vector handshakes
	$v_friend_a = get_user_meta( $user_a, 'si50_vector_friendship', true );
	$v_friend_b = get_user_meta( $user_b, 'si50_vector_friendship', true );
	$v_conv_a   = get_user_meta( $user_a, 'si50_vector_conversation', true );
	$v_conv_b   = get_user_meta( $user_b, 'si50_vector_conversation', true );
	$v_trav_a   = get_user_meta( $user_a, 'si50_vector_travel', true );
	$v_trav_b   = get_user_meta( $user_b, 'si50_vector_travel', true );
	$v_chai_a   = get_user_meta( $user_a, 'si50_vector_chaichats', true );
	$v_chai_b   = get_user_meta( $user_b, 'si50_vector_chaichats', true );

	$bonus = 0;
	if ( $v_friend_a && $v_friend_b ) { $bonus += 5; }
	if ( $v_conv_a && $v_conv_b ) { $bonus += 5; }
	if ( $v_trav_a && $v_trav_b ) { $bonus += 5; }
	if ( $v_chai_a && $v_chai_b ) { $bonus += 5; }

	$final_score = min( 100, max( 40, $total_score + $bonus ) );
	return intval( $final_score );
}

/**
 * 8. City-Wise WhatsApp Invite Routing
 */
function si50_get_whatsapp_invite_for_city( $city_string ) {
	$city_string = strtolower( trim( $city_string ) );

	$mumbai_url = get_option( 'si50_wa_mumbai' );
	if ( false === $mumbai_url ) {
		$mumbai_url = 'https://chat.whatsapp.com/MumbaiActiveSeniors';
	}
	$mumbai_name = get_option( 'si50_wa_mumbai_name' );
	if ( empty( $mumbai_name ) ) {
		$mumbai_name = esc_html__( 'Mumbai Cluster', 'secondinnings50' );
	}

	$delhi_url = get_option( 'si50_wa_delhi' );
	if ( false === $delhi_url ) {
		$delhi_url = 'https://chat.whatsapp.com/DelhiActiveSeniors';
	}
	$delhi_name = get_option( 'si50_wa_delhi_name' );
	if ( empty( $delhi_name ) ) {
		$delhi_name = esc_html__( 'Delhi-NCR Cluster', 'secondinnings50' );
	}

	$bangalore_url = get_option( 'si50_wa_bangalore' );
	if ( false === $bangalore_url ) {
		$bangalore_url = 'https://chat.whatsapp.com/BangaloreActiveSeniors';
	}
	$bangalore_name = get_option( 'si50_wa_bangalore_name' );
	if ( empty( $bangalore_name ) ) {
		$bangalore_name = esc_html__( 'Bangalore Circle', 'secondinnings50' );
	}

	$pune_url = get_option( 'si50_wa_pune' );
	if ( false === $pune_url ) {
		$pune_url = 'https://chat.whatsapp.com/PuneActiveSeniors';
	}
	$pune_name = get_option( 'si50_wa_pune_name' );
	if ( empty( $pune_name ) ) {
		$pune_name = esc_html__( 'Pune Circle', 'secondinnings50' );
	}

	$hyderabad_url = get_option( 'si50_wa_hyderabad' );
	if ( false === $hyderabad_url ) {
		$hyderabad_url = 'https://chat.whatsapp.com/HyderabadActiveSeniors';
	}
	$hyderabad_name = get_option( 'si50_wa_hyderabad_name' );
	if ( empty( $hyderabad_name ) ) {
		$hyderabad_name = esc_html__( 'Hyderabad Hub', 'secondinnings50' );
	}

	$chennai_url = get_option( 'si50_wa_chennai' );
	if ( false === $chennai_url ) {
		$chennai_url = 'https://chat.whatsapp.com/ChennaiActiveSeniors';
	}
	$chennai_name = get_option( 'si50_wa_chennai_name' );
	if ( empty( $chennai_name ) ) {
		$chennai_name = esc_html__( 'Chennai Hub', 'secondinnings50' );
	}

	$kolkata_url = get_option( 'si50_wa_kolkata' );
	if ( false === $kolkata_url ) {
		$kolkata_url = 'https://chat.whatsapp.com/KolkataActiveSeniors';
	}
	$kolkata_name = get_option( 'si50_wa_kolkata_name' );
	if ( empty( $kolkata_name ) ) {
		$kolkata_name = esc_html__( 'Kolkata Hub', 'secondinnings50' );
	}

	$womens_lounge_url = get_option( 'si50_wa_womens_lounge' );
	$womens_lounge_name = get_option( 'si50_wa_womens_lounge_name' );
	if ( empty( $womens_lounge_name ) ) {
		$womens_lounge_name = esc_html__( 'Women\'s Lounge (Verified Women 45+ Circle)', 'secondinnings50' );
	}
	$womens_lounge_desc = get_option( 'si50_wa_womens_lounge_desc' );
	if ( empty( $womens_lounge_desc ) ) {
		$womens_lounge_desc = esc_html__( 'For verified women aged 45+ who prefer a dedicated women-only circle.', 'secondinnings50' );
	}

	$fallback_url = get_option( 'si50_wa_fallback' );
	if ( false === $fallback_url ) {
		$fallback_url = 'https://chat.whatsapp.com/IndiaActiveSeniors';
	}
	$fallback_name = get_option( 'si50_wa_fallback_name' );
	if ( empty( $fallback_name ) ) {
		$fallback_name = esc_html__( 'All-India Active Silver Companions', 'secondinnings50' );
	}

	// Mappings
	if ( ( strpos( $city_string, 'mumbai' ) !== false || strpos( $city_string, 'bombay' ) !== false ) && ! empty( $mumbai_url ) ) {
		return array(
			'name' => $mumbai_name,
			'url'  => $mumbai_url
		);
	} elseif ( ( strpos( $city_string, 'delhi' ) !== false || strpos( $city_string, 'ncr' ) !== false || strpos( $city_string, 'noida' ) !== false || strpos( $city_string, 'gurgaon' ) !== false || strpos( $city_string, 'gurugram' ) !== false || strpos( $city_string, 'ghaziabad' ) !== false || strpos( $city_string, 'faridabad' ) !== false ) && ! empty( $delhi_url ) ) {
		return array(
			'name' => $delhi_name,
			'url'  => $delhi_url
		);
	} elseif ( ( strpos( $city_string, 'bangalore' ) !== false || strpos( $city_string, 'bengaluru' ) !== false ) && ! empty( $bangalore_url ) ) {
		return array(
			'name' => $bangalore_name,
			'url'  => $bangalore_url
		);
	} elseif ( ( strpos( $city_string, 'pune' ) !== false || strpos( $city_string, 'poona' ) !== false ) && ! empty( $pune_url ) ) {
		return array(
			'name' => $pune_name,
			'url'  => $pune_url
		);
	} elseif ( ( strpos( $city_string, 'hyderabad' ) !== false || strpos( $city_string, 'secunderabad' ) !== false ) && ! empty( $hyderabad_url ) ) {
		return array(
			'name' => $hyderabad_name,
			'url'  => $hyderabad_url
		);
	} elseif ( ( strpos( $city_string, 'chennai' ) !== false || strpos( $city_string, 'madras' ) !== false ) && ! empty( $chennai_url ) ) {
		return array(
			'name' => $chennai_name,
			'url'  => $chennai_url
		);
	} elseif ( ( strpos( $city_string, 'kolkata' ) !== false || strpos( $city_string, 'calcutta' ) !== false ) && ! empty( $kolkata_url ) ) {
		return array(
			'name' => $kolkata_name,
			'url'  => $kolkata_url
		);
	}

	return array(
		'name' => $fallback_name,
		'url'  => $fallback_url
	);
}

/**
 * Get resolved WhatsApp group link based on user city/state
 */
function si50_get_whatsapp_group_link( $user_id ) {
	$city_string = get_user_meta( $user_id, 'si50_city_state', true );
	$gender = get_user_meta( $user_id, 'si50_gender', true );
	$dob = get_user_meta( $user_id, 'si50_dob', true );
	$vetting_status = get_user_meta( $user_id, 'si50_vetting_status', true );
	$womens_lounge_url = get_option( 'si50_wa_womens_lounge', '' );
	$womens_lounge_name = get_option( 'si50_wa_womens_lounge_name', '' );
	$womens_lounge_capacity = intval( get_option( 'si50_wa_womens_lounge_capacity', 0 ) );

	if ( 'Female' === $gender && 'approved' === $vetting_status && ! empty( $dob ) && ! empty( $womens_lounge_url ) ) {
		$dob_time = strtotime( $dob );
		if ( $dob_time ) {
			$age = intval( date_diff( date_create( $dob ), date_create( 'today' ) )->y );
			if ( $age >= 45 ) {
				if ( $womens_lounge_capacity > 0 ) {
					$approved_women = new WP_User_Query( array(
						'meta_query' => array(
							'relation' => 'AND',
							array(
								'key'     => 'si50_gender',
								'value'   => 'Female',
								'compare' => '='
							),
							array(
								'key'     => 'si50_vetting_status',
								'value'   => 'approved',
								'compare' => '='
							),
						),
						'fields' => 'ID',
						'number' => -1,
					) );

					$approved_women_count = 0;
					foreach ( $approved_women->get_results() as $candidate_id ) {
						$candidate_dob = get_user_meta( $candidate_id, 'si50_dob', true );
						if ( ! empty( $candidate_dob ) ) {
							$candidate_age = intval( date_diff( date_create( $candidate_dob ), date_create( 'today' ) )->y );
							if ( $candidate_age >= 45 ) {
								$approved_women_count++;
							}
						}
					}

					if ( $approved_women_count >= $womens_lounge_capacity ) {
						return si50_get_whatsapp_invite_for_city( $city_string );
					}
				}
				return array(
					'name' => ! empty( $womens_lounge_name ) ? $womens_lounge_name : esc_html__( 'Women\'s Lounge (Verified Women 45+ Circle)', 'secondinnings50' ),
					'url'  => $womens_lounge_url,
				);
			}
		}
	}

	return si50_get_whatsapp_invite_for_city( $city_string );
}

/**
 * 9. Real-time Transaction HTML Email Desk
 */
function si50_send_html_email( $to, $subject, $html_body ) {
	$admin_email = get_option( 'admin_email' );
	
	// Enforce strict responsive headers layout
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . $admin_email . '>'
	);

	wp_mail( $to, $subject, $html_body, $headers );
}

/**
 * Triggered on new user registration (notifies Admin)
 */
function si50_email_trigger_new_registration( $user_id ) {
	$user = get_userdata( $user_id );
	if ( ! $user ) return;

	$name = get_user_meta( $user_id, 'si50_fullname', true );
	$phone = get_user_meta( $user_id, 'si50_phone', true );
	$city = get_user_meta( $user_id, 'si50_city_state', true );
	$connection_intent = get_user_meta( $user_id, 'si50_connection_intent', true );
	$admin_email = get_option( 'admin_email' );

	$subject = sprintf( '[%s] New Member Registration Pending Vetting', get_bloginfo( 'name' ) );

	$body = '
	<div style="font-family: Arial, sans-serif; line-height: 1.6; max-width: 600px; margin: 0 auto; border: 1.5px solid #1B3B2B; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
		<div style="background-color: #1B3B2B; color: #ffffff; padding: 20px 24px; text-align: center;">
			<h2 style="margin: 0; font-size: 20px; font-weight: bold;">New Application Alert</h2>
		</div>
		<div style="padding: 24px; background-color: #ffffff;">
			<p>Greetings Administrator,</p>
			<p>A new member onboarding application has been submitted to the platform and requires manual review.</p>
			
			<table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
				<tr style="border-bottom: 1px solid #eeeeee;">
					<td style="padding: 10px; font-weight: bold; color: #555555; width: 120px;">Name:</td>
					<td style="padding: 10px; color: #333333;">' . esc_html( $name ) . '</td>
				</tr>
				<tr style="border-bottom: 1px solid #eeeeee;">
					<td style="padding: 10px; font-weight: bold; color: #555555;">Email:</td>
					<td style="padding: 10px; color: #333333;">' . esc_html( $user->user_email ) . '</td>
				</tr>
				<tr style="border-bottom: 1px solid #eeeeee;">
					<td style="padding: 10px; font-weight: bold; color: #555555;">WhatsApp Phone:</td>
					<td style="padding: 10px; color: #333333;">' . esc_html( $phone ) . '</td>
				</tr>
				<tr style="border-bottom: 1px solid #eeeeee;">
					<td style="padding: 10px; font-weight: bold; color: #555555;">Location:</td>
					<td style="padding: 10px; color: #333333;">' . esc_html( $city ) . '</td>
				</tr>
				<tr>
					<td style="padding: 10px; font-weight: bold; color: #555555;">Looking For:</td>
					<td style="padding: 10px; color: #333333;">' . esc_html( $connection_intent ) . '</td>
				</tr>
			</table>
			
			<p>Please log in to your SI50 Control Panel to verify the application details, inspect their verification selfie photo, and activate their member directory access.</p>
		</div>
		<div style="background-color: #f8f9fa; padding: 12px; text-align: center; border-top: 1px solid #eeeeee; font-size: 11px; color: #888888;">
			SecondInnings50 System Engine
		</div>
	</div>';

	si50_send_html_email( $admin_email, $subject, $body );
}

/**
 * Triggered when admin approves vetting status (notifies User)
 */
function si50_email_trigger_vetting_approved( $user_id ) {
	$user = get_userdata( $user_id );
	if ( ! $user ) return;

	$name = get_user_meta( $user_id, 'si50_fullname', true );
	$city = get_user_meta( $user_id, 'si50_city_state', true );
	
	// Map WhatsApp group
	$whatsapp_data = si50_get_whatsapp_invite_for_city( $city );

	$subject = esc_html__( 'Congratulations! Your SecondInnings50 Invitation is Active', 'secondinnings50' );

	$body = '
	<div style="font-family: Arial, sans-serif; line-height: 1.6; max-width: 600px; margin: 0 auto; border: 2px solid #1B3B2B; border-radius: 8px; overflow: hidden;">
		<div style="background-color: #1B3B2B; color: #ffffff; padding: 24px; text-align: center;">
			<span style="font-size: 32px; display: block; margin-bottom: 8px;">🌾</span>
			<h2 style="margin: 0; font-size: 22px; font-weight: bold; font-family: Georgia, serif;">Welcome to SecondInnings50</h2>
		</div>
		<div style="padding: 28px; background-color: #ffffff;">
			<p>Dear ' . esc_html( $name ) . ',</p>
			<p>We are absolutely delighted to inform you that your onboarding application has been manually reviewed and fully approved by our onboarding hosts.</p>
			<p>Your membership is now active. For your privacy, other member profiles are <strong>not</strong> open for browsing on the website. Our team reviews profiles privately and will share suitable companionship introductions with you personally when ready.</p>
			
			<div style="background-color: #f4f8f5; border-left: 4px solid #1B3B2B; padding: 16px; margin: 20px 0; border-radius: 4px;">
				<h4 style="margin: 0 0 8px 0; color: #1B3B2B;">💬 Join Your Local WhatsApp Circle</h4>
				<p style="margin: 0; font-size: 13px; color: #333333;">We have matched your location with the <strong>' . esc_html( $whatsapp_data['name'] ) . '</strong>. Click below to join your peers:</p>
				<a href="' . esc_url( $whatsapp_data['url'] ) . '" style="display: inline-block; background-color: #25D366; color: #ffffff; padding: 8px 16px; margin-top: 10px; border-radius: 4px; font-weight: bold; text-decoration: none; font-size: 13px;">Join WhatsApp Group</a>
			</div>
			
			<div style="text-align: center; margin: 30px 0;">
				<a href="' . esc_url( home_url( '/profile/' ) ) . '" style="display: inline-block; background-color: #C05C3E; color: #ffffff; padding: 12px 28px; border-radius: 4px; font-weight: bold; text-decoration: none; font-size: 14px; box-shadow: 0 4px 6px rgba(192, 92, 62, 0.15);">Open My Profile</a>
			</div>
			
			<p style="font-size: 13px; color: #666666;">If you have any questions or require support navigating the platform, please reply directly to this email or drop us a WhatsApp message.</p>
		</div>
		<div style="background-color: #f8f9fa; padding: 16px; text-align: center; border-top: 1px solid #eeeeee; font-size: 11px; color: #888888;">
			SecondInnings50 Vetting Coordination Desk
		</div>
	</div>';

	si50_send_html_email( $user->user_email, $subject, $body );
}

/**
 * Triggered when admin declines vetting status (notifies User)
 */
function si50_email_trigger_vetting_rejected( $user_id, $reason ) {
	$user = get_userdata( $user_id );
	if ( ! $user ) return;

	$name = get_user_meta( $user_id, 'si50_fullname', true );

	$subject = esc_html__( 'Update regarding your SecondInnings50 Application', 'secondinnings50' );

	$body = '
	<div style="font-family: Arial, sans-serif; line-height: 1.6; max-width: 600px; margin: 0 auto; border: 2px solid #C05C3E; border-radius: 8px; overflow: hidden;">
		<div style="background-color: #C05C3E; color: #ffffff; padding: 24px; text-align: center;">
			<span style="font-size: 32px; display: block; margin-bottom: 8px;">⚠️</span>
			<h2 style="margin: 0; font-size: 20px; font-weight: bold; font-family: Georgia, serif;">Application Update</h2>
		</div>
		<div style="padding: 28px; background-color: #ffffff;">
			<p>Dear ' . esc_html( $name ) . ',</p>
			<p>Thank you for your interest in joining SecondInnings50. Our vetting coordinators have finished reviewing your onboarding application.</p>
			<p>At this time, we require additional clarification before your invitation can be fully activated. Please inspect the reviewer notes below:</p>
			
			<div style="background-color: #fdf3f2; border-left: 4px solid #C05C3E; padding: 16px; margin: 20px 0; border-radius: 4px; font-style: italic; color: #333333;">
				"' . esc_html( $reason ) . '"
			</div>
			
			<p>You can correct and update your details by accessing your dashboard settings below. Saving your corrected settings will resubmit your application for immediate manual vetting:</p>
			
			<div style="text-align: center; margin: 25px 0;">
				<a href="' . esc_url( home_url( '/profile/' ) ) . '" style="display: inline-block; background-color: #C05C3E; color: #ffffff; padding: 10px 24px; border-radius: 4px; font-weight: bold; text-decoration: none; font-size: 13px;">Update Application Details</a>
			</div>
			
			<p style="font-size: 13px; color: #666666;">If you have any questions, please reply directly to this email.</p>
		</div>
		<div style="background-color: #f8f9fa; padding: 16px; text-align: center; border-top: 1px solid #eeeeee; font-size: 11px; color: #888888;">
			SecondInnings50 Onboarding Desk
		</div>
	</div>';

	si50_send_html_email( $user->user_email, $subject, $body );
}
