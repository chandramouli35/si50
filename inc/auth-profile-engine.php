<?php
/**
 * SecondInnings50 - Custom Authentication & Profile Engine
 * Handles AJAX Login, AJAX Registration, User Meta Storage, Connection requests, and Vetting checks.
 *
 * @package SecondInnings50
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * 1. AJAX Registration Handler
 */
function si50_ajax_register() {
	// check_ajax_referer( 'si50_auth_nonce', 'security' ); // Disabled for public cached pages

	$name            = isset( $_POST['fullname'] ) ? sanitize_text_field( $_POST['fullname'] ) : '';
	$dob             = isset( $_POST['dob'] ) ? sanitize_text_field( $_POST['dob'] ) : '';
	$age_bracket     = isset( $_POST['age_bracket'] ) ? sanitize_text_field( $_POST['age_bracket'] ) : '';
	$marital_status  = isset( $_POST['marital_status'] ) ? sanitize_text_field( $_POST['marital_status'] ) : '';
	$occupation      = isset( $_POST['occupation'] ) ? sanitize_text_field( $_POST['occupation'] ) : '';
	$gender          = isset( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
	$email           = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	$phone           = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	$emergency_phone = isset( $_POST['emergency_phone'] ) ? sanitize_text_field( $_POST['emergency_phone'] ) : '';
	$city_state      = isset( $_POST['city_state'] ) ? sanitize_text_field( $_POST['city_state'] ) : '';
	$password        = isset( $_POST['password'] ) ? $_POST['password'] : '';
	$intro           = isset( $_POST['introduction'] ) ? sanitize_textarea_field( $_POST['introduction'] ) : '';
	$connection_intent = isset( $_POST['connection_intent'] ) ? sanitize_textarea_field( $_POST['connection_intent'] ) : '';

	// Companion lists and circles checkboxes
	$looking_for      = isset( $_POST['looking_for'] ) ? (array) $_POST['looking_for'] : array();
	$circles_interest = isset( $_POST['circles_interest'] ) ? (array) $_POST['circles_interest'] : array();
	$travel_dest      = isset( $_POST['travel_destinations'] ) ? (array) $_POST['travel_destinations'] : array();
	$travel_styles    = isset( $_POST['travel_styles'] ) ? (array) $_POST['travel_styles'] : array();
	$companion_styles = isset( $_POST['companion_styles'] ) ? sanitize_text_field( $_POST['companion_styles'] ) : '';
	$location_preference = isset( $_POST['location_preference'] ) ? sanitize_text_field( $_POST['location_preference'] ) : '';
	$hobbies_interests   = isset( $_POST['hobbies_interests'] ) ? (array) $_POST['hobbies_interests'] : array();
	$travel_dest_other   = isset( $_POST['travel_destinations_other'] ) ? sanitize_text_field( $_POST['travel_destinations_other'] ) : '';
	$special_incentives  = isset( $_POST['special_incentives'] ) ? (array) $_POST['special_incentives'] : array();
	$payment_mode        = isset( $_POST['payment_mode'] ) ? sanitize_text_field( $_POST['payment_mode'] ) : 'PayU';

	// Emergency contact removed for now
	if ( empty( $name ) || empty( $email ) || empty( $phone ) || empty( $password ) || empty( $gender ) || empty( $city_state ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please fill out all required fields.', 'secondinnings50' ) ) );
	}

	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please enter a valid email address.', 'secondinnings50' ) ) );
	}

	if ( email_exists( $email ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'This email address is already registered.', 'secondinnings50' ) ) );
	}

	if ( strlen( $password ) < 6 ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Password must be at least 6 characters long.', 'secondinnings50' ) ) );
	}

	// Generate unique username from email
	$username = sanitize_user( current( explode( '@', $email ) ) );
	if ( username_exists( $username ) || empty( $username ) ) {
		$username = $username . '_' . rand( 100, 999 );
	}

	// Create WordPress User
	$user_id = wp_create_user( $username, $password, $email );

	if ( is_wp_error( $user_id ) ) {
		wp_send_json_error( array( 'message' => $user_id->get_error_message() ) );
	}

	// Update display names
	wp_update_user( array(
		'ID'           => $user_id,
		'display_name' => $name,
		'first_name'   => $name
	) );

	// Store meta data
	update_user_meta( $user_id, 'si50_fullname', $name );
	update_user_meta( $user_id, 'si50_dob', $dob );
	update_user_meta( $user_id, 'si50_age_bracket', $age_bracket );
	update_user_meta( $user_id, 'si50_marital_status', $marital_status );
	update_user_meta( $user_id, 'si50_occupation', $occupation );
	update_user_meta( $user_id, 'si50_gender', $gender );
	update_user_meta( $user_id, 'si50_phone', $phone );
	update_user_meta( $user_id, 'si50_emergency_phone', $emergency_phone );
	update_user_meta( $user_id, 'si50_city_state', $city_state );
	update_user_meta( $user_id, 'si50_introduction', $intro );
	update_user_meta( $user_id, 'si50_connection_intent', $connection_intent );
	update_user_meta( $user_id, 'si50_location_preference', $location_preference );
	
	if ( 'Female' === $gender ) {
		// Launch Strategy: Check if within the first 100 female applications to assign premium status automatically
		$female_users = get_users( array(
			'role__not_in' => array( 'administrator' ),
			'meta_key'     => 'si50_gender',
			'meta_value'   => 'Female',
			'fields'       => 'ID'
		) );
		$female_count = count( $female_users );
		if ( $female_count < 100 ) {
			update_user_meta( $user_id, 'si50_membership_status', 'premium' );
		} else {
			update_user_meta( $user_id, 'si50_membership_status', 'pending' );
		}
	} else {
		update_user_meta( $user_id, 'si50_membership_status', 'pending' );
	}

	// Initialize membership status tags for the new user
	update_user_meta( $user_id, 'si50_member_status_tags', array() );

	// Process mandatory selfie uploader verification photo
	if ( ! empty( $_FILES['verification_selfie']['name'] ) ) {
		$selfie_result = si50_secure_upload_selfie( 'verification_selfie', $user_id );
		if ( is_wp_error( $selfie_result ) ) {
			wp_delete_user( $user_id );
			wp_send_json_error( array( 'message' => $selfie_result->get_error_message() ) );
		}
	} else {
		wp_delete_user( $user_id );
		wp_send_json_error( array( 'message' => esc_html__( 'Please upload a verification selfie photo to complete onboarding.', 'secondinnings50' ) ) );
	}

	// Process voice_intro upload
	if ( ! empty( $_FILES['voice_intro']['name'] ) ) {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		$uploadedfile = $_FILES['voice_intro'];
		$upload_overrides = array( 'test_form' => false );
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

		if ( $movefile && ! isset( $movefile['error'] ) ) {
			update_user_meta( $user_id, 'si50_voice_intro', $movefile['url'] );
		}
	}

	// Serialized lists
	update_user_meta( $user_id, 'si50_looking_for', array_map( 'sanitize_text_field', $looking_for ) );
	update_user_meta( $user_id, 'si50_circles_interest', array_map( 'sanitize_text_field', $circles_interest ) );
	update_user_meta( $user_id, 'si50_travel_destinations', array_map( 'sanitize_text_field', $travel_dest ) );
	update_user_meta( $user_id, 'si50_travel_styles', array_map( 'sanitize_text_field', $travel_styles ) );
	update_user_meta( $user_id, 'si50_companion_styles', $companion_styles );
	update_user_meta( $user_id, 'si50_hobbies_interests', array_map( 'sanitize_text_field', $hobbies_interests ) );
	update_user_meta( $user_id, 'si50_travel_destinations_other', $travel_dest_other );
	update_user_meta( $user_id, 'si50_special_incentives', array_map( 'sanitize_text_field', $special_incentives ) );
	
	// Checkboxes for consent and whatsapp opt-in
	$consent_terms    = isset( $_POST['consent_terms'] ) ? 'yes' : 'no';
	$consent_whatsapp = isset( $_POST['consent_whatsapp'] ) ? 'yes' : 'no';
	update_user_meta( $user_id, 'si50_consent_terms', $consent_terms );
	update_user_meta( $user_id, 'si50_consent_whatsapp', $consent_whatsapp );

	// Explicit 4-Vector Interest Sync parameters
	$v_friendship   = ( in_array( 'Friendship', $looking_for ) || in_array( 'Social Friendship Circles', $looking_for ) ) ? 'Social Friendship Circles' : '';
	$v_conversation = ( in_array( 'Conversation Companion', $looking_for ) || in_array( 'Conversation/Single Companionship', $looking_for ) || in_array( 'Long-Term Companionship', $looking_for ) ) ? 'Conversation/Single Companionship' : '';
	$v_travel       = ( in_array( 'Activity Companion', $looking_for ) || in_array( 'Activity Meetup Interest', $circles_interest ) || in_array( 'Local Meetup Interest', $circles_interest ) ) ? 'Activity Meetups' : '';
	$v_chaichats    = ( in_array( 'WhatsApp Group Interest', $circles_interest ) || in_array( 'Virtual Chai Chats Interest', $circles_interest ) ) ? 'Virtual Chai Chats Interest' : '';

	update_user_meta( $user_id, 'si50_vector_friendship', $v_friendship );
	update_user_meta( $user_id, 'si50_vector_conversation', $v_conversation );
	update_user_meta( $user_id, 'si50_vector_travel', $v_travel );
	update_user_meta( $user_id, 'si50_vector_chaichats', $v_chaichats );

	if ( 'Male' === $gender && 'PayU' === $payment_mode ) {
		// Force pending payment status
		update_user_meta( $user_id, 'si50_vetting_status', 'pending_payment' );

		// Generate PayU params
		$creds = si50_get_payu_credentials();
		$txnid = 'si50_tx_' . $user_id . '_' . time();
		$amount = '699.00';
		$productinfo = 'SecondInnings50 One-Time Membership';
		$surl = home_url( '/?si50_action=payu_callback' );
		$furl = home_url( '/?si50_action=payu_callback' );
		
		$hash_string = $creds['key'] . '|' . $txnid . '|' . $amount . '|' . $productinfo . '|' . $name . '|' . $email . '|||||||||||' . $creds['salt'];
		$hash = strtolower( hash( 'sha512', $hash_string ) );
		
		$payu_url = rtrim( $creds['base_url'], '/' ) . '/_payment';

		wp_send_json_success( array(
			'message'      => esc_html__( 'Initiating secure payment...', 'secondinnings50' ),
			'payu'         => true,
			'payu_url'     => $payu_url,
			'payu_params'  => array(
				'key'         => $creds['key'],
				'txnid'       => $txnid,
				'amount'      => $amount,
				'productinfo' => $productinfo,
				'firstname'   => $name,
				'email'       => $email,
				'phone'       => $phone,
				'surl'        => $surl,
				'furl'        => $furl,
				'hash'        => $hash
			)
		) );
	} else {
		// Female users or Male users who paid via QR Code
		update_user_meta( $user_id, 'si50_vetting_status', 'pending_review' );

		// If Male and QR Code, handle screenshot upload
		if ( 'Male' === $gender && 'QR_Code' === $payment_mode && ! empty( $_FILES['payment_screenshot']['name'] ) ) {
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			$uploadedfile = $_FILES['payment_screenshot'];
			$upload_overrides = array( 'test_form' => false );
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

			if ( $movefile && ! isset( $movefile['error'] ) ) {
				update_user_meta( $user_id, 'si50_payment_screenshot', $movefile['url'] );
			}
			update_user_meta( $user_id, 'si50_payment_mode', 'QR Code (Manual)' );
		}

		// Send Admin WhatsApp Alert Webhook
		$admin_users = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
		$admin_phone = '';
		if ( ! empty( $admin_users ) ) {
			$admin_phone = get_user_meta( $admin_users[0]->ID, 'si50_phone', true );
		}
		if ( empty( $admin_phone ) ) {
			$admin_phone = get_option( 'si50_admin_whatsapp_number', '' );
		}
		if ( ! empty( $admin_phone ) ) {
			$admin_msg = sprintf(
				esc_html__( "🔔 New Application Received! %s from %s has just submitted their profile. Please access your SI50 Control Panel to review their travel preferences and interest checkboxes.", 'secondinnings50' ),
				$name,
				$city_state
			);
			if ( 'Male' === $gender && 'QR_Code' === $payment_mode ) {
				$admin_msg .= ' Note: Member opted for QR Code payment and uploaded a screenshot. Please verify.';
			}
			si50_send_whatsapp_alert( $admin_phone, $admin_msg );
		}

		// Automatically authenticate the new applicant
		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id );

		// Send HTML notification email to site administrator
		si50_email_trigger_new_registration( $user_id );
		
		error_log( sprintf( 'SecondInnings50 [New registration account created] ID: %d, Name: %s, Vetting: pending_review', $user_id, $name ) );

		wp_send_json_success( array(
			'message'      => esc_html__( 'Registration successful. Redirecting to your dashboard...', 'secondinnings50' ),
			'redirect'     => home_url( '/application-received/' ),
			'redirect_url' => home_url( '/application-received/' )
		) );
	}
}
add_action( 'wp_ajax_si50_ajax_register', 'si50_ajax_register' );
add_action( 'wp_ajax_nopriv_si50_ajax_register', 'si50_ajax_register' );

/**
 * 2. AJAX Login Handler
 */
function si50_ajax_login() {
	// check_ajax_referer( 'si50_auth_nonce', 'security' ); // Disabled for public cached pages

	$username = isset( $_POST['username'] ) ? sanitize_text_field( $_POST['username'] ) : '';
	$password = isset( $_POST['password'] ) ? $_POST['password'] : '';

	if ( empty( $username ) || empty( $password ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please enter both credentials.', 'secondinnings50' ) ) );
	}

	// Try resolving email to username if email was provided
	if ( is_email( $username ) ) {
		$user = get_user_by( 'email', $username );
		if ( $user ) {
			$username = $user->user_login;
		}
	}

	$creds = array(
		'user_login'    => $username,
		'user_password' => $password,
		'remember'      => true
	);

	$user = wp_signon( $creds, false );

	if ( is_wp_error( $user ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid email/username or password.', 'secondinnings50' ) ) );
	}

	wp_send_json_success( array(
		'message'  => esc_html__( 'Login successful! Redirecting...', 'secondinnings50' ),
		'redirect' => home_url( '/directory/' )
	) );
}
add_action( 'wp_ajax_si50_ajax_login', 'si50_ajax_login' );
add_action( 'wp_ajax_nopriv_si50_ajax_login', 'si50_ajax_login' );

/**
 * 3. AJAX Submit Interest Handler (Admin Curated)
 */
function si50_ajax_submit_interest() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please log in to express interest.', 'secondinnings50' ) ) );
	}

	$sender_id   = get_current_user_id();
	$receiver_id = isset( $_POST['receiver_id'] ) ? intval( $_POST['receiver_id'] ) : 0;

	// Only approved vetting users can submit interest
	$status = get_user_meta( $sender_id, 'si50_vetting_status', true );
	if ( 'approved' !== $status ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Your profile review is pending. Interest submissions locked.', 'secondinnings50' ) ) );
	}

	if ( ! $receiver_id || $sender_id === $receiver_id ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Invalid profile choice.', 'secondinnings50' ) ) );
	}

	global $wpdb;
	$table_name = $wpdb->prefix . 'si50_connect_requests';

	// Check if request already exists
	$existing = $wpdb->get_row( $wpdb->prepare(
		"SELECT * FROM $table_name WHERE sender_id = %d AND receiver_id = %d ORDER BY timestamp DESC LIMIT 1",
		$sender_id, $receiver_id
	) );

	if ( $existing ) {
		wp_send_json_error( array( 'message' => esc_html__( 'You have already submitted an interest request for this profile. The admin team is reviewing it.', 'secondinnings50' ) ) );
	}

	// Insert Request Row as admin_review
	$result = $wpdb->insert(
		$table_name,
		array(
			'sender_id'   => $sender_id,
			'receiver_id' => $receiver_id,
			'status'      => 'admin_review',
			'timestamp'   => current_time( 'mysql' )
		),
		array( '%d', '%d', '%s', '%s' )
	);

	if ( false === $result ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Failed to execute database record.', 'secondinnings50' ) ) );
	}

	wp_send_json_success( array( 'message' => esc_html__( 'Interest submitted to Admin Team! We will review and contact both of you soon.', 'secondinnings50' ) ) );
}
add_action( 'wp_ajax_si50_submit_interest', 'si50_ajax_submit_interest' );
add_action( 'wp_ajax_nopriv_si50_submit_interest', 'si50_ajax_submit_interest' );

/**
 * 5. Render global login modal inside wp_footer.
 * Complies with strict contrast requirement (White Background #FFFFFF, Dark Border #333333).
 */
function si50_render_login_modal() {
	if ( is_user_logged_in() ) {
		return;
	}
	?>
	<div class="si50-login-backdrop" id="login-modal-backdrop" aria-hidden="true" role="dialog">
		<div class="si50-login-modal" style="background-color: #FFFFFF !important; border: 2px solid #333333 !important; border-radius: var(--radius-lg); padding: var(--spacing-lg); max-width: 420px; width: 90%; position: relative;">
			<button class="si50-login-close" id="close-login-modal" aria-label="<?php esc_attr_e( 'Close login modal', 'secondinnings50' ); ?>" style="position: absolute; right: 15px; top: 12px; background: none; border: none; font-size: 1.75rem; cursor: pointer; color: var(--color-charcoal);">&times;</button>
			<div class="si50-login-header" style="text-align: center; margin-bottom: 20px;">
				<h2 class="si50-login-title" style="font-family: var(--font-serif); font-weight: 700; color: var(--color-forest); margin-bottom: 6px; font-size: var(--fs-xl);"><?php esc_html_e( 'Log In to SecondInnings50', 'secondinnings50' ); ?></h2>
				<p class="si50-login-desc" style="font-size: var(--fs-xs); color: var(--color-charcoal-muted);"><?php esc_html_e( 'Reconnect with your companions, friends, and circles.', 'secondinnings50' ); ?></p>
			</div>
			
			<form id="si50-login-form" class="si50-modal-form">
				<div class="form-group" style="margin-bottom: 16px;">
					<label for="login-username" class="form-label" style="display: block; font-weight: 700; font-size: var(--fs-xs); margin-bottom: 6px; color: var(--color-charcoal);"><?php esc_html_e( 'Email or Username', 'secondinnings50' ); ?> <span class="required">*</span></label>
					<input type="text" id="login-username" name="username" class="form-control" required placeholder="<?php esc_attr_e( 'e.g. anand@email.com', 'secondinnings50' ); ?>" style="width: 100% !important; height: 48px !important; border: 1.5px solid #333333 !important; background-color: #FFFFFF !important; padding: 10px 16px !important; border-radius: var(--radius-md) !important; color: #333333 !important; font-weight: 600 !important;">
					<span class="error-msg" id="login-username-error" aria-live="polite" style="color: var(--color-terracotta); font-size: var(--fs-xs); display: block; margin-top: 4px;"></span>
				</div>
				<div class="form-group" style="margin-bottom: 20px;">
					<label for="login-password" class="form-label" style="display: block; font-weight: 700; font-size: var(--fs-xs); margin-bottom: 6px; color: var(--color-charcoal);"><?php esc_html_e( 'Password', 'secondinnings50' ); ?> <span class="required">*</span></label>
					<input type="password" id="login-password" name="password" class="form-control" required placeholder="<?php esc_attr_e( 'Enter your password', 'secondinnings50' ); ?>" style="width: 100% !important; height: 48px !important; border: 1.5px solid #333333 !important; background-color: #FFFFFF !important; padding: 10px 16px !important; border-radius: var(--radius-md) !important; color: #333333 !important; font-weight: 600 !important;">
					<span class="error-msg" id="login-password-error" aria-live="polite" style="color: var(--color-terracotta); font-size: var(--fs-xs); display: block; margin-top: 4px;"></span>
				</div>
				
				<div class="form-action">
					<button type="submit" class="btn btn-primary btn-block" style="width: 100%; height: 48px; border-radius: var(--radius-md); font-weight: 700; cursor: pointer;"><?php esc_html_e( 'Log In', 'secondinnings50' ); ?></button>
				</div>
				
				<div id="login-general-error" class="error-msg text-center" style="margin-top: 12px; color: var(--color-terracotta); font-size: var(--fs-xs); font-weight: 700; display: none; text-align: center;" aria-live="polite"></div>
			</form>
			
			<div class="si50-login-footer text-center" style="margin-top: 20px; font-size: var(--fs-xs); text-align: center; color: var(--color-charcoal-muted);">
				<p>
					<?php esc_html_e( 'New here?', 'secondinnings50' ); ?> 
					<a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" style="color: var(--color-terracotta); font-weight: 700; text-decoration: underline;">
						<?php esc_html_e( 'Apply for an Invitation', 'secondinnings50' ); ?>
					</a>
				</p>
			</div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'si50_render_login_modal' );

/**
 * 6. AJAX Profile Info Update Handler
 */
function si50_ajax_update_profile_info() {
	check_ajax_referer( 'si50_auth_nonce', 'security' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please log in to update your profile.', 'secondinnings50' ) ) );
	}

	$user_id = get_current_user_id();

	$name            = isset( $_POST['fullname'] ) ? sanitize_text_field( $_POST['fullname'] ) : '';
	$dob             = isset( $_POST['dob'] ) ? sanitize_text_field( $_POST['dob'] ) : '';
	$age_bracket     = isset( $_POST['age_bracket'] ) ? sanitize_text_field( $_POST['age_bracket'] ) : '';
	$marital_status  = isset( $_POST['marital_status'] ) ? sanitize_text_field( $_POST['marital_status'] ) : '';
	$occupation      = isset( $_POST['occupation'] ) ? sanitize_text_field( $_POST['occupation'] ) : '';
	$gender          = isset( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
	$phone           = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	$emergency_phone = isset( $_POST['emergency_phone'] ) ? sanitize_text_field( $_POST['emergency_phone'] ) : '';
	$city_state      = isset( $_POST['city_state'] ) ? sanitize_text_field( $_POST['city_state'] ) : '';
	$intro           = isset( $_POST['introduction'] ) ? sanitize_textarea_field( $_POST['introduction'] ) : '';
	$connection_intent = isset( $_POST['connection_intent'] ) ? sanitize_textarea_field( $_POST['connection_intent'] ) : '';
	$password        = isset( $_POST['password'] ) ? $_POST['password'] : '';
	$password_confirm = isset( $_POST['password_confirm'] ) ? $_POST['password_confirm'] : '';
	$location_preference = isset( $_POST['location_preference'] ) ? sanitize_text_field( $_POST['location_preference'] ) : '';
	$visibility      = isset( $_POST['profile_visibility'] ) ? sanitize_key( $_POST['profile_visibility'] ) : 'public';

	// Basic validation checks
	if ( empty( $name ) || empty( $phone ) || empty( $gender ) || empty( $city_state ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'All required fields must be filled out.', 'secondinnings50' ) ) );
	}

	if ( ! in_array( $visibility, array( 'public', 'connections', 'private' ), true ) ) {
		$visibility = 'public';
	}

	// Password validation if provided
	if ( ! empty( $password ) ) {
		if ( strlen( $password ) < 6 ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Password must be at least 6 characters long.', 'secondinnings50' ) ) );
		}
		if ( $password !== $password_confirm ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Passwords do not match.', 'secondinnings50' ) ) );
		}
	}

	// Update display names
	wp_update_user( array(
		'ID'           => $user_id,
		'display_name' => $name,
		'first_name'   => $name
	) );

	// Update password if requested
	if ( ! empty( $password ) ) {
		wp_update_user( array(
			'ID'        => $user_id,
			'user_pass' => $password
		) );
		// Since password changed, we re-auth to keep user logged in
		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id, true );
	}

	// Store meta data
	update_user_meta( $user_id, 'si50_fullname', $name );
	update_user_meta( $user_id, 'si50_dob', $dob );
	update_user_meta( $user_id, 'si50_age_bracket', $age_bracket );
	update_user_meta( $user_id, 'si50_marital_status', $marital_status );
	update_user_meta( $user_id, 'si50_occupation', $occupation );
	update_user_meta( $user_id, 'si50_gender', $gender );
	update_user_meta( $user_id, 'si50_phone', $phone );
	update_user_meta( $user_id, 'si50_emergency_phone', $emergency_phone );
	update_user_meta( $user_id, 'si50_city_state', $city_state );
	update_user_meta( $user_id, 'si50_introduction', $intro );
	update_user_meta( $user_id, 'si50_connection_intent', $connection_intent );
	update_user_meta( $user_id, 'si50_location_preference', $location_preference );
	update_user_meta( $user_id, 'si50_profile_visibility', $visibility );

	// Process voice_intro upload
	if ( ! empty( $_FILES['voice_intro']['name'] ) ) {
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		$uploadedfile = $_FILES['voice_intro'];
		$upload_overrides = array( 'test_form' => false );
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

		if ( $movefile && ! isset( $movefile['error'] ) ) {
			update_user_meta( $user_id, 'si50_voice_intro', $movefile['url'] );
		}
	}

	if ( 'Female' === $gender ) {
		update_user_meta( $user_id, 'si50_membership_status', 'premium' );
	}

	// Vetting status reset loop if previously rejected
	$current_status = get_user_meta( $user_id, 'si50_vetting_status', true );
	$resubmitted = false;
	if ( 'rejected' === $current_status ) {
		update_user_meta( $user_id, 'si50_vetting_status', 'pending_review' );
		delete_user_meta( $user_id, 'si50_rejection_note' ); // Clear the note
		$resubmitted = true;
		
		// Send notification email to site administrator about profile correction resubmission
		$user = get_userdata( $user_id );
		$admin_email = get_option( 'admin_email' );
		$subject = sprintf( '[%s] Declined Member Profile Updated & Resubmitted', get_bloginfo( 'name' ) );
		$body = "A member whose onboarding was previously declined has updated their profile details and resubmitted their application:\n\n";
		$body .= "Name: {$name}\n";
		$body .= "Email: {$user->user_email}\n";
		$body .= "Phone: {$phone}\n\n";
		$body .= "Please log in to your dashboard to review the updated application.";
		wp_mail( $admin_email, $subject, $body );
	}

	$msg = $resubmitted 
		? esc_html__( 'Profile updated successfully and resubmitted for verification review!', 'secondinnings50' ) 
		: esc_html__( 'Profile updated successfully!', 'secondinnings50' );

	wp_send_json_success( array( 'message' => $msg ) );
}
add_action( 'wp_ajax_si50_ajax_update_profile_info', 'si50_ajax_update_profile_info' );

/**
 * 7. AJAX Profile Preferences Update Handler
 */
function si50_ajax_update_profile_preferences() {
	check_ajax_referer( 'si50_auth_nonce', 'security' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please log in to update preferences.', 'secondinnings50' ) ) );
	}

	$user_id = get_current_user_id();

	// Companion lists and circles checkboxes
	$looking_for      = isset( $_POST['looking_for'] ) ? (array) $_POST['looking_for'] : array();
	$circles_interest = isset( $_POST['circles_interest'] ) ? (array) $_POST['circles_interest'] : array();
	$travel_dest      = isset( $_POST['travel_destinations'] ) ? (array) $_POST['travel_destinations'] : array();
	$travel_styles    = isset( $_POST['travel_styles'] ) ? (array) $_POST['travel_styles'] : array();
	$companion_styles = isset( $_POST['companion_styles'] ) ? sanitize_text_field( $_POST['companion_styles'] ) : '';
	$hobbies_interests   = isset( $_POST['hobbies_interests'] ) ? (array) $_POST['hobbies_interests'] : array();
	$travel_dest_other   = isset( $_POST['travel_destinations_other'] ) ? sanitize_text_field( $_POST['travel_destinations_other'] ) : '';
	$special_incentives  = isset( $_POST['special_incentives'] ) ? (array) $_POST['special_incentives'] : array();

	// Serialized lists
	update_user_meta( $user_id, 'si50_looking_for', array_map( 'sanitize_text_field', $looking_for ) );
	update_user_meta( $user_id, 'si50_circles_interest', array_map( 'sanitize_text_field', $circles_interest ) );
	update_user_meta( $user_id, 'si50_travel_destinations', array_map( 'sanitize_text_field', $travel_dest ) );
	update_user_meta( $user_id, 'si50_travel_styles', array_map( 'sanitize_text_field', $travel_styles ) );
	update_user_meta( $user_id, 'si50_companion_styles', $companion_styles );
	update_user_meta( $user_id, 'si50_hobbies_interests', array_map( 'sanitize_text_field', $hobbies_interests ) );
	update_user_meta( $user_id, 'si50_travel_destinations_other', $travel_dest_other );
	update_user_meta( $user_id, 'si50_special_incentives', array_map( 'sanitize_text_field', $special_incentives ) );

	// Explicit 4-Vector Interest Sync parameters
	$v_friendship   = ( in_array( 'Friendship', $looking_for ) || in_array( 'Social Friendship Circles', $looking_for ) ) ? 'Social Friendship Circles' : '';
	$v_conversation = ( in_array( 'Conversation Companion', $looking_for ) || in_array( 'Conversation/Single Companionship', $looking_for ) || in_array( 'Long-Term Companionship', $looking_for ) ) ? 'Conversation/Single Companionship' : '';
	$v_travel       = ( in_array( 'Activity Companion', $looking_for ) || in_array( 'Activity Meetup Interest', $circles_interest ) || in_array( 'Local Meetup Interest', $circles_interest ) ) ? 'Activity Meetups' : '';
	$v_chaichats    = ( in_array( 'WhatsApp Group Interest', $circles_interest ) || in_array( 'Virtual Chai Chats Interest', $circles_interest ) ) ? 'Virtual Chai Chats Interest' : '';

	update_user_meta( $user_id, 'si50_vector_friendship', $v_friendship );
	update_user_meta( $user_id, 'si50_vector_conversation', $v_conversation );
	update_user_meta( $user_id, 'si50_vector_travel', $v_travel );
	update_user_meta( $user_id, 'si50_vector_chaichats', $v_chaichats );

	wp_send_json_success( array( 'message' => esc_html__( 'Preferences saved successfully!', 'secondinnings50' ) ) );
}
add_action( 'wp_ajax_si50_ajax_update_profile_preferences', 'si50_ajax_update_profile_preferences' );

/**
 * 8. AJAX Forgot Password Reset Trigger Handler (For logged-in users inside profile dashboard)
 */
function si50_ajax_forgot_password() {
	check_ajax_referer( 'si50_auth_nonce', 'security' );

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Not logged in.', 'secondinnings50' ) ) );
	}

	$user_id = get_current_user_id();
	$user    = get_userdata( $user_id );

	// Trigger retrieve password process which sends the default WordPress reset email
	$errors = retrieve_password( $user->user_login );
	if ( is_wp_error( $errors ) ) {
		wp_send_json_error( array( 'message' => $errors->get_error_message() ) );
	}

	wp_send_json_success( array( 'message' => esc_html__( 'Password reset link has been dispatched to your email address.', 'secondinnings50' ) ) );
}
add_action( 'wp_ajax_si50_ajax_forgot_password', 'si50_ajax_forgot_password' );

