<?php
/**
 * SecondInnings50 - Backend Administrative Dashboard Panel
 * Handles manual member approval vetting and tracks invitation requests.
 *
 * @package SecondInnings50
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * 1. Register Administrative Menu Page
 */
function si50_register_admin_dashboard_pages() {
	add_menu_page(
		esc_html__( 'SecondInnings50 Onboarding', 'secondinnings50' ),
		esc_html__( 'SI50 Onboarding', 'secondinnings50' ),
		'manage_options',
		'si50-onboarding-dashboard',
		'si50_render_admin_onboarding_page',
		'dashicons-id-alt',
		30
	);
}
add_action( 'admin_menu', 'si50_register_admin_dashboard_pages' );

/**
 * Enqueue Google Developer aesthetic styles for the onboarding dashboard.
 */
function si50_admin_dashboard_styles() {
	$screen = get_current_screen();
	if ( $screen && 'toplevel_page_si50-onboarding-dashboard' === $screen->id ) {
		?>
		<style>
			#wpbody-content {
				background: #f8f9fa !important;
			}
			.si50-admin-wrap {
				margin: 20px 20px 0 0;
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
			}
			.si50-admin-header-bar {
				background: #ffffff;
				border: 1px solid #e0e0e0;
				border-radius: 8px;
				padding: 16px 24px;
				margin-bottom: 20px;
				box-shadow: 0 1px 3px rgba(0,0,0,0.02);
			}
			.si50-admin-title {
				font-size: 22px;
				font-weight: 500;
				color: #202124;
				margin: 0;
				display: flex;
				align-items: center;
			}
			.si50-admin-layout {
				display: flex;
				gap: 24px;
				align-items: flex-start;
			}
			@media (max-width: 960px) {
				.si50-admin-layout {
					flex-direction: column;
				}
				.si50-admin-sidebar {
					width: 100% !important;
					position: static !important;
				}
			}
			.si50-admin-sidebar {
				width: 280px;
				flex-shrink: 0;
				position: sticky;
				top: 50px;
				background: #ffffff;
				border: 1px solid #e0e0e0;
				border-radius: 8px;
				padding: 20px;
				box-shadow: 0 2px 6px rgba(0,0,0,0.04);
				box-sizing: border-box;
			}
			.si50-admin-sidebar h3 {
				margin: 0 0 12px 0;
				font-size: 11px;
				font-weight: 700;
				text-transform: uppercase;
				color: #5f6368;
				letter-spacing: 0.8px;
			}
			.si50-stats-widget {
				background: #f8f9fa;
				border-radius: 6px;
				padding: 12px 14px;
				border: 1px solid #e0e0e0;
				margin-bottom: 20px;
			}
			.si50-stats-widget:last-child {
				margin-bottom: 0;
			}
			.si50-stat-row {
				display: flex;
				justify-content: space-between;
				margin-bottom: 8px;
				font-size: 12px;
				color: #5f6368;
			}
			.si50-stat-row:last-child {
				margin-bottom: 0;
			}
			.si50-stat-value {
				font-weight: 700;
				color: #202124;
			}
			/* Global box-sizing reset for all dashboard panel content to prevent card overwidth */
			.si50-admin-wrap,
			.si50-admin-wrap * {
				box-sizing: border-box !important;
			}
			.si50-admin-main {
				flex-grow: 1;
				min-width: 0;
				max-width: 100%;
				display: flex;
				flex-direction: column;
				gap: 24px;
			}
			.si50-card {
				background: #ffffff;
				border: 1px solid #e0e0e0;
				border-radius: 8px;
				box-shadow: 0 2px 6px rgba(0,0,0,0.04);
				padding: 24px;
				width: 100% !important;
				max-width: 100% !important;
				overflow: hidden !important;
			}
			.si50-card-title {
				margin: 0 0 16px 0;
				font-size: 18px;
				font-weight: 500;
				color: #202124;
				border-bottom: 1px solid #e0e0e0;
				padding-bottom: 12px;
				display: flex;
				align-items: center;
				justify-content: space-between;
				flex-wrap: wrap;
				gap: 12px;
			}
			.si50-badge {
				display: inline-block;
				padding: 4px 10px;
				font-size: 11px;
				font-weight: 600;
				border-radius: 12px;
				line-height: 1.2;
			}
			.si50-badge-approved {
				background-color: #e6f4ea;
				color: #137333;
				border: 1px solid #ceead6;
			}
			.si50-badge-pending {
				background-color: #fef7e0;
				color: #b06000;
				border: 1px solid #fde293;
			}
			.si50-badge-rejected {
				background-color: #fce8e6;
				color: #c5221f;
				border: 1px solid #fad2cf;
			}
			.si50-table-container {
				width: 100% !important;
				max-width: 100% !important;
				overflow-x: auto !important;
				-webkit-overflow-scrolling: touch;
				margin-top: 10px;
				border: 1px solid #e0e0e0;
				border-radius: 6px;
			}
			.si50-list-table {
				width: 100%;
				border-collapse: collapse;
				margin-top: 10px;
				font-size: 13px;
				text-align: left;
			}
			.si50-list-table th {
				padding: 12px 10px;
				border-bottom: 2px solid #e0e0e0;
				color: #5f6368;
				font-weight: 600;
				background-color: #f8f9fa;
			}
			.si50-list-table td {
				padding: 14px 10px;
				border-bottom: 1px solid #e0e0e0;
				color: #3c4043;
				vertical-align: top;
				line-height: 1.4;
			}
			.si50-list-table tr:hover td {
				background-color: #fcfcfc;
			}
			.si50-list-table tr:last-child td {
				border-bottom: none;
			}
			.si50-modal-backdrop {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background-color: rgba(32, 33, 36, 0.6);
				backdrop-filter: blur(4px);
				-webkit-backdrop-filter: blur(4px);
				z-index: 999999;
				display: flex;
				align-items: center;
				justify-content: center;
				opacity: 0;
				pointer-events: none;
				transition: opacity 0.25s ease-in-out;
			}
			.si50-modal-backdrop.show {
				opacity: 1;
				pointer-events: auto;
			}
			.si50-modal-content {
				background: #ffffff;
				border: 3px solid #333333;
				border-radius: 8px;
				width: 90%;
				max-width: 500px;
				box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
				position: relative;
				padding: 24px;
				transform: scale(0.92);
				transition: transform 0.25s ease-in-out;
				box-sizing: border-box;
			}
			.si50-modal-backdrop.show .si50-modal-content {
				transform: scale(1);
			}
			.si50-modal-close {
				position: absolute;
				top: 16px;
				right: 16px;
				background: none;
				border: none;
				font-size: 26px;
				line-height: 1;
				cursor: pointer;
				color: #5f6368;
				padding: 4px;
				font-weight: 700;
			}
			.si50-modal-close:hover {
				color: #202124;
			}
			.si50-modal-header {
				margin: 0 0 16px 0;
				font-size: 1.25rem;
				font-weight: 700;
				color: #202124;
			}
			.si50-modal-body {
				font-size: 0.95rem;
				line-height: 1.5;
				color: #3c4043;
				margin-bottom: 24px;
			}
			.si50-modal-body label {
				display: block;
				font-weight: 700;
				margin-bottom: 8px;
				font-size: 0.9rem;
				color: #202124;
			}
			.si50-modal-body textarea {
				width: 100%;
				border: 2px solid #333333;
				border-radius: 4px;
				padding: 10px;
				font-size: 14px;
				background-color: #ffffff;
				box-sizing: border-box;
				color: #202124;
			}
			.si50-modal-footer {
				display: flex;
				justify-content: flex-end;
				gap: 12px;
			}
			.si50-btn-cancel {
				background-color: #ffffff;
				border: 2px solid #333333;
				color: #3c4043;
				padding: 8px 18px;
				border-radius: 4px;
				cursor: pointer;
				font-weight: 700;
				font-size: 13px;
				transition: background-color 0.15s;
			}
			.si50-btn-cancel:hover {
				background-color: #f1f3f4;
			}
			.si50-btn-confirm {
				background-color: #c5221f;
				border: 2px solid #333333;
				color: #ffffff;
				padding: 8px 18px;
				border-radius: 4px;
				cursor: pointer;
				font-weight: 700;
				font-size: 13px;
				transition: background-color 0.15s;
			}
			.si50-btn-confirm:hover {
				background-color: #b01f1c;
			}
			
			/* High-contrast styles for Eye-icon drawer sections */
			.si50-sec-title {
				margin: 0 0 12px 0; 
				font-size: 14px; 
				font-weight: 700; 
				text-transform: uppercase; 
				color: #1B3B2B; 
				border-bottom: 2px solid #333333; 
				padding-bottom: 6px;
			}
			.si50-sec-box {
				background: #ffffff; 
				border: 2.5px solid #333333; 
				border-radius: 6px; 
				padding: 16px; 
				box-shadow: 0 2px 6px rgba(0,0,0,0.04);
				box-sizing: border-box;
			}
			.si50-sec-tag {
				font-size: 11px; 
				font-weight: 600; 
				padding: 2px 6px; 
				border-radius: 4px;
				display: inline-block;
				margin-bottom: 4px;
			}
			.si50-action-btn {
				border: 2px solid #333333 !important;
				border-radius: 4px !important;
				padding: 6px 14px !important;
				font-size: 12px !important;
				font-weight: 700 !important;
				cursor: pointer !important;
				text-decoration: none !important;
				display: inline-flex !important;
				align-items: center !important;
				gap: 4px !important;
				transition: background-color 0.15s !important;
			}
			.si50-btn-approve-primary {
				background-color: #1a73e8 !important;
				color: #ffffff !important;
			}
			.si50-btn-approve-primary:hover {
				background-color: #155cb0 !important;
			}
			.si50-btn-reject-link {
				background-color: #ffffff !important;
				color: #c5221f !important;
			}
			.si50-btn-reject-link:hover {
				background-color: #fce8e6 !important;
			}
		</style>
		<?php
	}
}
add_action( 'admin_head', 'si50_admin_dashboard_styles' );

/**
 * Render Administrative Modals in Footer.
 */
function si50_render_admin_modals() {
	$screen = get_current_screen();
	if ( $screen && 'toplevel_page_si50-onboarding-dashboard' === $screen->id ) {
		?>
		<div id="si50-admin-modal" class="si50-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="si50-modal-title">
			<div class="si50-modal-content">
				<button type="button" class="si50-modal-close" id="si50-modal-close-btn" aria-label="Close modal">&times;</button>
				<h3 class="si50-modal-header" id="si50-modal-title">Confirm Action</h3>
				<div class="si50-modal-body" id="si50-modal-body">
					<!-- Dynamic Content -->
				</div>
				<div class="si50-modal-footer">
					<button type="button" class="si50-btn-cancel" id="si50-modal-cancel-btn"><?php esc_html_e( 'Cancel', 'secondinnings50' ); ?></button>
					<button type="button" class="si50-btn-confirm" id="si50-modal-confirm-btn"><?php esc_html_e( 'Confirm Action', 'secondinnings50' ); ?></button>
				</div>
			</div>
		</div>

		<script>
		document.addEventListener("DOMContentLoaded", function() {
			var modal = document.getElementById("si50-admin-modal");
			var closeBtn = document.getElementById("si50-modal-close-btn");
			var cancelBtn = document.getElementById("si50-modal-cancel-btn");
			var confirmBtn = document.getElementById("si50-modal-confirm-btn");
			var titleElem = document.getElementById("si50-modal-title");
			var bodyElem = document.getElementById("si50-modal-body");
			
			var redirectUrl = "";
			var isRejectionForm = false;

			function openModal(title, bodyHTML, onConfirm, isReject) {
				titleElem.innerText = title;
				bodyElem.innerHTML = bodyHTML;
				isRejectionForm = !!isReject;
				
				modal.classList.add("show");
				
				var newConfirmBtn = confirmBtn.cloneNode(true);
				confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
				confirmBtn = newConfirmBtn;
				
				confirmBtn.addEventListener("click", function() {
					if (isRejectionForm) {
						var form = document.getElementById("si50-modal-reject-form");
						if (form) {
							if (!form.reportValidity()) {
								return;
							}
							form.submit();
						}
					} else {
						if (typeof onConfirm === "function") {
							onConfirm();
						} else if (redirectUrl) {
							window.location.href = redirectUrl;
						}
					}
				});
			}

			function closeModal() {
				modal.classList.remove("show");
			}

			closeBtn.addEventListener("click", closeModal);
			cancelBtn.addEventListener("click", closeModal);
			
			modal.addEventListener("click", function(e) {
				if (e.target === modal) {
					closeModal();
				}
			});

			// Capture Approve clicks
			var approveBtns = document.querySelectorAll(".si50-admin-approve-btn");
			approveBtns.forEach(function(btn) {
				btn.addEventListener("click", function(e) {
					e.preventDefault();
					var url = this.getAttribute("href");
					var username = this.getAttribute("data-username");
					redirectUrl = url;
					
					var bodyHTML = "<p>" + 
						"<?php echo esc_js( __( 'Are you sure you want to approve the membership application for ', 'secondinnings50' ) ); ?><strong>" + escapeHTML(username) + "</strong>?" +
						"</p><p>" +
						"<?php echo esc_js( __( 'This will activate their profile in the directory and automatically dispatch their professional welcome email.', 'secondinnings50' ) ); ?>" +
						"</p>";
					
					openModal("<?php echo esc_js( __( 'Confirm Member Approval Request', 'secondinnings50' ) ); ?>", bodyHTML, null, false);
				});
			});

			// Capture Reject clicks
			var rejectBtns = document.querySelectorAll(".si50-admin-reject-btn");
			rejectBtns.forEach(function(btn) {
				btn.addEventListener("click", function(e) {
					e.preventDefault();
					var username = this.getAttribute("data-username");
					var userId = this.getAttribute("data-user-id");
					var nonce = this.getAttribute("data-nonce");
					
					var actionUrl = "admin.php?page=si50-onboarding-dashboard&action=reject_confirm&user_id=" + userId + "&_wpnonce=" + nonce;
					
					var bodyHTML = '<form id="si50-modal-reject-form" method="post" action="' + actionUrl + '">' +
						'<p style="color: #666; font-size: 0.95em; line-height: 1.5; margin-bottom: 15px;">' +
						'<?php echo esc_js( __( 'Please specify a professional note or reason for declining this member\'s onboarding application. This note will be sent directly to the user in their notification email.', 'secondinnings50' ) ); ?>' +
						'</p>' +
						'<div style="margin-bottom: 10px;">' +
						'<label for="modal_rejection_note" style="display: block; font-weight: bold; margin-bottom: 8px;">' +
						'<?php echo esc_js( __( 'Rejection Note / Feedback:', 'secondinnings50' ) ); ?>' +
						'</label>' +
						'<textarea id="modal_rejection_note" name="rejection_note" rows="5" required style="width:100%; border:2px solid #333333; padding:8px; box-sizing:border-box;" placeholder="' +
						'<?php echo esc_js( __( 'e.g. Please supply a valid WhatsApp phone number or real name to verify authenticity.', 'secondinnings50' ) ); ?>' +
						'"></textarea>' +
						'</div>' +
						'</form>';
					
					openModal("<?php echo esc_js( __( 'Confirm Member Rejection Request', 'secondinnings50' ) ); ?>", bodyHTML, null, true);
				});
			});

			// Capture Suspend clicks
			var suspendBtns = document.querySelectorAll(".si50-admin-suspend-btn");
			suspendBtns.forEach(function(btn) {
				btn.addEventListener("click", function(e) {
					e.preventDefault();
					var url = this.getAttribute("href");
					var username = this.getAttribute("data-username");
					redirectUrl = url;
					
					var bodyHTML = "<p>" + 
						"<?php echo esc_js( __( 'Are you sure you want to suspend the member ', 'secondinnings50' ) ); ?><strong>" + escapeHTML(username) + "</strong>?" +
						"</p><p>" +
						"<?php echo esc_js( __( 'This will immediately ban them from browsing the directory, lock their profile, and resolve this active report.', 'secondinnings50' ) ); ?>" +
						"</p>";
					
					openModal("<?php echo esc_js( __( 'Confirm Member Suspension', 'secondinnings50' ) ); ?>", bodyHTML, null, false);
				});
			});

			// Toggle user drawer (Preference details inspection)
			var toggleBtns = document.querySelectorAll(".si50-drawer-toggle-btn");
			toggleBtns.forEach(function(btn) {
				btn.addEventListener("click", function(e) {
					e.preventDefault();
					var userId = this.getAttribute("data-user-id");
					var drawerRow = document.getElementById("si50-drawer-row-" + userId);
					if (!drawerRow) return;

					var isExpanded = this.getAttribute("aria-expanded") === "true";
					this.setAttribute("aria-expanded", !isExpanded);
					
					var icon = this.querySelector(".dashicons");
					
					if (!isExpanded) {
						drawerRow.style.display = "table-row";
						if (icon) {
							icon.classList.remove("dashicons-visibility");
							icon.classList.add("dashicons-hidden");
						}
					} else {
						drawerRow.style.display = "none";
						if (icon) {
							icon.classList.remove("dashicons-hidden");
							icon.classList.add("dashicons-visibility");
						}
					}
				});
			});

			// Toggle inquiry message drawer
			var inquiryToggleBtns = document.querySelectorAll(".si50-inquiry-drawer-toggle-btn");
			inquiryToggleBtns.forEach(function(btn) {
				btn.addEventListener("click", function(e) {
					e.preventDefault();
					var inquiryId = this.getAttribute("data-inquiry-id");
					var drawerRow = document.getElementById("si50-inquiry-drawer-row-" + inquiryId);
					if (!drawerRow) return;

					var isExpanded = this.getAttribute("aria-expanded") === "true";
					
					var groupBtns = document.querySelectorAll('[data-inquiry-id="' + inquiryId + '"]');
					groupBtns.forEach(function(el) {
						el.setAttribute("aria-expanded", !isExpanded);
						var icon = el.querySelector(".dashicons");
						if (icon) {
							if (!isExpanded) {
								icon.classList.remove("dashicons-visibility");
								icon.classList.add("dashicons-hidden");
							} else {
								icon.classList.remove("dashicons-hidden");
								icon.classList.add("dashicons-visibility");
							}
						}
					});
					
					if (!isExpanded) {
						drawerRow.style.display = "table-row";
					} else {
						drawerRow.style.display = "none";
					}
				});
			});

			function escapeHTML(str) {
				return str.replace(/[&<>'"]/g, 
					tag => ({
						'&': '&amp;',
						'<': '&lt;',
						'>': '&gt;',
						"'": '&#39;',
						'"': '&quot;'
					}[tag] || tag)
				);
			}
		});
		</script>
		<?php
	}
}
add_action( 'admin_footer', 'si50_render_admin_modals' );

/**
 * Track modifications to member preferences.
 */
function si50_track_preference_modifications( $meta_id, $object_id, $meta_key, $_meta_value ) {
	$target_keys = array(
		'si50_looking_for',
		'si50_circles_interest',
		'si50_travel_destinations',
		'si50_travel_styles',
		'si50_companion_styles',
		'si50_introduction'
	);
	if ( in_array( $meta_key, $target_keys, true ) ) {
		remove_action( 'updated_user_meta', 'si50_track_preference_modifications', 10 );
		remove_action( 'added_user_meta', 'si50_track_preference_modifications', 10 );
		update_user_meta( $object_id, 'si50_preferences_modified_date', current_time( 'Y-m-d' ) );
		add_action( 'updated_user_meta', 'si50_track_preference_modifications', 10, 4 );
		add_action( 'added_user_meta', 'si50_track_preference_modifications', 10, 4 );
	}
}
add_action( 'updated_user_meta', 'si50_track_preference_modifications', 10, 4 );
add_action( 'added_user_meta', 'si50_track_preference_modifications', 10, 4 );

/**
 * 2. Process Admin Approval/Rejection Actions
 */
function si50_handle_admin_actions() {
	global $wpdb;

	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Process WhatsApp Settings Save
	if ( isset( $_POST['si50_save_whatsapp_settings'] ) ) {
		if ( ! isset( $_POST['si50_wa_nonce'] ) || ! wp_verify_nonce( $_POST['si50_wa_nonce'], 'si50_admin_whatsapp_settings' ) ) {
			wp_die( esc_html__( 'Security validation check failed.', 'secondinnings50' ) );
		}

		// Process default WhatsApp groups (names, URLs, and descriptions)
		$default_keys = array(
			'si50_wa_mumbai',
			'si50_wa_delhi',
			'si50_wa_bangalore',
			'si50_wa_pune',
			'si50_wa_hyderabad',
			'si50_wa_chennai',
			'si50_wa_kolkata',
			'si50_wa_womens_lounge',
			'si50_wa_fallback',
		);

		foreach ( $default_keys as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_option( $key, esc_url_raw( trim( $_POST[ $key ] ) ) );
			} else {
				// Clear URL if group deleted
				update_option( $key, '' );
			}

			if ( isset( $_POST[ $key . '_name' ] ) ) {
				update_option( $key . '_name', sanitize_text_field( trim( $_POST[ $key . '_name' ] ) ) );
			} else {
				update_option( $key . '_name', '' );
			}

			if ( isset( $_POST[ $key . '_desc' ] ) ) {
				update_option( $key . '_desc', sanitize_text_field( trim( $_POST[ $key . '_desc' ] ) ) );
			} else {
				update_option( $key . '_desc', '' );
			}
		}

if ( isset( $_POST['si50_wa_womens_lounge_capacity'] ) ) {
				$capacity = intval( $_POST['si50_wa_womens_lounge_capacity'] );
				update_option( 'si50_wa_womens_lounge_capacity', max( 0, $capacity ) );
			}

			// Process custom WhatsApp groups
		if ( isset( $_POST['custom_groups_name'] ) && isset( $_POST['custom_groups_url'] ) ) {
			$names = (array) $_POST['custom_groups_name'];
			$urls  = (array) $_POST['custom_groups_url'];
			$descs = isset( $_POST['custom_groups_desc'] ) ? (array) $_POST['custom_groups_desc'] : array();

			$custom_groups = array();
			for ( $i = 0; $i < count( $names ); $i++ ) {
				$name = sanitize_text_field( trim( $names[ $i ] ) );
				$url  = esc_url_raw( trim( $urls[ $i ] ) );
				$desc = isset( $descs[ $i ] ) ? sanitize_text_field( trim( $descs[ $i ] ) ) : '';

				if ( ! empty( $name ) && ! empty( $url ) ) {
					$custom_groups[] = array(
						'name' => $name,
						'url'  => $url,
						'desc' => $desc,
					);
				}
			}
			update_option( 'si50_custom_whatsapp_groups', $custom_groups );
		} else {
			update_option( 'si50_custom_whatsapp_groups', array() );
		}

		wp_safe_redirect( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=whatsapp_manager&msg=wa_saved' ) );
		exit;
	}

	if ( isset( $_GET['page'] ) && 'si50-onboarding-dashboard' === $_GET['page'] ) {
		if ( isset( $_GET['action'] ) ) {
			$action  = sanitize_text_field( $_GET['action'] );
			$user_id = isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : 0;
			$nonce   = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';

			// Verify general vetting nonce for vetting actions
			if ( in_array( $action, array( 'approve', 'reject_confirm', 'toggle_badge', 'suspend_member', 'resolve_report', 'toggle_pause', 'resolve_interest' ), true ) ) {
				if ( ! wp_verify_nonce( $nonce, 'si50_admin_vetting_action' ) ) {
					wp_die( esc_html__( 'Security validation check failed.', 'secondinnings50' ) );
				}
			}

			if ( 'approve' === $action ) {
				update_user_meta( $user_id, 'si50_vetting_status', 'approved' );
				update_user_meta( $user_id, 'si50_vetting_time', current_time( 'mysql' ) );

				// Send WhatsApp Welcome Message Webhook
				$user_phone = get_user_meta( $user_id, 'si50_phone', true );
				if ( ! empty( $user_phone ) ) {
					$welcome_msg = esc_html__( "Welcome to SecondInnings50! Your membership application has been manually reviewed and fully verified by our onboarding hosts. You can now log into the platform, browse the directory, and connect with compatible peers. Link: https://secondinnings50.in", 'secondinnings50' );
					si50_send_whatsapp_alert( $user_phone, $welcome_msg );
				}

				// Professional HTML welcome email
				$user = get_userdata( $user_id );
				if ( $user ) {
					si50_email_trigger_vetting_approved( $user_id );
					error_log( sprintf( 'SecondInnings50 Vetting - Approved user %d (%s) and sent HTML email.', $user_id, $user->user_email ) );
				}
				// Audit Log entry
				si50_log_activity( $user_id, 'approved', esc_html__( 'Member application manually approved by administrator.', 'secondinnings50' ) );

				wp_safe_redirect( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=applications&msg=approved' ) );
				exit;
			} elseif ( 'reject_confirm' === $action ) {
				$note = isset( $_POST['rejection_note'] ) ? sanitize_textarea_field( $_POST['rejection_note'] ) : '';
				update_user_meta( $user_id, 'si50_vetting_status', 'rejected' );
				update_user_meta( $user_id, 'si50_rejection_note', $note );
				// Professional HTML rejection email
				$user = get_userdata( $user_id );
				if ( $user ) {
					si50_email_trigger_vetting_rejected( $user_id, $note );
					error_log( sprintf( 'SecondInnings50 Vetting - Rejected user %d (%s), Note: %s, HTML email sent.', $user_id, $user->user_email, $note ) );
				}
				// Audit Log entry
				si50_log_activity( $user_id, 'rejected', sprintf( esc_html__( 'Member application rejected by administrator. Note: %s', 'secondinnings50' ), $note ) );

				wp_safe_redirect( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=applications&msg=rejected' ) );
				exit;
			} elseif ( 'toggle_badge' === $action ) {
				$is_verified = get_user_meta( $user_id, 'si50_verified_badge', true );
				if ( $is_verified ) {
					delete_user_meta( $user_id, 'si50_verified_badge' );
					si50_log_activity( $user_id, 'badge_removed', esc_html__( 'Verification badge removed by administrator.', 'secondinnings50' ) );
				} else {
					update_user_meta( $user_id, 'si50_verified_badge', '1' );
					si50_log_activity( $user_id, 'badge_awarded', esc_html__( 'Verification badge awarded by administrator.', 'secondinnings50' ) );
				}
				wp_safe_redirect( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=applications&msg=badge_toggled' ) );
				exit;
			} elseif ( 'toggle_payment' === $action ) {
				$payment_status = get_user_meta( $user_id, 'si50_payment_status', true );
				if ( 'paid' === $payment_status ) {
					update_user_meta( $user_id, 'si50_payment_status', 'unpaid' );
					si50_log_activity( $user_id, 'payment_unpaid', esc_html__( 'Payment status set to UNPAID by administrator.', 'secondinnings50' ) );
				} else {
					update_user_meta( $user_id, 'si50_payment_status', 'paid' );
					si50_log_activity( $user_id, 'payment_paid', esc_html__( 'Payment status set to PAID by administrator.', 'secondinnings50' ) );
				}
				wp_safe_redirect( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=directory&msg=payment_toggled' ) );
				exit;
			} elseif ( 'toggle_tier' === $action ) {
				$membership_tier = get_user_meta( $user_id, 'si50_membership_status', true );
				if ( 'premium' === $membership_tier ) {
					update_user_meta( $user_id, 'si50_membership_status', 'pending' ); // pending or basic
					si50_log_activity( $user_id, 'tier_basic', esc_html__( 'Membership tier downgraded to Registration (₹99) by administrator.', 'secondinnings50' ) );
				} else {
					update_user_meta( $user_id, 'si50_membership_status', 'premium' );
					si50_log_activity( $user_id, 'tier_premium', esc_html__( 'Membership tier upgraded to Complete (₹599) by administrator.', 'secondinnings50' ) );
				}
				wp_safe_redirect( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=applications&msg=tier_toggled' ) );
				exit;
			} elseif ( 'toggle_pause' === $action ) {
				$is_paused = get_user_meta( $user_id, 'si50_compatibility_paused', true );
				if ( $is_paused ) {
					delete_user_meta( $user_id, 'si50_compatibility_paused' );
					si50_log_activity( $user_id, 'profile_unpaused', esc_html__( 'Member profile unpaused by administrator.', 'secondinnings50' ) );
				} else {
					update_user_meta( $user_id, 'si50_compatibility_paused', '1' );
					si50_log_activity( $user_id, 'profile_paused', esc_html__( 'Member profile paused (Compatibility Period) by administrator.', 'secondinnings50' ) );
				}
				wp_safe_redirect( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=applications&msg=pause_toggled' ) );
				exit;
			} elseif ( 'resolve_interest' === $action ) {
				$req_id = isset( $_GET['req_id'] ) ? intval( $_GET['req_id'] ) : 0;
				if ( $req_id ) {
					$wpdb->update(
						"{$wpdb->prefix}si50_connect_requests",
						array( 'status' => 'resolved' ),
						array( 'id' => $req_id ),
						array( '%s' ),
						array( '%d' )
					);
				}
				wp_safe_redirect( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=interest_submissions&msg=resolved_interest' ) );
				exit;
			} elseif ( 'suspend_member' === $action ) {
				update_user_meta( $user_id, 'si50_vetting_status', 'suspended' );
				
				$report_id = isset( $_GET['report_id'] ) ? intval( $_GET['report_id'] ) : 0;
				if ( $report_id ) {
					$wpdb->update(
						"{$wpdb->prefix}si50_member_reports",
						array( 'status' => 'resolved' ),
						array( 'id' => $report_id ),
						array( '%s' ),
						array( '%d' )
					);
				}
				
				si50_log_activity( $user_id, 'suspended', sprintf( esc_html__( 'User account has been suspended by administration. Report ID: %d', 'secondinnings50' ), $report_id ) );
				
				wp_safe_redirect( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=safety_reports&msg=suspended' ) );
				exit;
			} elseif ( 'resolve_report' === $action ) {
				$report_id = isset( $_GET['report_id'] ) ? intval( $_GET['report_id'] ) : 0;
				if ( $report_id ) {
					$wpdb->update(
						"{$wpdb->prefix}si50_member_reports",
						array( 'status' => 'resolved' ),
						array( 'id' => $report_id ),
						array( '%s' ),
						array( '%d' )
					);
				}
				
				wp_safe_redirect( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=safety_reports&msg=resolved_report' ) );
				exit;
			} 
			
			// Individual Member Profile Dossiers Exporter (A4 compilation, Section A, B, C, D)
			elseif ( 'download_pdf' === $action ) {
				if ( ! wp_verify_nonce( $nonce, 'si50_admin_vetting_action' ) ) {
					wp_die( esc_html__( 'Security validation check failed.', 'secondinnings50' ) );
				}

				$user = get_userdata( $user_id );
				if ( ! $user ) {
					wp_die( esc_html__( 'User not found.', 'secondinnings50' ) );
				}

				$pdf_gen = new SI50_PDF_Generator();

				// PDF Title Header
				$pdf_gen->set_font( 'F2', 14 );
				$pdf_gen->write_text( 50, 800, 'SECONDINNINGS50 - MEMBER PROFILE APPLICATION DOSSIER' );
				$pdf_gen->set_font( 'F1', 8.5 );
				$pdf_gen->write_text( 50, 786, 'Confidential Onboarding Dossier for Verification & Archival' );
				$pdf_gen->draw_line( 50, 776, 545, 776 );

				// Fetch User Metadata
				$name = get_user_meta( $user_id, 'si50_fullname', true );
				if ( empty( $name ) ) {
					$name = $user->display_name;
				}
				$email = $user->user_email;
				$phone = get_user_meta( $user_id, 'si50_phone', true );
				$city_state = get_user_meta( $user_id, 'si50_city_state', true );
				$dob = get_user_meta( $user_id, 'si50_dob', true );
				$age_bracket = get_user_meta( $user_id, 'si50_age_bracket', true );
				$marital_status = get_user_meta( $user_id, 'si50_marital_status', true );
				$occupation = get_user_meta( $user_id, 'si50_occupation', true );
				$emergency = get_user_meta( $user_id, 'si50_emergency_phone', true );
				$gender = get_user_meta( $user_id, 'si50_gender', true );
				
				$reg_date = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $user->user_registered ) );
				$vetting_status = get_user_meta( $user_id, 'si50_vetting_status', true );
				if ( empty( $vetting_status ) ) {
					$vetting_status = 'pending_review';
				}
				$vetting_time = get_user_meta( $user_id, 'si50_vetting_time', true );
				$ver_timestamp = ! empty( $vetting_time ) ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $vetting_time ) ) : 'N/A';

				// Compute Age
				$age = '';
				if ( ! empty( $dob ) ) {
					$dob_time = strtotime( $dob );
					if ( $dob_time ) {
						$age = date_diff( date_create( $dob ), date_create( 'today' ) )->y;
					}
				}

				// Fetch Preferences Meta
				$looking_for = (array) get_user_meta( $user_id, 'si50_looking_for', true );
				$connection_intent = get_user_meta( $user_id, 'si50_connection_intent', true );
				$circles_interest = (array) get_user_meta( $user_id, 'si50_circles_interest', true );
				$travel_dest = (array) get_user_meta( $user_id, 'si50_travel_destinations', true );
				$travel_styles = (array) get_user_meta( $user_id, 'si50_travel_styles', true );
				$companion_styles = get_user_meta( $user_id, 'si50_companion_styles', true );
				$loc_pref = get_user_meta( $user_id, 'si50_location_preference', true );
				$hobbies = (array) get_user_meta( $user_id, 'si50_hobbies_interests', true );
				$travel_dest_other = get_user_meta( $user_id, 'si50_travel_destinations_other', true );
				$selfie_url = get_user_meta( $user_id, 'si50_verification_selfie_url', true );
				$special_incentives = (array) get_user_meta( $user_id, 'si50_special_incentives', true );
				$intro = get_user_meta( $user_id, 'si50_introduction', true );

				// Section A: Demographics
				$pdf_gen->set_font( 'F2', 10 );
				$pdf_gen->write_text( 50, 755, 'SECTION A: PROFILE IDENTITY & DEMOGRAPHICS' );
				$pdf_gen->draw_line( 50, 750, 545, 750 );

				// Two columns for Demographics to save height and keep it single page
				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, 735, 'Real Name:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 140, 735, $name );

				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, 722, 'Email Address:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 140, 722, $email );

				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, 709, 'WhatsApp Phone:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 140, 709, $phone );

				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, 696, 'Emergency Contact:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 140, 696, ( ! empty( $emergency ) ? $emergency . ' (Private)' : 'N/A' ) );

				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, 683, 'City/State:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 140, 683, $city_state );

				// Right Column
				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 310, 735, 'Age / DOB:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 395, 735, ( $age ? $age . ' years' : 'N/A' ) . ' (DOB: ' . $dob . ')' );

				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 310, 722, 'Gender / Marital:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 395, 722, $gender . ' / ' . $marital_status );

				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 310, 709, 'Occupation:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 395, 709, $occupation );

				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 310, 696, 'Registered On:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 395, 696, $reg_date );

				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 310, 683, 'Vetting State:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 395, 683, strtoupper( $vetting_status ) . ' (' . $ver_timestamp . ')' );

				// Outline Border Demographics Block
				$pdf_gen->draw_rect( 48, 672, 500, 72 );

				// Section B: Matching Intentions
				$y = 650;
				$pdf_gen->draw_heading( 'SECTION B: MATCHING INTENTIONS', $y );
				$y -= 18;
				
				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, $y, 'Location Preference:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 180, $y, ! empty( $loc_pref ) ? $loc_pref : 'None selected' );
				$y -= 14;

				$lf_text = ! empty( $looking_for ) ? implode( ', ', $looking_for ) : 'None selected';
				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, $y, 'Seeking Focus (Relationship Checkboxes):' );
				$y -= 12;
				$pdf_gen->set_font( 'F1', 8 );
				$pdf_gen->write_wrapped_text( 65, $y, $lf_text, 470, 11 );
				$y -= 4;

				$hb_text = ! empty( $hobbies ) ? implode( ', ', $hobbies ) : 'None selected';
				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, $y, 'Hobbies & Interests Checklist:' );
				$y -= 12;
				$pdf_gen->set_font( 'F1', 8 );
				$pdf_gen->write_wrapped_text( 65, $y, $hb_text, 470, 11 );
				$y -= 14;

				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, $y, 'Relationship Intentions (Custom Text):' );
				$y -= 12;
				$pdf_gen->set_font( 'F1', 8 );
				$pdf_gen->write_wrapped_text( 65, $y, ! empty( $connection_intent ) ? $connection_intent : 'Not specified', 470, 11 );
				$y -= 14;

				$ci_text = ! empty( $circles_interest ) ? implode( ', ', $circles_interest ) : 'None selected';
				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, $y, 'Circles Onboarding Interest:' );
				$y -= 12;
				$pdf_gen->set_font( 'F1', 8 );
				$pdf_gen->write_wrapped_text( 65, $y, $ci_text, 470, 11 );
				$y -= 10;

				// Section C: Travel Profile
				$pdf_gen->draw_heading( 'SECTION C: TEERTH YATRA & TRAVEL PROFILES', $y );
				$y -= 18;

				$dest_list = array();
				foreach ( $travel_dest as $d ) {
					if ( 'Other' === $d && ! empty( $travel_dest_other ) ) {
						$dest_list[] = 'Other (' . $travel_dest_other . ')';
					} else {
						$dest_list[] = $d;
					}
				}
				$dest_text = ! empty( $dest_list ) ? implode( ', ', $dest_list ) : 'None selected';
				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, $y, 'Checked Pilgrimage/Leisure Destinations:' );
				$y -= 12;
				$pdf_gen->set_font( 'F1', 8 );
				$pdf_gen->write_wrapped_text( 65, $y, $dest_text, 470, 11 );
				$y -= 4;

				$style_text = ! empty( $travel_styles ) ? implode( ', ', $travel_styles ) : 'None selected';
				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, $y, 'Preferred Travel Styles:' );
				$y -= 12;
				$pdf_gen->set_font( 'F1', 8 );
				$pdf_gen->write_wrapped_text( 65, $y, $style_text, 470, 11 );
				$y -= 4;

				$comp_text = ! empty( $companion_styles ) ? $companion_styles : 'None selected';
				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, $y, 'Companion Rules:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 180, $y, $comp_text );
				$y -= 20;

				// Section D: Safety Onboarding
				$pdf_gen->draw_heading( 'SECTION D: SAFETY ONBOARDING & PERSONAL BIO', $y );
				$y -= 18;

				$si_text = ! empty( $special_incentives ) ? implode( ', ', $special_incentives ) : 'None selected';
				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, $y, 'Self-Identification Segments:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 210, $y, $si_text );
				$y -= 14;

				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, $y, 'Verification Selfie Asset Path/URL:' );
				$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_wrapped_text( 65, $y, ! empty( $selfie_url ) ? $selfie_url : 'None uploaded', 470, 11 );
				$y -= 4;

				$bio_text = ! empty( $intro ) ? $intro : 'No biography submitted yet.';
				if ( strlen( $bio_text ) > 300 ) {
					$bio_text = substr( $bio_text, 0, 297 ) . '...';
				}
				$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 55, $y, 'Personal Biography Context (Truncated if >300 chars):' );
				$y -= 12;
				$pdf_gen->set_font( 'F1', 8 );
				$pdf_gen->write_wrapped_text( 65, $y, $bio_text, 470, 11 );

				// Footer Disclaimer
				$pdf_gen->draw_line( 50, 60, 545, 60 );
				$pdf_gen->set_font( 'F1', 7.5 );
				$pdf_gen->write_text( 50, 48, 'Confidential - Generated programmatically by SecondInnings50 Vetting Administration.' );
				$pdf_gen->write_text( 440, 48, 'https://secondinnings50.in' );

				$pdf_data = $pdf_gen->output();
				header( 'Content-Type: application/pdf' );
				header( 'Content-Disposition: attachment; filename="si50-application-' . $user_id . '.pdf"' );
				header( 'Content-Length: ' . strlen( $pdf_data ) );
				echo $pdf_data;
				exit;
			} 
			
			// Individual Invitation Requests Queue PDF Exporter
			elseif ( 'download_invitation_pdf' === $action ) {
				$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
				if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'si50_admin_invitation_action' ) ) {
					wp_die( esc_html__( 'Security validation check failed.', 'secondinnings50' ) );
				}

				$inv = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}si50_invitation_requests WHERE id = %d", $id ) );
				if ( ! $inv ) {
					wp_die( esc_html__( 'Invitation record not found.', 'secondinnings50' ) );
				}

				$pdf_gen = new SI50_PDF_Generator();

				// PDF Title Header
				$pdf_gen->set_font( 'F2', 15 );
				$pdf_gen->write_text( 50, 800, 'SECONDINNINGS50 - INVITATION INTAKE REQUEST DOSSIER' );
				$pdf_gen->set_font( 'F1', 9 );
				$pdf_gen->write_text( 50, 786, 'Captured from the Public Homepage Intake Lead Form' );
				$pdf_gen->draw_line( 50, 776, 545, 776 );

				// Details
				$pdf_gen->set_font( 'F2', 11 );
				$pdf_gen->write_text( 50, 750, 'RAW INTAKE FORM LOG DETAILS' );
				$pdf_gen->draw_line( 50, 744, 250, 744 );

				$pdf_gen->set_font( 'F2', 9 ); $pdf_gen->write_text( 55, 725, 'Applicant Name:' );
				$pdf_gen->set_font( 'F1', 9 ); $pdf_gen->write_text( 180, 725, $inv->name );

				$pdf_gen->set_font( 'F2', 9 ); $pdf_gen->write_text( 55, 712, 'Email Address:' );
				$pdf_gen->set_font( 'F1', 9 ); $pdf_gen->write_text( 180, 712, $inv->email );

				$pdf_gen->set_font( 'F2', 9 ); $pdf_gen->write_text( 55, 699, 'WhatsApp Phone Line:' );
				$pdf_gen->set_font( 'F1', 9 ); $pdf_gen->write_text( 180, 699, $inv->phone );

				$pdf_gen->set_font( 'F2', 9 ); $pdf_gen->write_text( 55, 686, 'Gender Identity:' );
				$pdf_gen->set_font( 'F1', 9 ); $pdf_gen->write_text( 180, 686, $inv->gender );

				$pdf_gen->set_font( 'F2', 9 ); $pdf_gen->write_text( 55, 673, 'Age Bracket Range:' );
				$pdf_gen->set_font( 'F1', 9 ); $pdf_gen->write_text( 180, 673, ! empty( $inv->age_bracket ) ? $inv->age_bracket : 'N/A' );

				$pdf_gen->set_font( 'F2', 9 ); $pdf_gen->write_text( 55, 660, 'Submission Timestamp:' );
				$pdf_gen->set_font( 'F1', 9 ); $pdf_gen->write_text( 180, 660, date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $inv->timestamp ) ) );

				// Outline Border Block
				$pdf_gen->draw_rect( 45, 645, 505, 120 );

				// Footer Disclaimer
				$pdf_gen->draw_line( 50, 60, 545, 60 );
				$pdf_gen->set_font( 'F1', 7.5 );
				$pdf_gen->write_text( 50, 48, 'Confidential - Generated programmatically by SecondInnings50 Vetting Administration.' );
				$pdf_gen->write_text( 440, 48, 'https://secondinnings50.in' );

				$pdf_data = $pdf_gen->output();
				header( 'Content-Type: application/pdf' );
				header( 'Content-Disposition: attachment; filename="si50-invitation-' . $inv->id . '.pdf"' );
				header( 'Content-Length: ' . strlen( $pdf_data ) );
				echo $pdf_data;
				exit;
			} 
			
			// Bulk Date-Wise PDF Dossier Export Engine
			elseif ( 'download_bulk_pdf' === $action ) {
				if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'si50_admin_bulk_pdf' ) ) {
					wp_die( esc_html__( 'Security validation check failed.', 'secondinnings50' ) );
				}

				$start_date = isset( $_GET['start_date'] ) ? sanitize_text_field( $_GET['start_date'] ) : '';
				$end_date   = isset( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : '';
				$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'invitations';

				$pdf_gen = new SI50_PDF_Generator();
				$y = 800;

				$range_label = 'All Time';
				if ( ! empty( $start_date ) && ! empty( $end_date ) ) {
					$range_label = $start_date . ' to ' . $end_date;
				} elseif ( ! empty( $start_date ) ) {
					$range_label = 'From ' . $start_date;
				} elseif ( ! empty( $end_date ) ) {
					$range_label = 'Until ' . $end_date;
				}

				if ( 'invitations' === $current_tab ) {
					// Bulk invitations query
					$inv_query = "SELECT * FROM {$wpdb->prefix}si50_invitation_requests";
					$where_clauses = array();
					if ( ! empty( $start_date ) ) {
						$where_clauses[] = $wpdb->prepare( "timestamp >= %s", $start_date . ' 00:00:00' );
					}
					if ( ! empty( $end_date ) ) {
						$where_clauses[] = $wpdb->prepare( "timestamp <= %s", $end_date . ' 23:59:59' );
					}
					if ( ! empty( $where_clauses ) ) {
						$inv_query .= " WHERE " . implode( " AND ", $where_clauses );
					}
					$inv_query .= " ORDER BY timestamp ASC";
					$records = $wpdb->get_results( $inv_query );

					$pdf_gen->set_fill_color( 0.75, 0.36, 0.24 ); // Terracotta
					$pdf_gen->set_font( 'F2', 13 );
					$pdf_gen->write_text( 50, $y, 'SECONDINNINGS50 - INVITATION REQUESTS BULK LEDGER' );
					$y -= 15;
					$pdf_gen->set_fill_color( 0.11, 0.23, 0.17 ); // Forest Green
					$pdf_gen->set_font( 'F1', 9 );
					$pdf_gen->write_text( 50, $y, sprintf( 'Date Range: %s | Exported: %s', $range_label, current_time( 'mysql' ) ) );
					$y -= 10;
					$pdf_gen->set_stroke_color( 0.75, 0.36, 0.24 ); // Terracotta
					$pdf_gen->draw_line( 50, $y, 545, $y );
					$pdf_gen->set_fill_color( 0.2, 0.2, 0.2 );
					$pdf_gen->set_stroke_color( 0.2, 0.2, 0.2 );
					$y -= 25;

					if ( empty( $records ) ) {
						$pdf_gen->set_font( 'F1', 11 );
						$pdf_gen->write_text( 50, $y, 'No invitation requests found matching the current filters.' );
					} else {
						foreach ( $records as $inv ) {
							if ( $y < 120 ) {
								$pdf_gen->add_page();
								$y = 800;
								$pdf_gen->set_fill_color( 0.75, 0.36, 0.24 ); // Terracotta
								$pdf_gen->set_font( 'F2', 11 );
								$pdf_gen->write_text( 50, $y, 'SECONDINNINGS50 - INVITATION REQUESTS BULK LEDGER (Continued)' );
								$y -= 8;
								$pdf_gen->set_stroke_color( 0.75, 0.36, 0.24 ); // Terracotta
								$pdf_gen->draw_line( 50, $y, 545, $y );
								$pdf_gen->set_fill_color( 0.2, 0.2, 0.2 );
								$pdf_gen->set_stroke_color( 0.2, 0.2, 0.2 );
								$y -= 25;
							}

							$pdf_gen->set_fill_color( 0.11, 0.23, 0.17 ); // Forest Green
							$pdf_gen->set_font( 'F2', 9.5 );
							$pdf_gen->write_text( 50, $y, 'REQUEST: ' . strtoupper( $inv->name ) );
							$pdf_gen->set_fill_color( 0.2, 0.2, 0.2 );
							$y -= 14;

							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 60, $y, 'Email Address:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 150, $y, $inv->email );
							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 320, $y, 'WhatsApp Phone:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 420, $y, $inv->phone );
							$y -= 12;

							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 60, $y, 'Gender / Age Bracket:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 150, $y, $inv->gender . ' / ' . ( ! empty( $inv->age_bracket ) ? $inv->age_bracket : 'N/A' ) );
							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 320, $y, 'Submitted Date:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 420, $y, $inv->timestamp );

							$y -= 12;
							$pdf_gen->set_stroke_color( 0.75, 0.36, 0.24 ); // Terracotta
							$pdf_gen->draw_line( 50, $y, 545, $y );
							$pdf_gen->set_stroke_color( 0.2, 0.2, 0.2 );
							$y -= 20;
						}
					}
				} elseif ( 'contact_inquiries' === $current_tab ) {
					// Bulk contact inquiries query
					$inq_query = "SELECT * FROM {$wpdb->prefix}si50_contact_inquiries";
					$where_clauses = array();
					if ( ! empty( $start_date ) ) {
						$where_clauses[] = $wpdb->prepare( "timestamp >= %s", $start_date . ' 00:00:00' );
					}
					if ( ! empty( $end_date ) ) {
						$where_clauses[] = $wpdb->prepare( "timestamp <= %s", $end_date . ' 23:59:59' );
					}
					if ( ! empty( $where_clauses ) ) {
						$inq_query .= " WHERE " . implode( " AND ", $where_clauses );
					}
					$inq_query .= " ORDER BY timestamp ASC";
					$records = $wpdb->get_results( $inq_query );

					$pdf_gen->set_fill_color( 0.75, 0.36, 0.24 ); // Terracotta
					$pdf_gen->set_font( 'F2', 13 );
					$pdf_gen->write_text( 50, $y, 'SECONDINNINGS50 - CONTACT SUPPORT LOGS LEDGER' );
					$y -= 15;
					$pdf_gen->set_fill_color( 0.11, 0.23, 0.17 ); // Forest Green
					$pdf_gen->set_font( 'F1', 9 );
					$pdf_gen->write_text( 50, $y, sprintf( 'Date Range: %s | Exported: %s', $range_label, current_time( 'mysql' ) ) );
					$y -= 10;
					$pdf_gen->set_stroke_color( 0.75, 0.36, 0.24 ); // Terracotta
					$pdf_gen->draw_line( 50, $y, 545, $y );
					$pdf_gen->set_fill_color( 0.2, 0.2, 0.2 );
					$pdf_gen->set_stroke_color( 0.2, 0.2, 0.2 );
					$y -= 25;

					if ( empty( $records ) ) {
						$pdf_gen->set_font( 'F1', 11 );
						$pdf_gen->write_text( 50, $y, 'No customer support inquiries found matching the current filters.' );
					} else {
						foreach ( $records as $inq ) {
							if ( $y < 150 ) {
								$pdf_gen->add_page();
								$y = 800;
								$pdf_gen->set_fill_color( 0.75, 0.36, 0.24 ); // Terracotta
								$pdf_gen->set_font( 'F2', 11 );
								$pdf_gen->write_text( 50, $y, 'SECONDINNINGS50 - CONTACT SUPPORT LOGS LEDGER (Continued)' );
								$y -= 8;
								$pdf_gen->set_stroke_color( 0.75, 0.36, 0.24 ); // Terracotta
								$pdf_gen->draw_line( 50, $y, 545, $y );
								$pdf_gen->set_fill_color( 0.2, 0.2, 0.2 );
								$pdf_gen->set_stroke_color( 0.2, 0.2, 0.2 );
								$y -= 25;
							}

							$pdf_gen->set_fill_color( 0.11, 0.23, 0.17 ); // Forest Green
							$pdf_gen->set_font( 'F2', 9.5 );
							$pdf_gen->write_text( 50, $y, 'SENDER: ' . strtoupper( $inq->name ) );
							$pdf_gen->set_fill_color( 0.2, 0.2, 0.2 );
							$y -= 14;

							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 60, $y, 'Email Address:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 150, $y, $inq->email );
							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 320, $y, 'WhatsApp Phone:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 420, $y, $inq->phone );
							$y -= 12;

							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 60, $y, 'Nature of Inquiry:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 150, $y, $inq->inquiry_nature );
							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 320, $y, 'Submitted Date:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 420, $y, $inq->timestamp );
							$y -= 12;

							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 60, $y, 'Message Payload:' );
							$y -= 10;
							$pdf_gen->set_font( 'F1', 8 );
							$msg_text = $inq->message;
							$pdf_gen->write_wrapped_text( 70, $y, $msg_text, 460, 11 );
							$y -= 6;
							
							$pdf_gen->set_stroke_color( 0.75, 0.36, 0.24 ); // Terracotta
							$pdf_gen->draw_line( 50, $y, 545, $y );
							$pdf_gen->set_stroke_color( 0.2, 0.2, 0.2 );
							$y -= 20;
						}
					}
				} else {
					// Bulk member applications query
					$args = array(
						'role__not_in' => array( 'administrator' ),
						'orderby'      => 'registered',
						'order'        => 'ASC'
					);
					if ( ! empty( $start_date ) || ! empty( $end_date ) ) {
						$date_query = array( 'relation' => 'AND' );
						if ( ! empty( $start_date ) ) {
							$date_query[] = array(
								'after'     => $start_date . ' 00:00:00',
								'inclusive' => true,
							);
						}
						if ( ! empty( $end_date ) ) {
							$date_query[] = array(
								'before'    => $end_date . ' 23:59:59',
								'inclusive' => true,
							);
						}
						$args['date_query'] = $date_query;
					}
					$records = get_users( $args );

					$pdf_gen->set_fill_color( 0.75, 0.36, 0.24 ); // Terracotta
					$pdf_gen->set_font( 'F2', 13 );
					$pdf_gen->write_text( 50, $y, 'SECONDINNINGS50 - MEMBER APPLICATIONS BULK LEDGER' );
					$y -= 15;
					$pdf_gen->set_fill_color( 0.11, 0.23, 0.17 ); // Forest Green
					$pdf_gen->set_font( 'F1', 9 );
					$pdf_gen->write_text( 50, $y, sprintf( 'Date Range: %s | Exported: %s', $range_label, current_time( 'mysql' ) ) );
					$y -= 10;
					$pdf_gen->set_stroke_color( 0.75, 0.36, 0.24 ); // Terracotta
					$pdf_gen->draw_line( 50, $y, 545, $y );
					$pdf_gen->set_fill_color( 0.2, 0.2, 0.2 );
					$pdf_gen->set_stroke_color( 0.2, 0.2, 0.2 );
					$y -= 25;

					if ( empty( $records ) ) {
						$pdf_gen->set_font( 'F1', 11 );
						$pdf_gen->write_text( 50, $y, 'No member applications found matching the current filters.' );
					} else {
						foreach ( $records as $user ) {
							if ( $y < 160 ) {
								$pdf_gen->add_page();
								$y = 800;
								$pdf_gen->set_fill_color( 0.75, 0.36, 0.24 ); // Terracotta
								$pdf_gen->set_font( 'F2', 11 );
								$pdf_gen->write_text( 50, $y, 'SECONDINNINGS50 - MEMBER APPLICATIONS BULK LEDGER (Continued)' );
								$y -= 8;
								$pdf_gen->set_stroke_color( 0.75, 0.36, 0.24 ); // Terracotta
								$pdf_gen->draw_line( 50, $y, 545, $y );
								$pdf_gen->set_fill_color( 0.2, 0.2, 0.2 );
								$pdf_gen->set_stroke_color( 0.2, 0.2, 0.2 );
								$y -= 25;
							}

							$user_id = $user->ID;
							$name = get_user_meta( $user_id, 'si50_fullname', true );
							if ( empty( $name ) ) { $name = $user->display_name; }
							$email = $user->user_email;
							$phone = get_user_meta( $user_id, 'si50_phone', true );
							$city_state = get_user_meta( $user_id, 'si50_city_state', true );
							$vetting_status = get_user_meta( $user_id, 'si50_vetting_status', true );
							if ( empty( $vetting_status ) ) { $vetting_status = 'pending_review'; }
							
							$special_incentives = (array) get_user_meta( $user_id, 'si50_special_incentives', true );
							$si_text = ! empty( $special_incentives ) ? implode( ', ', $special_incentives ) : 'None';

							$pdf_gen->set_fill_color( 0.11, 0.23, 0.17 ); // Forest Green
							$pdf_gen->set_font( 'F2', 9.5 );
							$pdf_gen->write_text( 50, $y, 'APPLICATION: ' . strtoupper( $name ) );
							$pdf_gen->set_fill_color( 0.2, 0.2, 0.2 );
							$y -= 14;

							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 60, $y, 'Email Address:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 150, $y, $email );
							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 320, $y, 'WhatsApp Phone:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 420, $y, $phone );
							$y -= 12;

							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 60, $y, 'Location / Status:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 150, $y, $city_state . ' / ' . strtoupper($vetting_status) );
							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 320, $y, 'Registration Date:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 420, $y, date( 'Y-m-d H:i', strtotime( $user->user_registered ) ) );
							$y -= 12;

							$pdf_gen->set_font( 'F2', 8 ); $pdf_gen->write_text( 60, $y, 'Segments/Flags:' );
							$pdf_gen->set_font( 'F1', 8 ); $pdf_gen->write_text( 150, $y, $si_text );

							$y -= 14;
							$pdf_gen->set_stroke_color( 0.75, 0.36, 0.24 ); // Terracotta
							$pdf_gen->draw_line( 50, $y, 545, $y );
							$pdf_gen->set_stroke_color( 0.2, 0.2, 0.2 );
							$y -= 20;
						}
					}
				}

				// Global Footer Disclaimer on each page
				$page_count = count( $pdf_gen->get_pages() );
				for ( $p = 0; $p < $page_count; $p++ ) {
					$pdf_gen->set_page( $p );
					$pdf_gen->draw_line( 50, 60, 545, 60 );
					$pdf_gen->set_font( 'F1', 7.5 );
					$pdf_gen->write_text( 50, 48, 'Confidential - Generated programmatically by SecondInnings50 Vetting Administration.' );
					$pdf_gen->write_text( 440, 48, sprintf( 'Page %d of %d', $p + 1, $page_count ) );
				}

				$pdf_data = $pdf_gen->output();
				header( 'Content-Type: application/pdf' );
				header( 'Content-Disposition: attachment; filename="si50-bulk-' . $current_tab . '.pdf"' );
				header( 'Content-Length: ' . strlen( $pdf_data ) );
				echo $pdf_data;
				exit;
			}
		}
	}
}
add_action( 'admin_init', 'si50_handle_admin_actions' );

/**
 * 3. Render Admin Onboarding Vetting Page
 */
function si50_render_admin_onboarding_page() {
	global $wpdb;

	$action  = isset( $_GET['action'] ) ? sanitize_text_field( $_GET['action'] ) : '';
	$user_id = isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : 0;
	$nonce   = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';

	// Render fallback reject page for non-JS/direct visits
	if ( 'reject' === $action && $user_id && wp_verify_nonce( $nonce, 'si50_admin_vetting_action' ) ) {
		$user = get_userdata( $user_id );
		if ( ! $user ) {
			wp_die( esc_html__( 'User not found.', 'secondinnings50' ) );
		}
		$name = get_user_meta( $user_id, 'si50_fullname', true );
		if ( empty( $name ) ) {
			$name = $user->display_name;
		}
		?>
		<div class="wrap si50-admin-wrap">
			<div class="si50-admin-header-bar">
				<h1 class="si50-admin-title" style="color: #c5221f;">
					<span class="dashicons dashicons-id-alt" style="font-size: 28px; width: 28px; height: 28px; line-height: 28px; color: #c5221f; margin-right: 8px;"></span>
					<?php esc_html_e( 'Reject Application - SecondInnings50', 'secondinnings50' ); ?>
				</h1>
			</div>
			<div class="si50-card" style="border: 2px solid #c5221f; max-width: 600px;">
				<h3 style="margin-top: 0; color: #202124;">
					<?php printf( esc_html__( 'Decline onboarding application for %s (%s)', 'secondinnings50' ), esc_html( $name ), esc_html( $user->user_email ) ); ?>
				</h3>
				<p style="color: #5f6368; font-size: 0.95em; line-height: 1.5; margin-bottom: 20px;">
					<?php esc_html_e( 'Please specify a professional note or reason for declining this member\'s onboarding application. This note will be sent directly to the user in their notification email.', 'secondinnings50' ); ?>
				</p>
				
				<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=si50-onboarding-dashboard&action=reject_confirm&user_id=' . $user_id . '&_wpnonce=' . $nonce ) ); ?>">
					<div style="margin-bottom: 20px;">
						<label for="rejection_note" style="display: block; font-weight: bold; margin-bottom: 8px;"><?php esc_html_e( 'Rejection Note / Feedback:', 'secondinnings50' ); ?></label>
						<textarea id="rejection_note" name="rejection_note" rows="6" style="width: 100%; border: 2px solid #333333; border-radius: 4px; padding: 10px; font-size: 14px; background: #ffffff;" required placeholder="<?php esc_attr_e( 'e.g. Please supply a valid WhatsApp phone number or real name to verify authenticity.', 'secondinnings50' ); ?>"></textarea>
					</div>
					<div>
						<button type="submit" class="si50-action-btn si50-btn-approve-primary" style="background-color: #c5221f; border-color: #333333; font-weight: bold;"><?php esc_html_e( 'Confirm Rejection & Send Email', 'secondinnings50' ); ?></button>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=applications' ) ); ?>" class="si50-action-btn si50-btn-reject-link" style="margin-left: 10px; border-color: #333333;"><?php esc_html_e( 'Cancel', 'secondinnings50' ); ?></a>
					</div>
				</form>
			</div>
		</div>
		<?php
		return;
	}

	// Active tab configuration
	$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'invitations';
	if ( ! in_array( $active_tab, array( 'invitations', 'applications', 'contact_inquiries', 'whatsapp_manager', 'safety_reports', 'interest_submissions' ), true ) ) {
		$active_tab = 'invitations';
	}

	// Get filter parameters
	$start_date = isset( $_GET['start_date'] ) ? sanitize_text_field( $_GET['start_date'] ) : '';
	$end_date   = isset( $_GET['end_date'] ) ? sanitize_text_field( $_GET['end_date'] ) : '';

	// Query 1: Raw homepage lead invitation requests
	$inv_query = "SELECT * FROM {$wpdb->prefix}si50_invitation_requests";
	$where_clauses = array();
	if ( ! empty( $start_date ) ) {
		$where_clauses[] = $wpdb->prepare( "timestamp >= %s", $start_date . ' 00:00:00' );
	}
	if ( ! empty( $end_date ) ) {
		$where_clauses[] = $wpdb->prepare( "timestamp <= %s", $end_date . ' 23:59:59' );
	}
	if ( ! empty( $where_clauses ) ) {
		$inv_query .= " WHERE " . implode( " AND ", $where_clauses );
	}
	$inv_query .= " ORDER BY timestamp DESC";
	$invitations = $wpdb->get_results( $inv_query );

	// Query 2: Vetted member applications (WordPress users)
	$args = array(
		'role__not_in' => array( 'administrator' ),
		'orderby'      => 'registered',
		'order'        => 'DESC'
	);
	if ( ! empty( $start_date ) || ! empty( $end_date ) ) {
		$date_query = array( 'relation' => 'AND' );
		if ( ! empty( $start_date ) ) {
			$date_query[] = array(
				'after'     => $start_date . ' 00:00:00',
				'inclusive' => true,
			);
		}
		if ( ! empty( $end_date ) ) {
			$date_query[] = array(
				'before'    => $end_date . ' 23:59:59',
				'inclusive' => true,
			);
		}
		$args['date_query'] = $date_query;
	}
	$users = get_users( $args );
	// Exclude draft applications pending payment
	$users = array_filter( $users, function( $u ) {
		return get_user_meta( $u->ID, 'si50_vetting_status', true ) !== 'pending_payment';
	} );

	// Query 3: Contact Inquiries Log
	$inquiries_query = "SELECT * FROM {$wpdb->prefix}si50_contact_inquiries";
	$where_clauses_inq = array();
	if ( ! empty( $start_date ) ) {
		$where_clauses_inq[] = $wpdb->prepare( "timestamp >= %s", $start_date . ' 00:00:00' );
	}
	if ( ! empty( $end_date ) ) {
		$where_clauses_inq[] = $wpdb->prepare( "timestamp <= %s", $end_date . ' 23:59:59' );
	}
	if ( ! empty( $where_clauses_inq ) ) {
		$inquiries_query .= " WHERE " . implode( " AND ", $where_clauses_inq );
	}
	$inquiries_query .= " ORDER BY timestamp DESC";
	$inquiries = $wpdb->get_results( $inquiries_query );

	$total_inquiries_count = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}si50_contact_inquiries" ) );

	// Calculate Stats Metrics (All-time static metrics)
	$all_non_admins = get_users( array( 'role__not_in' => array( 'administrator' ) ) );
	$pending_count = 0;
	$approved_count = 0;
	$rejected_count = 0;
	$suspended_count = 0;
	$total_registered_count = 0;

	foreach ( $all_non_admins as $u ) {
		$st = get_user_meta( $u->ID, 'si50_vetting_status', true );
		if ( 'pending_payment' === $st ) {
			continue;
		}
		$total_registered_count++;
		
		if ( 'approved' === $st ) {
			$approved_count++;
		} elseif ( 'rejected' === $st ) {
			$rejected_count++;
		} elseif ( 'suspended' === $st ) {
			$suspended_count++;
		} else {
			$pending_count++;
		}
	}
	
	$all_time_invitations_count = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}si50_invitation_requests" ) );

	// Query 4: Safety reports
	$reports_query = "SELECT * FROM {$wpdb->prefix}si50_member_reports";
	$where_clauses_rep = array();
	if ( ! empty( $start_date ) ) {
		$where_clauses_rep[] = $wpdb->prepare( "timestamp >= %s", $start_date . ' 00:00:00' );
	}
	if ( ! empty( $end_date ) ) {
		$where_clauses_rep[] = $wpdb->prepare( "timestamp <= %s", $end_date . ' 23:59:59' );
	}
	if ( ! empty( $where_clauses_rep ) ) {
		$reports_query .= " WHERE " . implode( " AND ", $where_clauses_rep );
	}
	$reports_query .= " ORDER BY timestamp DESC";
	$reports = $wpdb->get_results( $reports_query );

	$total_reports_count = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}si50_member_reports" ) );
	$active_reports_count = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}si50_member_reports WHERE status = 'active'" ) );
	$resolved_reports_count = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}si50_member_reports WHERE status = 'resolved'" ) );

	$msg = isset( $_GET['msg'] ) ? sanitize_text_field( $_GET['msg'] ) : '';
	?>
	<div class="wrap si50-admin-wrap">
		<div class="si50-admin-header-bar">
			<h1 class="si50-admin-title">
				<span class="dashicons dashicons-id-alt" style="font-size: 28px; width: 28px; height: 28px; line-height: 28px; color: #1a73e8; margin-right: 8px;"></span>
				<?php esc_html_e( 'SecondInnings50 Administrative Control Center', 'secondinnings50' ); ?>
			</h1>
		</div>

		<?php if ( 'approved' === $msg ) : ?>
			<div class="notice notice-success is-dismissible" style="border-left-color: #0c8a4d; border-radius: 4px;">
				<p><strong><?php esc_html_e( 'Success:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'User application has been approved and profile is unlocked in the directory.', 'secondinnings50' ); ?></p>
			</div>
		<?php elseif ( 'rejected' === $msg ) : ?>
			<div class="notice notice-warning is-dismissible" style="border-left-color: #c5221f; border-radius: 4px;">
				<p><strong><?php esc_html_e( 'Success:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'User application has been rejected and access is blocked.', 'secondinnings50' ); ?></p>
			</div>
		<?php elseif ( 'suspended' === $msg ) : ?>
			<div class="notice notice-warning is-dismissible" style="border-left-color: #c5221f; border-radius: 4px;">
				<p><strong><?php esc_html_e( 'Success:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'Member account has been suspended and access restricted.', 'secondinnings50' ); ?></p>
			</div>
		<?php elseif ( 'resolved_report' === $msg ) : ?>
			<div class="notice notice-success is-dismissible" style="border-left-color: #0c8a4d; border-radius: 4px;">
				<p><strong><?php esc_html_e( 'Success:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'Safety report has been marked as resolved.', 'secondinnings50' ); ?></p>
			</div>
		<?php elseif ( 'badge_toggled' === $msg ) : ?>
			<div class="notice notice-success is-dismissible" style="border-left-color: #0c8a4d; border-radius: 4px;">
				<p><strong><?php esc_html_e( 'Success:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'Member verification badge status updated.', 'secondinnings50' ); ?></p>
			</div>
		<?php elseif ( 'wa_saved' === $msg ) : ?>
			<div class="notice notice-success is-dismissible" style="border-left-color: #0c8a4d; border-radius: 4px;">
				<p><strong><?php esc_html_e( 'Success:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'WhatsApp Group invite links updated successfully.', 'secondinnings50' ); ?></p>
			</div>
		<?php elseif ( 'pause_toggled' === $msg ) : ?>
			<div class="notice notice-success is-dismissible" style="border-left-color: #0c8a4d; border-radius: 4px;">
				<p><strong><?php esc_html_e( 'Success:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'Member compatibility paused status updated successfully.', 'secondinnings50' ); ?></p>
			</div>
		<?php elseif ( 'resolved_interest' === $msg ) : ?>
			<div class="notice notice-success is-dismissible" style="border-left-color: #0c8a4d; border-radius: 4px;">
				<p><strong><?php esc_html_e( 'Success:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'Interest submission has been marked as resolved.', 'secondinnings50' ); ?></p>
			</div>
		<?php endif; ?>

		<!-- Nav Tabs Bar: Explicit Five-Tab Navigation -->
		<h2 class="nav-tab-wrapper" style="margin-bottom: 20px;">
			<a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'invitations', 'start_date' => $start_date, 'end_date' => $end_date ) ) ); ?>" class="nav-tab <?php echo ( 'invitations' === $active_tab ) ? 'nav-tab-active' : ''; ?>">
				📬 <?php esc_html_e( 'Invitation Requests Queue', 'secondinnings50' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'applications', 'start_date' => $start_date, 'end_date' => $end_date ) ) ); ?>" class="nav-tab <?php echo ( 'applications' === $active_tab ) ? 'nav-tab-active' : ''; ?>">
				📝 <?php esc_html_e( 'Vetted Member Applications', 'secondinnings50' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'contact_inquiries', 'start_date' => $start_date, 'end_date' => $end_date ) ) ); ?>" class="nav-tab <?php echo ( 'contact_inquiries' === $active_tab ) ? 'nav-tab-active' : ''; ?>">
				📞 <?php esc_html_e( 'Contact Inquiries Log', 'secondinnings50' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'whatsapp_manager', 'start_date' => $start_date, 'end_date' => $end_date ) ) ); ?>" class="nav-tab <?php echo ( 'whatsapp_manager' === $active_tab ) ? 'nav-tab-active' : ''; ?>">
				💬 <?php esc_html_e( 'WhatsApp Circles Manager', 'secondinnings50' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'safety_reports', 'start_date' => $start_date, 'end_date' => $end_date ) ) ); ?>" class="nav-tab <?php echo ( 'safety_reports' === $active_tab ) ? 'nav-tab-active' : ''; ?>">
				⚠️ <?php esc_html_e( 'Safety & Reports', 'secondinnings50' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'interest_submissions', 'start_date' => $start_date, 'end_date' => $end_date ) ) ); ?>" class="nav-tab <?php echo ( 'interest_submissions' === $active_tab ) ? 'nav-tab-active' : ''; ?>">
				🤝 <?php esc_html_e( 'Interest Submissions', 'secondinnings50' ); ?>
			</a>
		</h2>

		<!-- Filter Bar Panel -->
		<div class="si50-filter-panel" style="background: #ffffff; border: 1.5px solid #333333; border-radius: 8px; padding: 16px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); box-sizing: border-box;">
			<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" style="display: flex; align-items: center; gap: 12px; margin: 0; flex-wrap: wrap;">
				<input type="hidden" name="page" value="si50-onboarding-dashboard" />
				<input type="hidden" name="tab" value="<?php echo esc_attr( $active_tab ); ?>" />
				
				<label for="si50_start_date" style="font-weight: 700; font-size: 13px; color: #202124; margin: 0;">
					<?php esc_html_e( 'From Date:', 'secondinnings50' ); ?>
				</label>
				<input type="date" id="si50_start_date" name="start_date" value="<?php echo esc_attr( $start_date ); ?>" style="border: 2px solid #333333; border-radius: 4px; padding: 6px 10px; font-size: 13px; background-color: #ffffff; color: #202124; height: 36px; box-sizing: border-box;" />

				<label for="si50_end_date" style="font-weight: 700; font-size: 13px; color: #202124; margin: 0;">
					<?php esc_html_e( 'To Date:', 'secondinnings50' ); ?>
				</label>
				<input type="date" id="si50_end_date" name="end_date" value="<?php echo esc_attr( $end_date ); ?>" style="border: 2px solid #333333; border-radius: 4px; padding: 6px 10px; font-size: 13px; background-color: #ffffff; color: #202124; height: 36px; box-sizing: border-box;" />

				<button type="submit" class="button button-primary" style="height: 36px; line-height: 34px; font-weight: 700; font-size: 13px; border-radius: 4px; background-color: #1a73e8; border-color: #333333; border-width: 2px; border-style: solid;">
					<?php esc_html_e( 'Apply Filter', 'secondinnings50' ); ?>
				</button>
				<?php if ( ! empty( $start_date ) || ! empty( $end_date ) ) : ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=' . $active_tab ) ); ?>" class="button" style="height: 36px; line-height: 34px; font-weight: 700; font-size: 13px; border-radius: 4px; border: 2px solid #333333; color: #333333; background: #ffffff; display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">
						<?php esc_html_e( 'Clear Filter', 'secondinnings50' ); ?>
					</a>
				<?php endif; ?>
			</form>

			<!-- Bulk Export PDF Button -->
			<div>
				<?php if ( in_array( $active_tab, array( 'invitations', 'applications', 'contact_inquiries' ), true ) ) : ?>
					<?php
					$bulk_pdf_label = ( 'contact_inquiries' === $active_tab ) ? esc_html__( 'Download Date-Wise Support Report', 'secondinnings50' ) : esc_html__( 'Bulk Date-Wise Export (PDF)', 'secondinnings50' );
					?>
					<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=si50-onboarding-dashboard&action=download_bulk_pdf&tab=' . $active_tab . '&start_date=' . $start_date . '&end_date=' . $end_date ), 'si50_admin_bulk_pdf' ) ); ?>" class="si50-action-btn" style="background-color: #1b3b2b; color: #ffffff; border-color: #333333; border-width: 2px; border-style: solid; font-weight: 700; cursor: pointer; padding: 8px 16px; font-size: 13px; border-radius: 4px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; line-height: 1;">
						<span class="dashicons dashicons-pdf" style="font-size: 18px; width: 18px; height: 18px; line-height: 18px;"></span>
						<?php echo $bulk_pdf_label; ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="si50-admin-layout">
			
			<!-- Sticky Sidebar Stats Panel -->
			<aside class="si50-admin-sidebar">
				<h3><?php esc_html_e( 'Invitation Queue Metrics', 'secondinnings50' ); ?></h3>
				<div class="si50-stats-widget">
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Total Requests:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value"><?php echo $all_time_invitations_count; ?></span>
					</div>
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Filtered Count:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value"><?php echo count( $invitations ); ?></span>
					</div>
				</div>

				<h3><?php esc_html_e( 'Applications Metrics', 'secondinnings50' ); ?></h3>
				<div class="si50-stats-widget">
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Total Members:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value"><?php echo intval( $total_registered_count ); ?></span>
					</div>
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Pending Review:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value" style="color: #b06000; font-weight: bold;"><?php echo intval( $pending_count ); ?></span>
					</div>
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Approved Members:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value" style="color: #137333;"><?php echo intval( $approved_count ); ?></span>
					</div>
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Suspended Members:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value" style="color: #c5221f; font-weight: bold;"><?php echo intval( $suspended_count ); ?></span>
					</div>
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Rejected Members:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value" style="color: #c5221f;"><?php echo intval( $rejected_count ); ?></span>
					</div>
				</div>

				<h3><?php esc_html_e( 'Connections Metrics', 'secondinnings50' ); ?></h3>
				<div class="si50-stats-widget">
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Active Connections:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value" style="color: #137333; font-weight: bold;">
							<?php 
							$active_connections_count = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}si50_connect_requests WHERE status = 'approved'" ) );
							echo $active_connections_count; 
							?>
						</span>
					</div>
				</div>

				<h3><?php esc_html_e( 'Contact Inquiries Metrics', 'secondinnings50' ); ?></h3>
				<div class="si50-stats-widget">
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Total Inquiries:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value"><?php echo $total_inquiries_count; ?></span>
					</div>
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Filtered Count:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value"><?php echo count( $inquiries ); ?></span>
					</div>
				</div>

				<h3><?php esc_html_e( 'Safety & Reports Metrics', 'secondinnings50' ); ?></h3>
				<div class="si50-stats-widget">
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Total Reports:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value"><?php echo $total_reports_count; ?></span>
					</div>
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Active Reports:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value" style="color: #c5221f; font-weight: bold;"><?php echo $active_reports_count; ?></span>
					</div>
					<div class="si50-stat-row">
						<span><?php esc_html_e( 'Resolved Reports:', 'secondinnings50' ); ?></span>
						<span class="si50-stat-value" style="color: #137333;"><?php echo $resolved_reports_count; ?></span>
					</div>
				</div>
			</aside>
			
			<!-- Main Content Panel based on Active Tab -->
			<div class="si50-admin-main">
				
				<?php if ( 'invitations' === $active_tab ) : ?>
					<!-- TAB 1: INVITATION REQUESTS QUEUE -->
					<div id="invitation-requests-queue" class="si50-card">
						<h2 class="si50-card-title">
							<span>
								<span class="dashicons dashicons-email-alt" style="color: #202124; margin-right: 8px; font-size: 20px;"></span>
								<?php esc_html_e( 'Public Homepage Invitation Requests Queue', 'secondinnings50' ); ?>
							</span>
							<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=si50-onboarding-dashboard&action=download_bulk_pdf&tab=invitations&start_date=' . $start_date . '&end_date=' . $end_date ), 'si50_admin_bulk_pdf' ) ); ?>" class="si50-action-btn" style="background-color: #1b3b2b; color: #ffffff; border-color: #333333; font-size: 11px; padding: 4px 10px; font-weight: bold; text-decoration: none;">
								<span class="dashicons dashicons-pdf" style="font-size: 14px; width: 14px; height: 14px;"></span>
								<?php esc_html_e( 'Export This Queue (PDF)', 'secondinnings50' ); ?>
							</a>
						</h2>
						
						<?php if ( empty( $invitations ) ) : ?>
							<p style="color: #5f6368; font-style: italic;"><?php esc_html_e( 'No invitation requests found.', 'secondinnings50' ); ?></p>
						<?php else : ?>
							<div class="si50-table-container">
								<table class="si50-list-table">
									<thead>
										<tr>
											<th><?php esc_html_e( 'Submission Date', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Name', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Email', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'WhatsApp Phone Line', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Gender', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Age Bracket', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Actions', 'secondinnings50' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $invitations as $inv ) : ?>
											<tr>
												<td style="font-size: 0.85em; color: #5f6368;">
													<?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $inv->timestamp ) ) ); ?>
												</td>
												<td><strong><?php echo esc_html( $inv->name ); ?></strong></td>
												<td><?php echo esc_html( $inv->email ); ?></td>
												<td><?php echo esc_html( $inv->phone ); ?></td>
												<td><?php echo esc_html( $inv->gender ); ?></td>
												<td><?php echo esc_html( ! empty( $inv->age_bracket ) ? $inv->age_bracket : esc_html__( 'N/A', 'secondinnings50' ) ); ?></td>
												<td>
													<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=si50-onboarding-dashboard&action=download_invitation_pdf&id=' . $inv->id ), 'si50_admin_invitation_action' ) ); ?>" class="si50-action-btn" style="background-color: #1a73e8; color: #ffffff; border-color: #333333; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 12px; border-radius: 4px; text-decoration: none;">
														<span class="dashicons dashicons-download" style="font-size: 16px; width: 16px; height: 16px; line-height: 16px;"></span>
														<?php esc_html_e( 'Download Report', 'secondinnings50' ); ?>
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>
					</div>
				
				<?php elseif ( 'interest_submissions' === $active_tab ) : 
					// Query interest submissions
					$interest_query = "SELECT * FROM {$wpdb->prefix}si50_connect_requests WHERE status = 'admin_review'";
					$where_clauses_int = array();
					if ( ! empty( $start_date ) ) {
						$where_clauses_int[] = $wpdb->prepare( "timestamp >= %s", $start_date . ' 00:00:00' );
					}
					if ( ! empty( $end_date ) ) {
						$where_clauses_int[] = $wpdb->prepare( "timestamp <= %s", $end_date . ' 23:59:59' );
					}
					if ( ! empty( $where_clauses_int ) ) {
						$interest_query .= " AND " . implode( " AND ", $where_clauses_int );
					}
					$interest_query .= " ORDER BY timestamp DESC";
					$interests = $wpdb->get_results( $interest_query );
				?>
					<!-- TAB 6: INTEREST SUBMISSIONS -->
					<div id="interest-submissions-queue" class="si50-card">
						<h2 class="si50-card-title">
							<span>
								<span class="dashicons dashicons-heart" style="color: #202124; margin-right: 8px; font-size: 20px;"></span>
								<?php esc_html_e( 'Interest Submissions (Pending Admin Review)', 'secondinnings50' ); ?>
							</span>
						</h2>
						
						<?php if ( empty( $interests ) ) : ?>
							<p style="color: #5f6368; font-style: italic;"><?php esc_html_e( 'No interest submissions found.', 'secondinnings50' ); ?></p>
						<?php else : ?>
							<div class="si50-table-container">
								<table class="si50-list-table">
									<thead>
										<tr>
											<th><?php esc_html_e( 'Date', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Interested Member (Sender)', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Target Member (Receiver)', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Actions', 'secondinnings50' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $interests as $req ) : 
											$sender = get_userdata( $req->sender_id );
											$receiver = get_userdata( $req->receiver_id );
											if ( ! $sender || ! $receiver ) continue;
											$s_name = get_user_meta( $req->sender_id, 'si50_fullname', true ) ?: $sender->display_name;
											$r_name = get_user_meta( $req->receiver_id, 'si50_fullname', true ) ?: $receiver->display_name;
											$s_phone = get_user_meta( $req->sender_id, 'si50_phone', true );
											$r_phone = get_user_meta( $req->receiver_id, 'si50_phone', true );
										?>
											<tr>
												<td style="font-size: 0.85em; color: #5f6368;">
													<?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $req->timestamp ) ) ); ?>
												</td>
												<td>
													<strong><?php echo esc_html( $s_name ); ?></strong><br>
													<span style="font-size: 0.85em; color: #5f6368;"><?php echo esc_html( $s_phone ); ?></span>
												</td>
												<td>
													<strong><?php echo esc_html( $r_name ); ?></strong><br>
													<span style="font-size: 0.85em; color: #5f6368;"><?php echo esc_html( $r_phone ); ?></span>
												</td>
												<td>
													<a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $s_phone ) ); ?>?text=<?php echo rawurlencode('Hello ' . $s_name . ', we are from the SecondInnings team. We noticed your interest in ' . $r_name . '. Let us discuss this further.'); ?>" target="_blank" class="si50-action-btn" style="background-color: #25D366; color: #ffffff; border-color: #128C7E; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 12px; border-radius: 4px; text-decoration: none; margin-bottom: 4px;">
														<?php esc_html_e( 'Message Sender', 'secondinnings50' ); ?>
													</a>
													<a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $r_phone ) ); ?>" target="_blank" class="si50-action-btn" style="background-color: #128C7E; color: #ffffff; border-color: #128C7E; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 12px; border-radius: 4px; text-decoration: none; margin-bottom: 4px;">
														<?php esc_html_e( 'Message Target', 'secondinnings50' ); ?>
													</a>
													<?php 
													$resolve_url = admin_url( 'admin.php?page=si50-onboarding-dashboard&action=resolve_interest&req_id=' . $req->id . '&_wpnonce=' . wp_create_nonce( 'si50_admin_vetting_action' ) ); 
													?>
													<a href="<?php echo esc_url( $resolve_url ); ?>" class="si50-action-btn" style="background-color: #f1f3f4; color: #3c4043; border-color: #333333; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 12px; border-radius: 4px; text-decoration: none;">
														<span class="dashicons dashicons-yes-alt" style="font-size: 14px; width: 14px; height: 14px; line-height: 14px;"></span>
														<?php esc_html_e( 'Mark Resolved', 'secondinnings50' ); ?>
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>
					</div>
				<?php elseif ( 'applications' === $active_tab ) : ?>
					<!-- TAB 2: VETTED MEMBER APPLICATIONS -->
					<div id="vetted-member-applications" class="si50-card">
						<h2 class="si50-card-title">
							<span>
								<span class="dashicons dashicons-businessman" style="color: #202124; margin-right: 8px; font-size: 20px;"></span>
								<?php esc_html_e( 'Registered Onboarding Applications', 'secondinnings50' ); ?>
							</span>
							<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=si50-onboarding-dashboard&action=download_bulk_pdf&tab=applications&start_date=' . $start_date . '&end_date=' . $end_date ), 'si50_admin_bulk_pdf' ) ); ?>" class="si50-action-btn" style="background-color: #1b3b2b; color: #ffffff; border-color: #333333; font-size: 11px; padding: 4px 10px; font-weight: bold; text-decoration: none;">
								<span class="dashicons dashicons-pdf" style="font-size: 14px; width: 14px; height: 14px;"></span>
								<?php esc_html_e( 'Export This Queue (PDF)', 'secondinnings50' ); ?>
							</a>
						</h2>
						
						<?php if ( empty( $users ) ) : ?>
							<p style="color: #5f6368; font-style: italic;"><?php esc_html_e( 'No onboarding user applications found.', 'secondinnings50' ); ?></p>
						<?php else : ?>
							<div class="si50-table-container">
								<table class="si50-list-table">
									<thead>
										<tr>
											<th style="width: 40px; text-align: center;"></th>
											<th><?php esc_html_e( 'Registration Date', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Member Profile', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'WhatsApp Phone', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Identity Upload', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Payment & Tier', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Vetting State', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Actions', 'secondinnings50' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $users as $user ) : 
											$status = get_user_meta( $user->ID, 'si50_vetting_status', true );
											if ( empty( $status ) ) {
												$status = 'pending_review';
											}
											$phone = get_user_meta( $user->ID, 'si50_phone', true );
											$selfie_url = get_user_meta( $user->ID, 'si50_verification_selfie_url', true );
											?>
											<tr>
												<td style="vertical-align: middle; text-align: center;">
													<button type="button" class="si50-drawer-toggle-btn" data-user-id="<?php echo $user->ID; ?>" aria-expanded="false" style="background: none; border: none; cursor: pointer; color: #1a73e8; padding: 4px; display: inline-flex; align-items: center; justify-content: center;" title="<?php esc_attr_e( 'Inspect Preferences', 'secondinnings50' ); ?>">
														<span class="dashicons dashicons-visibility" style="font-size: 20px; width: 20px; height: 20px;"></span>
													</button>
												</td>
												<td style="font-size: 0.85em; color: #5f6368; vertical-align: middle;">
													<?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $user->user_registered ) ) ); ?>
												</td>
												<td style="vertical-align: middle;">
													<strong style="color: #202124;"><?php echo esc_html( $user->display_name ); ?></strong><br>
													<span style="font-size: 0.85em; color: #5f6368;"><?php echo esc_html( $user->user_email ); ?></span>
												</td>
												<td style="vertical-align: middle;">
													<?php echo esc_html( ! empty( $phone ) ? $phone : 'N/A' ); ?>
												</td>
												<td style="vertical-align: middle;">
													<?php if ( ! empty( $selfie_url ) ) : ?>
														<span class="si50-badge si50-badge-approved"><?php esc_html_e( 'Selfie Uploaded', 'secondinnings50' ); ?></span>
													<?php else : ?>
														<span class="si50-badge si50-badge-rejected"><?php esc_html_e( 'Missing Selfie', 'secondinnings50' ); ?></span>
													<?php endif; ?>
												</td>
												<td style="vertical-align: middle;">
													<?php
													$p_status = get_user_meta( $user->ID, 'si50_payment_status', true );
													$m_tier = get_user_meta( $user->ID, 'si50_membership_status', true );
													$p_mode = get_user_meta( $user->ID, 'si50_payment_mode', true );
													?>
													<div style="margin-bottom: 4px;">
														<?php if ( 'paid' === $p_status ) : ?>
															<span class="si50-badge si50-badge-approved" style="background-color: #e6f4ea; color: #137333; border-color: #ceead6;">Paid</span>
														<?php else : ?>
															<span class="si50-badge si50-badge-pending" style="background-color: #fef7e0; color: #b06000; border-color: #fce8b2;">Unpaid</span>
														<?php endif; ?>
													</div>
													<?php if ( ! empty( $p_mode ) ) : ?>
														<div style="margin-bottom: 4px; font-size: 11px; color: #5f6368; font-weight: bold;">
															<?php echo esc_html( $p_mode ); ?>
														</div>
													<?php endif; ?>
													<div>
														<?php if ( 'premium' === $m_tier ) : ?>
															<span class="si50-badge" style="background-color: #fce8e6; color: #c5221f; border: 1px solid #fad2cf;">Complete (₹599)</span>
														<?php else : ?>
															<span class="si50-badge" style="background-color: #e8f0fe; color: #1a73e8; border: 1px solid #d2e3fc;">Basic (₹99)</span>
														<?php endif; ?>
													</div>
												</td>
												<td style="vertical-align: middle;">
													<?php if ( 'approved' === $status ) : ?>
														<span class="si50-badge si50-badge-approved"><?php esc_html_e( 'Approved', 'secondinnings50' ); ?></span>
														<?php 
														$is_badge_verified = get_user_meta( $user->ID, 'si50_verified_badge', true );
														if ( $is_badge_verified ) :
															?>
															<div style="margin-top: 4px; display: inline-flex; align-items: center; gap: 2px; color: #10b981; font-weight: bold; font-size: 11px;" title="<?php esc_attr_e( 'Verified Member Badge Active', 'secondinnings50' ); ?>">
																🛡️ <?php esc_html_e( 'Verified', 'secondinnings50' ); ?>
															</div>
														<?php endif; ?>
													<?php elseif ( 'suspended' === $status ) : ?>
														<span class="si50-badge si50-badge-rejected" style="background-color: #fce8e6; color: #c5221f; border-color: #fad2cf;"><?php esc_html_e( 'Suspended', 'secondinnings50' ); ?></span>
													<?php elseif ( 'rejected' === $status ) : ?>
														<span class="si50-badge si50-badge-rejected"><?php esc_html_e( 'Rejected', 'secondinnings50' ); ?></span>
													<?php else : ?>
														<span class="si50-badge si50-badge-pending"><?php esc_html_e( 'Pending Vetting', 'secondinnings50' ); ?></span>
													<?php endif; ?>
												</td>
												<td style="vertical-align: middle;">
													<?php
													$nonce = wp_create_nonce( 'si50_admin_vetting_action' );
													$approve_url = admin_url( 'admin.php?page=si50-onboarding-dashboard&action=approve&user_id=' . $user->ID . '&_wpnonce=' . $nonce );
													$badge_url = admin_url( 'admin.php?page=si50-onboarding-dashboard&action=toggle_badge&user_id=' . $user->ID . '&_wpnonce=' . $nonce );
													$is_badge_verified = get_user_meta( $user->ID, 'si50_verified_badge', true );
													?>
													<div style="display: flex; flex-direction: column; gap: 6px; min-width: 120px;">
														<?php if ( 'approved' !== $status ) : ?>
															<a href="<?php echo esc_url( $approve_url ); ?>" class="si50-action-btn si50-btn-approve-primary si50-admin-approve-btn" style="text-align: center; white-space: nowrap;" data-username="<?php echo esc_attr( $user->display_name ); ?>"><?php esc_html_e( 'Approve', 'secondinnings50' ); ?></a>
														<?php endif; ?>
														<?php if ( 'rejected' !== $status && 'suspended' !== $status ) : ?>
															<a href="#" class="si50-action-btn si50-btn-reject-link si50-admin-reject-btn" style="text-align: center; white-space: nowrap;" data-username="<?php echo esc_attr( $user->display_name ); ?>" data-user-id="<?php echo $user->ID; ?>" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php esc_html_e( 'Reject', 'secondinnings50' ); ?></a>
														<?php endif; ?>
														<?php if ( 'approved' === $status ) : ?>
															<a href="<?php echo esc_url( $badge_url ); ?>" class="si50-action-btn" style="text-align: center; white-space: nowrap; background-color: <?php echo $is_badge_verified ? '#fce8e6' : '#e6f4ea'; ?> !important; color: <?php echo $is_badge_verified ? '#c5221f' : '#137333'; ?> !important; border-color: #333333 !important;">
																<?php echo $is_badge_verified ? esc_html__( 'Remove Badge', 'secondinnings50' ) : esc_html__( 'Verify Badge', 'secondinnings50' ); ?>
															</a>
														<?php endif; ?>
														
														<!-- Payment Toggle -->
														<?php $pay_url = admin_url( 'admin.php?page=si50-onboarding-dashboard&action=toggle_payment&user_id=' . $user->ID . '&_wpnonce=' . $nonce ); ?>
														<a href="<?php echo esc_url( $pay_url ); ?>" class="si50-action-btn" style="text-align: center; white-space: nowrap; background-color: #f1f3f4 !important; color: #3c4043 !important; border-color: #333333 !important;">
															<?php echo ( 'paid' === $p_status ) ? esc_html__( 'Mark Unpaid', 'secondinnings50' ) : esc_html__( 'Mark Paid', 'secondinnings50' ); ?>
														</a>
														
														<!-- Tier Toggle -->
														<?php $tier_url = admin_url( 'admin.php?page=si50-onboarding-dashboard&action=toggle_tier&user_id=' . $user->ID . '&_wpnonce=' . $nonce ); ?>
														<a href="<?php echo esc_url( $tier_url ); ?>" class="si50-action-btn" style="text-align: center; white-space: nowrap; background-color: #f1f3f4 !important; color: #3c4043 !important; border-color: #333333 !important;">
															<?php echo ( 'premium' === $m_tier ) ? esc_html__( 'Set to Basic', 'secondinnings50' ) : esc_html__( 'Set to Premium', 'secondinnings50' ); ?>
														</a>

														<!-- Pause Profile Toggle -->
														<?php 
														$pause_url = admin_url( 'admin.php?page=si50-onboarding-dashboard&action=toggle_pause&user_id=' . $user->ID . '&_wpnonce=' . $nonce ); 
														$is_paused = get_user_meta( $user->ID, 'si50_compatibility_paused', true );
														?>
														<a href="<?php echo esc_url( $pause_url ); ?>" class="si50-action-btn" style="text-align: center; white-space: nowrap; background-color: <?php echo $is_paused ? '#fce8e6' : '#fff3cd'; ?> !important; color: <?php echo $is_paused ? '#c5221f' : '#856404'; ?> !important; border-color: #333333 !important;">
															<?php echo $is_paused ? esc_html__( 'Unpause Profile', 'secondinnings50' ) : esc_html__( 'Pause Profile', 'secondinnings50' ); ?>
														</a>
													</div>
												</td>
											</tr>
											<!-- High-contrast granular Eye-icon drawer row -->
											<tr id="si50-drawer-row-<?php echo $user->ID; ?>" class="si50-drawer-row" style="display: none; background-color: #fafafa;">
												<td colspan="7" style="padding: 16px 20px; border-bottom: 1px solid #e0e0e0;">
													<?php
													$fullname = get_user_meta( $user->ID, 'si50_fullname', true );
													if ( empty( $fullname ) ) { $fullname = $user->display_name; }
													
													$dob = get_user_meta( $user->ID, 'si50_dob', true );
													$age_bracket = get_user_meta( $user->ID, 'si50_age_bracket', true );
													$gender = get_user_meta( $user->ID, 'si50_gender', true );
													$marital_status = get_user_meta( $user->ID, 'si50_marital_status', true );
													$occupation = get_user_meta( $user->ID, 'si50_occupation', true );
													$emergency = get_user_meta( $user->ID, 'si50_emergency_phone', true );

													$loc_pref = get_user_meta( $user->ID, 'si50_location_preference', true );
													$looking = (array) get_user_meta( $user->ID, 'si50_looking_for', true );
													$hobbies = (array) get_user_meta( $user->ID, 'si50_hobbies_interests', true );
													$circles_int = (array) get_user_meta( $user->ID, 'si50_circles_interest', true );

													$dest = (array) get_user_meta( $user->ID, 'si50_travel_destinations', true );
													$travel_dest_other = get_user_meta( $user->ID, 'si50_travel_destinations_other', true );
													$styles = (array) get_user_meta( $user->ID, 'si50_travel_styles', true );
													$companion = get_user_meta( $user->ID, 'si50_companion_styles', true );

													$selfie_url = get_user_meta( $user->ID, 'si50_verification_selfie_url', true );
													$special_incentives = (array) get_user_meta( $user->ID, 'si50_special_incentives', true );
													$intro = get_user_meta( $user->ID, 'si50_introduction', true );
													$connection_intent = get_user_meta( $user->ID, 'si50_connection_intent', true );
													$voice_intro = get_user_meta( $user->ID, 'si50_voice_intro', true );
													$payment_screenshot = get_user_meta( $user->ID, 'si50_payment_screenshot', true );
													$payment_mode = get_user_meta( $user->ID, 'si50_payment_mode', true );

													// Calculate Age
													$age = '';
													if ( ! empty( $dob ) ) {
														$dob_time = strtotime( $dob );
														if ( $dob_time ) {
															$age = date_diff( date_create( $dob ), date_create( 'today' ) )->y;
														}
													}
													?>
													<div class="si50-drawer-inner" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
														
														<!-- Section A: Demographics -->
														<div class="si50-sec-box">
															<h4 class="si50-sec-title"><?php esc_html_e( 'Section A: Demographics & Contact', 'secondinnings50' ); ?></h4>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Real Name:', 'secondinnings50' ); ?></strong> <?php echo esc_html( $fullname ); ?></p>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Email:', 'secondinnings50' ); ?></strong> <?php echo esc_html( $user->user_email ); ?></p>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'WhatsApp Phone:', 'secondinnings50' ); ?></strong> <?php echo esc_html( $phone ); ?></p>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'City & State:', 'secondinnings50' ); ?></strong> <?php echo esc_html( get_user_meta( $user->ID, 'si50_city_state', true ) ); ?></p>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Age:', 'secondinnings50' ); ?></strong> <?php echo esc_html( $age ? $age . ' ' . __( 'years', 'secondinnings50' ) : __( 'N/A', 'secondinnings50' ) ); ?></p>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Date of Birth:', 'secondinnings50' ); ?></strong> <?php echo esc_html( $dob ); ?></p>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Gender:', 'secondinnings50' ); ?></strong> <?php echo esc_html( $gender ); ?></p>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Occupation:', 'secondinnings50' ); ?></strong> <?php echo esc_html( $occupation ); ?></p>
															<p style="margin: 0 0 10px 0; font-size: 13px;"><strong><?php esc_html_e( 'Marital Status:', 'secondinnings50' ); ?></strong> <?php echo esc_html( $marital_status ); ?></p>
															<p style="margin: 0; padding: 8px; background: #fff3cd; border: 1.5px solid #ffeeba; border-radius: 4px; color: #856404; font-weight: 700; font-size: 12px;">
																🔒 <?php esc_html_e( 'Emergency Family Contact:', 'secondinnings50' ); ?> <?php echo esc_html( ! empty( $emergency ) ? $emergency : 'N/A' ); ?>
															</p>
														</div>

														<!-- Section B: Matching Intentions -->
														<div class="si50-sec-box">
															<h4 class="si50-sec-title"><?php esc_html_e( 'Section B: Matching Intentions', 'secondinnings50' ); ?></h4>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Location Preference:', 'secondinnings50' ); ?></strong> <?php echo esc_html( ! empty( $loc_pref ) ? $loc_pref : __( 'None', 'secondinnings50' ) ); ?></p>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Looking For (Custom Text):', 'secondinnings50' ); ?></strong> <?php echo esc_html( ! empty( $connection_intent ) ? $connection_intent : __( 'Not specified', 'secondinnings50' ) ); ?></p>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Seeking Focus (Relationship Checkboxes):', 'secondinnings50' ); ?></strong></p>
															<div style="display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 12px;">
																<?php if ( empty( $looking ) ) : ?>
																	<span style="font-size: 11px; color: #999;"><?php esc_html_e( 'None selected', 'secondinnings50' ); ?></span>
																<?php else : ?>
																	<?php foreach ( $looking as $lf ) : ?>
																		<span class="si50-sec-tag" style="background: #e8f0fe; color: #1a73e8; border: 1px solid #1a73e8; font-weight: 600;"><?php echo esc_html( $lf ); ?></span>
																	<?php endforeach; ?>
																<?php endif; ?>
															</div>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Hobbies Checklist:', 'secondinnings50' ); ?></strong></p>
															<div style="display: flex; flex-wrap: wrap; gap: 4px;">
																<?php if ( empty( $hobbies ) ) : ?>
																	<span style="font-size: 11px; color: #999;"><?php esc_html_e( 'None selected', 'secondinnings50' ); ?></span>
																<?php else : ?>
																	<?php foreach ( $hobbies as $hb ) : ?>
																		<span class="si50-sec-tag" style="background: #f1f3f4; color: #3c4043; border: 1px solid #dadce0; font-weight: 600;"><?php echo esc_html( $hb ); ?></span>
																	<?php endforeach; ?>
																<?php endif; ?>
															</div>
															<p style="margin: 8px 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Circles Onboarding Interest:', 'secondinnings50' ); ?></strong></p>
															<div style="display: flex; flex-wrap: wrap; gap: 4px;">
																<?php if ( empty( $circles_int ) ) : ?>
																	<span style="font-size: 11px; color: #999;"><?php esc_html_e( 'None selected', 'secondinnings50' ); ?></span>
																<?php else : ?>
																	<?php foreach ( $circles_int as $ci ) : ?>
																		<span class="si50-sec-tag" style="background: #e6f4ea; color: #137333; border: 1px solid #ceead6; font-weight: 600;"><?php echo esc_html( $ci ); ?></span>
																	<?php endforeach; ?>
																<?php endif; ?>
															</div>
														</div>

														<!-- Section C: Teerth Yatra & Travel Profiles (Bypassed/Hidden) -->
														<div class="si50-sec-box" style="display: none;">
															<h4 class="si50-sec-title"><?php esc_html_e( 'Section C: Teerth Yatra & Travel Profiles', 'secondinnings50' ); ?></h4>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Checked Pilgrimage/Leisure Destinations:', 'secondinnings50' ); ?></strong></p>
															<div style="display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 12px;">
																<?php if ( empty( $dest ) ) : ?>
																	<span style="font-size: 11px; color: #999;"><?php esc_html_e( 'None selected', 'secondinnings50' ); ?></span>
																<?php else : ?>
																	<?php foreach ( $dest as $d ) : 
																		if ( 'Other' === $d ) {
																			$other_lbl = ! empty( $travel_dest_other ) ? sprintf( __( 'Other (%s)', 'secondinnings50' ), $travel_dest_other ) : __( 'Other', 'secondinnings50' );
																			echo '<span class="si50-sec-tag" style="background: #e6f4ea; color: #137333; border: 1px solid #137333; font-weight: 700;">' . esc_html( $other_lbl ) . '</span>';
																		} else {
																			echo '<span class="si50-sec-tag" style="background: #e6f4ea; color: #137333; border: 1px solid #ceead6; font-weight: 600;">' . esc_html( $d ) . '</span>';
																		}
																	endforeach; ?>
																<?php endif; ?>
															</div>
															<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Preferred Travel Styles:', 'secondinnings50' ); ?></strong></p>
															<div style="display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 12px;">
																<?php if ( empty( $styles ) ) : ?>
																	<span style="font-size: 11px; color: #999;"><?php esc_html_e( 'None selected', 'secondinnings50' ); ?></span>
																<?php else : ?>
																	<?php foreach ( $styles as $ts ) : ?>
																		<span class="si50-sec-tag" style="background: #fef7e0; color: #b06000; border: 1px solid #fde293; font-weight: 600;"><?php echo esc_html( $ts ); ?></span>
																	<?php endforeach; ?>
																<?php endif; ?>
															</div>
															<p style="margin: 0; font-size: 13px;"><strong><?php esc_html_e( 'Companion Rules:', 'secondinnings50' ); ?></strong> <?php echo esc_html( ! empty( $companion ) ? $companion : __( 'None selected', 'secondinnings50' ) ); ?></p>
														</div>

														<!-- Section D: Safety Onboarding -->
														<div class="si50-sec-box" style="display: grid; grid-template-columns: 120px 1fr; gap: 15px;">
															<div>
																<span style="display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; color: #5f6368; margin-bottom: 6px;"><?php esc_html_e( 'Selfie Photo:', 'secondinnings50' ); ?></span>
																<?php if ( ! empty( $selfie_url ) ) : ?>
																	<a href="<?php echo esc_url( $selfie_url ); ?>" target="_blank">
																		<img src="<?php echo esc_url( $selfie_url ); ?>" style="width: 100%; height: 100px; object-fit: cover; border: 2.5px solid #333333; border-radius: 4px;" alt="<?php esc_attr_e( 'Verification Selfie', 'secondinnings50' ); ?>" />
																	</a>
																<?php else : ?>
																	<div style="width: 100%; height: 100px; background: #f1f3f4; border: 1.5px dashed #ccc; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999; text-align: center;"><?php esc_html_e( 'No Selfie Uploaded', 'secondinnings50' ); ?></div>
																<?php endif; ?>
																
																<?php if ( ! empty( $payment_screenshot ) ) : ?>
																	<span style="display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; color: #5f6368; margin-bottom: 6px; margin-top: 10px;"><?php esc_html_e( 'Payment Proof (QR):', 'secondinnings50' ); ?></span>
																	<a href="<?php echo esc_url( $payment_screenshot ); ?>" target="_blank">
																		<img src="<?php echo esc_url( $payment_screenshot ); ?>" style="width: 100%; height: 100px; object-fit: cover; border: 2.5px solid #137333; border-radius: 4px;" alt="<?php esc_attr_e( 'Payment Screenshot', 'secondinnings50' ); ?>" />
																	</a>
																<?php endif; ?>
															</div>
															<div>
																<h4 class="si50-sec-title"><?php esc_html_e( 'Section D: Safety Onboarding', 'secondinnings50' ); ?></h4>
																<p style="margin: 0 0 8px 0; font-size: 13px;"><strong><?php esc_html_e( 'Self-Identification Segments:', 'secondinnings50' ); ?></strong></p>
																<div style="display: flex; flex-wrap: wrap; gap: 4px; margin-bottom: 8px;">
																	<?php if ( empty( $special_incentives ) ) : ?>
																		<span style="font-size: 11px; color: #999; font-style: italic;"><?php esc_html_e( 'None selected', 'secondinnings50' ); ?></span>
																	<?php else : ?>
																		<?php foreach ( $special_incentives as $si ) : ?>
																			<span class="si50-sec-tag" style="background: #fce8e6; color: #c5221f; border: 1px solid #fad2cf; font-weight: 600;"><?php echo esc_html( $si ); ?></span>
																		<?php endforeach; ?>
																	<?php endif; ?>
																</div>
																<p style="margin: 0; font-size: 13px;"><strong><?php esc_html_e( 'Bio summary:', 'secondinnings50' ); ?></strong> <?php echo esc_html( wp_trim_words( $intro, 12 ) ); ?></p>
															</div>
														</div>

														<!-- Bio and PDF Exporter button line (Full Width) -->
														<div class="si50-sec-box" style="grid-column: span 2;">
															<h4 class="si50-sec-title"><?php esc_html_e( '📝 Personal Biography Statement', 'secondinnings50' ); ?></h4>
															
															<?php if ( ! empty( $voice_intro ) ) : ?>
																<div style="margin-bottom: 15px;">
																	<span style="font-size: 13px; font-weight: bold; display: block; margin-bottom: 6px;">🎙️ <?php esc_html_e( 'Voice Introduction Audio:', 'secondinnings50' ); ?></span>
																	<audio controls style="height: 32px;">
																		<source src="<?php echo esc_url( $voice_intro ); ?>" type="audio/mpeg">
																	</audio>
																</div>
															<?php endif; ?>

															<p style="font-size: 13px; line-height: 1.5; color: #3c4043; margin: 0 0 16px 0; background: #f8f9fa; padding: 12px; border-radius: 4px; border: 1px solid #e0e0e0; white-space: pre-line;">
																<?php echo esc_html( ! empty( $intro ) ? $intro : __( 'No biography submitted yet.', 'secondinnings50' ) ); ?>
															</p>
															<div style="display: flex; justify-content: flex-end;">
																<a href="<?php echo esc_url( admin_url( 'admin.php?page=si50-onboarding-dashboard&action=download_pdf&user_id=' . $user->ID . '&_wpnonce=' . wp_create_nonce( 'si50_admin_vetting_action' ) ) ); ?>" class="si50-action-btn" title="<?php esc_attr_e( 'Download Member Application PDF Dossier', 'secondinnings50' ); ?>" style="background-color: #1a73e8; color: #ffffff; border-color: #333333; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
																	<span class="dashicons dashicons-download" style="font-size: 16px; width: 16px; height: 16px; line-height: 16px;"></span>
																	<?php esc_html_e( 'Download Report', 'secondinnings50' ); ?>
																</a>
															</div>
														</div>

														<!-- Section E: Recent Platform Activity (Full Width) -->
														<div class="si50-sec-box" style="grid-column: span 2; margin-top: 10px;">
															<h4 class="si50-sec-title"><?php esc_html_e( 'Section E: Recent Platform Activity', 'secondinnings50' ); ?></h4>
															<?php
															$drawer_activities = $wpdb->get_results( $wpdb->prepare(
																"SELECT * FROM {$wpdb->prefix}si50_audit_logs 
																 WHERE user_id = %d 
																 ORDER BY timestamp DESC 
																 LIMIT 15",
																$user->ID
															) );
															if ( empty( $drawer_activities ) ) :
																?>
																<p style="font-size: 13px; color: #5f6368; font-style: italic; margin: 0;"><?php esc_html_e( 'No recent activity logs recorded for this user.', 'secondinnings50' ); ?></p>
															<?php else : ?>
																<div style="max-height: 200px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 4px; background: #ffffff;">
																	<table style="width: 100%; border-collapse: collapse; font-size: 12px; text-align: left;">
																		<thead>
																			<tr style="background-color: #f8f9fa; border-bottom: 1px solid #e0e0e0; position: sticky; top: 0; z-index: 10;">
																				<th style="padding: 8px 10px; font-weight: bold; color: #5f6368;"><?php esc_html_e( 'Date & Time', 'secondinnings50' ); ?></th>
																				<th style="padding: 8px 10px; font-weight: bold; color: #5f6368;"><?php esc_html_e( 'Action', 'secondinnings50' ); ?></th>
																				<th style="padding: 8px 10px; font-weight: bold; color: #5f6368;"><?php esc_html_e( 'Description', 'secondinnings50' ); ?></th>
																			</tr>
																		</thead>
																		<tbody>
																			<?php foreach ( $drawer_activities as $da ) : ?>
																				<tr style="border-bottom: 1px solid #eeeeee;">
																					<td style="padding: 8px 10px; color: #5f6368; white-space: nowrap;">
																						<?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $da->timestamp ) ) ); ?>
																					</td>
																					<td style="padding: 8px 10px; font-weight: bold; color: #1b3b2b;">
																						<?php echo esc_html( strtoupper( $da->action ) ); ?>
																					</td>
																					<td style="padding: 8px 10px; color: #3c4043;">
																						<?php echo esc_html( $da->details ); ?>
																					</td>
																				</tr>
																			<?php endforeach; ?>
																		</tbody>
																	</table>
																</div>
															<?php endif; ?>
														</div>

													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>
					</div>
				<?php elseif ( 'contact_inquiries' === $active_tab ) : ?>
					<!-- TAB 3: CONTACT INQUIRIES LOG -->
					<div id="contact-inquiries-log" class="si50-card">
						<h2 class="si50-card-title">
							<span>
								<span class="dashicons dashicons-email-alt" style="color: #202124; margin-right: 8px; font-size: 20px;"></span>
								<?php esc_html_e( 'Customer Support Contact Inquiries Log', 'secondinnings50' ); ?>
							</span>
							<a href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin.php?page=si50-onboarding-dashboard&action=download_bulk_pdf&tab=contact_inquiries&start_date=' . $start_date . '&end_date=' . $end_date ), 'si50_admin_bulk_pdf' ) ); ?>" class="si50-action-btn" style="background-color: #1b3b2b; color: #ffffff; border-color: #333333; font-size: 11px; padding: 4px 10px; font-weight: bold; text-decoration: none;">
								<span class="dashicons dashicons-pdf" style="font-size: 14px; width: 14px; height: 14px;"></span>
								<?php esc_html_e( 'Download Date-Wise Support Report', 'secondinnings50' ); ?>
							</a>
						</h2>
						
						<?php if ( empty( $inquiries ) ) : ?>
							<p style="color: #5f6368; font-style: italic;"><?php esc_html_e( 'No customer support contact inquiries found.', 'secondinnings50' ); ?></p>
						<?php else : ?>
							<div class="si50-table-container">
								<table class="si50-list-table">
									<thead>
										<tr>
											<th style="width: 40px; text-align: center;"></th>
											<th><?php esc_html_e( 'Submission Date', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Sender Name', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Email', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'WhatsApp Phone Number', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Nature of Inquiry', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Actions', 'secondinnings50' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $inquiries as $inq ) : ?>
											<tr>
												<td style="vertical-align: middle; text-align: center;">
													<button type="button" class="si50-inquiry-drawer-toggle-btn" data-inquiry-id="<?php echo $inq->id; ?>" aria-expanded="false" style="background: none; border: none; cursor: pointer; color: #1a73e8; padding: 4px; display: inline-flex; align-items: center; justify-content: center;" title="<?php esc_attr_e( 'Inspect Message Body', 'secondinnings50' ); ?>">
														<span class="dashicons dashicons-visibility" style="font-size: 20px; width: 20px; height: 20px;"></span>
													</button>
												</td>
												<td style="font-size: 0.85em; color: #5f6368; vertical-align: middle;">
													<?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $inq->timestamp ) ) ); ?>
												</td>
												<td><strong><?php echo esc_html( $inq->name ); ?></strong></td>
												<td><?php echo esc_html( $inq->email ); ?></td>
												<td><?php echo esc_html( $inq->phone ); ?></td>
												<td>
													<span class="si50-badge si50-badge-pending" style="font-size: 11px; border: 1px solid #c5a059; background: #fdfbf7; color: #1b3b2b; font-weight: 700;">
														<?php echo esc_html( $inq->inquiry_nature ); ?>
													</span>
												</td>
												<td style="vertical-align: middle;">
													<button type="button" class="si50-inquiry-drawer-toggle-btn si50-action-btn" data-inquiry-id="<?php echo $inq->id; ?>" style="background-color: #1a73e8; color: #ffffff; border-color: #333333; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 12px; border-radius: 4px; text-decoration: none; cursor: pointer;">
														<span class="dashicons dashicons-visibility" style="font-size: 16px; width: 16px; height: 16px; line-height: 16px;"></span>
														<?php esc_html_e( 'View Message', 'secondinnings50' ); ?>
													</button>
												</td>
											</tr>
											<!-- Expandable details drawer row -->
											<tr id="si50-inquiry-drawer-row-<?php echo $inq->id; ?>" class="si50-inquiry-drawer-row" style="display: none; background-color: #fafafa;">
												<td colspan="7" style="padding: 16px 20px; border-bottom: 1px solid #e0e0e0;">
													<div class="si50-sec-box" style="border-left: 5px solid #1b3b2b;">
														<h4 class="si50-sec-title" style="color: #c5221f; border-bottom-color: #1b3b2b;"><?php esc_html_e( '💬 Customer Support Inquiry Message Payload', 'secondinnings50' ); ?></h4>
														<p style="font-size: 15px; line-height: 1.6; color: #202124; margin: 0; padding: 12px; background: #fdfbf7; border-radius: 4px; border: 1.5px solid #e0e0e0; white-space: pre-line; font-family: inherit;">
															<?php echo esc_html( $inq->message ); ?>
														</p>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>
					</div>
				<?php elseif ( 'whatsapp_manager' === $active_tab ) : ?>
					<!-- TAB 4: WHATSAPP CIRCLES MANAGER -->
					<div id="whatsapp-circles-manager" class="si50-card" style="background: #ffffff; border: 1.5px solid #333333; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); box-sizing: border-box; margin-bottom: 20px;">
						<h2 class="si50-card-title" style="margin-bottom: 12px; font-family: var(--font-serif); color: #1b3b2b; display: flex; align-items: center; gap: 8px; border-bottom: none; padding-bottom: 0;">
							<span class="dashicons dashicons-share" style="color: #1b3b2b; font-size: 20px; width: 20px; height: 20px; line-height: 20px;"></span>
							<span><?php esc_html_e( 'WhatsApp Community Circles Link Manager', 'secondinnings50' ); ?></span>
						</h2>
						<p style="font-size: 13px; color: #5f6368; margin-bottom: 24px; margin-top: 0;">
							<?php esc_html_e( 'Manage regional cluster groups, fallback national groups, and custom interest circles in a single unified list. Use the Edit (pencil) and Delete (trash) icons beside each group to update their settings.', 'secondinnings50' ); ?>
						</p>

						<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=si50-onboarding-dashboard&tab=whatsapp_manager' ) ); ?>" style="max-width: 1000px;">
							<?php wp_nonce_field( 'si50_admin_whatsapp_settings', 'si50_wa_nonce' ); ?>
							<input type="hidden" name="si50_save_whatsapp_settings" value="1" />

							<style>
							#si50-wa-group-modal {
								position: fixed;
								top: 0;
								left: 0;
								width: 100%;
								height: 100%;
								background-color: rgba(32, 33, 36, 0.6);
								backdrop-filter: blur(4px);
								-webkit-backdrop-filter: blur(4px);
								z-index: 999999;
								display: none;
								align-items: center;
								justify-content: center;
								opacity: 0;
								transition: opacity 0.25s ease-in-out;
							}
							#si50-wa-group-modal.show {
								display: flex;
								opacity: 1;
							}
							#si50-wa-group-modal .si50-modal-content {
								background: #ffffff;
								border: 3px solid #333333;
								border-radius: 8px;
								width: 90%;
								max-width: 500px;
								box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
								position: relative;
								padding: 24px;
								transform: scale(0.92);
								transition: transform 0.25s ease-in-out;
								box-sizing: border-box;
							}
							#si50-wa-group-modal.show .si50-modal-content {
								transform: scale(1);
							}
							</style>

							<div class="si50-table-container" style="margin-bottom: 20px; border: 1.5px solid #333333; border-radius: 6px; overflow: hidden; background: #ffffff;">
								<table class="si50-list-table" id="si50-custom-groups-table" style="margin-top: 0; width: 100%; border-collapse: collapse;">
									<thead>
										<tr>
											<th style="width: 30%; font-weight: 700; color: #202124; background: #f1f3f4; padding: 12px 10px; border-bottom: 2px solid #e0e0e0; font-size: 13px; text-align: left;"><?php esc_html_e( 'Group Name', 'secondinnings50' ); ?></th>
											<th style="width: 35%; font-weight: 700; color: #202124; background: #f1f3f4; padding: 12px 10px; border-bottom: 2px solid #e0e0e0; font-size: 13px; text-align: left;"><?php esc_html_e( 'WhatsApp Invite URL', 'secondinnings50' ); ?></th>
											<th style="width: 25%; font-weight: 700; color: #202124; background: #f1f3f4; padding: 12px 10px; border-bottom: 2px solid #e0e0e0; font-size: 13px; text-align: left;"><?php esc_html_e( 'Description / Target', 'secondinnings50' ); ?></th>
											<th style="width: 10%; font-weight: 700; color: #202124; background: #f1f3f4; padding: 12px 10px; border-bottom: 2px solid #e0e0e0; font-size: 13px; text-align: center;"><?php esc_html_e( 'Actions', 'secondinnings50' ); ?></th>
										</tr>
									</thead>
									<tbody id="si50-custom-groups-tbody">
										<!-- Will be populated dynamically by JS -->
									</tbody>
								</table>
							</div>

							<!-- Hidden container for POST arrays -->
							<div id="si50-custom-groups-hidden-inputs"></div>

							<div style="margin: 16px 0 24px 0; padding: 16px; border: 1px solid #e0e0e0; border-radius: 8px; background: #fafafa;">
								<label for="si50-wa-womens-lounge-capacity" style="display: block; font-weight: 700; margin-bottom: 6px; font-size: 13px; color: #202124;">
									<?php esc_html_e( 'Women\'s Lounge Capacity', 'secondinnings50' ); ?>
								</label>
								<input type="number" id="si50-wa-womens-lounge-capacity" name="si50_wa_womens_lounge_capacity" min="0" step="1" value="<?php echo esc_attr( get_option( 'si50_wa_womens_lounge_capacity', 0 ) ); ?>" style="width: 120px; border: 1px solid #7e8993; border-radius: 4px; padding: 8px; font-size: 13px; height: 36px; box-sizing: border-box;" />
								<p style="margin: 8px 0 0 0; color: #5f6368; font-size: 12px; line-height: 1.4;">
									<?php esc_html_e( 'Enter the maximum number of verified women members for the dedicated Women\'s Lounge circle. Leave blank or zero for no limit.', 'secondinnings50' ); ?>
								</p>
							</div>

							<div style="margin-top: 12px; margin-bottom: 24px;">
								<button type="button" id="si50-add-group-btn" class="button" style="display: inline-flex; align-items: center; gap: 6px; border: 2px solid #333333; background: #ffffff; color: #333333; font-weight: 700; height: 36px; line-height: 32px; cursor: pointer;">
									<span class="dashicons dashicons-plus" style="font-size: 16px; width: 16px; height: 16px; line-height: 16px; margin-top: 4px;"></span>
									<span><?php esc_html_e( 'Add Custom WhatsApp Group', 'secondinnings50' ); ?></span>
								</button>
							</div>

							<!-- Modal for Adding / Editing Custom WhatsApp Groups -->
							<div id="si50-wa-group-modal" role="dialog" aria-modal="true" aria-labelledby="si50-wa-modal-title">
								<div class="si50-modal-content">
									<button type="button" class="si50-modal-close" id="si50-wa-modal-close-btn" aria-label="Close modal" style="position: absolute; top: 16px; right: 16px; background: none; border: none; font-size: 24px; cursor: pointer; color: #5f6368; line-height: 1;">&times;</button>
									<h3 class="si50-modal-header" id="si50-wa-modal-title" style="margin-top: 0; margin-bottom: 20px; font-family: var(--font-serif); color: #1b3b2b; font-size: 18px; font-weight: 700; border-bottom: 1px solid #eeeeee; padding-bottom: 12px;">Add Custom WhatsApp Group</h3>
									
									<div style="margin-bottom: 16px;">
										<label for="si50-wa-field-name" style="display: block; font-weight: 700; font-size: 13px; color: #202124; margin-bottom: 6px;">
											Group Name <span style="color: #c5221f;">*</span>
										</label>
										<input type="text" id="si50-wa-field-name" style="width: 100%; border: 1px solid #7e8993; border-radius: 4px; padding: 8px; font-size: 13px; height: 36px; box-sizing: border-box;" placeholder="e.g. Chess Enthusiasts" />
									</div>
									
									<div style="margin-bottom: 16px;">
										<label for="si50-wa-field-url" style="display: block; font-weight: 700; font-size: 13px; color: #202124; margin-bottom: 6px;">
											WhatsApp Invite URL <span style="color: #c5221f;">*</span>
										</label>
										<input type="url" id="si50-wa-field-url" style="width: 100%; border: 1px solid #7e8993; border-radius: 4px; padding: 8px; font-size: 13px; height: 36px; box-sizing: border-box;" placeholder="https://chat.whatsapp.com/..." />
									</div>
									
									<div style="margin-bottom: 20px;">
										<label for="si50-wa-field-desc" style="display: block; font-weight: 700; font-size: 13px; color: #202124; margin-bottom: 6px;">
											Description / Target
										</label>
										<input type="text" id="si50-wa-field-desc" style="width: 100%; border: 1px solid #7e8993; border-radius: 4px; padding: 8px; font-size: 13px; height: 36px; box-sizing: border-box;" placeholder="e.g. For Chess lovers in Mumbai" />
									</div>
									
									<div class="si50-modal-footer" style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eeeeee; padding-top: 16px;">
										<button type="button" class="button" id="si50-wa-modal-cancel-btn" style="height: 36px; line-height: 34px; font-weight: bold; border: 1.5px solid #7e8993; background: #ffffff; color: #3c4043; cursor: pointer; padding: 0 16px; border-radius: 4px;">Cancel</button>
										<button type="button" class="button button-primary" id="si50-wa-modal-submit-btn" style="height: 36px; line-height: 34px; font-weight: bold; background-color: #1b3b2b; border-color: #1b3b2b; color: #ffffff; cursor: pointer; padding: 0 16px; border-radius: 4px;">Add Group</button>
									</div>
								</div>
							</div>

							<script>
							jQuery(document).ready(function($) {
								<?php
								$wa_fields = array(
									'si50_wa_mumbai'    => array(
										'label'   => esc_html__( 'Mumbai Cluster WhatsApp Group Link', 'secondinnings50' ),
										'default' => 'https://chat.whatsapp.com/MumbaiActiveSeniors',
										'desc'    => esc_html__( 'Targeting Mumbai & Bombay region residents.', 'secondinnings50' )
									),
									'si50_wa_delhi'     => array(
										'label'   => esc_html__( 'Delhi-NCR Cluster WhatsApp Group Link', 'secondinnings50' ),
										'default' => 'https://chat.whatsapp.com/DelhiActiveSeniors',
										'desc'    => esc_html__( 'Targeting Delhi, NCR, Noida, Gurgaon, Gurugram, Ghaziabad, and Faridabad residents.', 'secondinnings50' )
									),
									'si50_wa_bangalore' => array(
										'label'   => esc_html__( 'Bangalore Circle WhatsApp Group Link', 'secondinnings50' ),
										'default' => 'https://chat.whatsapp.com/BangaloreActiveSeniors',
										'desc'    => esc_html__( 'Targeting Bangalore & Bengaluru region residents.', 'secondinnings50' )
									),
									'si50_wa_pune'      => array(
										'label'   => esc_html__( 'Pune Circle WhatsApp Group Link', 'secondinnings50' ),
										'default' => 'https://chat.whatsapp.com/PuneActiveSeniors',
										'desc'    => esc_html__( 'Targeting Pune & Poona region residents.', 'secondinnings50' )
									),
									'si50_wa_hyderabad' => array(
										'label'   => esc_html__( 'Hyderabad Hub WhatsApp Group Link', 'secondinnings50' ),
										'default' => 'https://chat.whatsapp.com/HyderabadActiveSeniors',
										'desc'    => esc_html__( 'Targeting Hyderabad & Secunderabad region residents.', 'secondinnings50' )
									),
									'si50_wa_chennai'   => array(
										'label'   => esc_html__( 'Chennai Hub WhatsApp Group Link', 'secondinnings50' ),
										'default' => 'https://chat.whatsapp.com/ChennaiActiveSeniors',
										'desc'    => esc_html__( 'Targeting Chennai & Madras region residents.', 'secondinnings50' )
									),
									'si50_wa_kolkata'   => array(
										'label'   => esc_html__( 'Kolkata Hub WhatsApp Group Link', 'secondinnings50' ),
										'default' => 'https://chat.whatsapp.com/KolkataActiveSeniors',
										'desc'    => esc_html__( 'Targeting Kolkata & Calcutta region residents.', 'secondinnings50' )
									),
'si50_wa_womens_lounge' => array(
							'label'   => esc_html__( 'Women\'s Lounge (Verified Women 45+ Circle) WhatsApp Group', 'secondinnings50' ),
							'default' => '',
							'desc'    => esc_html__( 'For verified women aged 45+ who prefer a dedicated women-only circle.', 'secondinnings50' )
						),
						'si50_wa_fallback'  => array(
										'label'   => esc_html__( 'Fallback: All-India Active Silver Companions WhatsApp Link', 'secondinnings50' ),
										'default' => 'https://chat.whatsapp.com/IndiaActiveSeniors',
										'desc'    => esc_html__( 'Assigned if the member\'s city is not mapped within any regional cluster.', 'secondinnings50' )
									),
								);

								$all_groups = array();
								foreach ( $wa_fields as $key => $data ) {
									$url = get_option( $key );
									if ( false === $url ) {
										$url = $data['default'];
									}
									$name = get_option( $key . '_name' );
									if ( empty( $name ) ) {
										$name = $data['label'];
									}
									$desc = get_option( $key . '_desc' );
									if ( empty( $desc ) ) {
										$desc = $data['desc'];
									}

									$all_groups[] = array(
										'key'        => $key,
										'name'       => $name,
										'url'        => $url,
										'desc'       => $desc,
										'is_default' => true,
									);
								}

								$custom_groups = get_option( 'si50_custom_whatsapp_groups', array() );
								if ( ! empty( $custom_groups ) ) {
									foreach ( $custom_groups as $group ) {
										$all_groups[] = array(
											'key'        => 'custom',
											'name'       => $group['name'],
											'url'        => $group['url'],
											'desc'       => isset( $group['desc'] ) ? $group['desc'] : '',
											'is_default' => false,
										);
									}
								}
								?>
								// Load initial groups array from PHP
								var whatsappGroups = <?php echo json_encode( $all_groups ); ?>;
								var activeIndex = -1; // -1 for add, index number for edit
								
								function renderGroups() {
									var tbody = $('#si50-custom-groups-tbody');
									var hiddenInputs = $('#si50-custom-groups-hidden-inputs');
									
									tbody.empty();
									hiddenInputs.empty();
									
									if (whatsappGroups.length === 0) {
										tbody.append('<tr class="si50-no-groups-row"><td colspan="4" style="text-align: center; color: #5f6368; font-style: italic; padding: 24px; background: #fafafa; border-bottom: 1px solid #e0e0e0;"><?php echo esc_js( __( 'No WhatsApp groups configured.', 'secondinnings50' ) ); ?></td></tr>');
										return;
									}
									
									$.each(whatsappGroups, function(index, group) {
										var tr = $('<tr class="si50-custom-group-tr">').attr('data-index', index);

										var tdName = $('<td style="font-weight: 600; color: #202124; padding: 12px 10px; border-bottom: 1px solid #e0e0e0; vertical-align: middle;">').text(group.name);

										var displayUrl = group.url || '<?php echo esc_js( __( '(no link configured)', 'secondinnings50' ) ); ?>';
										var tdUrl = $('<td style="padding: 12px 10px; border-bottom: 1px solid #e0e0e0; vertical-align: middle;">');
										if (group.url) {
											var link = $('<a>').attr({
												href: group.url,
												target: '_blank'
											}).css({
												color: '#007cba',
												'text-decoration': 'none',
												'word-break': 'break-all'
											}).text(group.url);
											tdUrl.append(link);
										} else {
											tdUrl.text(displayUrl).css('color', '#c5221f');
										}

										var tdDesc = $('<td style="color: #5f6368; padding: 12px 10px; border-bottom: 1px solid #e0e0e0; vertical-align: middle;">').text(group.desc || '');

										var editBtn = $('<button type="button" class="button button-small si50-edit-group-btn">')
											.css({
												'margin-right': '6px',
												border: '1.5px solid #007cba',
												background: '#ffffff',
												width: '30px',
												height: '30px',
												padding: '0',
												display: 'inline-flex',
												'align-items': 'center',
												'justify-content': 'center',
												cursor: 'pointer'
											})
											.attr('title', '<?php echo esc_js( __( "Edit Group", "secondinnings50" ) ); ?>')
											.append('<span class="dashicons dashicons-edit" style="font-size: 16px; width: 16px; height: 16px; line-height: 16px; color: #007cba;"></span>');

										var deleteBtn = $('<button type="button" class="button button-small si50-delete-group-btn">')
											.css({
												border: '1.5px solid #d63638',
												background: '#ffffff',
												width: '30px',
												height: '30px',
												padding: '0',
												display: 'inline-flex',
												'align-items': 'center',
												'justify-content': 'center',
												cursor: 'pointer'
											})
											.attr('title', '<?php echo esc_js( __( "Delete Group", "secondinnings50" ) ); ?>')
											.append('<span class="dashicons dashicons-trash" style="font-size: 16px; width: 16px; height: 16px; line-height: 16px; color: #d63638;"></span>');

										var tdActions = $('<td style="text-align: center; padding: 12px 10px; border-bottom: 1px solid #e0e0e0; vertical-align: middle; white-space: nowrap;">').append(editBtn).append(deleteBtn);

										tr.append(tdName).append(tdUrl).append(tdDesc).append(tdActions);
										tbody.append(tr);
										
										// Append hidden inputs safely based on group type
										if (group.key !== 'custom') {
											$('<input>').attr({
												type: 'hidden',
												name: group.key
											}).val(group.url).appendTo(hiddenInputs);

											$('<input>').attr({
												type: 'hidden',
												name: group.key + '_name'
											}).val(group.name).appendTo(hiddenInputs);

											$('<input>').attr({
												type: 'hidden',
												name: group.key + '_desc'
											}).val(group.desc || '').appendTo(hiddenInputs);
										} else {
											$('<input>').attr({
												type: 'hidden',
												name: 'custom_groups_name[]'
											}).val(group.name).appendTo(hiddenInputs);

											$('<input>').attr({
												type: 'hidden',
												name: 'custom_groups_url[]'
											}).val(group.url).appendTo(hiddenInputs);

											$('<input>').attr({
												type: 'hidden',
												name: 'custom_groups_desc[]'
											}).val(group.desc || '').appendTo(hiddenInputs);
										}
									});
								}
								
								// Initial Render
								renderGroups();
								
								// Show Modal function
								function showModal(title, submitLabel) {
									$('#si50-wa-modal-title').text(title);
									$('#si50-wa-modal-submit-btn').text(submitLabel);
									$('#si50-wa-group-modal').addClass('show');
								}
								
								// Hide Modal function
								function hideModal() {
									$('#si50-wa-group-modal').removeClass('show');
									$('#si50-wa-field-name').val('');
									$('#si50-wa-field-url').val('');
									$('#si50-wa-field-desc').val('');
									activeIndex = -1;
								}
								
								// Add Button Clicked
								$('#si50-add-group-btn').on('click', function(e) {
									e.preventDefault();
									activeIndex = -1;
									showModal('<?php echo esc_js( __( 'Add Custom WhatsApp Group', 'secondinnings50' ) ); ?>', '<?php echo esc_js( __( 'Add Group', 'secondinnings50' ) ); ?>');
								});
								
								// Edit Button Clicked
								$('#si50-custom-groups-tbody').on('click', '.si50-edit-group-btn', function(e) {
									e.preventDefault();
									var tr = $(this).closest('tr');
									activeIndex = parseInt(tr.attr('data-index'));
									var group = whatsappGroups[activeIndex];
									
									$('#si50-wa-field-name').val(group.name);
									$('#si50-wa-field-url').val(group.url);
									$('#si50-wa-field-desc').val(group.desc || '');
									
									showModal('<?php echo esc_js( __( 'Edit Custom WhatsApp Group', 'secondinnings50' ) ); ?>', '<?php echo esc_js( __( 'Update Group', 'secondinnings50' ) ); ?>');
								});
								
								// Delete Button Clicked
								$('#si50-custom-groups-tbody').on('click', '.si50-delete-group-btn', function(e) {
									e.preventDefault();
									var tr = $(this).closest('tr');
									var index = parseInt(tr.attr('data-index'));
									var group = whatsappGroups[index];
									
									var confirmMsg = '';
									if (group.key !== 'custom') {
										confirmMsg = '<?php echo esc_js( __( 'Are you sure you want to remove the default regional group? Inactive default groups will fallback to the fallback national group.', 'secondinnings50' ) ); ?> "' + group.name + '"?';
									} else {
										confirmMsg = '<?php echo esc_js( __( 'Are you sure you want to remove this custom WhatsApp group?', 'secondinnings50' ) ); ?> "' + group.name + '"?';
									}
									
									if (confirm(confirmMsg)) {
										whatsappGroups.splice(index, 1);
										renderGroups();
									}
								});
								
								// Modal Cancel Clicked
								$('#si50-wa-modal-close-btn, #si50-wa-modal-cancel-btn').on('click', function(e) {
									e.preventDefault();
									hideModal();
								});
								
								// Modal Close when clicking outside content area
								$('#si50-wa-group-modal').on('click', function(e) {
									if ($(e.target).is('#si50-wa-group-modal')) {
										hideModal();
									}
								});
								
								// Modal Submit Clicked
								$('#si50-wa-modal-submit-btn').on('click', function(e) {
									e.preventDefault();
									
									var name = $.trim($('#si50-wa-field-name').val());
									var url = $.trim($('#si50-wa-field-url').val());
									var desc = $.trim($('#si50-wa-field-desc').val());
									
									if (!name) {
										alert('<?php echo esc_js( __( 'Please enter a Group Name.', 'secondinnings50' ) ); ?>');
										$('#si50-wa-field-name').focus();
										return;
									}
									
									if (!url) {
										alert('<?php echo esc_js( __( 'Please enter a WhatsApp Invite URL.', 'secondinnings50' ) ); ?>');
										$('#si50-wa-field-url').focus();
										return;
									}
									
									// Basic URL validation
									var urlPattern = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/i;
									if (!urlPattern.test(url) && !url.startsWith('https://chat.whatsapp.com/')) {
										alert('<?php echo esc_js( __( 'Please enter a valid URL.', 'secondinnings50' ) ); ?>');
										$('#si50-wa-field-url').focus();
										return;
									}
									
									if (activeIndex === -1) {
										// Add mode (always custom)
										whatsappGroups.push({
											key: 'custom',
											name: name,
											url: url,
											desc: desc,
											is_default: false
										});
									} else {
										// Edit mode (preserve key and default status)
										var existingGroup = whatsappGroups[activeIndex];
										existingGroup.name = name;
										existingGroup.url = url;
										existingGroup.desc = desc;
									}
									
									renderGroups();
									hideModal();
								});
							});
							</script>

							<div style="margin-top: 32px; border-top: 1.5px solid #eeeeee; padding-top: 20px;">
								<button type="submit" class="button button-primary" style="height: 44px; line-height: 42px; font-weight: 700; font-size: 14px; border-radius: 4px; background-color: #1b3b2b; border-color: #333333; border-width: 2px; border-style: solid; padding: 0 32px; cursor: pointer;">
									<?php esc_html_e( 'Save WhatsApp Group Settings', 'secondinnings50' ); ?>
								</button>
							</div>
						</form>
					</div>
				<?php elseif ( 'safety_reports' === $active_tab ) : ?>
					<!-- TAB 5: SAFETY & REPORTS -->
					<div id="safety-reports-queue" class="si50-card">
						<h2 class="si50-card-title">
							<span>
								<span class="dashicons dashicons-shield-alt" style="color: #202124; margin-right: 8px; font-size: 20px;"></span>
								<?php esc_html_e( 'Safety Reports & Misuse Complaints Log', 'secondinnings50' ); ?>
							</span>
						</h2>
						
						<?php if ( empty( $reports ) ) : ?>
							<p style="color: #5f6368; font-style: italic;"><?php esc_html_e( 'No safety reports logged.', 'secondinnings50' ); ?></p>
						<?php else : ?>
							<div class="si50-table-container">
								<table class="si50-list-table">
									<thead>
										<tr>
											<th><?php esc_html_e( 'Report Date', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Reporter', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Reported Member', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Reason Category', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Description/Details', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Status', 'secondinnings50' ); ?></th>
											<th><?php esc_html_e( 'Actions', 'secondinnings50' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $reports as $rep ) : 
											$reporter = get_userdata( $rep->reporter_id );
											$reported = get_userdata( $rep->reported_id );
											
											$rep_name = $reporter ? get_user_meta( $rep->reporter_id, 'si50_fullname', true ) : '';
											if ( empty( $rep_name ) && $reporter ) {
												$rep_name = $reporter->display_name;
											}
											
											$tgt_name = $reported ? get_user_meta( $rep->reported_id, 'si50_fullname', true ) : '';
											if ( empty( $tgt_name ) && $reported ) {
												$tgt_name = $reported->display_name;
											}
											
											$tgt_phone = $reported ? get_user_meta( $rep->reported_id, 'si50_phone', true ) : '';
											?>
											<tr>
												<td style="font-size: 0.85em; color: #5f6368; vertical-align: middle;">
													<?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $rep->timestamp ) ) ); ?>
												</td>
												<td>
													<strong><?php echo esc_html( $rep_name ? $rep_name : '#' . $rep->reporter_id ); ?></strong><br>
													<span style="font-size: 0.85em; color: #5f6368;"><?php echo esc_html( $reporter ? $reporter->user_email : 'N/A' ); ?></span>
												</td>
												<td>
													<strong><?php echo esc_html( $tgt_name ? $tgt_name : '#' . $rep->reported_id ); ?></strong><br>
													<span style="font-size: 0.85em; color: #5f6368;"><?php echo esc_html( $reported ? $reported->user_email : 'N/A' ); ?></span><br>
													<span style="font-size: 0.85em; color: #5f6368;"><?php echo esc_html( ! empty( $tgt_phone ) ? $tgt_phone : '' ); ?></span>
												</td>
												<td style="vertical-align: middle;">
													<span style="font-weight: 700; color: #c5221f;"><?php echo esc_html( $rep->reason ); ?></span>
												</td>
												<td style="font-size: 0.9em; max-width: 250px; word-wrap: break-word; vertical-align: middle;">
													<?php echo esc_html( $rep->details ); ?>
												</td>
												<td style="vertical-align: middle;">
													<?php if ( 'active' === $rep->status ) : ?>
														<span class="si50-badge si50-badge-pending"><?php esc_html_e( 'Active', 'secondinnings50' ); ?></span>
													<?php else : ?>
														<span class="si50-badge si50-badge-approved"><?php esc_html_e( 'Resolved', 'secondinnings50' ); ?></span>
													<?php endif; ?>
												</td>
												<td style="vertical-align: middle;">
													<?php if ( 'active' === $rep->status ) : 
														$nonce = wp_create_nonce( 'si50_admin_vetting_action' );
														$resolve_url = admin_url( 'admin.php?page=si50-onboarding-dashboard&action=resolve_report&report_id=' . $rep->id . '&_wpnonce=' . $nonce );
														$suspend_url = admin_url( 'admin.php?page=si50-onboarding-dashboard&action=suspend_member&user_id=' . $rep->reported_id . '&report_id=' . $rep->id . '&_wpnonce=' . $nonce );
														?>
														<div style="display: flex; flex-direction: column; gap: 6px;">
															<a href="<?php echo esc_url( $resolve_url ); ?>" class="si50-action-btn si50-btn-approve-primary" style="background-color: #1a73e8; border-color: #333333; text-align: center; justify-content: center;"><?php esc_html_e( 'Dismiss (Resolve)', 'secondinnings50' ); ?></a>
															<?php
															// Check if already suspended
															$tgt_status = get_user_meta( $rep->reported_id, 'si50_vetting_status', true );
															if ( 'suspended' !== $tgt_status ) :
																?>
																<a href="<?php echo esc_url( $suspend_url ); ?>" class="si50-action-btn si50-btn-reject-link si50-admin-suspend-btn" data-username="<?php echo esc_attr( $tgt_name ); ?>" style="border-color: #333333; background-color: #c5221f !important; color: #ffffff !important; text-align: center; justify-content: center;"><?php esc_html_e( 'Suspend Member', 'secondinnings50' ); ?></a>
															<?php endif; ?>
														</div>
													<?php else : ?>
														<span style="color: #5f6368; font-style: italic; font-size: 0.9em;"><?php esc_html_e( 'No actions pending', 'secondinnings50' ); ?></span>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>

		</div>
	</div>
	<?php
}

/**
 * Self-contained programmatical PDF Document compiler (Task 2).
 * Renders A4 layouts with clean margins and text wrapping dynamically.
 */
class SI50_PDF_Generator {
	private $pages = array();
	private $current_page = 0;
	private $fonts = array();
	private $current_font = 'F1';
	private $current_size = 10;

	public function __construct() {
		$this->fonts['F1'] = 'Helvetica';
		$this->fonts['F2'] = 'Helvetica-Bold';
		$this->add_page();
	}

	public function add_page() {
		$this->pages[] = "0.2 0.2 0.2 r\n0.2 0.2 0.2 R\n0.5 w\n";
		$this->current_page = count( $this->pages ) - 1;
	}

	public function set_stroke_color( $r, $g, $b ) {
		$this->pages[ $this->current_page ] .= sprintf( "%F %F %F R\n", $r, $g, $b );
	}

	public function set_fill_color( $r, $g, $b ) {
		$this->pages[ $this->current_page ] .= sprintf( "%F %F %F r\n", $r, $g, $b );
	}

	public function get_pages() {
		return $this->pages;
	}

	public function set_page( $page_index ) {
		if ( isset( $this->pages[$page_index] ) ) {
			$this->current_page = $page_index;
		}
	}

	public function set_font( $font, $size ) {
		$this->current_font = $font;
		$this->current_size = $size;
	}

	public function write_text( $x, $y, $text ) {
		$text = str_replace( array( '\\', '(', ')' ), array( '\\\\', '\\(', '\\)' ), $text );
		$this->pages[ $this->current_page ] .= sprintf( "BT /%s %F Tf %F %F Td (%s) Tj ET\n", $this->current_font, $this->current_size, $x, $y, $text );
	}

	public function draw_line( $x1, $y1, $x2, $y2 ) {
		$this->pages[ $this->current_page ] .= sprintf( "%F %F m %F %F l S\n", $x1, $y1, $x2, $y2 );
	}

	public function draw_rect( $x, $y, $w, $h, $fill = false ) {
		$op = $fill ? 'f' : 's';
		$this->pages[ $this->current_page ] .= sprintf( "%F %F %F %F re %s\n", $x, $y, $w, $h, $op );
	}

	public function draw_heading( $text, $y ) {
		$this->set_fill_color( 0.11, 0.23, 0.17 ); // Forest Green
		$this->set_font( 'F2', 10 );
		$this->write_text( 50, $y, $text );
		$this->set_stroke_color( 0.75, 0.36, 0.24 ); // Terracotta
		$this->draw_line( 50, $y - 4, 545, $y - 4 );
		$this->set_fill_color( 0.2, 0.2, 0.2 ); // Reset to charcoal
		$this->set_stroke_color( 0.2, 0.2, 0.2 );
	}

	public function write_wrapped_text( $x, &$y, $text, $max_width = 300, $line_height = 14 ) {
		$paragraphs = explode( "\n", str_replace( "\r", "", $text ) );
		foreach ( $paragraphs as $paragraph ) {
			if ( empty( $paragraph ) ) {
				$y -= $line_height;
				continue;
			}
			$words = explode( ' ', $paragraph );
			$line = '';
			foreach ( $words as $word ) {
				$test_line = empty( $line ) ? $word : $line . ' ' . $word;
				// Estimate char width: ~5.2 points per char for Helvetica size 9-10
				if ( strlen( $test_line ) * 5.2 > $max_width ) {
					if ( $y < 80 ) {
						$this->add_page();
						$y = 780;
					}
					$this->write_text( $x, $y, $line );
					$y -= $line_height;
					$line = $word;
				} else {
					$line = $test_line;
				}
			}
			if ( ! empty( $line ) ) {
				if ( $y < 80 ) {
					$this->add_page();
					$y = 780;
				}
				$this->write_text( $x, $y, $line );
				$y -= $line_height;
			}
		}
	}

	public function output() {
		$pdf = "%PDF-1.4\n";
		$objects = array();
		
		$page_count = count( $this->pages );
		
		$objects[1] = "<< /Type /Catalog /Pages 2 0 R >>";
		
		$kids_str = '';
		for ( $p = 0; $p < $page_count; $p++ ) {
			$page_obj_id = 5 + ( $p * 2 );
			$kids_str .= $page_obj_id . ' 0 R ';
		}
		$objects[2] = sprintf( "<< /Type /Pages /Kids [%s] /Count %d >>", trim( $kids_str ), $page_count );
		
		$objects[3] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
		$objects[4] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>";
		
		$res = "<< /Font << /F1 3 0 R /F2 4 0 R >> >>";
		
		for ( $p = 0; $p < $page_count; $p++ ) {
			$page_obj_id = 5 + ( $p * 2 );
			$stream_obj_id = $page_obj_id + 1;
			
			$objects[ $page_obj_id ] = sprintf( "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595.28 841.89] /Contents %d 0 R /Resources %s >>", $stream_obj_id, $res );
			
			$stream = $this->pages[ $p ];
			$objects[ $stream_obj_id ] = sprintf( "<< /Length %d >>\nstream\n%s\nendstream", strlen( $stream ), $stream );
		}
		
		$total_objects = 4 + ( $page_count * 2 );
		
		$offsets = array();
		for ( $i = 1; $i <= $total_objects; $i++ ) {
			$offsets[$i] = strlen( $pdf );
			$pdf .= sprintf( "%d 0 obj\n%s\nendobj\n", $i, $objects[$i] );
		}
		
		$xref_pos = strlen( $pdf );
		$pdf .= "xref\n";
		$pdf .= sprintf( "0 %d\n", $total_objects + 1 );
		$pdf .= "0000000000 65535 f \n";
		for ( $i = 1; $i <= $total_objects; $i++ ) {
			$pdf .= sprintf( "%010d 00000 n \n", $offsets[$i] );
		}
		
		$pdf .= "trailer\n";
		$pdf .= sprintf( "<< /Size %d /Root 1 0 R >>\n", $total_objects + 1 );
		$pdf .= "startxref\n";
		$pdf .= $xref_pos . "\n";
		$pdf .= "%%EOF\n";
		
		return $pdf;
	}
}
