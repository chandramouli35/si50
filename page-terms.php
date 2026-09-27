<?php
/**
 * Template Name: Terms of Service Template
 *
 * @package SecondInnings50
 */

get_header();
?>

<main id="main-content" class="site-main content-area" style="background-color: #F8F9FA; min-height: 80vh; padding: 40px 0; box-sizing: border-box;">
  <!-- Google Developer Editorial Page Container -->
  <div class="si50-legal-container" style="max-width: 800px; margin: 0 auto; padding: 60px 30px; font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; color: #333333; line-height: 1.8; background: #FFFFFF; border: 1px solid #E0E0E0; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); box-sizing: border-box;">
    
    <style>
      .si50-legal-container h1, .si50-legal-container h2, .si50-legal-container h3 {
        font-family: 'Playfair Display', Georgia, serif;
        color: #23282d;
        margin-top: 1.5em;
        margin-bottom: 0.5em;
        font-weight: 700;
      }
      .si50-legal-container h1 {
        font-size: 2.25rem;
        margin-top: 0;
        border-bottom: 2px solid #E0E0E0;
        padding-bottom: 15px;
      }
      .si50-legal-container h2 {
        font-size: 1.5rem;
        border-bottom: 1px solid #E0E0E0;
        padding-bottom: 8px;
        margin-top: 2em;
      }
      .si50-legal-container h3 {
        font-size: 1.2rem;
        color: #374151;
      }
      .si50-legal-container p {
        margin-bottom: 1.25em;
        font-size: 1.05rem;
      }
      .si50-legal-container ul, .si50-legal-container ol {
        margin-bottom: 1.5em;
        padding-left: 20px;
      }
      .si50-legal-container li {
        margin-bottom: 0.5em;
        font-size: 1.025rem;
      }
      .si50-legal-container .meta-info {
        font-size: 0.9rem;
        color: #666666;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #E0E0E0;
        padding-bottom: 12px;
      }
      .si50-legal-container .disclaimer-box {
        background-color: #FEF2F2;
        border-left: 4px solid #EF4444;
        padding: 20px;
        margin: 25px 0;
        border-radius: 4px;
      }
      .si50-legal-container .disclaimer-box h4 {
        margin-top: 0;
        color: #991B1B;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 8px;
      }
      .si50-legal-container .disclaimer-box p {
        margin-bottom: 0;
        font-size: 0.975rem;
        color: #7F1D1D;
      }
      .si50-legal-container .guidelines-section {
        background-color: #F8FAFC;
        border: 1px solid #E2E8F0;
        padding: 30px;
        border-radius: 6px;
        margin-top: 40px;
      }
      .si50-legal-container .guidelines-section h2 {
        margin-top: 0;
        border-bottom: 2px solid #CBD5E1;
        color: #1E293B;
      }
      .si50-back-btn {
        display: inline-block;
        margin-top: 30px;
        padding: 12px 24px;
        background-color: #23282d;
        color: #FFFFFF !important;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: background-color 0.2s ease-in-out;
      }
      .si50-back-btn:hover {
        background-color: #4A5568;
      }
      @media (max-width: 600px) {
        .si50-legal-container {
          padding: 30px 20px;
          border-radius: 0;
          border-left: none;
          border-right: none;
        }
        .si50-legal-container h1 {
          font-size: 1.75rem;
        }
        .si50-legal-container h2 {
          font-size: 1.35rem;
        }
      }
    </style>

    <h1><?php esc_html_e( 'SecondInnings50 Terms of Service', 'secondinnings50' ); ?></h1>
    
    <div class="meta-info">
      <span><?php esc_html_e( 'Scope: Indian Senior Community Platform', 'secondinnings50' ); ?></span>
      <span><strong><?php esc_html_e( 'Last Updated:', 'secondinnings50' ); ?></strong> <?php echo date( 'F d, Y' ); ?></span>
    </div>

    <ol>
      <li><?php esc_html_e( 'SecondInnings50.in is a companionship and meaningful connections platform for adults aged 45+.', 'secondinnings50' ); ?></li>
      <li><?php esc_html_e( 'Users must provide accurate information during registration.', 'secondinnings50' ); ?></li>
      <li><?php esc_html_e( 'Respectful behaviour is mandatory. Harassment, abusive language, vulgar content, spam, or misleading information may result in suspension or removal.', 'secondinnings50' ); ?></li>
      <li><?php esc_html_e( 'Members are responsible for their personal interactions and decisions.', 'secondinnings50' ); ?></li>
      <li><?php esc_html_e( 'SecondInnings50.in does not guarantee friendship, companionship, marriage, or any specific outcome.', 'secondinnings50' ); ?></li>
      <li><?php esc_html_e( 'Membership fees are non-refundable except in approved exceptional cases.', 'secondinnings50' ); ?></li>
      <li><?php esc_html_e( 'The platform reserves the right to modify policies and community guidelines when required.', 'secondinnings50' ); ?></li>
    </ol>

    <div style="border-top: 1px solid #E0E0E0; margin-top: 40px; padding-top: 20px; text-align: center;">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="si50-back-btn">
        <?php esc_html_e( 'Return to SecondInnings50', 'secondinnings50' ); ?>
      </a>
    </div>

  </div>
</main>

<?php
get_footer();
