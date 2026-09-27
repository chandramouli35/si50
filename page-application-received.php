<?php
/**
 * Template Name: Application Received Template
 *
 * @package SecondInnings50
 */

// Strict logout check to clear any lingering auth cookies
if ( is_user_logged_in() ) {
	wp_logout();
}

get_header();
?>

<main id="main-content" class="site-main content-area" style="background-color: #F8F9FA; min-height: 85vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; box-sizing: border-box;">
  <div class="si50-holding-container" style="max-width: 600px; width: 100%; background: #FFFFFF; border: 1.5px solid #333333; border-radius: 8px; padding: 50px 30px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); box-sizing: border-box; text-align: center; font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;">
    <div style="margin-bottom: 25px;">
      <span class="dashicons dashicons-shield-assistant" style="font-size: 64px; width: 64px; height: 64px; color: #1B3B2B;"></span>
    </div>
    
    <h1 style="font-family: 'Playfair Display', Georgia, serif; font-size: 2rem; color: #202124; margin-top: 0; margin-bottom: 15px; font-weight: 700; line-height: 1.3;">
      <?php esc_html_e( 'Secure Senior Onboarding Circle - Application Safely Received', 'secondinnings50' ); ?>
    </h1>
    
    <div style="width: 50px; height: 3px; background-color: #1B3B2B; margin: 0 auto 25px auto;"></div>
    
    <p style="font-size: 1.05rem; line-height: 1.8; color: #3c4043; margin-bottom: 30px;">
      <?php esc_html_e( 'Your profile has been safely received and is undergoing identity vetting by our community hosts. We will notify you via email and WhatsApp once approved.', 'secondinnings50' ); ?>
    </p>
    
    <div style="background-color: #F1F3F4; border-radius: 6px; padding: 18px; border: 1px solid #E0E0E0; margin-bottom: 30px; text-align: left;">
      <h3 style="font-size: 0.9rem; font-weight: 700; color: #202124; margin-top: 0; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
        🔒 <?php esc_html_e( 'Privacy & Confidentiality Guarantee', 'secondinnings50' ); ?>
      </h3>
      <p style="font-size: 0.85rem; line-height: 1.6; color: #5f6368; margin: 0;">
        <?php esc_html_e( 'All data, including your emergency family contact number and uploaded verification selfie, is securely stored on encrypted servers. Only verified host administrators can access this data to complete your safety check.', 'secondinnings50' ); ?>
      </p>
    </div>
    
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display: inline-block; background-color: #1B3B2B; color: #FFFFFF !important; text-decoration: none; padding: 12px 30px; font-size: 0.95rem; font-weight: 700; border: 2px solid #333333; border-radius: 4px; transition: background-color 0.2s ease;">
      <?php esc_html_e( 'Return to Homepage', 'secondinnings50' ); ?>
    </a>
  </div>
</main>

<?php
get_footer();
