<?php
/**
 * SecondInnings50 Theme Functions and Definitions
 *
 * @package SecondInnings50
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'SI50_CRYPTO_SALT' ) ) {
    define( 'SI50_CRYPTO_SALT', 'local_staging_test_salt_1234567890_abc' );
}
if ( ! defined( 'PAYU_MERCHANT_KEY' ) ) {
    // PLACEHOLDER: Replace 'gtKFFx' with your live PayU Merchant Key
    define( 'PAYU_MERCHANT_KEY', 'xenjGO' );
}
if ( ! defined( 'PAYU_MERCHANT_SALT' ) ) {
    // PLACEHOLDER: Replace 'eCwWELxi' with your live PayU Merchant Salt
    define( 'PAYU_MERCHANT_SALT', 'mMKj5IdsBi4lvfB1COa54BQCyKYzBMTt' );
}
// Set to 'https://secure.payu.in' for LIVE or 'https://test.payu.in' for TEST
if ( ! defined( 'PAYU_BASE_URL' ) ) {
    define( 'PAYU_BASE_URL', 'https://secure.payu.in' );
}
if ( ! defined( 'SI50_SMS_GATEWAY_TOKEN' ) ) {
    define( 'SI50_SMS_GATEWAY_TOKEN', 'mock_sms_gateway_token_for_staging' );
}
if ( ! defined( 'SI50_SMS_API_URL' ) ) {
    define( 'SI50_SMS_API_URL', 'https://localhost/mock-sms-endpoint' );
}
if ( ! defined( 'SI50_WHATSAPP_API_KEY' ) ) {
    define( 'SI50_WHATSAPP_API_KEY', 'mock_whatsapp_bearer_token' );
}
if ( ! defined( 'SI50_WHATSAPP_SENDER' ) ) {
    define( 'SI50_WHATSAPP_SENDER', '+910000000000' );
}
if ( ! defined( 'SI50_SMTP_HOST' ) ) {
    define( 'SI50_SMTP_HOST', 'localhost' );
}
if ( ! defined( 'SI50_SMTP_USER' ) ) {
    define( 'SI50_SMTP_USER', 'test@secondinnings50.local' );
}
if ( ! defined( 'SI50_SMTP_PASS' ) ) {
    define( 'SI50_SMTP_PASS', 'test_password' );
}
if ( ! defined( 'SI50_SMTP_PORT' ) ) {
    define( 'SI50_SMTP_PORT', '25' );
}
if ( ! defined( 'SI50_SMTP_ENCRYPTION' ) ) {
    define( 'SI50_SMTP_ENCRYPTION', 'none' );
}
if ( ! defined( 'SI50_GOOGLE_MAPS_API_KEY' ) ) {
    define( 'SI50_GOOGLE_MAPS_API_KEY', 'mock_google_maps_key_placeholder' );
}
// Staging/Testing OTP Bypass Framework Switch
if ( ! defined( 'SI50_OTP_BYPASS' ) ) {
	define( 'SI50_OTP_BYPASS', true );
}


/**
 * Setup SecondInnings50 theme defaults and registers support for various WordPress features.
 */
function secondinnings50_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Register Navigation Menus.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary Header Menu', 'secondinnings50' ),
	) );

	// Switch default core markup for search form, comment form, etc. to output valid HTML5.
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Add support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'height'      => 250,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );
}
add_action( 'after_setup_theme', 'secondinnings50_setup' );

/**
 * Enqueue scripts and styles.
 */
function secondinnings50_scripts() {
	// Enqueue Google Fonts
	wp_enqueue_style( 'secondinnings50-fonts', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap', array(), null );

	// Enqueue main stylesheet (style.css) with cache-busting
	$style_ver = file_exists( get_template_directory() . '/style.css' ) ? filemtime( get_template_directory() . '/style.css' ) : '1.2.0';
	wp_enqueue_style( 'secondinnings50-style', get_stylesheet_uri(), array( 'secondinnings50-fonts' ), $style_ver );

	// Enqueue main javascript (app.js) in the footer with cache-busting
	$js_ver = file_exists( get_template_directory() . '/app.js' ) ? filemtime( get_template_directory() . '/app.js' ) : '1.0.0';
	wp_enqueue_script( 'secondinnings50-script', get_template_directory_uri() . '/app.js', array(), $js_ver, true );
	
	// Localize AJAX url and nonce for app.js to use enqueued variables instead of hardcoded paths
	wp_localize_script( 'secondinnings50-script', 'si50_ajax', array(
		'ajax_url'   => admin_url( 'admin-ajax.php' ),
		'nonce'      => wp_create_nonce( 'si50_auth_nonce' ),
		'otp_bypass' => defined( 'SI50_OTP_BYPASS' ) ? SI50_OTP_BYPASS : false,
	) );
}
add_action( 'wp_enqueue_scripts', 'secondinnings50_scripts' );

/**
 * Automatically bind custom templates for specific page slugs if they aren't assigned.
 * Hardened with absolute root routing and priority override to bypass GoDaddy layout caching.
 */
function si50_force_custom_page_templates( $template ) {
	$theme_path = defined( 'TEMPLATEPATH' ) ? TEMPLATEPATH : get_stylesheet_directory();

	if ( is_page( 'application-received' ) || ( isset( $_SERVER['REQUEST_URI'] ) && strpos( $_SERVER['REQUEST_URI'], '/application-received/' ) !== false ) ) {
		$custom_template = $theme_path . '/page-application-received.php';
		if ( file_exists( $custom_template ) ) {
			global $wp_query;
			if ( isset( $wp_query ) && $wp_query->is_404 ) {
				$wp_query->is_404 = false;
				status_header( 200 );
			}
			return $custom_template;
		}
	}

	if ( is_page( 'about-us' ) || is_page( 'about' ) ) {
		$custom_template = $theme_path . '/page-about.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}
	if ( is_page( 'join-application' ) || is_page( 'join' ) ) {
		$custom_template = $theme_path . '/page-join.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}
	if ( is_page( 'contact-us' ) || is_page( 'contact' ) ) {
		$custom_template = $theme_path . '/page-contact.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}
	if ( is_page( 'privacy-policy' ) || is_page( 'privacy' ) ) {
		$custom_template = $theme_path . '/page-privacy.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}
	if ( is_page( 'terms-and-conditions' ) || is_page( 'terms' ) ) {
		$custom_template = $theme_path . '/page-terms.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}
	if ( is_page( 'directory' ) || is_page( 'members' ) ) {
		$custom_template = $theme_path . '/page-directory.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}
	if ( is_page( 'profile' ) || is_page( 'my-profile' ) ) {
		$custom_template = $theme_path . '/page-profile.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}
	if ( is_page( 'notifications' ) ) {
		$custom_template = $theme_path . '/page-notifications.php';
		if ( file_exists( $custom_template ) ) {
			return $custom_template;
		}
	}
	return $template;
}
add_filter( 'template_include', 'si50_force_custom_page_templates', 99 );

/**
 * AJAX Lead Submission Handler (Homepage Form)
 */
function si50_handle_lead_submission() {
	global $wpdb;

	$name        = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
	$email       = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	$phone       = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	$gender      = isset( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
	$age_bracket = isset( $_POST['age_bracket'] ) ? sanitize_text_field( $_POST['age_bracket'] ) : '';

	// Separate required empty fields check from invalid formats check
	if ( empty( $name ) || empty( $_POST['email'] ) || empty( $phone ) || empty( $gender ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Required fields are missing.', 'secondinnings50' ) ) );
	}

	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please enter a valid email address.', 'secondinnings50' ) ) );
	}

	// Store request in the database
	$table_inv = $wpdb->prefix . 'si50_invitation_requests';
	$wpdb->insert(
		$table_inv,
		array(
			'name'        => $name,
			'email'       => $email,
			'phone'       => $phone,
			'gender'      => $gender,
			'age_bracket' => $age_bracket,
			'timestamp'   => current_time( 'mysql' )
		),
		array( '%s', '%s', '%s', '%s', '%s', '%s' )
	);

	$admin_email = get_option( 'admin_email' );
	$subject     = sprintf( '[%s] New Invitation Request', get_bloginfo( 'name' ) );
	
	$body  = "You have received a new invitation request:\n\n";
	$body .= "Name: " . $name . "\n";
	$body .= "Email: " . $email . "\n";
	$body .= "Phone: " . $phone . "\n";
	$body .= "Gender: " . $gender . "\n";
	$body .= "Age Bracket: " . $age_bracket . "\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . $admin_email . '>'
	);

	// Log submission for testing/diagnosis backup
	error_log( sprintf( 'SecondInnings50 [Lead Submission] - Name: %s, Email: %s, Phone: %s, Gender: %s, Age Bracket: %s', $name, $email, $phone, $gender, $age_bracket ) );

	wp_mail( $admin_email, $subject, $body, $headers );
	wp_send_json_success( array( 'message' => esc_html__( 'Thank you! Your request has been received.', 'secondinnings50' ) ) );
}
add_action( 'wp_ajax_si50_submit_lead', 'si50_handle_lead_submission' );
add_action( 'wp_ajax_nopriv_si50_submit_lead', 'si50_handle_lead_submission' );

/**
 * AJAX Onboarding Application Submission Handler (Join Form)
 */
function si50_handle_join_submission() {
	$name           = isset( $_POST['fullname'] ) ? sanitize_text_field( $_POST['fullname'] ) : '';
	$email          = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	$phone          = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	$city_state     = isset( $_POST['city_state'] ) ? sanitize_text_field( $_POST['city_state'] ) : ( isset( $_POST['city'] ) ? sanitize_text_field( $_POST['city'] ) : '' );
	$age            = isset( $_POST['age_bracket'] ) ? sanitize_text_field( $_POST['age_bracket'] ) : '';
	$dob            = isset( $_POST['dob'] ) ? sanitize_text_field( $_POST['dob'] ) : '';
	$gender         = isset( $_POST['gender'] ) ? sanitize_text_field( $_POST['gender'] ) : '';
	$marital_status = isset( $_POST['marital_status'] ) ? sanitize_text_field( $_POST['marital_status'] ) : '';
	$occupation     = isset( $_POST['occupation'] ) ? sanitize_text_field( $_POST['occupation'] ) : '';
	$intro          = isset( $_POST['introduction'] ) ? sanitize_textarea_field( $_POST['introduction'] ) : '';
	$emergency_phone = isset( $_POST['emergency_phone'] ) ? sanitize_text_field( $_POST['emergency_phone'] ) : '';

	// Looking For Options
	$looking_for     = isset( $_POST['looking_for'] ) ? $_POST['looking_for'] : array();
	$looking_for_str = is_array( $looking_for ) ? implode( ', ', array_map( 'sanitize_text_field', $looking_for ) ) : sanitize_text_field( $looking_for );

	// Circles Onboarding Flags
	$circles_interest     = isset( $_POST['circles_interest'] ) ? $_POST['circles_interest'] : array();
	$circles_interest_str = is_array( $circles_interest ) ? implode( ', ', array_map( 'sanitize_text_field', $circles_interest ) ) : sanitize_text_field( $circles_interest );

	// Travel Matrix - Destinations
	$travel_dest     = isset( $_POST['travel_destinations'] ) ? $_POST['travel_destinations'] : array();
	$travel_dest_str = is_array( $travel_dest ) ? implode( ', ', array_map( 'sanitize_text_field', $travel_dest ) ) : sanitize_text_field( $travel_dest );

	// Travel Style Profile
	$travel_styles     = isset( $_POST['travel_styles'] ) ? $_POST['travel_styles'] : array();
	$travel_styles_str = is_array( $travel_styles ) ? implode( ', ', array_map( 'sanitize_text_field', $travel_styles ) ) : sanitize_text_field( $travel_styles );

	// Companion Style Match
	$companion_styles = isset( $_POST['companion_styles'] ) ? sanitize_text_field( $_POST['companion_styles'] ) : '';

	// Backward Compatibility/Fallback Fields
	$location     = isset( $_POST['location_preference'] ) ? sanitize_text_field( $_POST['location_preference'] ) : '';
	$relationship = isset( $_POST['relationship_preference'] ) ? sanitize_text_field( $_POST['relationship_preference'] ) : '';

	$interests = isset( $_POST['interests'] ) ? $_POST['interests'] : array();
	$interests_str = is_array( $interests ) ? implode( ', ', array_map( 'sanitize_text_field', $interests ) ) : sanitize_text_field( $interests );

	// Separate required empty fields check from invalid formats check
	if ( empty( $name ) || empty( $_POST['email'] ) || empty( $phone ) || empty( $city_state ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Required fields are missing.', 'secondinnings50' ) ) );
	}

	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please enter a valid email address.', 'secondinnings50' ) ) );
	}

	$admin_email = get_option( 'admin_email' );
	$subject     = sprintf( '[%s] New Member Onboarding Application', get_bloginfo( 'name' ) );

	$body  = "A new membership application has been received:\n\n";
	$body .= "=== PERSONAL IDENTITY ===\n";
	$body .= "Name: " . $name . "\n";
	$body .= "Date of Birth: " . $dob . "\n";
	$body .= "Gender: " . $gender . "\n";
	$body .= "Marital Status: " . $marital_status . "\n";
	$body .= "Occupation: " . $occupation . "\n\n";

	$body .= "=== SECURE CONTACT ===\n";
	$body .= "Email: " . $email . "\n";
	$body .= "Phone (WhatsApp): " . $phone . "\n";
	$body .= "City & State: " . $city_state . "\n\n";

	$body .= "=== SAFETY & EMERGENCY ===\n";
	$body .= "Emergency Contact: " . $emergency_phone . "\n\n";

	$body .= "=== PLATFORM MATCHING FLAGS ===\n";
	$body .= "Looking For: " . ( ! empty( $looking_for_str ) ? $looking_for_str : $relationship ) . "\n";
	$body .= "Circles Interest: " . $circles_interest_str . "\n\n";

	$body .= "=== TRAVEL MATRIX SELECTION ===\n";
	$body .= "Travel Destinations: " . $travel_dest_str . "\n";
	$body .= "Travel Styles: " . $travel_styles_str . "\n";
	$body .= "Companion Style Match: " . $companion_styles . "\n\n";

	if ( ! empty( $location ) || ! empty( $interests_str ) ) {
		$body .= "=== LEGACY FIELDS ===\n";
		$body .= "Location Preference: " . $location . "\n";
		$body .= "Interests Selected: " . $interests_str . "\n\n";
	}

	$body .= "=== ABOUT ME / INTRODUCTION ===\n";
	$body .= $intro . "\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . $admin_email . '>'
	);

	// Log submission for testing/diagnosis backup
	error_log( sprintf( 'SecondInnings50 [Join Submission] - Name: %s, Email: %s, Phone: %s, Gender: %s', $name, $email, $phone, $gender ) );

	wp_mail( $admin_email, $subject, $body, $headers );
	wp_send_json_success( array( 'message' => esc_html__( 'Application submitted successfully.', 'secondinnings50' ) ) );
}
add_action( 'wp_ajax_si50_submit_join', 'si50_handle_join_submission' );
add_action( 'wp_ajax_nopriv_si50_submit_join', 'si50_handle_join_submission' );

/**
 * AJAX Contact Support Submission Handler (Contact Form)
 */
function si50_handle_contact_submission() {
	global $wpdb;
	$name           = isset( $_POST['fullname'] ) ? sanitize_text_field( $_POST['fullname'] ) : '';
	$email          = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	$phone          = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	$inquiry_nature = isset( $_POST['inquiry_nature'] ) ? sanitize_text_field( $_POST['inquiry_nature'] ) : '';
	$message        = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

	// Separate required empty fields check from invalid formats check
	if ( empty( $name ) || empty( $_POST['email'] ) || empty( $message ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Required fields are missing.', 'secondinnings50' ) ) );
	}

	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => esc_html__( 'Please enter a valid email address.', 'secondinnings50' ) ) );
	}

	// Insert into custom database table
	$wpdb->insert(
		$wpdb->prefix . 'si50_contact_inquiries',
		array(
			'name'           => $name,
			'email'          => $email,
			'phone'          => $phone,
			'inquiry_nature' => $inquiry_nature,
			'message'        => $message,
			'timestamp'      => current_time( 'mysql' ),
		),
		array( '%s', '%s', '%s', '%s', '%s', '%s' )
	);

	$admin_email = get_option( 'admin_email' );
	$subject     = sprintf( '[%s] New Support Message - %s', get_bloginfo( 'name' ), $inquiry_nature );

	$body  = "You have received a new customer support message:\n\n";
	$body .= "Name: " . $name . "\n";
	$body .= "Email: " . $email . "\n";
	$body .= "Phone (WhatsApp): " . $phone . "\n";
	$body .= "Nature of Inquiry: " . $inquiry_nature . "\n\n";
	$body .= "Message:\n" . $message . "\n";

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'From: ' . get_bloginfo( 'name' ) . ' <' . $admin_email . '>'
	);

	// Log submission for testing/diagnosis backup
	error_log( sprintf( 'SecondInnings50 [Contact Submission] - Name: %s, Email: %s, Phone: %s, Nature of Inquiry: %s', $name, $email, $phone, $inquiry_nature ) );

	wp_mail( $admin_email, $subject, $body, $headers );
	wp_send_json_success( array( 'message' => esc_html__( 'Thank you. Your inquiry has been securely routed to our verification hosts. We will contact you shortly.', 'secondinnings50' ) ) );
}
add_action( 'wp_ajax_si50_submit_contact', 'si50_handle_contact_submission' );
add_action( 'wp_ajax_nopriv_si50_submit_contact', 'si50_handle_contact_submission' );

/**
 * Filter wp_nav_menu links to resolve anchor tags on subpages.
 * If we are on a page other than the front page or home page, we prepend the home URL
 * to any custom relative anchor links (e.g., #about, #offerings) so they route back to the homepage correctly.
 */
function si50_nav_menu_anchor_links( $atts, $item, $args ) {
	if ( ! is_front_page() && ! is_home() ) {
		if ( isset( $atts['href'] ) ) {
			$href = $atts['href'];
			// Check if the URL starts with '#'
			if ( strpos( $href, '#' ) === 0 ) {
				$atts['href'] = home_url( '/' ) . $href;
			} elseif ( preg_match( '/^\/[a-zA-Z0-9_\-]*#/', $href ) ) {
				// If it starts with a slash and contains hash (e.g. /#about), make it absolute to avoid subfolder path issues
				$atts['href'] = home_url( $href );
			}
		}
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'si50_nav_menu_anchor_links', 10, 3 );

/**
 * Include Milestone 2 Custom Profile and Vetting Engines
 */
require_once get_template_directory() . '/inc/environment-registry.php';
require_once get_template_directory() . '/inc/auth-profile-engine.php';
require_once get_template_directory() . '/inc/admin-dashboard.php';
require_once get_template_directory() . '/inc/production-engine.php';
require_once get_template_directory() . '/inc/payment-gateway.php';

/**
 * Install custom database tables for SecondInnings50.
 */
function si50_install_custom_db_tables() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'si50_connect_requests';
	$table_inv = $wpdb->prefix . 'si50_invitation_requests';
	$table_contact = $wpdb->prefix . 'si50_contact_inquiries';
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		sender_id bigint(20) NOT NULL,
		receiver_id bigint(20) NOT NULL,
		status varchar(50) NOT NULL DEFAULT 'pending',
		timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY  (id),
		KEY sender_id (sender_id),
		KEY receiver_id (receiver_id)
	) $charset_collate;";

	$sql_inv = "CREATE TABLE $table_inv (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		name varchar(255) NOT NULL,
		email varchar(255) NOT NULL,
		phone varchar(100) NOT NULL,
		gender varchar(50) NOT NULL,
		age_bracket varchar(50) NOT NULL,
		timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	$sql_contact = "CREATE TABLE $table_contact (
		id bigint(20) NOT NULL AUTO_INCREMENT,
		name varchar(255) NOT NULL,
		email varchar(255) NOT NULL,
		phone varchar(100) NOT NULL,
		inquiry_nature varchar(255) NOT NULL,
		message text NOT NULL,
		timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
	dbDelta( $sql_inv );
	dbDelta( $sql_contact );

	// Trigger production engine tables installation
	si50_install_production_tables();
}
add_action( 'after_switch_theme', 'si50_install_custom_db_tables' );

/**
 * Ensure table is installed if missing during admin initialization.
 */
function si50_check_and_install_db() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'si50_connect_requests';
	$table_audit = $wpdb->prefix . 'si50_audit_logs';
	$table_inv = $wpdb->prefix . 'si50_invitation_requests';
	$table_contact = $wpdb->prefix . 'si50_contact_inquiries';
	$table_reports = $wpdb->prefix . 'si50_member_reports';
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) !== $table_name 
		|| $wpdb->get_var( "SHOW TABLES LIKE '$table_audit'" ) !== $table_audit 
		|| $wpdb->get_var( "SHOW TABLES LIKE '$table_inv'" ) !== $table_inv
		|| $wpdb->get_var( "SHOW TABLES LIKE '$table_contact'" ) !== $table_contact
		|| $wpdb->get_var( "SHOW TABLES LIKE '$table_reports'" ) !== $table_reports ) {
		si50_install_custom_db_tables();
	}
}
add_action( 'admin_init', 'si50_check_and_install_db' );

/**
 * Get connection request details between two users.
 */
function si50_get_connection_request( $user_a, $user_b ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'si50_connect_requests';
	$user_a = intval( $user_a );
	$user_b = intval( $user_b );

	return $wpdb->get_row( $wpdb->prepare(
		"SELECT * FROM $table_name 
		 WHERE (sender_id = %d AND receiver_id = %d) 
		    OR (sender_id = %d AND receiver_id = %d) 
		 ORDER BY timestamp DESC LIMIT 1",
		$user_a, $user_b, $user_b, $user_a
	) );
}

/**
 * Get connection request status between two users.
 */
function si50_get_connection_status( $user_a, $user_b ) {
	$row = si50_get_connection_request( $user_a, $user_b );
	return $row ? $row->status : false;
}

/**
 * Check if two users are approved connections.
 */
function si50_are_connected( $user_a, $user_b ) {
	$status = si50_get_connection_status( $user_a, $user_b );
	return ( 'approved' === $status );
}

/**
 * 4. SecondInnings50 Complete Global Meta SEO Optimization Engine
 * Programmatically injects dynamic canonical urls, OpenGraph markup,
 * and optimized descriptions targeted for active Indian seniors and adults.
 */
function si50_seo_meta_tags() {
	global $wp;
	
	// Canonical URL bindings mirroring the active request link
	$canonical_url = home_url( add_query_arg( array(), $wp->request ) );
	if ( is_front_page() || is_home() ) {
		$canonical_url = home_url( '/' );
	}
	
	echo "\n" . '<!-- SecondInnings50 Global SEO Meta Engine -->' . "\n";
	echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '">' . "\n";

	// Default OpenGraph parameters
	$og_title     = get_bloginfo( 'name' );
	$og_desc      = get_bloginfo( 'description' );
	$og_type      = 'website';
	$og_site_name = 'SecondInnings50';

	// Map customized tags optimized with keywords for active Indian adults
	if ( is_front_page() || is_home() ) {
		$og_title = esc_html__( 'SecondInnings50 | Premium Companionship & Shared Life Partnerships for 45+', 'secondinnings50' );
		$og_desc  = esc_html__( 'Discover meaningful companionship, friendship, and vetted interest groups for mature adults over 45 in India. A safe, vetted community for active seniors.', 'secondinnings50' );
	} elseif ( is_page( 'about-us' ) || is_page( 'about' ) ) {
		$og_title = esc_html__( 'Our Purpose & Dignified Social Circle | SecondInnings50', 'secondinnings50' );
		$og_desc  = esc_html__( 'Learn about SecondInnings50, a dedicated social platform focusing on safety, dignity, security, and companionship for active Indian adults in their silver years.', 'secondinnings50' );
	} elseif ( is_page( 'join-application' ) || is_page( 'join' ) ) {
		$og_title = esc_html__( 'Begin Your New Beginning | Secure Application Gateway | SecondInnings50', 'secondinnings50' );
		$og_desc  = esc_html__( 'Join SecondInnings50 today. Secure onboarding and safety verification for companionship, friendship, and meaningful shared partnerships.', 'secondinnings50' );
	} elseif ( is_page( 'directory' ) || is_page( 'members' ) ) {
		$og_title = esc_html__( 'Vetted Senior Directory | Meaningful Connections | SecondInnings50', 'secondinnings50' );
		$og_desc  = esc_html__( 'Browse our secure, vetted directory of active adults over 45 seeking companionship, friendship, and shared life partnerships.', 'secondinnings50' );
	} elseif ( is_page( 'profile' ) || is_page( 'my-profile' ) ) {
		$og_title = esc_html__( 'My Profile Settings | SecondInnings50', 'secondinnings50' );
		$og_desc  = esc_html__( 'Manage your member profile, update social matching preferences, and view your approved community connections securely.', 'secondinnings50' );
	} elseif ( is_page( 'notifications' ) ) {
		$og_title = esc_html__( 'Connection Invitations & Activity Logs | SecondInnings50', 'secondinnings50' );
		$og_desc  = esc_html__( 'Manage incoming connection requests and browse accepted companion status logs securely on your private dashboard.', 'secondinnings50' );
	} elseif ( is_page( 'contact-us' ) || is_page( 'contact' ) ) {
		$og_title = esc_html__( 'Contact Us | SecondInnings50', 'secondinnings50' );
		$og_desc  = esc_html__( 'Get in touch with SecondInnings50 support. We are here to help you find meaningful companionship and shared partnerships.', 'secondinnings50' );
	} elseif ( is_page( 'application-received' ) ) {
		$og_title = esc_html__( 'Application Safely Received | SecondInnings50', 'secondinnings50' );
		$og_desc  = esc_html__( 'Your onboarding profile has been safely received. It is undergoing identity verification by our coordination team.', 'secondinnings50' );
	} elseif ( is_page() ) {
		$og_title = get_the_title() . ' | ' . $og_site_name;
		$og_desc  = wp_strip_all_tags( get_the_excerpt() );
		if ( empty( $og_desc ) ) {
			$og_desc = esc_html__( 'SecondInnings50 is the trusted social companion portal designed specifically for active Indian adults.', 'secondinnings50' );
		}
	}

	$site_logo = '';
	if ( has_custom_logo() ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		$logo_data = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $logo_data ) {
			$site_logo = $logo_data[0];
		}
	}
	if ( empty( $site_logo ) ) {
		$site_logo = esc_url( get_template_directory_uri() . '/assets/hero.jpg' );
	}

	echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $og_desc ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $canonical_url ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $og_site_name ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $site_logo ) . '">' . "\n";
	
	// Twitter Card Tags
	echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $og_title ) . '" />' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $og_desc ) . '" />' . "\n";
	echo '<meta name="twitter:image" content="' . esc_url( $site_logo ) . '" />' . "\n";

	// Standard metadata tag fallback
	echo '<meta name="description" content="' . esc_attr( $og_desc ) . '">' . "\n";
	echo '<!-- End SecondInnings50 Global SEO Meta Engine -->' . "\n";
}
add_action( 'wp_head', 'si50_seo_meta_tags', 1 );

/**
 * Programmatically filter document title parts for search engines.
 * Overrides layout title details to fit search engine requirements.
 */
function si50_seo_title_parts( $title_parts ) {
	if ( is_front_page() || is_home() ) {
		$title_parts['title']   = esc_html__( 'SecondInnings50 | Premium Companionship & Shared Life Partnerships for 45+', 'secondinnings50' );
		$title_parts['tagline'] = '';
		$title_parts['site']    = '';
	} elseif ( is_page( 'about-us' ) || is_page( 'about' ) ) {
		$title_parts['title']   = esc_html__( 'Our Purpose & Dignified Social Community | SecondInnings50', 'secondinnings50' );
		$title_parts['tagline'] = '';
		$title_parts['site']    = '';
	} elseif ( is_page( 'join-application' ) || is_page( 'join' ) ) {
		$title_parts['title']   = esc_html__( 'Begin Your New Beginning | Secure Application Gateway | SecondInnings50', 'secondinnings50' );
		$title_parts['tagline'] = '';
		$title_parts['site']    = '';
	} elseif ( is_page( 'directory' ) || is_page( 'members' ) ) {
		$title_parts['title']   = esc_html__( 'Vetted Senior Directory | Meaningful Connections | SecondInnings50', 'secondinnings50' );
		$title_parts['tagline'] = '';
		$title_parts['site']    = '';
	} elseif ( is_page( 'profile' ) || is_page( 'my-profile' ) ) {
		$title_parts['title']   = esc_html__( 'My Profile Settings | SecondInnings50', 'secondinnings50' );
		$title_parts['tagline'] = '';
		$title_parts['site']    = '';
	} elseif ( is_page( 'notifications' ) ) {
		$title_parts['title']   = esc_html__( 'Notifications | SecondInnings50', 'secondinnings50' );
		$title_parts['tagline'] = '';
		$title_parts['site']    = '';
	} elseif ( is_page( 'contact-us' ) || is_page( 'contact' ) ) {
		$title_parts['title']   = esc_html__( 'Contact Us | SecondInnings50', 'secondinnings50' );
		$title_parts['tagline'] = '';
		$title_parts['site']    = '';
	} elseif ( is_page( 'application-received' ) ) {
		$title_parts['title']   = esc_html__( 'Application Safely Received | SecondInnings50', 'secondinnings50' );
		$title_parts['tagline'] = '';
		$title_parts['site']    = '';
	}
	return $title_parts;
}
add_filter( 'document_title_parts', 'si50_seo_title_parts', 99 );

/**
 * Inject JSON-LD Schema structured data in footer.
 */
function si50_inject_schema_markup() {
	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'SocialGroup',
		'name'        => 'SecondInnings50',
		'url'         => esc_url( home_url( '/' ) ),
		'description' => esc_html__( 'Premium Companionship, friendship interest groups, and shared life partnerships platform for active mature adults over 45 in India.', 'secondinnings50' ),
		'areaServed'  => array(
			'@type' => 'Country',
			'name'  => 'India'
		),
		'audience' => array(
			'@type' => 'Audience',
			'audienceType' => esc_html__( 'Active mature adults, seniors, and retired individuals seeking companions', 'secondinnings50' )
		),
		'knowsAbout' => array(
			esc_html__( 'Senior Companionship', 'secondinnings50' ),
			esc_html__( 'Companionship Interest Groups', 'secondinnings50' ),
			esc_html__( 'Life Partnerships', 'secondinnings50' ),
			esc_html__( 'Senior Social Circles', 'secondinnings50' )
		)
	);

	echo "\n<!-- SecondInnings50 Schema Structured Data -->\n";
	echo '<script type="application/ld+json">' . "\n";
	echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "\n";
	echo "</script>\n";
}
add_action( 'wp_footer', 'si50_inject_schema_markup' );

/**
 * Dispatch an automated WhatsApp notification.
 * Integrates a clean transactional gateway hook and safe fallback logging.
 */
function si50_send_whatsapp_alert( $to_phone, $message ) {
	if ( empty( $to_phone ) ) {
		error_log( 'SecondInnings50 WhatsApp Error: Recipient phone number is empty.' );
		return false;
	}

	// Clean the phone number (pre-formatted with India country code prefix 91)
	$clean_phone = preg_replace( '/[^0-9]/', '', $to_phone );
	if ( 10 === strlen( $clean_phone ) ) {
		$clean_phone = '91' . $clean_phone;
	}

	// Safe transactional API hook filter
	// Developers can hook into 'si50_whatsapp_api_request' to route to Twilio, Gupshup, etc.
	$payload = array(
		'to'      => $clean_phone,
		'message' => $message,
	);
	
	$payload = apply_filters( 'si50_whatsapp_payload', $payload );

	// Background request with error_log fallback to prevent timeout failures
	// For simulation/production routing, we log to PHP error log as requested.
	error_log( sprintf( '🔔 [WhatsApp Outgoing SMS] To: +%s, Message: "%s"', $clean_phone, $message ) );

	// Standard transactional API request (e.g. wp_safe_remote_post to a WhatsApp Fiduciary API)
	$api_url = si50_resolve_credential( 'SI50_WHATSAPP_API_URL', 'whatsapp_api_url', false );
	if ( ! empty( $api_url ) ) {
		wp_remote_post( $api_url, array(
			'method'      => 'POST',
			'timeout'     => 5,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => false, // async background processing
			'headers'     => array(
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . si50_get_whatsapp_api_key()
			),
			'body'        => json_encode( $payload ),
		) );
	}

	return true;
}

/**
 * Delete User Account Action (Hard Delete)
 */
function si50_ajax_delete_account() {
    check_ajax_referer( 'si50_auth_nonce', 'security' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Unauthorized request.', 'secondinnings50' ) ) );
    }

    $user_id = get_current_user_id();

    // Include the user administration API to use wp_delete_user
    if ( ! function_exists( 'wp_delete_user' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/user.php' );
    }

    // Perform hard delete
    if ( wp_delete_user( $user_id ) ) {
        // Log out the user since their account is deleted
        wp_logout();
        wp_send_json_success( array( 'message' => esc_html__( 'Account successfully deleted.', 'secondinnings50' ) ) );
    } else {
        wp_send_json_error( array( 'message' => esc_html__( 'Failed to delete account.', 'secondinnings50' ) ) );
    }
}
add_action( 'wp_ajax_si50_ajax_delete_account', 'si50_ajax_delete_account' );

/**
 * Report Member Action
 */
function si50_report_member() {
    check_ajax_referer( 'si50_auth_nonce', 'security' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Unauthorized request.', 'secondinnings50' ) ) );
    }

    $reporter_id = get_current_user_id();
    $reported_id = isset( $_POST['reported_user_id'] ) ? intval( $_POST['reported_user_id'] ) : 0;
    $reason      = isset( $_POST['reason'] ) ? sanitize_textarea_field( $_POST['reason'] ) : '';

    if ( ! $reported_id || empty( $reason ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid report data.', 'secondinnings50' ) ) );
    }

    // Save report in user meta of the reported user (or log to DB). We'll save it as user meta array.
    $reports = get_user_meta( $reported_id, 'si50_user_reports', true );
    if ( ! is_array( $reports ) ) {
        $reports = array();
    }
    
    $reports[] = array(
        'reporter_id' => $reporter_id,
        'reason'      => $reason,
        'timestamp'   => current_time( 'mysql' )
    );
    
    update_user_meta( $reported_id, 'si50_user_reports', $reports );

    $reported_members = get_user_meta( $reporter_id, 'si50_reported_members', true );
    if ( ! is_array( $reported_members ) ) {
        $reported_members = array();
    }
    if ( ! in_array( $reported_id, $reported_members, true ) ) {
        $reported_members[] = $reported_id;
        update_user_meta( $reporter_id, 'si50_reported_members', $reported_members );
    }

    $reported_by_members = get_user_meta( $reported_id, 'si50_reported_by_members', true );
    if ( ! is_array( $reported_by_members ) ) {
        $reported_by_members = array();
    }
    if ( ! in_array( $reporter_id, $reported_by_members, true ) ) {
        $reported_by_members[] = $reporter_id;
        update_user_meta( $reported_id, 'si50_reported_by_members', $reported_by_members );
    }

    // Email admin
    $admin_email = get_option( 'admin_email' );
    $reporter_info = get_userdata( $reporter_id );
    $reported_info = get_userdata( $reported_id );
    $subject     = 'SecondInnings50 Alert: User Report Submitted';
    $message     = sprintf( 
        "A user has been reported on SecondInnings50.\n\nReporter: %s (ID: %d)\nReported User: %s (ID: %d)\nReason: %s", 
        $reporter_info ? $reporter_info->user_email : 'Unknown',
        $reporter_id, 
        $reported_info ? $reported_info->user_email : 'Unknown',
        $reported_id, 
        $reason 
    );
    wp_mail( $admin_email, $subject, $message );

    wp_send_json_success( array( 'message' => esc_html__( 'Report submitted successfully. Our team will review this promptly.', 'secondinnings50' ) ) );
}
add_action( 'wp_ajax_si50_report_member', 'si50_report_member' );

/**
 * Block Member Action
 */
function si50_block_member() {
    check_ajax_referer( 'si50_auth_nonce', 'security' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Unauthorized request.', 'secondinnings50' ) ) );
    }

    $blocker_id = get_current_user_id();
    $blocked_id = isset( $_POST['blocked_user_id'] ) ? intval( $_POST['blocked_user_id'] ) : 0;

    if ( ! $blocked_id ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid member ID.', 'secondinnings50' ) ) );
    }

    // Save blocked user ID in blocker's user meta
    $blocked_users = get_user_meta( $blocker_id, 'si50_blocked_users', true );
    if ( ! is_array( $blocked_users ) ) {
        $blocked_users = array();
    }

    if ( ! in_array( $blocked_id, $blocked_users ) ) {
        $blocked_users[] = $blocked_id;
        update_user_meta( $blocker_id, 'si50_blocked_users', $blocked_users );
    }

    $blocked_members = get_user_meta( $blocker_id, 'si50_blocked_members', true );
    if ( ! is_array( $blocked_members ) ) {
        $blocked_members = array();
    }
    if ( ! in_array( $blocked_id, $blocked_members, true ) ) {
        $blocked_members[] = $blocked_id;
        update_user_meta( $blocker_id, 'si50_blocked_members', $blocked_members );
    }
    
    // Reverse block (also block the blocker from the blocked user's perspective, or at least update both)
    $reverse_blocked = get_user_meta( $blocked_id, 'si50_blocked_users', true );
    if ( ! is_array( $reverse_blocked ) ) {
        $reverse_blocked = array();
    }
    if ( ! in_array( $blocker_id, $reverse_blocked ) ) {
        $reverse_blocked[] = $blocker_id;
        update_user_meta( $blocked_id, 'si50_blocked_users', $reverse_blocked );
    }

    $blocked_by_members = get_user_meta( $blocked_id, 'si50_blocked_by_members', true );
    if ( ! is_array( $blocked_by_members ) ) {
        $blocked_by_members = array();
    }
    if ( ! in_array( $blocker_id, $blocked_by_members, true ) ) {
        $blocked_by_members[] = $blocker_id;
        update_user_meta( $blocked_id, 'si50_blocked_by_members', $blocked_by_members );
    }

    wp_send_json_success( array( 'message' => esc_html__( 'User blocked successfully. You will no longer see their profile.', 'secondinnings50' ) ) );
}
add_action( 'wp_ajax_si50_block_member', 'si50_block_member' );

/**
 * Get Blocked User IDs Helper
 */
if ( ! function_exists( 'si50_get_blocked_user_ids' ) ) {
	function si50_get_blocked_user_ids( $user_id ) {
		$blocked_users = get_user_meta( $user_id, 'si50_blocked_users', true );
		if ( ! is_array( $blocked_users ) ) {
			return array();
		}
		return array_map( 'intval', $blocked_users );
	}
}

function si50_get_blocked_member_ids( $user_id ) {
    $blocked_members = get_user_meta( $user_id, 'si50_blocked_members', true );
    if ( ! is_array( $blocked_members ) ) {
        return array();
    }
    return array_map( 'intval', $blocked_members );
}

function si50_get_blocked_by_member_ids( $user_id ) {
    $blocked_by_members = get_user_meta( $user_id, 'si50_blocked_by_members', true );
    if ( ! is_array( $blocked_by_members ) ) {
        return array();
    }
    return array_map( 'intval', $blocked_by_members );
}

function si50_get_reported_member_ids( $user_id ) {
    $reported_members = get_user_meta( $user_id, 'si50_reported_members', true );
    if ( ! is_array( $reported_members ) ) {
        return array();
    }
    return array_map( 'intval', $reported_members );
}

function si50_get_reported_by_member_ids( $user_id ) {
    $reported_by_members = get_user_meta( $user_id, 'si50_reported_by_members', true );
    if ( ! is_array( $reported_by_members ) ) {
        return array();
    }
    return array_map( 'intval', $reported_by_members );
}

function si50_get_member_status_tags( $user_id ) {
    $tags = get_user_meta( $user_id, 'si50_member_status_tags', true );
    if ( ! is_array( $tags ) ) {
        return array();
    }
    return $tags;
}

function si50_has_member_status_tag( $user_id, $tag ) {
    $tags = si50_get_member_status_tags( $user_id );
    return in_array( $tag, $tags, true );
}

function si50_add_member_status_tag( $user_id, $tag ) {
    if ( empty( $tag ) ) {
        return;
    }
    $tags = si50_get_member_status_tags( $user_id );
    if ( ! in_array( $tag, $tags, true ) ) {
        $tags[] = sanitize_text_field( $tag );
        update_user_meta( $user_id, 'si50_member_status_tags', $tags );
    }
}

function si50_remove_member_status_tag( $user_id, $tag ) {
    if ( empty( $tag ) ) {
        return;
    }
    $tags = si50_get_member_status_tags( $user_id );
    if ( in_array( $tag, $tags, true ) ) {
        $tags = array_values( array_diff( $tags, array( $tag ) ) );
        update_user_meta( $user_id, 'si50_member_status_tags', $tags );
    }
}



/**
 * Generate Membership ID
 */
function si50_generate_membership_id( $user_id ) {
    $existing = get_user_meta( $user_id, 'si50_membership_id', true );
    if ( ! empty( $existing ) ) {
        return $existing;
    }
    $gender = get_user_meta( $user_id, 'si50_gender', true );
    $g_char = ( 'Female' === $gender || 'female' === strtolower($gender) ) ? 'F' : 'M';
    $new_id = 'SI-' . $g_char . '-' . $user_id;
    update_user_meta( $user_id, 'si50_membership_id', $new_id );
    return $new_id;
}
add_action( 'user_register', 'si50_generate_membership_id' );
add_action( 'profile_update', 'si50_generate_membership_id' );

/**
 * Handle Download Profile Card Action
 */
function si50_handle_download_profile_card() {
    if ( isset( $_GET['si50_download_card'] ) && isset( $_GET['user_id'] ) && is_admin() && current_user_can('manage_options') ) {
        $user_id = intval( $_GET['user_id'] );
        $user = get_userdata( $user_id );
        if ( ! $user ) wp_die('User not found.');
        
        $m_id = si50_generate_membership_id( $user_id );
        $name = get_user_meta( $user_id, 'si50_fullname', true );
        if ( empty($name) ) $name = $user->first_name;
        if ( empty($name) ) $name = $user->display_name;
        
        $age = get_user_meta( $user_id, 'si50_age_bracket', true );
        $city = get_user_meta( $user_id, 'si50_city_state', true );
        $occupation = get_user_meta( $user_id, 'si50_occupation', true );
        $focus = implode(', ', (array)get_user_meta( $user_id, 'si50_looking_for', true ));
        $circles = implode(', ', (array)get_user_meta( $user_id, 'si50_circles_interest', true ));
        
        // Fix for hobbies and travel
        $hobbies = implode(', ', (array)get_user_meta( $user_id, 'si50_hobbies_interests', true ));
        $travel_dest = implode(', ', (array)get_user_meta( $user_id, 'si50_travel_destinations', true ));
        $travel_styles = implode(', ', (array)get_user_meta( $user_id, 'si50_travel_styles', true ));
        $travel = $travel_dest;
        if ( !empty($travel_styles) ) $travel .= (empty($travel) ? '' : ' | ') . $travel_styles;
        
        $about = get_user_meta( $user_id, 'si50_introduction', true );
        $reg_date = date('F j, Y', strtotime($user->user_registered));
        
        // Fix for photo
        $photo_url = get_user_meta( $user_id, 'si50_verification_selfie_url', true );
        
        // Extended fields
        $dob = get_user_meta( $user_id, 'si50_dob', true ) ?: 'Not provided';
        $tob = get_user_meta( $user_id, 'si50_tob', true ) ?: 'Not provided';
        $pob = get_user_meta( $user_id, 'si50_pob', true ) ?: 'Not provided';
        $marital = get_user_meta( $user_id, 'si50_marital_status', true ) ?: 'Not provided';
        $lifestyle = get_user_meta( $user_id, 'si50_lifestyle', true ) ?: 'Not provided';
        
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Member Card - <?php echo esc_attr($name); ?></title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Great+Vibes&family=Playfair+Display:ital,wght@1,600&display=swap" rel="stylesheet">
            <style>
                :root {
                    --gold: #dfb15b;
                    --dark-blue: #0b2545;
                    --mid-blue: #134074;
                    --light-blue: #8da9c4;
                    --bg-blue: #eef4ed;
                    --text-dark: #222;
                    --text-light: #555;
                }
                body {
                    font-family: 'Poppins', sans-serif;
                    background: #d6e0f0;
                    margin: 0;
                    padding: 20px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                }
                .card-container {
                    width: 800px;
                    background: white;
                    position: relative;
                    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
                    overflow: hidden;
                    box-sizing: border-box;
                    padding: 3px;
                    background: linear-gradient(135deg, var(--gold) 0%, #f9f9f9 50%, var(--gold) 100%);
                    border-radius: 20px;
                }
                
                /* Decorative Borders */
                .inner-border {
                    border: 2px solid transparent;
                    border-radius: 17px;
                    padding: 35px 35px 0 35px;
                    position: relative;
                    background: url('data:image/svg+xml;utf8,<svg width="100" height="100" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path d="M0,0 Q50,0 50,50 T100,100" stroke="%238da9c4" stroke-width="0.5" fill="none" opacity="0.1"/></svg>'), linear-gradient(to bottom, #ffffff, #f4f7f6);
                    z-index: 2;
                }

                /* Corner Leaves SVGs */
                .corner-leaf { position: absolute; width: 140px; height: 140px; opacity: 0.8; z-index: 1; pointer-events: none; }
                .tl-leaf { top: -20px; left: -20px; transform: rotate(0deg); }
                .tr-leaf { top: -20px; right: -20px; transform: rotate(90deg); }
                .bl-leaf { bottom: 40px; left: -20px; transform: rotate(-90deg); }
                .br-leaf { bottom: 40px; right: -20px; transform: rotate(180deg); }
                
                .header-top {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-bottom: 20px;
                    position: relative;
                    z-index: 5;
                }
                
                .logo-area {
                    flex: 1;
                    display: flex;
                    align-items: center;
                    gap: 15px;
                }
                .logo-icon {
                    width: 50px;
                    height: 50px;
                    background: var(--dark-blue);
                    mask: url('data:image/svg+xml;utf8,<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>') no-repeat center / contain;
                    -webkit-mask: url('data:image/svg+xml;utf8,<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>') no-repeat center / contain;
                }
                .brand-name {
                    color: var(--mid-blue);
                    font-size: 32px;
                    font-weight: 700;
                    margin: 0;
                    line-height: 1;
                    letter-spacing: -0.5px;
                }
                .brand-tag {
                    color: var(--text-light);
                    font-size: 11px;
                    margin-top: 4px;
                    letter-spacing: 0.5px;
                }
                
                .verified-badge {
                    background: linear-gradient(135deg, var(--mid-blue) 0%, var(--dark-blue) 100%);
                    color: white;
                    padding: 8px 18px;
                    border-radius: 8px;
                    font-size: 11px;
                    font-weight: 600;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    box-shadow: 0 4px 15px rgba(11,37,69,0.3);
                    text-align: right;
                    line-height: 1.4;
                }
                .verified-badge svg { width: 24px; height: 24px; fill: white; }
                
                .id-pill-container {
                    text-align: center;
                    margin: 0px 0 25px;
                    position: relative;
                    z-index: 5;
                }
                .id-pill {
                    display: inline-flex;
                    align-items: center;
                    border: 2px solid var(--light-blue);
                    color: var(--mid-blue);
                    font-size: 26px;
                    font-weight: 700;
                    padding: 8px 45px;
                    border-radius: 40px;
                    background: white;
                    position: relative;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                }
                .id-pill::before, .id-pill::after {
                    content: '♥';
                    color: var(--gold);
                    font-size: 18px;
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                }
                .id-pill::before { left: 15px; }
                .id-pill::after { right: 15px; }
                
                .id-line {
                    position: absolute;
                    top: 50%;
                    left: 10%;
                    right: 10%;
                    height: 1px;
                    background: linear-gradient(90deg, transparent 0%, var(--gold) 50%, transparent 100%);
                    z-index: -1;
                }
                
                .main-grid {
                    display: flex;
                    gap: 25px;
                    margin-bottom: 20px;
                    position: relative;
                    z-index: 5;
                }
                
                .left-col {
                    flex: 1.25;
                }
                .right-col {
                    flex: 0.75;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
                
                .section-header {
                    background: linear-gradient(90deg, var(--dark-blue) 0%, var(--mid-blue) 100%);
                    color: white;
                    padding: 10px 18px;
                    border-radius: 8px 8px 0 0;
                    font-weight: 600;
                    font-size: 14px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                }
                
                .details-table {
                    width: 100%;
                    border-collapse: collapse;
                    background: white;
                    border-radius: 0 0 8px 8px;
                    border: 1px solid #dce8f5;
                    border-top: none;
                }
                .details-table tr {
                    border-bottom: 1px dashed #dce8f5;
                }
                .details-table tr:last-child {
                    border-bottom: none;
                }
                .details-table td {
                    padding: 10px 15px;
                    font-size: 12px;
                    vertical-align: top;
                }
                .details-table .lbl {
                    font-weight: 600;
                    color: var(--mid-blue);
                    width: 130px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }
                .details-table .val {
                    color: var(--text-dark);
                }
                
                .photo-wrapper {
                    border: 3px solid var(--gold);
                    border-radius: 12px;
                    padding: 6px;
                    background: white;
                    position: relative;
                    width: 100%;
                    box-sizing: border-box;
                    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
                }
                .photo {
                    width: 100%;
                    height: 270px;
                    object-fit: cover;
                    border-radius: 8px;
                }
                
                .fancy-text {
                    font-family: 'Great Vibes', cursive;
                    color: var(--mid-blue);
                    font-size: 32px;
                    text-align: center;
                    margin-top: 25px;
                    line-height: 1.1;
                    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
                }
                
                .two-col-sections {
                    display: flex;
                    gap: 20px;
                    margin-bottom: 15px;
                    position: relative;
                    z-index: 5;
                }
                .half-section {
                    flex: 1;
                    background: white;
                    border: 1px solid var(--mid-blue);
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                }
                .half-section .section-header {
                    border-radius: 0;
                    box-shadow: none;
                }
                .half-section .content {
                    padding: 15px;
                    font-size: 13px;
                    color: var(--text-dark);
                    min-height: 40px;
                }
                
                .full-section {
                    border: 1px solid var(--mid-blue);
                    border-radius: 8px;
                    margin-bottom: 15px;
                    overflow: hidden;
                    background: white;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                    position: relative;
                    z-index: 5;
                }
                .full-section .section-header {
                    border-radius: 0;
                    box-shadow: none;
                }
                .full-section .content {
                    padding: 15px;
                    font-size: 13px;
                    color: var(--text-dark);
                    line-height: 1.6;
                }
                
                .trust-badges {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 20px;
                    padding: 20px 0;
                    position: relative;
                    z-index: 5;
                }
                .trust-badge {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    font-size: 10px;
                    color: var(--mid-blue);
                    width: 23%;
                }
                .trust-icon {
                    background: var(--mid-blue);
                    color: white;
                    width: 32px;
                    height: 32px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 8px;
                    font-size: 16px;
                    flex-shrink: 0;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                }
                .trust-text strong {
                    display: block;
                    font-size: 11px;
                    color: var(--dark-blue);
                }
                
                .footer-banner {
                    background: linear-gradient(90deg, var(--dark-blue) 0%, var(--mid-blue) 100%);
                    color: white;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 20px 35px;
                    margin: 0 -35px;
                    border-bottom-left-radius: 17px;
                    border-bottom-right-radius: 17px;
                    position: relative;
                    z-index: 6;
                }
                .footer-lock {
                    display: flex;
                    align-items: center;
                    gap: 20px;
                }
                .footer-text strong {
                    font-size: 16px;
                    display: block;
                    letter-spacing: 0.5px;
                }
                .footer-text span {
                    font-size: 11px;
                    color: #d6e0f0;
                }
                
                @media print {
                    body { background: white; padding: 0; display: block; }
                    .card-container { width: 100%; box-shadow: none; border: none; padding: 0; margin: 0; }
                }
            </style>
        </head>
        <body>
            <div class="card-container">
                <div class="inner-border">
                    <!-- Leaf Graphics simulated with SVG -->
                    <svg class="corner-leaf tl-leaf" viewBox="0 0 100 100"><path d="M0,0 Q60,10 80,40 T100,100 Q40,90 20,60 T0,0" fill="#8da9c4" opacity="0.3"/><path d="M10,10 Q50,20 60,40 T80,80 Q40,70 30,50 T10,10" fill="#134074" opacity="0.4"/></svg>
                    <svg class="corner-leaf tr-leaf" viewBox="0 0 100 100"><path d="M0,0 Q60,10 80,40 T100,100 Q40,90 20,60 T0,0" fill="#8da9c4" opacity="0.3"/><path d="M10,10 Q50,20 60,40 T80,80 Q40,70 30,50 T10,10" fill="#134074" opacity="0.4"/></svg>
                    <svg class="corner-leaf bl-leaf" viewBox="0 0 100 100"><path d="M0,0 Q60,10 80,40 T100,100 Q40,90 20,60 T0,0" fill="#8da9c4" opacity="0.3"/><path d="M10,10 Q50,20 60,40 T80,80 Q40,70 30,50 T10,10" fill="#134074" opacity="0.4"/></svg>
                    <svg class="corner-leaf br-leaf" viewBox="0 0 100 100"><path d="M0,0 Q60,10 80,40 T100,100 Q40,90 20,60 T0,0" fill="#8da9c4" opacity="0.3"/><path d="M10,10 Q50,20 60,40 T80,80 Q40,70 30,50 T10,10" fill="#134074" opacity="0.4"/></svg>

                    <div class="header-top">
                        <div class="logo-area">
                            <div class="logo-icon"></div>
                            <div>
                                <h1 class="brand-name">SecondInnings50.in</h1>
                                <div class="brand-tag">Genuine Companionship. Meaningful Connections.</div>
                            </div>
                        </div>
                        <div class="verified-badge">
                            <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                            <div>VERIFIED MEMBER<br>PROFILE PREVIEW</div>
                        </div>
                    </div>
                    
                    <div class="id-pill-container">
                        <div class="id-line"></div>
                        <div class="id-pill"><?php echo esc_html($m_id); ?></div>
                        <div style="font-size: 10px; color: var(--gold); letter-spacing: 2px; margin-top: 10px; font-weight: 600;">NEW BEGINNINGS ♥ BRIGHTER TOMORROWS</div>
                    </div>
                    
                    <div class="main-grid">
                        <div class="left-col">
                            <div class="section-header">
                                👤 PROFILE DETAILS
                            </div>
                            <table class="details-table">
                                <tr><td class="lbl">🪪 Name</td><td class="val">: <?php echo esc_html($name); ?></td></tr>
                                <tr><td class="lbl">🎂 Age</td><td class="val">: <?php echo esc_html($age); ?></td></tr>
                                <tr><td class="lbl">📅 Date Of Birth</td><td class="val">: <?php echo esc_html($dob); ?></td></tr>
                                <tr><td class="lbl">⏰ Time Of Birth</td><td class="val">: <?php echo esc_html($tob); ?></td></tr>
                                <tr><td class="lbl">📍 Place Of Birth</td><td class="val">: <?php echo esc_html($pob); ?></td></tr>
                                <tr><td class="lbl">❤️ Marital Status</td><td class="val">: <?php echo esc_html($marital); ?></td></tr>
                                <tr><td class="lbl">💼 Occupation</td><td class="val">: <?php echo esc_html($occupation); ?></td></tr>
                                <tr><td class="lbl">🎯 Seeking Focus</td><td class="val">: <?php echo esc_html($focus); ?></td></tr>
                                <tr><td class="lbl">⭐ Lifestyle</td><td class="val">: <?php echo esc_html($lifestyle); ?></td></tr>
                                <tr><td class="lbl">🏙️ Location Pref.</td><td class="val">: <?php echo esc_html($city); ?></td></tr>
                                <tr><td class="lbl">🗓️ Registered On</td><td class="val">: <?php echo esc_html($reg_date); ?></td></tr>
                                <tr><td class="lbl">✔️ Vetting Status</td><td class="val">: Verified</td></tr>
                            </table>
                        </div>
                        
                        <div class="right-col">
                            <div class="photo-wrapper">
                                <?php if ($photo_url) : ?>
                                    <img src="<?php echo esc_url($photo_url); ?>" class="photo" alt="Member Photo">
                                <?php else: ?>
                                    <div class="photo" style="background:#f4f7f6; display:flex; align-items:center; justify-content:center; color:#8da9c4; font-weight:600; flex-direction:column; gap:10px;">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                        No Photo
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="fancy-text">
                                New<br>Connections<br>Brighter<br>Tomorrows
                            </div>
                            <div style="font-family:'Playfair Display', serif; font-size:10px; text-transform:uppercase; text-align:center; color:var(--gold); margin-top:15px; letter-spacing:1px; line-height:1.4;">
                                Same<br>Values<br>Brighter<br>Days
                            </div>
                        </div>
                    </div>
                    
                    <div class="two-col-sections">
                        <div class="half-section">
                            <div class="section-header">✈️ TRAVEL PREFERENCE</div>
                            <div class="content"><?php echo esc_html($travel); ?></div>
                        </div>
                        <div class="half-section">
                            <div class="section-header">⭐ INTERESTS & HOBBIES</div>
                            <div class="content"><?php echo esc_html($hobbies); ?></div>
                        </div>
                    </div>
                    
                    <div class="full-section">
                        <div class="section-header">👥 CIRCLES INTEREST</div>
                        <div class="content"><?php echo esc_html($circles); ?></div>
                    </div>
                    
                    <div class="full-section">
                        <div class="section-header">🎯 LOOKING FOR</div>
                        <div class="content"><?php echo esc_html($focus); ?></div>
                    </div>
                    
                    <div class="full-section">
                        <div class="section-header">👤 ABOUT ME</div>
                        <div class="content"><?php echo nl2br(esc_html($about)); ?></div>
                    </div>
                    
                    <div class="trust-badges">
                        <div class="trust-badge">
                            <div class="trust-icon">✔️</div>
                            <div class="trust-text"><strong>Verified Member</strong> Background verified by SecondInnings50.in team</div>
                        </div>
                        <div class="trust-badge">
                            <div class="trust-icon">👥</div>
                            <div class="trust-text"><strong>Carefully Matched</strong> Matched based on shared values and preferences</div>
                        </div>
                        <div class="trust-badge">
                            <div class="trust-icon">🔒</div>
                            <div class="trust-text"><strong>Privacy Protected</strong> Contact details are private and never shared</div>
                        </div>
                        <div class="trust-badge">
                            <div class="trust-icon">💙</div>
                            <div class="trust-text"><strong>Genuine Connections</strong> For meaningful companionship only</div>
                        </div>
                    </div>
                    
                    <div class="footer-banner">
                        <div class="footer-lock">
                            <div style="font-size: 36px; color: var(--gold);">🔒</div>
                            <div class="footer-text">
                                <strong>CONTACT DETAILS KEPT PRIVATE</strong>
                                <span>Will be shared only after mutual interest and consent from both members.</span>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 20px; font-weight: bold; letter-spacing: -0.5px;">SecondInnings50.in ♥</div>
                            <div style="font-size: 11px; color: #8da9c4; letter-spacing: 0.5px; margin-top:3px;">A Community for Meaningful Companionship</div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <script>
                // Delay print just slightly so fonts load
                setTimeout(() => { window.print(); }, 800);
            </script>
        </body>
        </html>
        <?php
        exit;
    }
}
add_action( 'init', 'si50_handle_download_profile_card' );
