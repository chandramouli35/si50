<?php
/**
 * Template Name: Refund Policy Template
 *
 * @package SecondInnings50
 */

get_header();
?>

<main id="main-content" class="site-main content-area" style="background-color: #F8F9FA; min-height: 80vh; padding: 40px 0; box-sizing: border-box;">
  <div class="si50-legal-container" style="max-width: 800px; margin: 0 auto; padding: 60px 30px; font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; color: #333333; line-height: 1.8; background: #FFFFFF; border: 1px solid #E0E0E0; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); box-sizing: border-box;">
    
    <style>
      .si50-legal-container h1 { font-family: 'Playfair Display', Georgia, serif; color: #23282d; margin-top: 0; border-bottom: 2px solid #E0E0E0; padding-bottom: 15px; font-size: 2.25rem; font-weight: 700; }
      .si50-legal-container p { margin-bottom: 1.25em; font-size: 1.05rem; }
      .si50-legal-container .meta-info { font-size: 0.9rem; color: #666666; margin-bottom: 30px; display: flex; justify-content: space-between; border-bottom: 1px solid #E0E0E0; padding-bottom: 12px; }
      .si50-back-btn { display: inline-block; margin-top: 30px; padding: 12px 24px; background-color: #23282d; color: #FFFFFF !important; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 0.95rem; transition: background-color 0.2s ease-in-out; }
      .si50-back-btn:hover { background-color: #4A5568; }
      @media (max-width: 600px) { .si50-legal-container { padding: 30px 20px; border-radius: 0; border-left: none; border-right: none; } .si50-legal-container h1 { font-size: 1.75rem; } }
    </style>

    <h1><?php esc_html_e( 'SecondInnings50 Refund Policy', 'secondinnings50' ); ?></h1>
    
    <div class="meta-info">
      <span><?php esc_html_e( 'Scope: Indian Senior Community Platform', 'secondinnings50' ); ?></span>
      <span><strong><?php esc_html_e( 'Last Updated:', 'secondinnings50' ); ?></strong> <?php echo date( 'F d, Y' ); ?></span>
    </div>

    <p><?php esc_html_e( 'Membership/registration fees once paid are non-refundable.', 'secondinnings50' ); ?></p>
    <p><?php esc_html_e( 'In case of duplicate payment or technical payment failure where the amount is deducted but registration is not completed, the user may contact support for review.', 'secondinnings50' ); ?></p>
    <p><?php esc_html_e( 'The platform reserves the right to approve or reject refund requests after verification.', 'secondinnings50' ); ?></p>

    <div style="border-top: 1px solid #E0E0E0; margin-top: 40px; padding-top: 20px; text-align: center;">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="si50-back-btn">
        <?php esc_html_e( 'Return to SecondInnings50', 'secondinnings50' ); ?>
      </a>
    </div>

  </div>
</main>

<?php
get_footer();
