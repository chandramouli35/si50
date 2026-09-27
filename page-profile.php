<?php
/**
 * Template Name: Edit Profile Dashboard
 *
 * @package SecondInnings50
 */

get_header();

// Protect: User must be logged in to view their profile dashboard
if ( ! is_user_logged_in() ) {
	?>
	<main id="main-content" class="site-main content-area" style="background-color: var(--bg-warm); min-height: 80vh; padding: var(--spacing-xl) 0;">
	  <div class="container">
		<div class="card text-center" style="max-width: 600px; margin: 40px auto; padding: var(--spacing-xl); border: 2px solid #333333 !important; background-color: #FFFFFF !important; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: var(--radius-lg);">
			<span style="font-size: 3rem; display: block; margin-bottom: var(--spacing-md);">🔒</span>
			<h2 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-forest); margin-bottom: 12px;">
				<?php esc_html_e( 'Private Profile Dashboard', 'secondinnings50' ); ?>
			</h2>
			<p style="color: var(--color-charcoal-muted); margin-bottom: 24px; line-height: 1.6;">
				<?php esc_html_e( 'Please log in to manage your account details, interest circles, companionship preferences, and emergency contacts.', 'secondinnings50' ); ?>
			</p>
			<div>
				<a href="#login" class="btn btn-primary si50-trigger-login" style="font-weight: 700; padding: 12px 24px;"><?php esc_html_e( 'Log In Securely', 'secondinnings50' ); ?></a>
			</div>
		</div>
	  </div>
	</main>
	<?php
	get_footer();
	exit;
}

$user_id = get_current_user_id();
$m_name = get_user_meta( $user_id, 'si50_fullname', true );
$m_dob = get_user_meta( $user_id, 'si50_dob', true );
$m_age = get_user_meta( $user_id, 'si50_age_bracket', true );
$m_gender = get_user_meta( $user_id, 'si50_gender', true );
$m_marital = get_user_meta( $user_id, 'si50_marital_status', true );
$m_occupation = get_user_meta( $user_id, 'si50_occupation', true );
$m_phone = get_user_meta( $user_id, 'si50_phone', true );
$m_emergency = get_user_meta( $user_id, 'si50_emergency_phone', true );
$m_city = get_user_meta( $user_id, 'si50_city_state', true );
$m_intro = get_user_meta( $user_id, 'si50_introduction', true );
$m_looking = (array) get_user_meta( $user_id, 'si50_looking_for', true );
$m_connection_intent = get_user_meta( $user_id, 'si50_connection_intent', true );
$m_voice_intro = get_user_meta( $user_id, 'si50_voice_intro', true );
$m_circles = (array) get_user_meta( $user_id, 'si50_circles_interest', true );
$m_dest = (array) get_user_meta( $user_id, 'si50_travel_destinations', true );
$m_styles = (array) get_user_meta( $user_id, 'si50_travel_styles', true );
$m_companion = get_user_meta( $user_id, 'si50_companion_styles', true );
$vetting_status = get_user_meta( $user_id, 'si50_vetting_status', true );
$rejection_note = get_user_meta( $user_id, 'si50_rejection_note', true );
$m_loc_pref = get_user_meta( $user_id, 'si50_location_preference', true );
$m_hobbies = (array) get_user_meta( $user_id, 'si50_hobbies_interests', true );
$m_travel_dest_other = get_user_meta( $user_id, 'si50_travel_destinations_other', true );
$m_special_incentives = (array) get_user_meta( $user_id, 'si50_special_incentives', true );
$compatibility_paused = get_user_meta( $user_id, 'si50_compatibility_paused', true );
$userdata = get_userdata( $user_id );
if ( empty( $m_name ) ) {
	$m_name = $userdata->display_name;
}

// Determine Active Tab
$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'dashboard';
if ( ! in_array( $active_tab, array( 'dashboard', 'info', 'preferences', 'events', 'activity-log' ) ) ) {
	$active_tab = 'dashboard';
}

$connections_count = 0; // Keeping variable just in case used elsewhere, but peer connections are removed
?>

  <!-- EMBEDDED STYLES FOR PREMIUM PROFILE DASHBOARD -->
  <style>
    .profile-section {
      padding: var(--spacing-lg) 0 var(--spacing-xl) 0;
      background-color: var(--bg-warm);
    }
    .profile-container {
      max-width: 1000px;
      margin: 0 auto;
    }
    .profile-header {
      margin-bottom: var(--spacing-md);
    }
    .profile-card {
      background-color: var(--bg-white);
      border: 2px solid #333333 !important;
      border-radius: var(--radius-lg);
      padding: var(--spacing-md);
    }
    fieldset.form-section-group {
      border: 1.5px solid #333333 !important;
      border-radius: var(--radius-md);
      padding: var(--spacing-md) var(--spacing-sm);
      margin-bottom: var(--spacing-md);
      background-color: var(--bg-white);
    }
    fieldset.form-section-group legend {
      font-family: var(--font-serif);
      font-size: var(--fs-sm);
      font-weight: 700;
      color: var(--color-forest);
      padding: 0 10px;
      margin-left: 10px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .form-group-full {
      margin-bottom: var(--spacing-sm);
    }
    .form-group-full:last-child {
      margin-bottom: 0;
    }
    .travel-destinations-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 10px;
      margin-top: 10px;
    }
    .travel-styles-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 12px;
      margin-top: 10px;
    }
    .gender-radio-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 12px;
      margin-top: 6px;
    }
    .circles-checkbox-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 12px;
      margin-top: 6px;
    }
    .looking-for-checkbox-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 12px;
      margin-top: 6px;
    }
    .form-group-section-title {
      font-size: var(--fs-xs);
      font-weight: 700;
      color: var(--color-forest);
      margin-bottom: 8px;
      display: block;
    }

    /* CRITICAL UI/UX ACCESSIBILITY STYLING FIXES */
    .form-control, .select-control, .textarea-control {
      background-color: #FFFFFF !important;
      border: 1.5px solid var(--color-charcoal) !important;
      color: var(--color-charcoal) !important;
      font-weight: 600 !important;
      opacity: 1 !important;
      box-sizing: border-box !important;
    }
    input[type="text"].form-control,
    input[type="email"].form-control,
    input[type="tel"].form-control,
    input[type="password"].form-control,
    input[type="date"].form-control,
    select.form-control,
    .select-control {
      height: 48px !important;
      width: 100% !important;
      max-width: 100% !important;
    }
    input[type="text"].form-control,
    input[type="email"].form-control,
    input[type="tel"].form-control,
    input[type="password"].form-control,
    input[type="date"].form-control {
      padding: 0 12px !important;
      line-height: 44px !important;
    }
    select.form-control,
    .select-control {
      padding: 0 32px 0 12px !important;
      line-height: 44px !important;
      height: 48px !important;
    }
    .form-control::placeholder {
      color: rgba(45, 45, 45, 0.7) !important;
      opacity: 1 !important;
    }
    .form-control:focus {
      border-color: var(--color-terracotta) !important;
      box-shadow: 0 0 0 3px var(--color-terracotta-light) !important;
      background-color: #FFFFFF !important;
    }
    .form-label, .form-group-section-title {
      color: var(--color-forest-dark) !important;
      font-weight: 700 !important;
    }
    .radio-container, .checkbox-container {
      display: flex !important;
      align-items: flex-start !important;
      position: relative !important;
      padding-left: 0 !important;
      gap: 12px !important;
      width: 100% !important;
      min-height: 24px;
      cursor: pointer;
      user-select: none;
    }
    .checkmark, .radiomark {
      position: relative !important;
      display: inline-block !important;
      flex-shrink: 0 !important;
      height: 22px !important;
      width: 22px !important;
      border: 1.5px solid var(--color-charcoal) !important;
      background-color: #FFFFFF !important;
      margin-top: 2px !important;
      top: auto !important;
      left: auto !important;
    }
    .radiomark {
      border-radius: 50% !important;
    }
    .checkbox-container:hover input ~ .checkmark,
    .radio-container:hover input ~ .radiomark {
      border-color: var(--color-terracotta) !important;
    }
    .checkbox-container input:checked ~ .checkmark,
    .radio-container input:checked ~ .radiomark {
      background-color: var(--color-terracotta) !important;
      border-color: var(--color-terracotta) !important;
    }
    .checkmark::after, .radiomark::after {
      content: "";
      position: absolute;
      display: none;
    }
    .checkbox-container input:checked ~ .checkmark::after,
    .radio-container input:checked ~ .radiomark::after {
      display: block !important;
    }
    .checkbox-container .checkmark::after {
      left: 7px;
      top: 3px;
      width: 6px;
      height: 11px;
      border: solid #FFFFFF;
      border-width: 0 2.5px 2.5px 0;
      transform: rotate(45deg);
    }
    .radio-container .radiomark::after {
      left: 6.5px;
      top: 6.5px;
      width: 9px;
      height: 9px;
      background-color: #FFFFFF !important;
      border-radius: 50% !important;
    }
    .checkbox-label-text, .radio-label-text {
      flex: 1 !important;
      word-break: break-word !important;
      color: var(--color-charcoal) !important;
      font-weight: 600 !important;
      padding-left: 0 !important;
      line-height: 1.5 !important;
    }
    
    @media (max-width: 768px) {
      .profile-container {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0 !important;
        margin: 0 !important;
      }
      .profile-dashboard-layout {
        grid-template-columns: 1fr !important;
        width: 100% !important;
        max-width: 100% !important;
        min-width: 0 !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
        gap: 20px !important;
      }
      .profile-sidebar {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        margin-bottom: 15px !important;
        overflow: hidden !important;
      }
      .profile-sidebar .card {
        position: static !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        padding: 10px !important;
      }
      .profile-sidebar ul {
        flex-direction: row !important;
        overflow-x: auto !important;
        white-space: nowrap !important;
        padding-bottom: 8px;
        -webkit-overflow-scrolling: touch;
        gap: 8px !important;
        scrollbar-width: none !important;
        width: 100% !important;
        max-width: 100% !important;
        display: flex !important;
      }
      .profile-sidebar ul::-webkit-scrollbar {
        display: none !important;
      }
      .profile-sidebar ul li {
        flex-shrink: 0 !important;
        display: inline-block !important;
      }
      .profile-tab-link {
        padding: 10px 12px !important;
        font-size: 12px !important;
      }
      .profile-content {
        min-width: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
      }
      .profile-card {
        padding: var(--spacing-sm) !important;
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
      }
      .profile-submit-row {
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 12px !important;
      }
      .profile-submit-row button,
      .profile-submit-row a {
        width: 100% !important;
        flex: none !important;
      }
      .si50-whatsapp-box {
        padding: 15px !important;
      }
      .si50-whatsapp-box div[style*="min-width: 280px"] {
        min-width: 100% !important;
      }
      .si50-whatsapp-box a.btn {
        width: 100% !important;
      }
    }

    /* Premium Matching Suggestions Carousel Styles */
    .si50-carousel-section {
      margin-top: 30px;
      width: 100% !important;
      max-width: 100% !important;
      overflow: hidden !important;
      box-sizing: border-box !important;
    }
    .si50-carousel-container {
      display: flex !important;
      gap: 20px !important;
      overflow-x: auto !important;
      scroll-snap-type: x mandatory !important;
      padding: 10px 4px 20px 4px !important;
      -webkit-overflow-scrolling: touch !important;
      scrollbar-width: thin !important;
      scrollbar-color: var(--color-forest) #f1f3f4 !important;
      box-sizing: border-box !important;
      width: 100% !important;
    }
    .si50-carousel-container::-webkit-scrollbar {
      height: 6px !important;
    }
    .si50-carousel-container::-webkit-scrollbar-track {
      background: #f1f3f4 !important;
      border-radius: 10px !important;
    }
    .si50-carousel-container::-webkit-scrollbar-thumb {
      background: var(--color-forest) !important;
      border-radius: 10px !important;
    }
    .si50-carousel-item {
      flex: 0 0 320px !important;
      scroll-snap-align: start !important;
      box-sizing: border-box !important;
      display: flex !important;
      flex-direction: column !important;
    }
    @media (max-width: 680px) {
      .si50-carousel-item {
        flex: 0 0 100% !important;
      }
    }
  </style>

<main id="primary" class="site-main content-area">
  <section class="profile-section" aria-label="<?php esc_attr_e( 'Manage your profile settings', 'secondinnings50' ); ?>">
    <div class="container">
      <div class="profile-container">
        
        <!-- Header Text -->
        <div class="profile-header text-center">
          <span class="section-tag"><?php esc_html_e( 'Member Dashboard', 'secondinnings50' ); ?></span>
          <h1 class="section-title"><?php esc_html_e( 'Account Settings & Companionship Preferences', 'secondinnings50' ); ?></h1>
          <p class="section-subtitle">
            <?php esc_html_e( 'Manage your personal identity, contact coordinates, companion matches, and interest circle choices.', 'secondinnings50' ); ?>
          </p>
        </div>

        <?php if ( '1' === $compatibility_paused || 'yes' === $compatibility_paused || true === $compatibility_paused ) : ?>
			<div style="background-color: #fdf3f2; border: 2.5px dashed var(--color-terracotta); padding: 20px; border-radius: var(--radius-md); margin-bottom: 24px; color: var(--color-terracotta-dark); text-align: center;">
				<h3 style="margin: 0 0 10px 0; font-weight: 800; font-size: var(--fs-lg); text-transform: uppercase;">
					⏸️ <?php esc_html_e( 'Profile Paused (Compatibility Period)', 'secondinnings50' ); ?>
				</h3>
				<p style="margin: 0; font-size: var(--fs-md); line-height: 1.5; color: var(--color-charcoal);">
					<?php esc_html_e( 'Your profile is currently paused from the directory while you explore companionship with your match. Take this time to communicate and understand each other better.', 'secondinnings50' ); ?>
				</p>
			</div>
        <?php endif; ?>

        <!-- Verification Vetting Status Banner Notification -->
        <?php if ( 'approved' === $vetting_status ) : ?>
			<div style="background-color: #e7f4e8; border: 1.5px solid #1e7e34; padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 24px; color: #1e7e34;">
				<p style="margin: 0; font-weight: 700; font-size: var(--fs-md);">
					✓ <?php esc_html_e( 'Profile Verified: Your account is active in the directory and you can send and receive connect requests.', 'secondinnings50' ); ?>
				</p>
			</div>
        <?php elseif ( 'rejected' === $vetting_status ) : ?>
			<div style="background-color: #fdf3f2; border: 2px solid var(--color-terracotta); padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 24px; color: var(--color-terracotta-dark);">
				<p style="margin: 0 0 8px 0; font-weight: 700; font-size: var(--fs-md); text-transform: uppercase;">
					⚠️ <?php esc_html_e( 'Application Declined', 'secondinnings50' ); ?>
				</p>
				<p style="margin: 0 0 8px 0; font-size: var(--fs-sm); color: var(--color-charcoal); font-style: italic; background: #ffffff; padding: 10px; border-radius: 4px; border-left: 3.5px solid var(--color-terracotta);">
					"<?php echo esc_html( $rejection_note ); ?>"
				</p>
				<p style="margin: 0; font-size: var(--fs-xs); line-height: 1.4; color: var(--color-charcoal);">
					<?php esc_html_e( 'Please correct any details requested in the reviewer\'s note below. Updating and saving your profile info will resubmit your application for vetting verification.', 'secondinnings50' ); ?>
				</p>
			</div>
        <?php elseif ( 'suspended' === $vetting_status ) : ?>
			<div style="background-color: #fdf3f2; border: 2.5px solid var(--color-terracotta) !important; padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 24px; color: var(--color-terracotta-dark);">
				<p style="margin: 0; font-weight: 700; font-size: var(--fs-md);">
					🔒 <?php esc_html_e( 'Account Suspended: Your profile is suspended due to safety or misuse complaints. You can no longer browse the directory or interact with members.', 'secondinnings50' ); ?>
				</p>
			</div>
		<?php else : ?>
			<div style="background-color: var(--color-gold-light); border: 1.5px solid var(--color-gold); padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 24px; color: var(--color-forest-dark);">
				<p style="margin: 0; font-weight: 700; font-size: var(--fs-md);">
					⏳ <?php esc_html_e( 'Verification Pending: Your profile is currently under review by our coordinator team.', 'secondinnings50' ); ?>
				</p>
			</div>
        <?php endif; ?>

		<!-- Split Sidebar & Content Grid -->
		<div class="profile-dashboard-layout" style="display: grid; grid-template-columns: 260px 1fr; gap: 30px; margin-top: 24px; align-items: start;">
			
			<!-- Left Navigation Sidebar -->
			<aside class="profile-sidebar">
				<div class="card" style="background: #FFFFFF; border: 2px solid #333333; padding: 15px; border-radius: var(--radius-md); position: sticky; top: 100px;">
					<ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
						<li>
							<a href="<?php echo esc_url( add_query_arg( 'tab', 'dashboard' ) ); ?>" class="profile-tab-link" style="display: block; padding: 12px 16px; border: 1.5px solid #333333; border-radius: var(--radius-sm); font-weight: 700; text-decoration: none; font-size: var(--fs-xs); <?php echo ('dashboard' === $active_tab) ? 'background-color: var(--color-forest) !important; color: #FFFFFF !important;' : 'background-color: #FFFFFF !important; color: #333333 !important;'; ?>">
								📈 <?php esc_html_e( 'My Dashboard', 'secondinnings50' ); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( add_query_arg( 'tab', 'info' ) ); ?>" class="profile-tab-link" style="display: block; padding: 12px 16px; border: 1.5px solid #333333; border-radius: var(--radius-sm); font-weight: 700; text-decoration: none; font-size: var(--fs-xs); <?php echo ('info' === $active_tab) ? 'background-color: var(--color-forest) !important; color: #FFFFFF !important;' : 'background-color: #FFFFFF !important; color: #333333 !important;'; ?>">
								👤 <?php esc_html_e( 'My Profile Info', 'secondinnings50' ); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( add_query_arg( 'tab', 'preferences' ) ); ?>" class="profile-tab-link" style="display: block; padding: 12px 16px; border: 1.5px solid #333333; border-radius: var(--radius-sm); font-weight: 700; text-decoration: none; font-size: var(--fs-xs); <?php echo ('preferences' === $active_tab) ? 'background-color: var(--color-forest) !important; color: #FFFFFF !important;' : 'background-color: #FFFFFF !important; color: #333333 !important;'; ?>">
								⚙️ <?php esc_html_e( 'My Preferences', 'secondinnings50' ); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( add_query_arg( 'tab', 'connections' ) ); ?>" class="profile-tab-link" style="display: block; padding: 12px 16px; border: 1.5px solid #333333; border-radius: var(--radius-sm); font-weight: 700; text-decoration: none; font-size: var(--fs-xs); <?php echo ('connections' === $active_tab) ? 'background-color: var(--color-forest) !important; color: #FFFFFF !important;' : 'background-color: #FFFFFF !important; color: #333333 !important;'; ?>">
								👥 <?php esc_html_e( 'My Connections', 'secondinnings50' ); ?>
								<span style="background-color: var(--color-terracotta); color: #FFFFFF; font-size: 0.7rem; font-weight: 700; padding: 1px 6px; border-radius: 10px; margin-left: 4px;"><?php echo intval( $connections_count ); ?></span>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url( add_query_arg( 'tab', 'events' ) ); ?>" class="profile-tab-link" style="display: block; padding: 12px 16px; border: 1.5px solid #333333; border-radius: var(--radius-sm); font-weight: 700; text-decoration: none; font-size: var(--fs-xs); <?php echo ('events' === $active_tab) ? 'background-color: var(--color-forest) !important; color: #FFFFFF !important;' : 'background-color: #FFFFFF !important; color: #333333 !important;'; ?>">
								📅 <?php esc_html_e( 'Community Meetups', 'secondinnings50' ); ?>
							</a>
						</li>

						<li>
							<a href="<?php echo esc_url( add_query_arg( 'tab', 'activity-log' ) ); ?>" class="profile-tab-link" style="display: block; padding: 12px 16px; border: 1.5px solid #333333; border-radius: var(--radius-sm); font-weight: 700; text-decoration: none; font-size: var(--fs-xs); <?php echo ('activity-log' === $active_tab) ? 'background-color: var(--color-forest) !important; color: #FFFFFF !important;' : 'background-color: #FFFFFF !important; color: #333333 !important;'; ?>">
								📜 <?php esc_html_e( 'Activity & Login History', 'secondinnings50' ); ?>
							</a>
						</li>
					</ul>
				</div>
			</aside>

			<!-- Right Content Container -->
			<div class="profile-content" style="display: flex; flex-direction: column; gap: 20px; box-sizing: border-box; width: 100%; max-width: 100%;">
				
				<?php if ( 'dashboard' === $active_tab ) : ?>
					<!-- Circular Progress Profile Completion Indicator -->
					<?php 
					$completion_pct = si50_get_profile_completion_percentage( $user_id );
					?>
					<div class="card si50-progress-card" style="border: 2px solid #333333 !important; background-color: #FFFFFF !important; padding: 16px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-sizing: border-box;">
					<div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
						<!-- Circular SVG progress indicator -->
						<div style="position: relative; width: 60px; height: 60px; flex-shrink: 0;">
							<svg width="60" height="60" viewBox="0 0 36 36" style="transform: rotate(-90deg);">
								<path stroke="#f1f3f4" stroke-width="3.5" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
								<path stroke="var(--color-forest)" stroke-dasharray="<?php echo intval( $completion_pct ); ?>, 100" stroke-width="3.5" stroke-linecap="round" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" style="transition: stroke-dasharray 0.5s ease;" />
							</svg>
							<div style="position: absolute; top: 0; left: 0; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: var(--fs-xs); font-weight: 800; color: var(--color-forest);">
								<?php echo intval( $completion_pct ); ?>%
							</div>
						</div>
						<div>
							<h3 style="margin: 0; font-family: var(--font-serif); font-size: var(--fs-md); color: var(--color-forest); font-weight: 700;"><?php esc_html_e( 'Profile Completion Progress', 'secondinnings50' ); ?></h3>
							<p style="margin: 2px 0 0 0; font-size: var(--fs-xs); color: var(--color-charcoal-muted); line-height: 1.4;">
								<?php 
								if ( $completion_pct < 100 ) {
									esc_html_e( 'Complete all details (Bio, Preferences, Interests, Selfie photo) to unlock 100% status.', 'secondinnings50' );
								} else {
									esc_html_e( 'Excellent! Your profile is complete and optimized for compatibility matching.', 'secondinnings50' );
								}
								?>
							</p>
						</div>
					</div>
					<div style="font-size: var(--fs-xs); font-weight: 700; color: var(--color-terracotta);">
						<?php echo esc_html( 100 - $completion_pct ); ?>% <?php esc_html_e( 'Remaining', 'secondinnings50' ); ?>
					</div>
				</div>

				<!-- Dynamic WhatsApp Invite Dashboard Card -->
				<?php if ( 'approved' === $vetting_status ) : 
					$wa_group = si50_get_whatsapp_group_link( $user_id );
					$first_name = ! empty( $m_name ) ? explode( ' ', trim( $m_name ) )[0] : esc_html__( 'Member', 'secondinnings50' );
					?>
					<div class="card si50-whatsapp-box" style="border: 2px solid #1B3B2B !important; background-color: #e8efea !important; padding: 20px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 4px 12px rgba(27, 59, 43, 0.08); box-sizing: border-box; margin-bottom: 24px;">
						<div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap; flex: 1; min-width: 280px;">
							<div style="background-color: #1B3B2B; color: #FFFFFF; width: 44px; height: 44px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: var(--fs-lg); flex-shrink: 0;">
								💬
							</div>
							<div style="flex: 1;">
								<h3 style="margin: 0; font-family: var(--font-serif); font-size: var(--fs-md); color: #1B3B2B; font-weight: 700;"><?php echo esc_html( $wa_group['name'] ); ?></h3>
								<p style="margin: 6px 0 0 0; font-size: var(--fs-sm); color: #2D4C3E; line-height: 1.5;">
									<?php printf( esc_html__( 'Connect with your local circle! %1$s, join your fellow members in the %2$s SecondInnings50 WhatsApp Group to plan tea meetups and local activities.', 'secondinnings50' ), esc_html( $first_name ), esc_html( $wa_group['name'] ) ); ?>
								</p>
							</div>
						</div>
						<div>
							<a href="<?php echo esc_url( $wa_group['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="btn" style="background-color: #1B3B2B !important; border: none !important; color: #FFFFFF !important; font-weight: 700; height: 48px; padding: 0 24px; font-size: var(--fs-sm); text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; border-radius: var(--radius-sm); box-sizing: border-box;">
								<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" style="display:inline-block; vertical-align:middle;">
									<path d="M12.012 2c-5.506 0-9.988 4.482-9.988 9.988 0 1.758.459 3.409 1.258 4.853L2 22l5.319-1.393c1.4.764 3.003 1.199 4.693 1.199 5.506 0 9.988-4.482 9.988-9.988S17.518 2 12.012 2zm4.7 13.99c-.23.65-1.32 1.26-1.81 1.3-1.33.12-3.15-.36-5.07-2.12C7.91 13.4 6.81 11.4 6.81 9.8c0-.98.5-1.54 1-1.98.15-.13.33-.2.5-.2.15 0 .3 0 .4.1.4.9.9 1.9 1 2 .1.2.1.4 0 .6-.1.2-.2.3-.4.5l-.5.6c-.2.2-.3.4-.1.7.3.6.8 1.2 1.4 1.7.8.7 1.6 1.1 2.1 1.2.3.1.5 0 .7-.2.3-.3.6-.7.9-.9.2-.2.4-.2.7-.1.3.1 1.9.9 2 1 .1.1.2.2.2.3 0 .2-.1.9-.3 1.3z" />
								</svg>
								<span><?php esc_html_e( 'Join Local WhatsApp Group', 'secondinnings50' ); ?></span>
							</a>
						</div>
					</div>
				<?php endif; ?>

				<!-- Dynamic Custom WhatsApp Interest Circles Grid -->
				<?php 
				$custom_groups = get_option( 'si50_custom_whatsapp_groups', array() );
				if ( 'approved' === $vetting_status && ! empty( $custom_groups ) ) : 
					?>
					<div class="card si50-custom-whatsapp-container" style="border: 2px solid #333333 !important; background-color: #FFFFFF !important; padding: 20px; border-radius: var(--radius-md); box-sizing: border-box; margin-bottom: 24px; width: 100% !important; max-width: 100% !important; overflow: hidden !important;">
						<h3 style="margin: 0 0 16px 0; font-family: var(--font-serif); font-size: var(--fs-md); color: var(--color-forest); font-weight: 700; display: flex; align-items: center; gap: 8px;">
							<span>💬</span>
							<span><?php esc_html_e( 'Browse Interest Circles & Additional Groups', 'secondinnings50' ); ?></span>
						</h3>
						<div class="si50-custom-groups-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; width: 100%; box-sizing: border-box;">
							<?php foreach ( $custom_groups as $group ) : ?>
								<div class="si50-custom-group-card" style="border: 1.5px solid #333333; border-radius: var(--radius-sm); padding: 16px; background-color: var(--bg-warm); display: flex; flex-direction: column; justify-content: space-between; min-height: 140px; box-sizing: border-box;">
									<div>
										<h4 style="margin: 0 0 6px 0; font-family: var(--font-serif); font-size: var(--fs-sm); color: var(--color-forest); font-weight: 700; line-height: 1.3;">
											<?php echo esc_html( $group['name'] ); ?>
										</h4>
										<?php if ( ! empty( $group['desc'] ) ) : ?>
											<p style="margin: 0 0 12px 0; font-size: 13px; color: var(--color-charcoal-muted); line-height: 1.4;">
												<?php echo esc_html( $group['desc'] ); ?>
											</p>
										<?php endif; ?>
									</div>
									<div style="margin-top: auto; padding-top: 10px; width: 100%;">
										<a href="<?php echo esc_url( $group['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="btn text-center" style="background-color: #1B3B2B !important; color: #ffffff !important; font-weight: 700; width: 100%; border: none !important; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; padding: 10px; border-radius: var(--radius-sm); font-size: var(--fs-xs); box-sizing: border-box; height: 38px;">
											<span><?php esc_html_e( 'Join Group', 'secondinnings50' ); ?></span>
										</a>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- Razorpay Payment Upgrade Notice Gating (Point 6 Pipeline) -->
				<!-- Payment section removed as per direct PayU trigger flow -->

				<?php 
				// Suggested Companions Match Carousel (Point 12)
				$blocked_ids = function_exists( 'si50_get_blocked_user_ids' ) ? si50_get_blocked_user_ids( $user_id ) : array();
				$exclude_ids = array_merge( array( $user_id ), $blocked_ids );

				$match_args = array(
					'role__not_in' => array( 'administrator' ),
					'exclude'      => $exclude_ids,
					'meta_query'   => array(
						'relation' => 'AND',
						array(
							'key'     => 'si50_vetting_status',
							'value'   => 'approved',
							'compare' => '='
						),
						array(
							'relation' => 'OR',
							array(
								'key'     => 'si50_profile_visibility',
								'value'   => 'private',
								'compare' => '!='
							),
							array(
								'key'     => 'si50_profile_visibility',
								'compare' => 'NOT EXISTS'
							)
						)
					)
				);
				$potential_matches = get_users( $match_args );

				$compatible_suggestions = array();
				foreach ( $potential_matches as $pm ) {
					$score = si50_calculate_compatibility( $user_id, $pm->ID );
					if ( $score > 60 ) {
						$compatible_suggestions[] = array(
							'user'  => $pm,
							'score' => $score
						);
					}
				}

				// Sort suggestions by score descending
				usort( $compatible_suggestions, function( $a, $b ) {
					return $b['score'] - $a['score'];
				} );
				?>
				<div class="si50-carousel-section">
					<h3 style="margin: 0 0 8px 0; font-family: var(--font-serif); font-size: var(--fs-md); color: var(--color-forest); font-weight: 700; display: flex; align-items: center; gap: 8px;">
						<span>🍀</span>
						<span><?php esc_html_e( 'Compatible Member Matches (> 60% Score)', 'secondinnings50' ); ?></span>
					</h3>
					<p style="margin: 0 0 16px 0; font-size: var(--fs-xs); color: var(--color-charcoal-muted); line-height: 1.4;">
						<?php esc_html_e( 'Connect with mature members sharing similar companion focus, interests, and lifestyle preferences.', 'secondinnings50' ); ?>
					</p>

					<?php if ( empty( $compatible_suggestions ) ) : ?>
						<div class="card text-center" style="padding: 30px 15px; border: 1.5px dashed var(--color-border) !important; background-color: #FFFFFF !important; border-radius: var(--radius-md);">
							<span style="font-size: 2rem; display: block; margin-bottom: 8px;">🌾</span>
							<p style="color: var(--color-charcoal-muted); font-size: var(--fs-xs); margin: 0;">
								<?php esc_html_e( 'No matches above 60% found yet. Complete your preferences and interest circles to find compatible peers.', 'secondinnings50' ); ?>
							</p>
						</div>
					<?php else : ?>
						<div class="si50-carousel-container">
							<?php foreach ( $compatible_suggestions as $suggestion ) : 
								$member_user = $suggestion['user'];
								$member_id   = $member_user->ID;
								$score       = $suggestion['score'];
								$m_name      = esc_html( $member_user->display_name );
								$m_gender    = get_user_meta( $member_id, 'si50_gender', true );
								$m_age       = get_user_meta( $member_id, 'si50_age_bracket', true );
								$m_city      = get_user_meta( $member_id, 'si50_city_state', true );
								$m_occupation= get_user_meta( $member_id, 'si50_occupation', true );
								$m_intro     = get_user_meta( $member_id, 'si50_introduction', true );
								$m_looking_for = (array) get_user_meta( $member_id, 'si50_looking_for', true );
								$m_connection_intent = get_user_meta( $member_id, 'si50_connection_intent', true );
								$m_voice_intro = get_user_meta( $member_id, 'si50_voice_intro', true );
								$m_phone     = get_user_meta( $member_id, 'si50_phone', true );

								// Check connections handshake
								$connection_req = si50_get_connection_request( $user_id, $member_id );
								$connection_status = $connection_req ? $connection_req->status : false;
								$is_connected   = ( 'approved' === $connection_status );

								// Evaluate visibility controls
								$m_visibility = get_user_meta( $member_id, 'si50_profile_visibility', true );
								if ( empty( $m_visibility ) ) {
									$m_visibility = 'public';
								}
								$hide_details = ( 'connections' === $m_visibility && ! $is_connected );
								?>
								<div class="card si50-member-card si50-carousel-item" style="background-color: #FFFFFF !important; border: 1.5px solid var(--color-border) !important; border-radius: var(--radius-lg); padding: var(--spacing-sm); display: flex; flex-direction: column; justify-content: space-between; transition: border-color 0.2s ease; position: relative;">
									
									<div>
										<!-- Match score and tags -->
										<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 8px; flex-wrap: wrap;">
											<span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--color-terracotta); background-color: var(--color-gold-light); padding: 2px 6px; border-radius: var(--radius-sm); border: 1px solid var(--color-gold); white-space: nowrap;">
												🎂 <?php echo esc_html( $m_age ) . ' / ' . esc_html( $m_gender ); ?>
											</span>
											<span style="font-size: 11px; font-weight: 700; color: #1e7e34; background-color: #e7f4e8; padding: 2px 6px; border-radius: var(--radius-sm); border: 1px solid #1e7e34; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;">
												🍀 <?php echo esc_html( $score ); ?>% Match
											</span>
										</div>

										<h4 style="font-family: var(--font-serif); font-size: var(--fs-sm); font-weight: 800; color: var(--color-forest); margin: 0 0 2px 0; display: flex; align-items: center; gap: 6px;">
											<?php echo esc_html( $m_name ); ?>
											<?php 
											$is_badge_verified = get_user_meta( $member_id, 'si50_verified_badge', true );
											if ( $is_badge_verified ) :
												?>
												<span class="si50-emerald-badge" title="<?php esc_attr_e( 'SecondInnings50 Verified Member Identity Badge', 'secondinnings50' ); ?>" style="display: inline-flex; align-items: center; color: #10b981; vertical-align: middle;">
													<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" style="display: inline-block; vertical-align: middle; color: #10B981; flex-shrink: 0;" aria-hidden="true">
														<path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
													</svg>
												</span>
											<?php endif; ?>
										</h4>
										<p style="font-size: 11px; color: var(--color-charcoal-muted); font-weight: 600; font-style: italic; margin-bottom: 8px;">
											💼 <?php echo esc_html( $m_occupation ); ?>
										</p>
										<p style="font-size: 11px; color: var(--color-charcoal-muted); font-weight: 600; margin-bottom: 8px;">
											📍 <?php echo esc_html( $m_city ); ?>
										</p>

										<?php if ( $hide_details ) : ?>
											<div style="background-color: #fdfbf7; border: 1.5px dashed var(--color-gold); border-radius: var(--radius-sm); padding: 12px; margin: 10px 0; text-align: center; box-sizing: border-box;">
												<span style="font-size: 1.25rem; display: block; margin-bottom: 4px;">🔒</span>
												<strong style="color: var(--color-forest); font-size: 11px; display: block; margin-bottom: 4px; font-weight: 700;"><?php esc_html_e( 'Profile Locked', 'secondinnings50' ); ?></strong>
												<p style="font-size: 11px; line-height: 1.3; color: var(--color-charcoal-muted); margin: 0;">
													<?php printf( esc_html__( '%s has restricted details to approved connections only.', 'secondinnings50' ), $m_name ); ?>
												</p>
											</div>
										<?php else : ?>
											<?php if ( ! empty( $m_voice_intro ) ) : ?>
												<div style="margin-bottom: 12px; padding: 10px; background-color: var(--bg-warm); border-radius: var(--radius-sm); border: 1px solid var(--color-gold);">
													<span style="font-size: var(--fs-xs); font-weight: 700; color: var(--color-forest); display: block; margin-bottom: 6px;">🎙️ <?php esc_html_e( 'Voice Introduction', 'secondinnings50' ); ?></span>
													<audio controls style="width: 100%; height: 32px;">
														<source src="<?php echo esc_url( $m_voice_intro ); ?>" type="audio/mpeg">
														<?php esc_html_e( 'Your browser does not support the audio element.', 'secondinnings50' ); ?>
													</audio>
												</div>
											<?php endif; ?>
											<?php if ( ! empty( $m_intro ) ) : ?>
												<p style="font-size: var(--fs-xs); line-height: 1.4; color: var(--color-charcoal); margin-bottom: 12px; background-color: var(--bg-warm); padding: 8px; border-radius: var(--radius-sm); border-left: 2.5px solid var(--color-forest);">
													<?php echo nl2br( esc_html( wp_trim_words( $m_intro, 15, '...' ) ) ); ?>
												</p>
											<?php endif; ?>
											<?php if ( ! empty( $m_connection_intent ) ) : ?>
												<p style="font-size: var(--fs-xs); line-height: 1.4; color: var(--color-charcoal-muted); margin-bottom: 12px;">
													<strong><?php esc_html_e( 'Looking for:', 'secondinnings50' ); ?></strong> <?php echo esc_html( wp_trim_words( $m_connection_intent, 15, '...' ) ); ?>
												</p>
											<?php endif; ?>
										<?php endif; ?>
									</div>

									<div style="border-top: 1px solid var(--color-border); padding-top: 8px; margin-top: 8px; box-sizing: border-box;">
										<?php if ( $is_connected ) : 
											$clean_phone = preg_replace( '/[^0-9]/', '', $m_phone );
											if ( 10 === strlen( $clean_phone ) ) {
												$clean_phone = '91' . $clean_phone;
											}
											$wa_link = 'https://wa.me/' . $clean_phone;
											?>
											<a href="<?php echo esc_url( $wa_link ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-block" style="width: 100%; font-weight: 700; background-color: #25D366 !important; color: #FFFFFF !important; padding: 8px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; border-radius: var(--radius-sm); border: none; text-decoration: none; font-size: var(--fs-xs); box-sizing: border-box; height: 38px;">
												<svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" style="margin-top: 1px;"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.457L0 24zm6.59-4.846c1.666.988 3.31 1.493 5.405 1.494 5.276 0 9.57-4.287 9.573-9.564.001-2.556-1.002-4.959-2.816-6.78C16.924 2.5 14.55 1.5 12.008 1.5c-5.282 0-9.58 4.29-9.583 9.566-.001 2.01.522 3.823 1.517 5.485L2.94 20.897l6.707-1.743zM16.595 13.7c-.253-.127-1.5-.74-1.73-.824-.231-.084-.399-.127-.567.127-.168.252-.65.824-.796.993-.147.168-.294.19-.547.063-.253-.127-1.07-.394-2.04-1.259-.755-.674-1.266-1.506-1.414-1.759-.148-.252-.016-.39.11-.516.114-.112.253-.295.38-.442.127-.147.169-.253.253-.422.084-.168.042-.316-.021-.442-.063-.127-.567-1.36-.777-1.865-.205-.496-.41-.427-.567-.427-.147-.003-.315-.003-.483-.003-.168 0-.441.063-.672.316-.231.253-.882.863-.882 2.106 0 1.242.903 2.443 1.029 2.612.126.168 1.776 2.712 4.302 3.802.6.26 1.07.414 1.434.529.603.192 1.152.165 1.587.1.485-.072 1.5-.612 1.712-1.206.21-.595.21-1.106.147-1.206-.063-.1-.231-.143-.483-.27z"/></svg>
												<span><?php esc_html_e( 'Chat on WhatsApp', 'secondinnings50' ); ?></span>
											</a>
										<?php elseif ( 'pending' === $connection_status ) : ?>
											<button class="btn btn-block" disabled style="width: 100%; cursor: not-allowed; font-weight: 700; border: 1.5px solid var(--color-gold) !important; background-color: var(--color-gold-light) !important; color: var(--color-forest-dark) !important; padding: 8px; font-size: var(--fs-xs); height: 38px;">
												⏱ <?php esc_html_e( 'Connection Pending', 'secondinnings50' ); ?>
											</button>
										<?php else : ?>
											<button class="btn btn-primary btn-block si50-btn-connect" data-receiver-id="<?php echo esc_attr( $member_id ); ?>" style="width: 100%; padding: 8px; font-weight: 700; font-size: var(--fs-xs); height: 38px;">
												🤝 <?php esc_html_e( 'Send Connect Request', 'secondinnings50' ); ?>
											</button>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php if ( 'info' === $active_tab ) : ?>
					<!-- TAB 1: MY PROFILE INFO -->
					<div class="card profile-card">
						<form class="join-form" id="si50-profile-info-form" method="post" enctype="multipart/form-data" aria-label="<?php esc_attr_e( 'Edit Profile Info', 'secondinnings50' ); ?>">
							<?php wp_nonce_field( 'si50_auth_nonce', 'security' ); ?>
							
							<fieldset class="form-section-group">
								<legend><?php esc_html_e( 'Account details', 'secondinnings50' ); ?></legend>
								
								<div class="form-row-2col">
									<div class="form-group">
										<span class="form-group-section-title"><?php esc_html_e( 'Gender Identity', 'secondinnings50' ); ?> <span class="required">*</span></span>
										<div class="gender-radio-grid">
											<label class="radio-container">
												<input type="radio" name="gender" value="Male" required aria-required="true" <?php checked( $m_gender, 'Male' ); ?>>
												<span class="radiomark"></span>
												<span class="radio-label-text"><?php esc_html_e( 'Male', 'secondinnings50' ); ?></span>
											</label>
											<label class="radio-container">
												<input type="radio" name="gender" value="Female" <?php checked( $m_gender, 'Female' ); ?>>
												<span class="radiomark"></span>
												<span class="radio-label-text"><?php esc_html_e( 'Female', 'secondinnings50' ); ?></span>
											</label>
											<label class="radio-container">
												<input type="radio" name="gender" value="Prefer Not to Say" <?php checked( $m_gender, 'Prefer Not to Say' ); ?>>
												<span class="radiomark"></span>
												<span class="radio-label-text"><?php esc_html_e( 'Prefer Not to Say', 'secondinnings50' ); ?></span>
											</label>
										</div>
									</div>

									<div class="form-group">
										<label for="profile-name" class="form-label"><?php esc_html_e( 'Full Name', 'secondinnings50' ); ?> <span class="required">*</span></label>
										<input type="text" id="profile-name" name="fullname" class="form-control" placeholder="<?php esc_attr_e( 'Your Full Name', 'secondinnings50' ); ?>" required aria-required="true" value="<?php echo esc_attr( $m_name ); ?>">
										<span class="error-msg" id="profile-name-error" aria-live="polite"></span>
									</div>
								</div>

								<div class="form-row-2col">
									<div class="form-group">
										<label for="profile-dob" class="form-label"><?php esc_html_e( 'Date of Birth', 'secondinnings50' ); ?> <span class="required">*</span></label>
										<input type="date" id="profile-dob" name="dob" class="form-control" required aria-required="true" value="<?php echo esc_attr( $m_dob ); ?>">
										<span class="error-msg" id="profile-dob-error" aria-live="polite"></span>
									</div>

									<div class="form-group">
										<label for="profile-age" class="form-label"><?php esc_html_e( 'Age Bracket', 'secondinnings50' ); ?> <span class="required">*</span></label>
										<select id="profile-age" name="age_bracket" class="form-control select-control" required aria-required="true">
											<option value="" disabled><?php esc_html_e( 'Select your age range', 'secondinnings50' ); ?></option>
											<option value="40-45" <?php selected( $m_age, '40-45' ); ?>><?php esc_html_e( '40 to 45 Years', 'secondinnings50' ); ?></option>
											<option value="46-50" <?php selected( $m_age, '46-50' ); ?>><?php esc_html_e( '46 to 50 Years', 'secondinnings50' ); ?></option>
											<option value="51-55" <?php selected( $m_age, '51-55' ); ?>><?php esc_html_e( '51 to 55 Years', 'secondinnings50' ); ?></option>
											<option value="56-60" <?php selected( $m_age, '56-60' ); ?>><?php esc_html_e( '56 to 60 Years', 'secondinnings50' ); ?></option>
											<option value="60+" <?php selected( $m_age, '60+' ); ?>><?php esc_html_e( '60 Years & Above', 'secondinnings50' ); ?></option>
										</select>
										<span class="error-msg" id="profile-age-error" aria-live="polite"></span>
									</div>
								</div>

								<div class="form-row-2col">
									<div class="form-group">
										<label for="profile-marital" class="form-label"><?php esc_html_e( 'Marital Status', 'secondinnings50' ); ?> <span class="required">*</span></label>
										<select id="profile-marital" name="marital_status" class="form-control select-control" required aria-required="true">
											<option value="" disabled><?php esc_html_e( 'Select Marital Status', 'secondinnings50' ); ?></option>
											<option value="Single" <?php selected( $m_marital, 'Single' ); ?>><?php esc_html_e( 'Single / Never Married', 'secondinnings50' ); ?></option>
											<option value="Divorced" <?php selected( $m_marital, 'Divorced' ); ?>><?php esc_html_e( 'Divorced', 'secondinnings50' ); ?></option>
											<option value="Widowed" <?php selected( $m_marital, 'Widowed' ); ?>><?php esc_html_e( 'Widowed', 'secondinnings50' ); ?></option>
											<option value="Separated" <?php selected( $m_marital, 'Separated' ); ?>><?php esc_html_e( 'Separated', 'secondinnings50' ); ?></option>
										</select>
										<span class="error-msg" id="profile-marital-error" aria-live="polite"></span>
									</div>

									<div class="form-group">
										<label for="profile-occupation" class="form-label"><?php esc_html_e( 'Occupation / Profession', 'secondinnings50' ); ?> <span class="required">*</span></label>
										<input type="text" id="profile-occupation" name="occupation" class="form-control" placeholder="<?php esc_attr_e( 'e.g. Retired Teacher', 'secondinnings50' ); ?>" required aria-required="true" value="<?php echo esc_attr( $m_occupation ); ?>">
										<span class="error-msg" id="profile-occupation-error" aria-live="polite"></span>
									</div>
								</div>
							</fieldset>

							<fieldset class="form-section-group">
								<legend><?php esc_html_e( 'Secure Contacts', 'secondinnings50' ); ?></legend>
								<div class="form-row-2col">
									<div class="form-group">
										<label for="profile-email" class="form-label"><?php esc_html_e( 'Email Address', 'secondinnings50' ); ?></label>
										<input type="email" id="profile-email" class="form-control" value="<?php echo esc_attr( $userdata->user_email ); ?>" disabled readonly style="background-color: #f0f0f0 !important; cursor: not-allowed !important; border: 1.5px solid #cccccc !important;">
										<span style="font-size: 0.75rem; color: #666; margin-top: 4px; display: block;"><?php esc_html_e( 'Email address is locked.', 'secondinnings50' ); ?></span>
									</div>

									<div class="form-group">
										<label for="profile-phone" class="form-label"><?php esc_html_e( 'WhatsApp Number', 'secondinnings50' ); ?> <span class="required">*</span></label>
										<input type="tel" id="profile-phone" name="phone" class="form-control" placeholder="<?php esc_attr_e( 'e.g. 9876543210', 'secondinnings50' ); ?>" required aria-required="true" value="<?php echo esc_attr( $m_phone ); ?>">
										<span class="error-msg" id="profile-phone-error" aria-live="polite"></span>
									</div>
								</div>

								<div class="form-row-2col">
									<div class="form-group">
										<label for="profile-city" class="form-label"><?php esc_html_e( 'Current City & State (Address)', 'secondinnings50' ); ?> <span class="required">*</span></label>
										<input type="text" id="profile-city" name="city_state" class="form-control" placeholder="<?php esc_attr_e( 'e.g. Pune, Maharashtra', 'secondinnings50' ); ?>" required aria-required="true" value="<?php echo esc_attr( $m_city ); ?>">
										<span class="error-msg" id="profile-city-error" aria-live="polite"></span>
									</div>

									<!-- Emergency Contact removed for now -->
								</div>

								<div class="form-row-2col" style="margin-top: 15px;">
									<div class="form-group">
										<label for="profile-visibility" class="form-label"><?php esc_html_e( 'Profile Visibility Privacy Setting', 'secondinnings50' ); ?> <span class="required">*</span></label>
										<?php 
										$m_visibility = get_user_meta( $user_id, 'si50_profile_visibility', true );
										if ( empty( $m_visibility ) ) {
											$m_visibility = 'public';
										}
										?>
										<select id="profile-visibility" name="profile_visibility" class="form-control select-control" required aria-required="true" style="border: 1.5px solid #333333 !important; background-color: #FFFFFF !important;">
											<option value="public" <?php selected( $m_visibility, 'public' ); ?>><?php esc_html_e( 'Public: Visible to all verified members', 'secondinnings50' ); ?></option>
											<option value="connections" <?php selected( $m_visibility, 'connections' ); ?>><?php esc_html_e( 'Connections Only: Only approved connections view full details', 'secondinnings50' ); ?></option>
											<option value="private" <?php selected( $m_visibility, 'private' ); ?>><?php esc_html_e( 'Private: Completely hidden from directory searches', 'secondinnings50' ); ?></option>
										</select>
										<span class="error-msg" id="profile-visibility-error" aria-live="polite"></span>
									</div>
									<div class="form-group" style="display: flex; align-items: center; padding-top: 25px;">
										<span style="font-size: var(--fs-xs); color: var(--color-charcoal-muted); line-height: 1.4;">
											<?php esc_html_e( 'Manage who has access to view your detailed profile card, biography description, and companion preferences.', 'secondinnings50' ); ?>
										</span>
									</div>
								</div>

								<div class="form-group-full" style="margin-top: 15px;">
									<label for="profile-location-pref" class="form-label"><?php esc_html_e( 'Location Match Preference', 'secondinnings50' ); ?> <span class="required">*</span></label>
									<select id="profile-location-pref" name="location_preference" class="form-control select-control" required aria-required="true" style="border: 1.5px solid #333333 !important; background-color: #FFFFFF !important;">
										<option value="" disabled <?php selected( empty( $m_loc_pref ) ); ?>><?php esc_html_e( 'Select location preference', 'secondinnings50' ); ?></option>
										<option value="Same City" <?php selected( $m_loc_pref, 'Same City' ); ?>><?php esc_html_e( 'Same City', 'secondinnings50' ); ?></option>
										<option value="Same State" <?php selected( $m_loc_pref, 'Same State' ); ?>><?php esc_html_e( 'Same State', 'secondinnings50' ); ?></option>
										<option value="Anywhere in India" <?php selected( $m_loc_pref, 'Anywhere in India' ); ?>><?php esc_html_e( 'Anywhere in India', 'secondinnings50' ); ?></option>
										<option value="Open to Relocation" <?php selected( $m_loc_pref, 'Open to Relocation' ); ?>><?php esc_html_e( 'Open to Relocation', 'secondinnings50' ); ?></option>
									</select>
									<span class="error-msg" id="profile-location-pref-error" aria-live="polite"></span>
								</div>

								<div class="form-group-full" style="margin-top: 16px;">
									<span class="form-group-section-title"><?php esc_html_e( 'Special Segments & Incentives (Optional - Select if applicable)', 'secondinnings50' ); ?></span>
									<div class="checkbox-group-grid checkbox-2col" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px; margin-top: 6px;">
										<label class="checkbox-container">
											<input type="checkbox" name="special_incentives[]" value="Single Mother" <?php checked( in_array( 'Single Mother', $m_special_incentives ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Single Mother', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="special_incentives[]" value="Widowed Woman" <?php checked( in_array( 'Widowed Woman', $m_special_incentives ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Widowed Woman', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="special_incentives[]" value="Widower" <?php checked( in_array( 'Widower', $m_special_incentives ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Widower', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="special_incentives[]" value="Living Alone" <?php checked( in_array( 'Living Alone', $m_special_incentives ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Living Alone / Independent', 'secondinnings50' ); ?></span>
										</label>
									</div>
								</div>
							</fieldset>

							<fieldset class="form-section-group">
								<legend><?php esc_html_e( 'Biography / About Me', 'secondinnings50' ); ?></legend>
								<div class="form-group-full">
									<label for="profile-message" class="form-label"><?php esc_html_e( 'Biography / About Me', 'secondinnings50' ); ?> <span class="required">*</span></label>
									<textarea id="profile-message" name="introduction" class="form-control textarea-control" rows="15" placeholder="<?php esc_attr_e( 'Share a detailed message about yourself, your interests, your background, and your life journey...', 'secondinnings50' ); ?>" required aria-required="true"><?php echo esc_textarea( $m_intro ); ?></textarea>
									<span style="font-size: var(--fs-xs); color: var(--color-charcoal-muted); display: block; margin-top: 6px;">
										<?php esc_html_e( 'You can write up to 1000+ words to comfortably share your thoughts.', 'secondinnings50' ); ?>
									</span>
									<span class="error-msg" id="profile-message-error" aria-live="polite"></span>
								</div>
								<div class="form-group-full" style="margin-top: 16px;">
									<label for="profile-voice" class="form-label"><?php esc_html_e( 'Voice Introduction (Optional)', 'secondinnings50' ); ?></label>
									<input type="file" id="profile-voice" name="voice_intro" accept="audio/*" class="form-control" style="padding: 8px; border: 1.5px solid var(--color-charcoal) !important;">
									<span style="font-size: var(--fs-xs); color: var(--color-charcoal-muted); display: block; margin-top: 6px;">
										<?php esc_html_e( 'Upload a short voice recording (MP3, WAV, etc.) introducing yourself. Hearing your voice creates trust and comfort faster than text alone.', 'secondinnings50' ); ?>
									</span>
									<?php if ( ! empty( $m_voice_intro ) ) : ?>
										<div style="margin-top: 10px;">
											<span style="font-size: var(--fs-xs); font-weight: 700;"><?php esc_html_e( 'Current Voice Intro:', 'secondinnings50' ); ?></span><br>
											<audio controls style="height: 32px; width: 100%; max-width: 300px; margin-top: 4px;">
												<source src="<?php echo esc_url( $m_voice_intro ); ?>" type="audio/mpeg">
											</audio>
										</div>
									<?php endif; ?>
								</div>
							</fieldset>

							<fieldset class="form-section-group">
								<legend><?php esc_html_e( 'Change Password (Optional)', 'secondinnings50' ); ?></legend>
								<div class="form-row-2col">
									<div class="form-group">
										<label for="profile-password" class="form-label"><?php esc_html_e( 'New Password', 'secondinnings50' ); ?></label>
										<input type="password" id="profile-password" name="password" class="form-control" placeholder="<?php esc_attr_e( 'Minimum 6 characters', 'secondinnings50' ); ?>">
										<span class="error-msg" id="profile-password-error" aria-live="polite"></span>
									</div>
									
									<div class="form-group">
										<label for="profile-password-confirm" class="form-label"><?php esc_html_e( 'Confirm New Password', 'secondinnings50' ); ?></label>
										<input type="password" id="profile-password-confirm" name="password_confirm" class="form-control" placeholder="<?php esc_attr_e( 'Confirm your password', 'secondinnings50' ); ?>">
										<span class="error-msg" id="profile-password-confirm-error" aria-live="polite"></span>
									</div>
								</div>
								
								<div style="margin-top: 15px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
									<span style="font-size: var(--fs-xs); color: var(--color-charcoal-muted);"><?php esc_html_e( 'Forgot your current password?', 'secondinnings50' ); ?></span>
									<button type="button" id="profile-forgot-password-btn" class="btn" style="border: 1.5px solid #333333; background: #FFFFFF; color: #333333; padding: 6px 12px; font-size: 0.75rem; font-weight: 700; cursor: pointer; border-radius: var(--radius-sm);">
										🔑 <?php esc_html_e( 'Send Reset Password Link', 'secondinnings50' ); ?>
									</button>
								</div>
							</fieldset>

							<fieldset class="form-section-group" style="border: 2px solid var(--color-terracotta); background-color: #fffaf9;">
								<legend style="color: var(--color-terracotta); font-weight: 700;">⚠️ <?php esc_html_e( 'Danger Zone', 'secondinnings50' ); ?></legend>
								<div class="form-group-full">
									<p style="font-size: var(--fs-xs); color: var(--color-charcoal-muted); margin-bottom: 12px;">
										<?php esc_html_e( 'Permanently delete your account and remove all associated data, connections, and preferences. This action cannot be undone.', 'secondinnings50' ); ?>
									</p>
									<button type="button" id="profile-delete-account-btn" class="btn" style="border: 1.5px solid var(--color-terracotta); background: #FFFFFF; color: var(--color-terracotta); padding: 8px 16px; font-size: 0.875rem; font-weight: 700; cursor: pointer; border-radius: var(--radius-sm);">
										🗑️ <?php esc_html_e( 'Delete Account', 'secondinnings50' ); ?>
									</button>
								</div>
							</fieldset>

							<!-- Action Status Alert Notifications -->
							<div id="profile-status-msg" class="error-msg text-center" style="margin-bottom: 15px; display: none; font-weight: bold; font-size: var(--fs-sm);" aria-live="polite"></div>
							
							<div class="profile-submit-row" style="display: flex; gap: 15px; align-items: center; margin-top: 20px;">
								<button type="submit" class="btn btn-primary" id="profile-submit-btn" style="width: 100%; height: 48px; font-weight: 700;">
									<?php esc_html_e( 'Save Info Changes', 'secondinnings50' ); ?>
								</button>
							</div>
						</form>
					</div>

				<?php elseif ( 'preferences' === $active_tab ) : ?>
					<!-- TAB 2: MY PREFERENCES -->
					<div class="card profile-card">
						<form class="join-form" id="si50-profile-preferences-form" method="post" aria-label="<?php esc_attr_e( 'Edit Preferences', 'secondinnings50' ); ?>">
							<?php wp_nonce_field( 'si50_auth_nonce', 'security' ); ?>
							
							<fieldset class="form-section-group">
								<legend><?php esc_html_e( 'Community Circle Seeking Focus', 'secondinnings50' ); ?></legend>
								<div class="form-group-full" style="margin-bottom: 20px;">
									<span class="form-group-section-title"><?php esc_html_e( 'What are you looking for in our community? (Select all that apply)', 'secondinnings50' ); ?></span>
									<div class="looking-for-checkbox-grid">
										<label class="checkbox-container">
											<input type="checkbox" name="looking_for[]" value="Friendship" <?php checked( in_array( 'Friendship', $m_looking ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Friendship / New Friends', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="looking_for[]" value="Conversation Companion" <?php checked( in_array( 'Conversation Companion', $m_looking ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Conversation Companion', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="looking_for[]" value="Activity Companion" <?php checked( in_array( 'Activity Companion', $m_looking ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Activity & Interest Companion', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="looking_for[]" value="Long-Term Companionship" <?php checked( in_array( 'Long-Term Companionship', $m_looking ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Long-Term Companionship', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="looking_for[]" value="Shared Life Partnership" <?php checked( in_array( 'Shared Life Partnership', $m_looking ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Shared Life Partnership', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="looking_for[]" value="Live-in Relationship" <?php checked( in_array( 'Live-in Relationship', $m_looking ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Meaningful Companionship', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="looking_for[]" value="Open to New Beginnings" <?php checked( in_array( 'Open to New Beginnings', $m_looking ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Open to a New Chapter in Life', 'secondinnings50' ); ?></span>
										</label>
									</div>
								</div>

								<div class="form-group-full" style="margin-bottom: 20px;">
									<label for="profile-connection-intent" class="form-label"><?php esc_html_e( 'What Kind of Connection Am I Looking For?', 'secondinnings50' ); ?></label>
									<textarea id="profile-connection-intent" name="connection_intent" class="form-control textarea-control" rows="6" placeholder="<?php esc_attr_e( 'Freely describe what you are looking for (e.g., Friendship, Phone Friend, Travel Companion, Meaningful Companionship, Long-Term Connection, Marriage / Life Partner, etc.). You can write in English or Hindi.', 'secondinnings50' ); ?>"><?php echo esc_textarea( $m_connection_intent ); ?></textarea>
								</div>

								<div class="form-group-full" style="margin-top: 15px; margin-bottom: 15px;">
									<span class="form-group-section-title"><?php esc_html_e( 'Hobby & Interest Circles Registry (Select all that interest you)', 'secondinnings50' ); ?></span>
									<div class="checkbox-group-grid checkbox-3col" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-top: 6px;">
										<label class="checkbox-container">
											<input type="checkbox" name="hobbies_interests[]" value="Social Outings" <?php checked( in_array( 'Social Outings', $m_hobbies ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Social Outings', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="hobbies_interests[]" value="Gardening" <?php checked( in_array( 'Gardening', $m_hobbies ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Gardening', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="hobbies_interests[]" value="Books & Poetry" <?php checked( in_array( 'Books & Poetry', $m_hobbies ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Books & Poetry', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="hobbies_interests[]" value="Music" <?php checked( in_array( 'Music', $m_hobbies ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Music', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="hobbies_interests[]" value="Spirituality" <?php checked( in_array( 'Spirituality', $m_hobbies ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Spirituality', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="hobbies_interests[]" value="Morning Walks" <?php checked( in_array( 'Morning Walks', $m_hobbies ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Morning Walks', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="hobbies_interests[]" value="Health & Wellness" <?php checked( in_array( 'Health & Wellness', $m_hobbies ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Health & Wellness', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="hobbies_interests[]" value="Business & Finance" <?php checked( in_array( 'Business & Finance', $m_hobbies ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Business & Finance', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="hobbies_interests[]" value="Cooking" <?php checked( in_array( 'Cooking', $m_hobbies ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Cooking', 'secondinnings50' ); ?></span>
										</label>
									</div>
								</div>

								<div class="form-group-full" style="margin-top: 15px;">
									<span class="form-group-section-title"><?php esc_html_e( 'Circles Onboarding Interest', 'secondinnings50' ); ?></span>
									<div class="circles-checkbox-grid">
										<label class="checkbox-container">
											<input type="checkbox" name="circles_interest[]" value="WhatsApp Group Interest" <?php checked( in_array( 'WhatsApp Group Interest', $m_circles ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'WhatsApp Group Interest', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="circles_interest[]" value="Local Meetup Interest" <?php checked( in_array( 'Local Meetup Interest', $m_circles ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Local Meetup Interest', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="circles_interest[]" value="Activity Meetup Interest" <?php checked( in_array( 'Activity Meetup Interest', $m_circles ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Activity Meetup Interest', 'secondinnings50' ); ?></span>
										</label>
									</div>
								</div>
							</fieldset>

							<fieldset class="form-section-group" style="display: none;">
								<legend><?php esc_html_e( 'Travel Selections & Trip Preferences', 'secondinnings50' ); ?></legend>
								
								<div class="form-group-full" style="margin-bottom: 20px;">
									<span class="form-group-section-title"><?php esc_html_e( 'Teerth Yatra & Leisure Destinations (Select interested)', 'secondinnings50' ); ?></span>
									<div class="travel-destinations-grid">
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Ayodhya" <?php checked( in_array( 'Ayodhya', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Ayodhya Dham', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Haridwar & Rishikesh" <?php checked( in_array( 'Haridwar & Rishikesh', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Haridwar & Rishikesh', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Vaishno Devi" <?php checked( in_array( 'Vaishno Devi', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Vaishno Devi', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Amritsar" <?php checked( in_array( 'Amritsar', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Amritsar (Golden Temple)', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Khatu Shyam Ji" <?php checked( in_array( 'Khatu Shyam Ji', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Khatu Shyam Ji', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Salasar Balaji" <?php checked( in_array( 'Salasar Balaji', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Salasar Balaji', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Varanasi" <?php checked( in_array( 'Varanasi', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Varanasi Kashi', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Ujjain Mahakal" <?php checked( in_array( 'Ujjain Mahakal', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Ujjain Mahakal', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Tirupati Balaji" <?php checked( in_array( 'Tirupati Balaji', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Tirupati Balaji', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Jagannath Puri" <?php checked( in_array( 'Jagannath Puri', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Jagannath Puri', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Jaipur & Udaipur" <?php checked( in_array( 'Jaipur & Udaipur', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Jaipur & Udaipur', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Shimla & Manali" <?php checked( in_array( 'Shimla & Manali', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Shimla & Manali', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Kashmir" <?php checked( in_array( 'Kashmir', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Kashmir Valley', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Kerala" <?php checked( in_array( 'Kerala', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Kerala Backwaters', 'secondinnings50' ); ?></span>
										</label>
										<label class="checkbox-container">
											<input type="checkbox" name="travel_destinations[]" value="Goa" <?php checked( in_array( 'Goa', $m_dest ) ); ?>>
											<span class="checkmark"></span>
											<span class="checkbox-label-text"><?php esc_html_e( 'Goa Beaches', 'secondinnings50' ); ?></span>
										</label>
										<div class="other-destination-wrapper" style="grid-column: 1 / -1; display: flex; align-items: center; flex-wrap: wrap; gap: 10px; width: 100%;">
											<label class="checkbox-container" style="width: auto !important; display: inline-flex !important; align-items: center; margin-bottom: 0;">
												<input type="checkbox" id="profile-travel-dest-other-cb" name="travel_destinations[]" value="Other" <?php checked( in_array( 'Other', $m_dest ) ); ?>>
												<span class="checkmark"></span>
												<span class="checkbox-label-text" style="flex: 0 0 auto !important; margin-right: 5px;"><?php esc_html_e( 'Other:', 'secondinnings50' ); ?></span>
											</label>
											<input type="text" id="profile-travel-dest-other-text" name="travel_destinations_other" class="form-control" placeholder="<?php esc_attr_e( 'Enter other destination details', 'secondinnings50' ); ?>" value="<?php echo esc_attr( $m_travel_dest_other ); ?>" style="flex: 1 1 200px !important; min-width: 150px; height: 36px; font-size: 14px; padding: 6px 12px; border: 1.5px solid #333333 !important; background-color: #FFFFFF !important;">
										</div>
									</div>
								</div>

								<div class="form-row-2col" style="margin-top: 15px;">
									<div class="form-group">
										<span class="form-group-section-title"><?php esc_html_e( 'Preferred Travel Style', 'secondinnings50' ); ?></span>
										<div class="travel-styles-grid">
											<label class="checkbox-container">
												<input type="checkbox" name="travel_styles[]" value="Spiritual Travel" <?php checked( in_array( 'Spiritual Travel', $m_styles ) ); ?>>
												<span class="checkmark"></span>
												<span class="checkbox-label-text"><?php esc_html_e( 'Spiritual Travel', 'secondinnings50' ); ?></span>
											</label>
											<label class="checkbox-container">
												<input type="checkbox" name="travel_styles[]" value="Heritage & Culture" <?php checked( in_array( 'Heritage & Culture', $m_styles ) ); ?>>
												<span class="checkmark"></span>
												<span class="checkbox-label-text"><?php esc_html_e( 'Heritage & Culture', 'secondinnings50' ); ?></span>
											</label>
											<label class="checkbox-container">
												<input type="checkbox" name="travel_styles[]" value="Nature & Mountains" <?php checked( in_array( 'Nature & Mountains', $m_styles ) ); ?>>
												<span class="checkmark"></span>
												<span class="checkbox-label-text"><?php esc_html_e( 'Nature & Mountains', 'secondinnings50' ); ?></span>
											</label>
											<label class="checkbox-container">
												<input type="checkbox" name="travel_styles[]" value="Leisure Holidays" <?php checked( in_array( 'Leisure Holidays', $m_styles ) ); ?>>
												<span class="checkmark"></span>
												<span class="checkbox-label-text"><?php esc_html_e( 'Leisure Holidays', 'secondinnings50' ); ?></span>
											</label>
											<label class="checkbox-container">
												<input type="checkbox" name="travel_styles[]" value="Weekend Getaways" <?php checked( in_array( 'Weekend Getaways', $m_styles ) ); ?>>
												<span class="checkmark"></span>
												<span class="checkbox-label-text"><?php esc_html_e( 'Weekend Getaways', 'secondinnings50' ); ?></span>
											</label>
										</div>
									</div>

									<div class="form-group">
										<span class="form-group-section-title"><?php esc_html_e( 'Companion Style Preference', 'secondinnings50' ); ?></span>
										<div class="form-group-full">
											<label class="radio-container" style="margin-bottom: 6px;">
												<input type="radio" name="companion_styles" value="Group Travel Only" <?php checked( $m_companion, 'Group Travel Only' ); ?>>
												<span class="radiomark"></span>
												<span class="radio-label-text"><?php esc_html_e( 'Group Travel Only', 'secondinnings50' ); ?></span>
											</label>
											<label class="radio-container" style="margin-bottom: 6px;">
												<input type="radio" name="companion_styles" value="Women-Only Group Travel" <?php checked( $m_companion, 'Women-Only Group Travel' ); ?>>
												<span class="radiomark"></span>
												<span class="radio-label-text"><?php esc_html_e( 'Women-Only Group Travel', 'secondinnings50' ); ?></span>
											</label>
											<label class="radio-container" style="margin-bottom: 6px;">
												<input type="radio" name="companion_styles" value="Men-Only Group Travel" <?php checked( $m_companion, 'Men-Only Group Travel' ); ?>>
												<span class="radiomark"></span>
												<span class="radio-label-text"><?php esc_html_e( 'Men-Only Group Travel', 'secondinnings50' ); ?></span>
											</label>
											<label class="radio-container" style="margin-bottom: 6px;">
												<input type="radio" name="companion_styles" value="Mixed Group Travel" <?php checked( $m_companion, 'Mixed Group Travel' ); ?>>
												<span class="radiomark"></span>
												<span class="radio-label-text"><?php esc_html_e( 'Mixed Group Travel', 'secondinnings50' ); ?></span>
											</label>
											<label class="radio-container">
												<input type="radio" name="companion_styles" value="Open to Suggestions" <?php checked( $m_companion, 'Open to Suggestions' ); ?>>
												<span class="radiomark"></span>
												<span class="radio-label-text"><?php esc_html_e( 'Open to Suggestions', 'secondinnings50' ); ?></span>
											</label>
										</div>
									</div>
								</div>
							</fieldset>

							<!-- Action Status Alert Notifications -->
							<div id="preferences-status-msg" class="error-msg text-center" style="margin-bottom: 15px; display: none; font-weight: bold; font-size: var(--fs-sm);" aria-live="polite"></div>

							<div class="form-action text-center">
								<button type="submit" class="btn btn-primary btn-block" id="preferences-submit-btn" style="height: 48px; font-weight: 700; width: 100%;">
									<?php esc_html_e( 'Save My Preferences', 'secondinnings50' ); ?>
								</button>
							</div>
						</form>
					</div>



				<?php elseif ( 'events' === $active_tab ) : 
					// Query community events
					global $wpdb;
					$table_events = $wpdb->prefix . 'si50_events';
					$events = $wpdb->get_results( "SELECT * FROM $table_events ORDER BY event_date ASC" );
					?>
					<!-- TAB 4: COMMUNITY EVENTS -->
					<div class="card profile-card si50-events-card" style="border: 2px solid #333333 !important; background-color: #FFFFFF !important; box-sizing: border-box; width: 100%;">
						<h2 style="font-family: var(--font-serif); font-size: var(--fs-lg); font-weight: 800; color: var(--color-forest); border-bottom: 1.5px solid var(--color-border); padding-bottom: 12px; margin: 0 0 20px 0;">
							📅 <?php esc_html_e( 'Community Meetups & Circles', 'secondinnings50' ); ?>
						</h2>
						<p style="font-size: var(--fs-sm); color: var(--color-charcoal-muted); margin-bottom: 20px; line-height: 1.5;">
							<?php esc_html_e( 'Participate in city-wise meetups, nature walks, retro music sessions, and local cultural gatherings with verified silver companions.', 'secondinnings50' ); ?>
						</p>

						<?php if ( empty( $events ) ) : ?>
							<p style="color: var(--color-charcoal-muted); font-style: italic;"><?php esc_html_e( 'No upcoming community meetups scheduled.', 'secondinnings50' ); ?></p>
						<?php else : ?>
							<div class="si50-events-list" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
								<?php foreach ( $events as $event ) : ?>
									<div class="si50-event-item-card" style="border: 1.5px solid #333333; border-radius: var(--radius-md); padding: 16px; background-color: var(--bg-warm); display: flex; flex-direction: column; justify-content: space-between; box-sizing: border-box;">
										<div>
											<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; flex-wrap: wrap; gap: 6px;">
												<span style="font-size: 0.75rem; font-weight: 700; color: var(--color-terracotta); background-color: var(--color-gold-light); padding: 2px 6px; border-radius: var(--radius-sm); border: 1px solid var(--color-gold);">
													📍 <?php echo esc_html( $event->city ); ?>
												</span>
												<span style="font-size: 0.75rem; font-weight: 600; color: var(--color-charcoal-muted);">
													⏰ <?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $event->event_date ) ) ); ?>
												</span>
											</div>
											<h3 style="font-family: var(--font-serif); font-size: var(--fs-md); font-weight: 800; color: var(--color-forest); margin: 0 0 8px 0;">
												<?php echo esc_html( $event->title ); ?>
											</h3>
											<p style="font-size: var(--fs-sm); color: var(--color-charcoal); line-height: 1.5; margin-bottom: 12px;">
												<?php echo esc_html( $event->description ); ?>
											</p>
											<p style="font-size: var(--fs-xs); color: var(--color-charcoal-muted); margin-bottom: 12px;">
												🏢 <strong><?php esc_html_e( 'Venue:', 'secondinnings50' ); ?></strong> <?php echo esc_html( $event->location ); ?>
											</p>
										</div>
										<div style="margin-top: 10px; border-top: 1px dashed var(--color-border); padding-top: 12px;">
											<a href="<?php echo esc_url( $event->whatsapp_group_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn text-center" style="background-color: #25D366; color: #ffffff; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px; border-radius: var(--radius-sm); font-size: var(--fs-xs); text-decoration: none; width: 100%; box-sizing: border-box;">
												<svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" style="margin-top: 2px;"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.457L0 24zm6.59-4.846c1.666.988 3.31 1.493 5.405 1.494 5.276 0 9.57-4.287 9.573-9.564.001-2.556-1.002-4.959-2.816-6.78C16.924 2.5 14.55 1.5 12.008 1.5c-5.282 0-9.58 4.29-9.583 9.566-.001 2.01.522 3.823 1.517 5.485L2.94 20.897l6.707-1.743zM16.595 13.7c-.253-.127-1.5-.74-1.73-.824-.231-.084-.399-.127-.567.127-.168.252-.65.824-.796.993-.147.168-.294.19-.547.063-.253-.127-1.07-.394-2.04-1.259-.755-.674-1.266-1.506-1.414-1.759-.148-.252-.016-.39.11-.516.114-.112.253-.295.38-.442.127-.147.169-.253.253-.422.084-.168.042-.316-.021-.442-.063-.127-.567-1.36-.777-1.865-.205-.496-.41-.427-.567-.427-.147-.003-.315-.003-.483-.003-.168 0-.441.063-.672.316-.231.253-.882.863-.882 2.106 0 1.242.903 2.443 1.029 2.612.126.168 1.776 2.712 4.302 3.802.6.26 1.07.414 1.434.529.603.192 1.152.165 1.587.1.485-.072 1.5-.612 1.712-1.206.21-.595.21-1.106.147-1.206-.063-.1-.231-.143-.483-.27z"/></svg>
												<?php esc_html_e( 'Join RSVP Group on WhatsApp', 'secondinnings50' ); ?>
											</a>
										</div>
										<div class="si50-share-container" style="margin-top: 10px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
											<span style="font-size: 11px; font-weight: 700; color: var(--color-charcoal-muted);"><?php esc_html_e( 'Share Meetup:', 'secondinnings50' ); ?></span>
											<?php 
											$event_share_msg = sprintf( __( 'Hey! Join me for this SecondInnings50 Meetup: %1$s on %2$s in %3$s! Details at: %4$s', 'secondinnings50' ), $event->title, date_i18n( get_option( 'date_format' ), strtotime( $event->event_date ) ), $event->city, home_url( '/profile/?tab=events' ) );
											$wa_share_url = 'https://api.whatsapp.com/send?text=' . rawurlencode( $event_share_msg );
											$fb_share_url = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode( home_url( '/profile/?tab=events' ) );
											?>
											<a href="<?php echo esc_url( $wa_share_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn text-center" style="background-color: #25D366 !important; color: #ffffff !important; border: none; font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;" title="<?php esc_attr_e( 'Share via WhatsApp', 'secondinnings50' ); ?>">
												WhatsApp
											</a>
											<a href="<?php echo esc_url( $fb_share_url ); ?>" target="_blank" rel="noopener noreferrer" class="btn text-center" style="background-color: #1877F2 !important; color: #ffffff !important; border: none; font-size: 11px; font-weight: 700; padding: 4px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;" title="<?php esc_attr_e( 'Share via Facebook', 'secondinnings50' ); ?>">
												Facebook
											</a>
											<button type="button" class="si50-copy-share-link" data-share-title="<?php echo esc_attr( $event->title ); ?>" data-share-url="<?php echo esc_url( home_url( '/profile/?tab=events' ) ); ?>" style="border: 1.5px solid #33; font-weight: 700; background: #fff; cursor: pointer; font-size: 11px; padding: 4px 8px; border-radius: 4px; display: inline-flex; align-items: center; gap: 4px; height: auto;">
												🔗 <?php esc_html_e( 'Copy Invite', 'secondinnings50' ); ?>
											</button>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>

<?php elseif ( 'activity-log' === $active_tab ) : 
					$activities = $wpdb->get_results( $wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}si50_audit_logs 
						 WHERE user_id = %d 
						 ORDER BY timestamp DESC 
						 LIMIT 20",
						$user_id
					) );
					?>
					<!-- TAB 6: ACTIVITY & LOGIN HISTORY -->
					<div class="card profile-card" style="border: 2px solid #333333 !important; background-color: #FFFFFF !important;">
						<h2 style="font-family: var(--font-serif); font-size: var(--fs-lg); font-weight: 800; color: var(--color-forest); border-bottom: 1.5px solid var(--color-border); padding-bottom: 12px; margin: 0 0 20px 0;">
							📜 <?php esc_html_e( 'My Activity & Login History', 'secondinnings50' ); ?>
						</h2>
						<p style="font-size: var(--fs-sm); color: var(--color-charcoal-muted); margin-bottom: 20px; line-height: 1.5;">
							<?php esc_html_e( 'Audit logs of your recent activities, profile updates, and login sessions on the SecondInnings50 platform.', 'secondinnings50' ); ?>
						</p>

						<?php if ( empty( $activities ) ) : ?>
							<p style="color: var(--color-charcoal-muted); font-style: italic;"><?php esc_html_e( 'No recent activity logs recorded.', 'secondinnings50' ); ?></p>
						<?php else : ?>
							<div style="border: 1.5px solid #333333; border-radius: var(--radius-sm); overflow: hidden; background: #ffffff;">
								<table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
									<thead>
										<tr style="background-color: #f9f9f9; border-bottom: 2px solid #333333;">
											<th style="padding: 12px 10px; font-weight: bold; color: var(--color-charcoal);"><?php esc_html_e( 'Date & Time', 'secondinnings50' ); ?></th>
											<th style="padding: 12px 10px; font-weight: bold; color: var(--color-charcoal);"><?php esc_html_e( 'Action Type', 'secondinnings50' ); ?></th>
											<th style="padding: 12px 10px; font-weight: bold; color: var(--color-charcoal);"><?php esc_html_e( 'Activity Description', 'secondinnings50' ); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $activities as $act ) : ?>
											<tr style="border-bottom: 1px solid var(--color-border);">
												<td style="padding: 12px 10px; color: var(--color-charcoal-muted); white-space: nowrap;">
													<?php echo esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $act->timestamp ) ) ); ?>
												</td>
												<td style="padding: 12px 10px;">
													<span style="font-size: 11px; font-weight: 700; background-color: var(--color-forest-light); color: var(--color-forest-dark); border: 1px solid var(--color-forest); padding: 2px 6px; border-radius: 4px; display: inline-block;">
														<?php echo esc_html( strtoupper( str_replace( '_', ' ', $act->action ) ) ); ?>
													</span>
												</td>
												<td style="padding: 12px 10px; color: var(--color-charcoal); line-height: 1.4;">
													<?php echo esc_html( $act->details ); ?>
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
    </div>
  </section>
</main>

<?php
get_footer();
