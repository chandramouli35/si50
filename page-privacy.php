<?php
/**
 * Template Name: Privacy Policy Template
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
      .si50-legal-container .handshake-box {
        background-color: #F0FDF4;
        border-left: 4px solid #16A34A;
        padding: 20px;
        margin: 25px 0;
        border-radius: 4px;
      }
      .si50-legal-container .handshake-box h4 {
        margin-top: 0;
        color: #15803D;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 8px;
      }
      .si50-legal-container .handshake-box p {
        margin-bottom: 0;
        font-size: 0.975rem;
        color: #166534;
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

    <!-- SEO Optimized H1 Tag -->
    <h1><?php esc_html_e( 'SecondInnings50 Privacy Policy', 'secondinnings50' ); ?></h1>
    
    <div class="meta-info">
      <span><?php esc_html_e( 'Scope: Indian Senior Community Platform', 'secondinnings50' ); ?></span>
      <span><strong><?php esc_html_e( 'Last Updated:', 'secondinnings50' ); ?></strong> <?php echo date( 'F d, Y' ); ?></span>
    </div>

    <p>
      <?php esc_html_e( 'Welcome to SecondInnings50. We believe that genuine companionship, peer support, and active lifestyles in the second innings of life must be built upon a foundation of absolute trust, dignity, and digital privacy. This Privacy Policy details our strict collection protocols, security frameworks, and regulatory alignments designed explicitly for active adults aged 45 and above in urban India.', 'secondinnings50' ); ?>
    </p>

    <!-- SEO Optimized H2 Tag -->
    <h2><?php esc_html_e( 'Secure Senior Community Data Protection', 'secondinnings50' ); ?></h2>
    
    <p>
      <?php esc_html_e( 'Unlike conventional, open-enrollment social utilities or public matrimonial sites, SecondInnings50 implements an isolated directory paradigm. We do not index your personal identifiers for public search engines, nor do we expose your primary contact information to unverified visitors. Our server architectures employ end-to-end Transport Layer Security (TLS 1.3) protocols for all network transmissions and AES-256 database encryption at rest.', 'secondinnings50' ); ?>
    </p>

    <h3><?php esc_html_e( '1. Data Collection & Preference Matrices', 'secondinnings50' ); ?></h3>
    <p>
      <?php esc_html_e( 'To connect you with compatible peers, coordinate local interest-based meetups, and suggest travel companions, we collect and store the following data points:', 'secondinnings50' ); ?>
    </p>
    
    <ul>
      <li>
        <strong><?php esc_html_e( 'Account Metadata:', 'secondinnings50' ); ?></strong> 
        <?php esc_html_e( 'Full legal name, verified email address, mobile phone number (used for WhatsApp integration), age bracket, and residing city/state.', 'secondinnings50' ); ?>
      </li>
      <li>
        <strong><?php esc_html_e( 'Verification Records:', 'secondinnings50' ); ?></strong> 
        <?php esc_html_e( 'Reference details obtained during our mandatory telephone onboarding verification host call, along with ID proof verification status.', 'secondinnings50' ); ?>
      </li>
      <li>
        <strong><?php esc_html_e( 'Companion Preference Matrices:', 'secondinnings50' ); ?></strong> 
        <?php esc_html_e( 'Your interest indicators (e.g., Book Clubs, Gardening, Culinary Arts, Tech Workshops), preferred travel destinations (national and international), travel styles (leisure, luxury, backpacking), and companion preferences (gender focus, age group, activity level).', 'secondinnings50' ); ?>
      </li>
      <li>
        <strong><?php esc_html_e( 'Personal Bio Context:', 'secondinnings50' ); ?></strong> 
        <?php esc_html_e( 'Self-authored introductions, professional background, lifestyle preferences, and photographs uploaded to your profile.', 'secondinnings50' ); ?>
      </li>
    </ul>

    <!-- Handshake Protocol Disclosure Box -->
    <div class="handshake-box">
      <h4>🔒 <?php esc_html_e( 'The Masked Contact Handshake Protocol', 'secondinnings50' ); ?></h4>
      <p>
        <?php esc_html_e( 'To eliminate unsolicited outreach, advertising spam, or invasive contact attempts, your WhatsApp number and email address are strictly masked in the directory. Other verified members can view your name, location, interests, and bio, but they cannot see your phone number or email. This information is only revealed once a connection request is intentionally sent by one party and explicitly accepted by the other. You remain in complete control of your communication gateway.', 'secondinnings50' ); ?>
      </p>
    </div>

    <!-- SEO Optimized H2 Tag for Indian Compliance -->
    <h2><?php esc_html_e( 'Indian Data Privacy Compliance', 'secondinnings50' ); ?></h2>
    <p>
      <?php esc_html_e( 'We process personal data in strict compliance with the provisions of the Digital Personal Data Protection Act, 2023 (DPDPA 2023) of India. SecondInnings50 acts as the Data Fiduciary, and you hold clear rights as a Data Principal under the statutory framework.', 'secondinnings50' ); ?>
    </p>

    <h3><?php esc_html_e( '2. Your Rights as a Data Principal', 'secondinnings50' ); ?></h3>
    <ul>
      <li>
        <strong><?php esc_html_e( 'Right to Access & Information:', 'secondinnings50' ); ?></strong> 
        <?php esc_html_e( 'You have the right to request a summary of the personal data we process about you, the list of verified peers with whom your contact details have been shared under the Handshake Protocol, and processing logs.', 'secondinnings50' ); ?>
      </li>
      <li>
        <strong><?php esc_html_e( 'Right to Correction & Updating:', 'secondinnings50' ); ?></strong> 
        <?php esc_html_e( 'You can edit, refine, or update your personal bio, travel destinations, interest circles, and city of residence directly via your Profile Settings. These changes synchronize instantly across our member index.', 'secondinnings50' ); ?>
      </li>
      <li>
        <strong><?php esc_html_e( 'Right to Erasure & Withdrawal of Consent:', 'secondinnings50' ); ?></strong> 
        <?php esc_html_e( 'You have the right to withdraw your consent to data processing at any time. You can request the permanent erasure of your account, verification records, preference matrices, and matched logs by contacting our grievance officer or selecting the account closure option in your profile.', 'secondinnings50' ); ?>
      </li>
      <li>
        <strong><?php esc_html_e( 'Right of Grievance Redressal:', 'secondinnings50' ); ?></strong> 
        <?php esc_html_e( 'If you believe your personal data is being processed in violation of the DPDPA or this policy, you have the right to lodge a formal complaint with our grievance officer at safety@secondinnings50.com, with a guaranteed resolution cycle of 15 business days.', 'secondinnings50' ); ?>
      </li>
    </ul>

    <h3><?php esc_html_e( '3. Cookie & Tracking Policies', 'secondinnings50' ); ?></h3>
    <p>
      <?php esc_html_e( 'Our platform utilizes minor cookies strictly essential for maintaining secure authentication sessions (keeping you logged in) and remembering layout preference states. We do not deploy third-party remarketing tags or track your browser activity outside the domain of SecondInnings50.', 'secondinnings50' ); ?>
    </p>

    <h3><?php esc_html_e( '4. Disclosure & Data Transfer Boundaries', 'secondinnings50' ); ?></h3>
    <p>
      <?php esc_html_e( 'We do not transfer your personal data, identity files, or preference databases to external advertising networks or commercial brokers. Data disclosures occur strictly under the following criteria: (a) Explicit peer consent matches initiated by you; (b) Mandated legal requests or judicial orders from lawful Indian authorities; (c) Trusted technology subprocessors who manage our secure hosting infrastructures under strict data-protection agreements.', 'secondinnings50' ); ?>
    </p>

    <div style="border-top: 1px solid #E0E0E0; margin-top: 40px; padding-top: 20px; text-align: center;">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="si50-back-btn">
        <?php esc_html_e( 'Return to SecondInnings50', 'secondinnings50' ); ?>
      </a>
    </div>

  </div>
</main>

<?php
get_footer();
