<?php
/**
 * Template Name: Members Directory
 *
 * PRIVACY LOCK (Sep 2026):
 * Member profiles / algorithm matches are NEVER shown to members.
 * Matching is Admin-only. This page now explains curated introductions.
 *
 * @package SecondInnings50
 */

get_header();

// Guard access: User must be logged in and approved
$current_user_id = get_current_user_id();
$is_logged_in    = is_user_logged_in();
$vetting_status  = $is_logged_in ? get_user_meta( $current_user_id, 'si50_vetting_status', true ) : 'pending_review';

if ( empty( $vetting_status ) ) {
	$vetting_status = 'pending_review';
}

$is_approved = ( 'approved' === $vetting_status );
?>

<main id="main-content" class="site-main content-area" style="background-color: var(--bg-warm); min-height: 80vh; padding: var(--spacing-xl) 0;">
  <div class="container">

	<?php if ( ! $is_logged_in ) : ?>
		<div class="card text-center" style="max-width: 600px; margin: 40px auto; padding: var(--spacing-xl); border: 2px solid #333333 !important; background-color: #FFFFFF !important; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: var(--radius-lg);">
			<span style="font-size: 3rem; display: block; margin-bottom: var(--spacing-md);">🔒</span>
			<h2 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-forest); margin-bottom: 12px;">
				<?php esc_html_e( 'Private Member Area', 'secondinnings50' ); ?>
			</h2>
			<p style="color: var(--color-charcoal-muted); margin-bottom: 24px; line-height: 1.6;">
				<?php esc_html_e( 'To safeguard the security, dignity, and privacy of our senior community, companion introductions are handled privately by our team. Please log in or submit an onboarding application to proceed.', 'secondinnings50' ); ?>
			</p>
			<div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
				<a href="#login" class="btn btn-primary si50-trigger-login" style="font-weight: 700; padding: 12px 24px;"><?php esc_html_e( 'Log In Securely', 'secondinnings50' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" class="btn btn-gold" style="font-weight: 700; padding: 12px 24px;"><?php esc_html_e( 'Apply to Join', 'secondinnings50' ); ?></a>
			</div>
		</div>

	<?php elseif ( 'rejected' === $vetting_status ) :
		$rejection_note = get_user_meta( $current_user_id, 'si50_rejection_note', true );
		if ( empty( $rejection_note ) ) {
			$rejection_note = esc_html__( 'No additional details provided. Please check that you supplied a valid WhatsApp contact number and real name.', 'secondinnings50' );
		}
		?>
		<div class="card text-center" style="max-width: 600px; margin: 40px auto; padding: var(--spacing-xl); border: 2.5px solid var(--color-terracotta) !important; background-color: #FFFFFF !important; box-shadow: 0 10px 30px rgba(217, 83, 79, 0.15); border-radius: var(--radius-lg);">
			<span style="font-size: 3.5rem; display: block; margin-bottom: var(--spacing-md);">⚠️</span>
			<h2 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-terracotta-dark); margin-bottom: 15px;">
				<?php esc_html_e( 'Application Declined', 'secondinnings50' ); ?>
			</h2>
			<div style="background-color: #fdf3f2; border: 1.5px solid var(--color-terracotta); padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 20px; text-align: left;">
				<p style="color: var(--color-terracotta-dark); font-weight: 700; margin-bottom: 8px; font-size: var(--fs-md);">
					<?php esc_html_e( 'Feedback from our Coordination Team:', 'secondinnings50' ); ?>
				</p>
				<p style="color: var(--color-charcoal); font-size: var(--fs-sm); line-height: 1.5; margin: 0; font-style: italic;">
					"<?php echo esc_html( $rejection_note ); ?>"
				</p>
			</div>
			<div>
				<a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>" class="btn btn-primary" style="font-weight: 700; padding: 12px 24px; text-decoration: none; display: inline-block;"><?php esc_html_e( 'Update My Profile', 'secondinnings50' ); ?></a>
			</div>
		</div>

	<?php elseif ( 'suspended' === $vetting_status ) : ?>
		<div class="card text-center" style="max-width: 600px; margin: 40px auto; padding: var(--spacing-xl); border: 2.5px solid var(--color-terracotta) !important; background-color: #FFFFFF !important; box-shadow: 0 10px 30px rgba(217, 83, 79, 0.15); border-radius: var(--radius-lg);">
			<span style="font-size: 3.5rem; display: block; margin-bottom: var(--spacing-md);">🚫</span>
			<h2 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-terracotta-dark); margin-bottom: 15px;">
				<?php esc_html_e( 'Account Suspended', 'secondinnings50' ); ?>
			</h2>
			<div style="background-color: #fdf3f2; border: 1.5px solid var(--color-terracotta); padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 20px; text-align: left;">
				<p style="color: var(--color-charcoal); font-size: var(--fs-sm); line-height: 1.5; margin: 0;">
					<?php esc_html_e( 'Your account has been suspended due to reports of activity that violates our community guidelines or safety protocols. Please contact support at hello@secondinnings50.in if you believe this is in error.', 'secondinnings50' ); ?>
				</p>
			</div>
		</div>

	<?php elseif ( ! $is_approved ) : ?>
		<div class="card text-center" style="max-width: 600px; margin: 40px auto; padding: var(--spacing-xl); border: 2.5px solid var(--color-gold) !important; background-color: #FFFFFF !important; box-shadow: 0 10px 30px rgba(197, 160, 89, 0.15); border-radius: var(--radius-lg);">
			<span style="font-size: 3.5rem; display: block; margin-bottom: var(--spacing-md);">⏳</span>
			<h2 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-forest); margin-bottom: 15px;">
				<?php esc_html_e( 'Verification Vetting in Progress', 'secondinnings50' ); ?>
			</h2>
			<div style="background-color: var(--color-gold-light); border: 1px solid var(--color-gold); padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 20px; text-align: left;">
				<p style="color: var(--color-forest-dark); font-weight: 700; margin-bottom: 8px; font-size: var(--fs-md);">
					<?php esc_html_e( 'Your membership application is securely received.', 'secondinnings50' ); ?>
				</p>
				<p style="color: var(--color-forest-dark); font-size: var(--fs-sm); line-height: 1.5; margin: 0;">
					<?php esc_html_e( 'Access will be unlocked following your standard manual verification welcome call. Our coordination team will contact you within 24 hours to confirm authenticity and activate your profile.', 'secondinnings50' ); ?>
				</p>
			</div>
		</div>

	<?php else : ?>
		<!-- Approved members: NO profile browsing. Admin-curated introductions only. -->
		<div class="card text-center" style="max-width: 720px; margin: 40px auto; padding: var(--spacing-xl); border: 2px solid #1B3B2B !important; background-color: #FFFFFF !important; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: var(--radius-lg);">
			<span style="font-size: 3rem; display: block; margin-bottom: var(--spacing-md);">🔒</span>
			<span class="section-tag" style="display: inline-block; margin-bottom: 8px;"><?php esc_html_e( 'Privacy First', 'secondinnings50' ); ?></span>
			<h1 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-forest); margin: 0 0 16px 0; font-size: 1.85em; line-height: 1.25;">
				<?php esc_html_e( 'Team-Curated Companionship', 'secondinnings50' ); ?>
			</h1>
			<p style="color: var(--color-charcoal-muted); margin-bottom: 16px; line-height: 1.7; text-align: left;">
				<?php esc_html_e( 'For your privacy and dignity, member profiles are not open for browsing on this website. Matching is handled only by the SecondInnings team in a secure admin panel.', 'secondinnings50' ); ?>
			</p>
			<p style="color: var(--color-charcoal); margin-bottom: 12px; line-height: 1.7; text-align: left; font-weight: 600;">
				<?php esc_html_e( 'You will not see other members\' names, photos, match percentages, or algorithm suggestions anywhere after login.', 'secondinnings50' ); ?>
			</p>
			<p style="color: var(--color-charcoal-muted); margin-bottom: 24px; line-height: 1.7; text-align: left;">
				<?php esc_html_e( 'When our team finds a suitable companionship introduction for you, we will share it with you personally (for example on WhatsApp) after careful review. Contact details stay private until both people give consent.', 'secondinnings50' ); ?>
			</p>
			<div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
				<a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>" class="btn btn-primary" style="font-weight: 700; padding: 12px 24px; text-decoration: none;"><?php esc_html_e( 'Go to My Profile', 'secondinnings50' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-gold" style="font-weight: 700; padding: 12px 24px; text-decoration: none;"><?php esc_html_e( 'Contact Our Team', 'secondinnings50' ); ?></a>
			</div>
		</div>
	<?php endif; ?>

  </div>
</main>

<?php
get_footer();
