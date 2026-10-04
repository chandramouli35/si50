<?php
/**
 * The template for displaying the front page.
 *
 * @package SecondInnings50
 */

get_header();
?>

  <!-- EMBEDDED STYLES FOR VISUAL REFERENCE & TRAVEL EXPANSION -->
  <style>
    /* Hero Custom Visual Reference Stylings */
    .hero-grid {
      align-items: center;
      grid-template-columns: 1.15fr 0.85fr;
    }
    .hero-content {
      display: flex;
      flex-direction: column;
    }
    .hero-title-script {
      font-family: var(--font-serif);
      font-size: var(--fs-hero-title);
      font-weight: 700;
      line-height: 1.2;
      color: var(--color-forest);
      margin-bottom: 0.5rem;
    }
    .highlight-script {
      font-family: var(--font-serif);
      font-style: italic;
      color: var(--color-gold);
      font-weight: 600;
    }
    .heart-inline {
      color: var(--color-gold);
      display: inline-block;
      margin-left: 4px;
      font-size: 0.8em;
    }
    .hero-divider-heart {
      display: flex;
      align-items: center;
      gap: 15px;
      margin: 1.25rem 0;
      max-width: 320px;
    }
    .hero-divider-heart::before, .hero-divider-heart::after {
      content: '';
      flex: 1;
      height: 1px;
      background-color: var(--color-border);
    }
    .hero-divider-heart .heart-icon {
      color: var(--color-gold);
      font-size: 1rem;
    }
    .hero-subtitle-hindi {
      font-size: var(--fs-lg);
      font-weight: 500;
      color: var(--color-charcoal-muted);
      line-height: 1.6;
      margin-bottom: 1.5rem;
    }
    .hero-callout-card {
      background: linear-gradient(135deg, #c5a059 0%, #b48e47 100%);
      color: var(--color-forest-dark);
      padding: 1.1rem 1.8rem;
      border-radius: var(--radius-md);
      box-shadow: 0 10px 25px rgba(197, 160, 89, 0.25);
      display: inline-block;
      margin-bottom: 2rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      max-width: 350px;
    }
    .hero-callout-card .callout-label {
      font-size: var(--fs-xs);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      font-weight: 700;
      margin: 0;
      line-height: 1.2;
      color: var(--color-forest-dark);
    }
    .hero-callout-card .callout-script {
      font-family: var(--font-serif);
      font-size: var(--fs-md);
      font-style: italic;
      margin: 4px 0 0 0;
      line-height: 1.2;
      font-weight: 700;
    }
    .hero-bottom-badges {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      align-items: center;
      border-top: 1px solid var(--color-border);
      padding-top: 1.75rem;
      margin-top: 2rem;
      width: 100%;
      gap: 1.5rem;
    }
    .hero-badge-item {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      color: var(--color-forest);
      font-weight: 600;
      font-size: var(--fs-xs);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      position: relative;
    }
    .hero-badge-item svg {
      color: var(--color-gold);
      flex-shrink: 0;
    }
    /* Add vertical dividers between grid items on desktop */
    .hero-badge-item:not(:last-child)::after {
      content: "";
      position: absolute;
      right: -0.75rem;
      top: 10%;
      height: 80%;
      width: 1px;
      background-color: var(--color-border);
    }

    /* Travel Circles Styles */
    .travel-circles-section {
      padding: var(--spacing-xl) 0;
      background-color: var(--bg-white);
      border-top: 1px solid var(--color-border);
      border-bottom: 1px solid var(--color-border);
    }
    .travel-styles-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: var(--spacing-md);
      margin-bottom: var(--spacing-xl);
    }
    .style-badge-card {
      background-color: var(--bg-warm);
      border: 1px solid var(--color-border);
      border-radius: var(--radius-md);
      padding: 1.5rem 1.25rem;
      text-align: center;
      transition: var(--transition-smooth);
    }
    .style-badge-card:hover {
      border-color: var(--color-terracotta);
      transform: translateY(-3px);
      box-shadow: 0 10px 20px var(--color-shadow);
    }
    .style-icon {
      font-size: 1.75rem;
      display: block;
      margin-bottom: 8px;
    }
    .style-badge-card h3 {
      font-family: var(--font-sans);
      font-size: var(--fs-sm);
      font-weight: 700;
      color: var(--color-forest);
      margin-bottom: 4px;
    }
    .style-badge-card p {
      font-size: var(--fs-xs);
      color: var(--color-charcoal-muted);
      line-height: 1.4;
    }
    .destinations-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: var(--spacing-lg);
    }
    .destination-card {
      background-color: var(--bg-warm);
      border: 1px solid var(--color-border);
      border-radius: var(--radius-lg);
      overflow: hidden;
      transition: var(--transition-smooth);
      display: flex;
      flex-direction: column;
    }
    .destination-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px var(--color-shadow-hover);
      border-color: var(--color-gold);
    }
    .dest-image-placeholder {
      height: 180px;
      background-color: var(--color-forest-light);
      position: relative;
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .dest-image-placeholder::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.4));
      z-index: 1;
    }
    .dest-image-inner-text {
      position: relative;
      z-index: 2;
      color: var(--bg-warm);
      font-family: var(--font-serif);
      font-style: italic;
      font-size: var(--fs-xl);
      text-shadow: 0 2px 4px rgba(0,0,0,0.6);
      font-weight: 700;
    }
    .dest-tag {
      position: absolute;
      top: 15px;
      left: 15px;
      background-color: var(--color-gold);
      color: var(--color-forest-dark);
      font-size: 0.65rem;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 50px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      z-index: 3;
    }
    .dest-content {
      padding: var(--spacing-md);
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }
    .dest-content h3 {
      font-size: var(--fs-lg);
      color: var(--color-forest);
      margin-bottom: 8px;
    }
    .dest-content p {
      font-size: var(--fs-sm);
      color: var(--color-charcoal-muted);
      line-height: 1.5;
      margin-bottom: 1.25rem;
      flex-grow: 1;
    }
    .dest-link {
      text-decoration: none;
      font-weight: 700;
      font-size: var(--fs-sm);
      color: var(--color-terracotta);
      transition: var(--transition-fast);
      display: inline-flex;
      align-items: center;
      gap: 4px;
      margin-top: auto;
    }
    .dest-link:hover {
      color: var(--color-terracotta-dark);
      padding-left: 4px;
    }

    /* Lead Capture Form High Contrast Accessibility Fixes */
    .cta-form .form-group input,
    .cta-form .form-group select {
      background-color: #FFFFFF !important;
      border: 1.5px solid var(--color-charcoal) !important;
      color: var(--color-charcoal) !important;
      font-weight: 600 !important;
      opacity: 1 !important;
      width: 100% !important;
      height: 48px !important;
      padding: 10px 16px !important;
      border-radius: var(--radius-md) !important;
      font-size: var(--fs-sm) !important;
      box-sizing: border-box !important;
    }
    .cta-form .form-group input::placeholder {
      color: rgba(45, 45, 45, 0.7) !important;
      opacity: 1 !important;
    }
    .cta-form .form-group select {
      color: rgba(45, 45, 45, 0.7) !important; /* Default unselected placeholder color */
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%232D2D2D' d='M6 8.825L1.175 4 2.59 2.59 6 6l3.41-3.41L10.825 4z'/%3E%3C/svg%3E") !important;
      background-repeat: no-repeat !important;
      background-position: right 16px center !important;
      background-size: 12px !important;
      cursor: pointer;
    }
    .cta-form .form-group select.value-selected {
      color: var(--color-charcoal) !important;
    }
    .cta-form .form-group select option {
      color: var(--color-charcoal) !important;
      background-color: #FFFFFF !important;
    }
    .cta-form .form-group input:focus,
    .cta-form .form-group select:focus {
      border-color: var(--color-forest) !important;
      box-shadow: 0 0 0 4px rgba(27, 59, 43, 0.25) !important;
      background-color: #FFFFFF !important;
      outline: none !important;
    }

    .cta-form .error-msg {
      color: #FFD2D2 !important; /* accessible error message on orange background */
    }
    .cta-form .form-row {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      margin-bottom: 15px;
    }
    .cta-form .form-group {
      margin-bottom: 0;
    }

    @media (max-width: 992px) {
      .travel-styles-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .destinations-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      .hero-bottom-badges {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem 2rem;
      }
      .hero-badge-item {
        justify-content: flex-start;
      }
      /* Remove desktop dividers */
      .hero-badge-item::after {
        display: none !important;
      }
      /* Add vertical dividers between column 1 and column 2 on tablet */
      .hero-badge-item:nth-child(odd)::after {
        content: "";
        position: absolute;
        right: -1rem;
        top: 10%;
        height: 80%;
        width: 1px;
        display: block;
        background-color: var(--color-border);
      }
      .cta-form .form-row {
        grid-template-columns: repeat(2, 1fr) !important;
      }
    }
    @media (max-width: 768px) {
      .hero-grid {
        grid-template-columns: 1fr;
      }
      .hero-bottom-badges {
        grid-template-columns: 1fr;
        gap: 12px;
      }
      .hero-badge-item {
        justify-content: flex-start;
      }
      .hero-badge-item::after {
        display: none !important;
      }
      .hero-title-script {
        font-size: var(--fs-xxl);
      }
      .cta-form .form-row {
        grid-template-columns: 1fr !important;
      }
    }
    @media (max-width: 576px) {
      .travel-styles-grid {
        grid-template-columns: 1fr;
      }
      .destinations-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>

  <!-- MAIN CONTENT -->
  <main id="main-content">

    <!-- SECTION 1: HERO SECTION -->
    <section class="hero-section" aria-label="<?php esc_attr_e( 'Welcome to SecondInnings50', 'secondinnings50' ); ?>">
      <div class="container grid hero-grid">
        <div class="hero-content">
          <span class="hero-badge"><?php esc_html_e( 'A Dignified Social Club', 'secondinnings50' ); ?></span>
          
          <!-- Large Copy matching Visual Reference image styling -->
          <h1 class="hero-title-script">
            <span class="screen-reader-text" style="position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); border: 0;"><?php esc_html_e( 'SecondInnings50 - Genuine Companionship & Shared Life Partnerships for 45+', 'secondinnings50' ); ?></span>
            <span class="highlight-script"><?php esc_html_e( 'Aapki Pasand,', 'secondinnings50' ); ?></span><br>
            <span class="highlight-script"><?php esc_html_e( 'Aapki Raftar,', 'secondinnings50' ); ?></span><br>
            <?php esc_html_e( 'Aapke Log.', 'secondinnings50' ); ?>
          </h1>

          <div class="hero-divider-heart">
            <span class="heart-icon">♥</span>
          </div>

          <p class="hero-subtitle-hindi">
            <?php esc_html_e( 'Connect based on age group, city, hobbies, and interests. Talk safely, build genuine friendships, meet up for local activities, and, if mutually comfortable, explore meaningful relationships.', 'secondinnings50' ); ?>
          </p>

          <!-- Gold callout card matching image badge -->
          <div class="hero-callout-card">
            <p class="callout-label"><?php esc_html_e( 'Aapki Second Innings', 'secondinnings50' ); ?></p>
            <p class="callout-script"><?php esc_html_e( 'Abhi Shuru Hui Hai.', 'secondinnings50' ); ?> <span class="heart-inline">♥</span></p>
          </div>

          <div class="hero-actions-group">
            <?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Apply to Join', 'secondinnings50' ); ?></a>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/join/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Join the Community', 'secondinnings50' ); ?></a>
            <?php endif; ?>
            <a href="#offerings" class="btn btn-secondary">
              <?php esc_html_e( 'Explore Circles', 'secondinnings50' ); ?>
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
          </div>
        </div>
        
        <div class="hero-visual">
          <div class="visual-wrapper">
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/hero1.png" alt="<?php esc_attr_e( 'Dil se Dosti: Loving mature Indian couple sitting on a park bench, sharing stories and smiling warmly.', 'secondinnings50' ); ?>" title="<?php esc_attr_e( 'SecondInnings50 Companionship & Shared Life Partnerships for 45+', 'secondinnings50' ); ?>" class="hero-img">
            <!-- Floating Trust Badge -->
            <div class="floating-badge text-card">
              <div class="badge-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M9 12L11 14L15 10M12 3L4 6V11C4 16.55 7.42 21.74 12 23C16.58 21.74 20 16.55 20 11V6L12 3Z" stroke="var(--color-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <div class="badge-content">
                <span class="badge-title"><?php esc_html_e( 'Safe & Exclusive', 'secondinnings50' ); ?></span>
                <span class="badge-desc"><?php esc_html_e( 'Strictly verified membership', 'secondinnings50' ); ?></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Bottom Row Badges matching the image footer icons -->
        <div class="hero-bottom-badges">
          <div class="hero-badge-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M23 21V19C22.9986 18.1841 22.7285 17.3916 22.23 16.74C21.7314 16.0884 21.0315 15.613 20.23 15.38" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M16 3.13C16.8052 3.35028 17.5097 3.82424 18.0122 4.48206C18.5148 5.13988 18.7887 5.94098 18.7887 6.765" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span><?php esc_html_e( 'Meaningful Connections', 'secondinnings50' ); ?></span>
          </div>
          <div class="hero-badge-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M21 15C21 15.5304 20.7893 16.0391 20.4142 16.4142C20.0391 16.7893 19.5304 17 19 17H7L3 21V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span><?php esc_html_e( 'Friendly Conversations', 'secondinnings50' ); ?></span>
          </div>
          <div class="hero-badge-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M12 22C12 22 20 18 20 12C20 9.5 18 7.5 15.5 7.5C14 7.5 12.5 8.5 12 10C11.5 8.5 10 7.5 8.5 7.5C6 7.5 4 9.5 4 12C4 18 12 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M12 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            <span><?php esc_html_e( 'Hobby Groups', 'secondinnings50' ); ?></span>
          </div>
          <div class="hero-badge-item">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
              <path d="M12 6V12L16 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span><?php esc_html_e( 'Local Meetups', 'secondinnings50' ); ?></span>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 2: DIL SE DOSTI / FRIENDSHIP BLOCK -->
    <section class="empathy-section" id="about" aria-label="<?php esc_attr_e( 'Our Philosophy', 'secondinnings50' ); ?>">
      <div class="container">
        <div class="section-header text-center">
          <span class="section-tag"><?php esc_html_e( 'Dil se Dosti', 'secondinnings50' ); ?></span>
          <h2 class="section-title"><?php esc_html_e( 'Har rishta pyaar se nahi... kuch rishte samajh se bante hain.', 'secondinnings50' ); ?></h2>
          <p class="section-subtitle">
            <?php esc_html_e( 'Jahan 45+ mature adults samman, samajh aur meaningful companionship ki talash mein judte hain. Umar ke is padaav par dil ko kisi dikhave ki nahi, balki aise insaan ki zaroorat hoti hai jo aapki baat samjhe, aapki bhaavno ka samman kare aur aapke saath hone ka ehsaas de.', 'secondinnings50' ); ?>
          </p>
        </div>

        <div class="grid grid-3col empathy-grid">
          <!-- Column 1: Safe & Verified Profiles -->
          <div class="card empathy-card">
            <div class="card-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="var(--color-terracotta)" stroke-width="2"/>
                <path d="M9 12L11 14L15 10" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <h3 class="card-title"><?php esc_html_e( 'Safe & Verified Profiles', 'secondinnings50' ); ?></h3>
            <p class="card-description">
              <?php esc_html_e( 'Every member is vetted through secure verification. Connect with peace of mind knowing the community is spam-free, authentic, and safe.', 'secondinnings50' ); ?>
            </p>
          </div>

          <!-- Column 2: Interest-Based Circles -->
          <div class="card empathy-card">
            <div class="card-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M23 21V19C22.9986 18.1841 22.7285 17.3916 22.23 16.74C21.7314 16.0884 21.0315 15.613 20.23 15.38" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round"/>
                <path d="M16 3.13C16.8052 3.35028 17.5097 3.82424 18.0122 4.48206C18.5148 5.13988 18.7887 5.94098 18.7887 6.765C18.7887 7.58902 18.5148 8.39012 18.0122 9.04794C17.5097 9.70576 16.8052 10.1797 16 10.4" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <h3 class="card-title"><?php esc_html_e( 'Interest-Based Circles', 'secondinnings50' ); ?></h3>
            <p class="card-description">
              <?php esc_html_e( 'Find friends who share your passion. Whether it\'s morning walks, old Bollywood classics, gardening, or finance, we have a Circle for you.', 'secondinnings50' ); ?>
            </p>
          </div>

          <!-- Column 3: Meaningful Connections -->
          <div class="card empathy-card">
            <div class="card-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M21 15C21 15.5304 20.7893 16.0391 20.4142 16.4142C20.0391 16.7893 19.5304 17 19 17H7L3 21V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V15Z" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <h3 class="card-title"><?php esc_html_e( 'Meaningful Connections', 'secondinnings50' ); ?></h3>
            <p class="card-description">
              <?php esc_html_e( 'No superficial swiping. Participate in moderated discussions and interactive storytelling formats designed to trigger deep bonds and long-term companionship.', 'secondinnings50' ); ?>
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 3: SACHA SAATH / COMPANIONSHIP -->
    <section class="companion-section" aria-label="<?php esc_attr_e( 'Sacha Saath - Companionship', 'secondinnings50' ); ?>">
      <div class="container grid grid-2col companion-grid">
        <div class="companion-content">
          <span class="section-tag"><?php esc_html_e( 'Sacha Saath', 'secondinnings50' ); ?></span>
          <h2 class="section-title"><?php esc_html_e( 'Is umr mein pyaar se zyada... saath nibhane wala haath ki keemat hoti hai.', 'secondinnings50' ); ?></h2>
          <p class="companion-text">
            <?php esc_html_e( "Zindagi ke is padaav par, jahan samajh ho, samman ho aur ek sachha saath ho, wahi rishte sabse khoobsurat hote hain. SecondInnings50 provides a respectful, vetted space where mature adults meet, talk, and share life's serene, joyful moments. We match you with refined peers who share your values, pace, and interests so you are never alone on this beautiful journey.", 'secondinnings50' ); ?>
          </p>
          <div class="companion-bullet-grid">
            <div class="companion-bullet">
              <span class="bullet-gold-dot">✦</span>
              <p><strong><?php esc_html_e( 'Dignified Partnerships:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'Find refined companions who value mutual respect, clear understanding, and long-term friendship.', 'secondinnings50' ); ?></p>
            </div>
            <div class="companion-bullet">
              <span class="bullet-gold-dot">✦</span>
              <p><strong><?php esc_html_e( 'Joyful Shared Moments:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'Build serene memories by meeting for quiet walks, retro music sessions, city hobby meetups, or pleasant chats.', 'secondinnings50' ); ?></p>
            </div>
            <div class="companion-bullet">
              <span class="bullet-gold-dot">✦</span>
              <p><strong><?php esc_html_e( 'Privacy & Honor:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'A completely secure environment where your boundary rules and profiles are treated with absolute dignity.', 'secondinnings50' ); ?></p>
            </div>
          </div>
          <div class="companion-cta-wrapper">
            <?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Apply to Join', 'secondinnings50' ); ?></a>
            <?php endif; ?>
          </div>
        </div>
        
        <div class="companion-visual-card">
          <div class="visual-wrapper" style="border: 2px solid var(--color-border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 15px 35px var(--color-shadow); height: 100%;">
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/hero2.png" alt="<?php esc_attr_e( 'Sacha Saath: Serene companionship, walking hand-in-hand together.', 'secondinnings50' ); ?>" style="width: 100%; height: 100%; min-height: 400px; object-fit: cover; display: block;">
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 4: WHATSAPP INTEREST CIRCLES -->
    <section class="whatsapp-circles-section" aria-label="<?php esc_attr_e( 'WhatsApp Interest Circles', 'secondinnings50' ); ?>">
      <div class="container">
        <div class="grid grid-2col apni-baatein-showcase" style="align-items: center; gap: var(--spacing-lg); margin-bottom: var(--spacing-xl);">
          <div class="apni-baatein-content">
            <span class="section-tag"><?php esc_html_e( 'Chai Chats', 'secondinnings50' ); ?></span>
            <h2 class="section-title" style="text-align: left; margin-bottom: 15px;"><?php esc_html_e( 'Na Judge Karenge... Bas Dil Se Sunenge.', 'secondinnings50' ); ?></h2>
            <p class="section-subtitle" style="text-align: left; max-width: 100%; margin-bottom: 25px;">
              <?php esc_html_e( 'Zindagi ke is padaav par kabhi-kabhi sirf ek achhi baatcheet hi kaafi hoti hai. Phone friendship also available. Aaiye, naye doston se judiye aur apni baat khulkar kahiye.', 'secondinnings50' ); ?>
            </p>
            
            <!-- Safety Badges -->
            <div class="safety-badges-horizontal" style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 25px;">
              <div class="badge-item" style="display: flex; align-items: center; gap: 10px; font-weight: 700; color: var(--color-forest-dark); font-size: var(--fs-sm);">
                <span style="font-size: 20px;">🔒</span> <?php esc_html_e( 'Confidential Conversations', 'secondinnings50' ); ?>
              </div>
              <div class="badge-item" style="display: flex; align-items: center; gap: 10px; font-weight: 700; color: #C05C3E; font-size: var(--fs-sm);">
                <span style="font-size: 20px;">🚫</span> <?php esc_html_e( 'No Vulgar Talk', 'secondinnings50' ); ?>
              </div>
              <div class="badge-item" style="display: flex; align-items: center; gap: 10px; font-weight: 700; color: #C05C3E; font-size: var(--fs-sm);">
                <span style="font-size: 20px;">💸</span> <?php esc_html_e( 'No Money Exchange', 'secondinnings50' ); ?>
              </div>
            </div>
          </div>
          <div class="apni-baatein-visual" style="border: 2px solid var(--color-border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 15px 35px var(--color-shadow); height: 100%;">
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/hero3.png" alt="<?php esc_attr_e( 'Apni Baatein: Sharing deep, heartfelt conversations with a trusted friend.', 'secondinnings50' ); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block; min-height: 350px;">
          </div>
        </div>

        <div style="text-align: center; margin-bottom: var(--spacing-md);">
          <h3 style="font-size: var(--fs-lg); color: var(--color-forest); font-weight: 700; margin-bottom: 10px;"><?php esc_html_e( 'Curated WhatsApp Interest Circles', 'secondinnings50' ); ?></h3>
          <p style="font-size: var(--fs-sm); color: var(--color-charcoal-muted); margin-bottom: 30px;"><?php esc_html_e( 'Approved premium members gain direct entry into our private, human-moderated WhatsApp Interest Circles to interact with peers locally and online.', 'secondinnings50' ); ?></p>
        </div>

        <div class="grid grid-3col whatsapp-grid">
          <!-- Card 1: Age-Wise Groups -->
          <div class="card whatsapp-card">
            <div class="whatsapp-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="12" cy="12" r="10" stroke="var(--color-terracotta)" stroke-width="2"/>
                <path d="M12 6V12L16 14" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <h3 class="whatsapp-card-title"><?php esc_html_e( 'Age-Wise Circles', 'secondinnings50' ); ?></h3>
            <p class="whatsapp-card-desc">
              <?php esc_html_e( 'Interact in dedicated sub-groups tailored by age (e.g. 45-50, 50-55, 55-60, and 60+) so conversations remain relevant and closely match your life stage.', 'secondinnings50' ); ?>
            </p>
          </div>

          <!-- Card 2: City-Wise Groups -->
          <div class="card whatsapp-card">
            <div class="whatsapp-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2Z" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="9" r="3" stroke="var(--color-terracotta)" stroke-width="2"/>
              </svg>
            </div>
            <h3 class="whatsapp-card-title"><?php esc_html_e( 'City & Local Circles', 'secondinnings50' ); ?></h3>
            <p class="whatsapp-card-desc">
              <?php esc_html_e( 'Connect with peers residing in your city (Mumbai, Delhi-NCR, Bangalore, Pune, etc.) to exchange local updates and coordinate weekend meetups.', 'secondinnings50' ); ?>
            </p>
          </div>

          <!-- Card 3: Hobby-Wise Groups -->
          <div class="card whatsapp-card">
            <div class="whatsapp-icon-box">
              <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12 22C12 22 20 18 20 12C20 9.5 18 7.5 15.5 7.5C14 7.5 12.5 8.5 12 10C11.5 8.5 10 7.5 8.5 7.5C6 7.5 4 9.5 4 12C4 18 12 22 12 22Z" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round"/>
                <path d="M12 2V6" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <h3 class="whatsapp-card-title"><?php esc_html_e( 'Interest & Hobby Circles', 'secondinnings50' ); ?></h3>
            <p class="whatsapp-card-desc">
              <?php esc_html_e( 'Share books and poetry, gardening tips, recipes, creative writing, or investment insights in focused spaces dedicated exclusively to your passions.', 'secondinnings50' ); ?>
            </p>
          </div>
        </div>
        
        <div class="whatsapp-footer-note text-center">
          <p>
            <span class="lock-icon">🔒</span> <strong><?php esc_html_e( 'Safety First:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'All groups are strictly moderated by community admins. Phone numbers are kept confidential and are only visible inside vetted groups.', 'secondinnings50' ); ?>
          </p>
        </div>
      </div>
    </section>

    <!-- SECTION 5: COMMUNITY MEETUPS & INTEREST GROUPS -->
    <section class="travel-circles-section" id="travel-circles" aria-label="<?php esc_attr_e( 'Community Meetups and Interest Groups', 'secondinnings50' ); ?>">
      <div class="container">
        
        <!-- Featured Image & Text Layout -->
        <div class="grid grid-2col community-showcase" style="align-items: center; gap: var(--spacing-lg); margin-bottom: var(--spacing-xl);">
          <div class="community-visual" style="border: 2px solid var(--color-border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: 0 15px 35px var(--color-shadow); height: 100%;">
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/hero4.jpg" alt="<?php esc_attr_e( 'Community Circles: Active mature couple enjoying a motorcycle road trip together.', 'secondinnings50' ); ?>" style="width: 100%; height: 100%; min-height: 380px; object-fit: cover; display: block;">
          </div>
          <div class="community-intro">
            <span class="section-tag"><?php esc_html_e( 'Community Circles', 'secondinnings50' ); ?></span>
            <h2 class="section-title" style="text-align: left; margin-bottom: 15px;"><?php esc_html_e( 'Community Meetups & Interest Groups', 'secondinnings50' ); ?></h2>
            <p class="section-subtitle" style="text-align: left; max-width: 100%; font-style: italic; color: var(--color-terracotta-dark); font-weight: 700; margin-bottom: 15px;">
              "<?php esc_html_e( 'Kuch safar manzil se nahi... saath se khoobsurat bante hain.', 'secondinnings50' ); ?>"
            </p>
            <p class="section-subtitle" style="text-align: left; max-width: 100%; margin-bottom: 25px;">
              <?php esc_html_e( 'Join our city-wise WhatsApp groups for offline chai meetups, hobbies, and shared activities. Safe, verified, and friendly circles designed for active mature adults.', 'secondinnings50' ); ?>
            </p>
            <div class="companion-cta-wrapper">
              <?php if ( is_user_logged_in() ) : ?>
                  <a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Join a Circle', 'secondinnings50' ); ?></a>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Interest Theme Badges -->
        <div class="travel-styles-grid" style="margin-bottom: var(--spacing-xl);">
          <div class="style-badge-card">
            <span class="style-icon">☕</span>
            <h3><?php esc_html_e( 'City Chai Meetups', 'secondinnings50' ); ?></h3>
            <p><?php esc_html_e( 'Relaxed, local weekend tea chats with verified peers.', 'secondinnings50' ); ?></p>
          </div>
          <div class="style-badge-card">
            <span class="style-icon">🎨</span>
            <h3><?php esc_html_e( 'Hobby & Art', 'secondinnings50' ); ?></h3>
            <p><?php esc_html_e( 'Sharing painting, gardening, and creative pursuits.', 'secondinnings50' ); ?></p>
          </div>
          <div class="style-badge-card">
            <span class="style-icon">🧘</span>
            <h3><?php esc_html_e( 'Wellness & Walks', 'secondinnings50' ); ?></h3>
            <p><?php esc_html_e( 'Healthy living routines and peaceful walking clubs.', 'secondinnings50' ); ?></p>
          </div>
          <div class="style-badge-card">
            <span class="style-icon">🎵</span>
            <h3><?php esc_html_e( 'Classic Music', 'secondinnings50' ); ?></h3>
            <p><?php esc_html_e( 'Reliving old golden Bollywood classics and evergreen melodies.', 'secondinnings50' ); ?></p>
          </div>
        </div>

        <!-- Regional Community Circles -->
        <div class="destinations-grid">
          <!-- Mumbai -->
          <div class="destination-card">
            <div class="dest-image-placeholder" style="background-color: #5D4037;">
              <span class="dest-tag"><?php esc_html_e( 'City Circle', 'secondinnings50' ); ?></span>
              <span class="dest-image-inner-text"><?php esc_html_e( 'Mumbai Circle', 'secondinnings50' ); ?></span>
            </div>
            <div class="dest-content">
              <h3><?php esc_html_e( 'Mumbai Chai Meetups', 'secondinnings50' ); ?></h3>
              <p><?php esc_html_e( 'Coordinate pleasant weekend meetups at Carter Road, shared walks in local parks, and lively offline discussions over hot cutting chai.', 'secondinnings50' ); ?></p>
              <?php if ( is_user_logged_in() ) : ?>
                  <a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" class="dest-link">
                    <?php esc_html_e( 'Join Community Circle', 'secondinnings50' ); ?>
                    <span>→</span>
                  </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- Delhi -->
          <div class="destination-card">
            <div class="dest-image-placeholder" style="background-color: #00796B;">
              <span class="dest-tag"><?php esc_html_e( 'City Circle', 'secondinnings50' ); ?></span>
              <span class="dest-image-inner-text"><?php esc_html_e( 'Delhi-NCR Circle', 'secondinnings50' ); ?></span>
            </div>
            <div class="dest-content">
              <h3><?php esc_html_e( 'Delhi-NCR Socials', 'secondinnings50' ); ?></h3>
              <p><?php esc_html_e( 'Serene morning meetups at Lodhi Gardens, shared visits to heritage monuments, and engaging virtual interest chats on chilly winter evenings.', 'secondinnings50' ); ?></p>
              <?php if ( is_user_logged_in() ) : ?>
                  <a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" class="dest-link">
                    <?php esc_html_e( 'Join Community Circle', 'secondinnings50' ); ?>
                    <span>→</span>
                  </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- Bangalore -->
          <div class="destination-card">
            <div class="dest-image-placeholder" style="background-color: #E64A19;">
              <span class="dest-tag"><?php esc_html_e( 'City Circle', 'secondinnings50' ); ?></span>
              <span class="dest-image-inner-text"><?php esc_html_e( 'Bangalore Circle', 'secondinnings50' ); ?></span>
            </div>
            <div class="dest-content">
              <h3><?php esc_html_e( 'Bangalore Activity Club', 'secondinnings50' ); ?></h3>
              <p><?php esc_html_e( 'Interact with local peers for pleasant morning strolls in Cubbon Park, exchange gardening tips, and enjoy freshly brewed filter coffee chats.', 'secondinnings50' ); ?></p>
              <?php if ( is_user_logged_in() ) : ?>
                  <a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" class="dest-link">
                    <?php esc_html_e( 'Join Community Circle', 'secondinnings50' ); ?>
                    <span>→</span>
                  </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- Pune -->
          <div class="destination-card">
            <div class="dest-image-placeholder" style="background-color: #3E2723;">
              <span class="dest-tag"><?php esc_html_e( 'City Circle', 'secondinnings50' ); ?></span>
              <span class="dest-image-inner-text"><?php esc_html_e( 'Pune Circle', 'secondinnings50' ); ?></span>
            </div>
            <div class="dest-content">
              <h3><?php esc_html_e( 'Pune Senior Socials', 'secondinnings50' ); ?></h3>
              <p><?php esc_html_e( 'Meetups for local cultural talks, weekend strolls, sharing retro Hindi film lyrics, and building deep, face-to-face friendships.', 'secondinnings50' ); ?></p>
              <?php if ( is_user_logged_in() ) : ?>
                  <a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" class="dest-link">
                    <?php esc_html_e( 'Join Community Circle', 'secondinnings50' ); ?>
                    <span>→</span>
                  </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- Book & Poetry -->
          <div class="destination-card">
            <div class="dest-image-placeholder" style="background-color: #F57C00;">
              <span class="dest-tag"><?php esc_html_e( 'Hobby Circle', 'secondinnings50' ); ?></span>
              <span class="dest-image-inner-text"><?php esc_html_e( 'Literature & Poetry', 'secondinnings50' ); ?></span>
            </div>
            <div class="dest-content">
              <h3><?php esc_html_e( 'Literature & Ghazal Circle', 'secondinnings50' ); ?></h3>
              <p><?php esc_html_e( 'Share evergreen ghazals, read books together, discuss timeless literature, and participate in friendly virtual reading meetups.', 'secondinnings50' ); ?></p>
              <?php if ( is_user_logged_in() ) : ?>
                  <a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" class="dest-link">
                    <?php esc_html_e( 'Join Community Circle', 'secondinnings50' ); ?>
                    <span>→</span>
                  </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- Wellness -->
          <div class="destination-card">
            <div class="dest-image-placeholder" style="background-color: #0288D1;">
              <span class="dest-tag"><?php esc_html_e( 'Wellness Circle', 'secondinnings50' ); ?></span>
              <span class="dest-image-inner-text"><?php esc_html_e( 'Hobby & Wellness', 'secondinnings50' ); ?></span>
            </div>
            <div class="dest-content">
              <h3><?php esc_html_e( 'Hobby & Healthy Living', 'secondinnings50' ); ?></h3>
              <p><?php esc_html_e( 'A warm space to share gardening updates, cooking recipes, yoga habits, and smartphone photography tricks with active seniors.', 'secondinnings50' ); ?></p>
              <?php if ( is_user_logged_in() ) : ?>
                  <a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" class="dest-link">
                    <?php esc_html_e( 'Join Community Circle', 'secondinnings50' ); ?>
                    <span>→</span>
                  </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 6: KEY OFFERINGS / WHAT YOU CAN DO -->
    <section class="offerings-section" id="offerings" aria-label="<?php esc_attr_e( 'Platform Offerings', 'secondinnings50' ); ?>">
      <div class="container">
        <div class="section-header text-center">
          <span class="section-tag"><?php esc_html_e( 'Engage & Grow', 'secondinnings50' ); ?></span>
          <h2 class="section-title"><?php esc_html_e( 'Discover What Awaits You at SecondInnings50', 'secondinnings50' ); ?></h2>
          <p class="section-subtitle">
            <?php esc_html_e( 'We provide structured, friendly online and offline formats so you can comfortably ease into the community without any awkwardness.', 'secondinnings50' ); ?>
          </p>
        </div>

        <div class="grid grid-2col offerings-grid">
          <!-- Card 1: Virtual Chai Chats -->
          <div class="card offering-card" tabindex="0" data-feature="chai">
            <div class="offering-img-overlay">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="offering-svg-icon" aria-hidden="true">
                <path d="M18 8H20C20.5304 8 21.0391 8.21071 21.4142 8.58579C21.7893 8.96086 22 9.46957 22 10V12C22 12.5304 21.7893 13.0391 21.4142 13.4142C21.0391 13.7893 20.5304 14 20 14H18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 8H18V14C18 15.0609 17.5786 16.0783 16.8284 16.8284C16.0783 17.5786 15.0609 18 14 18H6C4.93913 18 3.92172 17.5786 3.17157 16.8284C2.42143 16.0783 2 15.0609 2 14V8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6 2V4M10 2V4M14 2V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="offering-content">
              <h3 class="offering-title"><?php esc_html_e( 'Virtual Chai Chats', 'secondinnings50' ); ?></h3>
              <p class="offering-desc">
                <?php esc_html_e( 'Daily, lighthearted virtual discussion circles. Join a table of 5-6 peers, sip your afternoon tea, and chat about books, retro music, gardening, or life philosophies.', 'secondinnings50' ); ?>
              </p>
              <div class="offering-footer">
                <span class="offering-link-text"><?php esc_html_e( 'Learn More', 'secondinnings50' ); ?></span>
                <span class="arrow-icon">→</span>
              </div>
            </div>
          </div>

          <!-- Card 2: Local Meetups -->
          <div class="card offering-card" tabindex="0" data-feature="meetups">
            <div class="offering-img-overlay">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="offering-svg-icon" aria-hidden="true">
                <path d="M21 10C21 17 12 23 12 23C12 23 3 17 3 10C3 7.61305 3.94821 5.32387 5.63604 3.63604C7.32387 1.94821 9.61305 1 12 1C14.3869 1 16.6761 1.94821 18.364 3.63604C20.0518 5.32387 21 7.61305 21 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="offering-content">
              <h3 class="offering-title"><?php esc_html_e( 'Local Meetups', 'secondinnings50' ); ?></h3>
              <p class="offering-desc">
                <?php esc_html_e( 'Safe, carefully curated offline get-togethers in your city. From morning heritage walks and museum visits to calm Sunday brunches, feel the warmth of face-to-face connections.', 'secondinnings50' ); ?>
              </p>
              <div class="offering-footer">
                <span class="offering-link-text"><?php esc_html_e( 'Learn More', 'secondinnings50' ); ?></span>
                <span class="arrow-icon">→</span>
              </div>
            </div>
          </div>

          <!-- Card 3: Hobby Circles -->
          <div class="card offering-card" tabindex="0" data-feature="hobbies">
            <div class="offering-img-overlay">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="offering-svg-icon" aria-hidden="true">
                <path d="M12 22C12 22 20 18 20 12C20 9.5 18 7.5 15.5 7.5C14 7.5 12.5 8.5 12 10C11.5 8.5 10 7.5 8.5 7.5C6 7.5 4 9.5 4 12C4 18 12 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 2V6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="offering-content">
              <h3 class="offering-title"><?php esc_html_e( 'Hobby Circles', 'secondinnings50' ); ?></h3>
              <p class="offering-desc">
                <?php esc_html_e( 'Find partners for kitchen gardening, exchanging recipes, learning watercolor paintings, discussing stock market investing, or starting a community library together.', 'secondinnings50' ); ?>
              </p>
              <div class="offering-footer">
                <span class="offering-link-text"><?php esc_html_e( 'Learn More', 'secondinnings50' ); ?></span>
                <span class="arrow-icon">→</span>
              </div>
            </div>
          </div>

          <!-- Card 4: Lifelong Learning -->
          <div class="card offering-card" tabindex="0" data-feature="learning">
            <div class="offering-img-overlay">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="offering-svg-icon" aria-hidden="true">
                <path d="M22 10V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M22 6L12 11L2 6L12 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M6 10V15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="offering-content">
              <h3 class="offering-title"><?php esc_html_e( 'Lifelong Learning', 'secondinnings50' ); ?></h3>
              <p class="offering-desc">
                <?php esc_html_e( 'Age is just a number when it comes to curiosity. Attend interactive webinars and masterclasses covering digital banking, smartphone photography, yoga, health wellness, and writing.', 'secondinnings50' ); ?>
              </p>
              <div class="offering-footer">
                <span class="offering-link-text"><?php esc_html_e( 'Learn More', 'secondinnings50' ); ?></span>
                <span class="arrow-icon">→</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION 7: TRUST & SAFETY BENCHMARK -->
    <section class="safety-section" id="safety" aria-label="<?php esc_attr_e( 'Trust and Safety Measures', 'secondinnings50' ); ?>">
      <div class="container">
        <div class="safety-wrapper">
          <div class="safety-grid-content">
            <span class="safety-badge"><?php esc_html_e( 'Security & Dignity First', 'secondinnings50' ); ?></span>
            <h2 class="safety-title"><?php esc_html_e( 'Your Peace of Mind is Our Priority', 'secondinnings50' ); ?></h2>
            <p class="safety-text">
              <?php esc_html_e( "We understand that stepping into online communities requires trust. That's why SecondInnings50 is built like a gated neighborhood—thoughtfully guarded, highly secure, and deeply respectful.", 'secondinnings50' ); ?>
            </p>
            
            <div class="safety-bullets">
              <div class="safety-bullet-item">
                <div class="bullet-check">✓</div>
                <div>
                  <h3 class="bullet-title"><?php esc_html_e( 'Strict Member Verification', 'secondinnings50' ); ?></h3>
                  <p class="bullet-desc"><?php esc_html_e( 'We verify the identity of every applicant before admitting them into the community circles.', 'secondinnings50' ); ?></p>
                </div>
              </div>
              <div class="safety-bullet-item">
                <div class="bullet-check">✓</div>
                <div>
                  <h3 class="bullet-title"><?php esc_html_e( 'Zero-Tolerance Spam Policy', 'secondinnings50' ); ?></h3>
                  <p class="bullet-desc"><?php esc_html_e( 'No forward chain letters, advertising, or unsolicited promotional messages are allowed in discussion spaces.', 'secondinnings50' ); ?></p>
                </div>
              </div>
              <div class="safety-bullet-item">
                <div class="bullet-check">✓</div>
                <div>
                  <h3 class="bullet-title"><?php esc_html_e( 'Dedicated Safety Coordinators', 'secondinnings50' ); ?></h3>
                  <p class="bullet-desc"><?php esc_html_e( 'Our human hosts guide chats and events to ensure they remain respectful, comforting, and helpful.', 'secondinnings50' ); ?></p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="safety-stamp-container">
            <div class="safety-stamp-box">
              <div class="stamp-circle">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" fill="var(--color-terracotta)" stroke="var(--color-gold)" stroke-width="2"/>
                  <path d="M9 12L11 14L15 10" stroke="var(--color-white)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <h3 class="stamp-title"><?php esc_html_e( '100% Vetted Members', 'secondinnings50' ); ?></h3>
              <p class="stamp-text"><?php esc_html_e( 'Exclusive invitation-only entry system protecting privacy.', 'secondinnings50' ); ?></p>
            </div>
          </div>
        </div>
      </div>
    </section>



  <!-- Inline Modal for homepage Feature Cards (Task 4) -->
  <div id="si50-offering-modal" class="si50-front-modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="si50-offering-modal-title" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); z-index: 11000; align-items: center; justify-content: center; padding: 20px;">
    <div class="si50-front-modal-content" style="background: #FFFFFF; border: 3px solid #333333; border-radius: 12px; width: 100%; max-width: 600px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); position: relative; padding: 30px; animation: si50ModalScale 0.25s ease-out; text-align: left; box-sizing: border-box; max-height: 90vh; overflow-y: auto;">
      <button type="button" class="si50-front-modal-close" id="si50-offering-modal-close" aria-label="<?php esc_attr_e( 'Close details modal', 'secondinnings50' ); ?>" style="position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 28px; line-height: 1; cursor: pointer; color: #333333; padding: 5px; font-weight: bold;">&times;</button>
      
      <div id="si50-offering-modal-content-area">
        <!-- Content will be injected dynamically via JS -->
      </div>
    </div>
  </div>

  <style>
    @keyframes si50ModalScale {
      from { transform: scale(0.95); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    .si50-front-modal-backdrop {
      display: none;
    }
    .si50-front-modal-backdrop.show {
      display: flex !important;
    }
    .si50-modal-schedule-badge {
      display: inline-block;
      background-color: #E6F4EA;
      color: #137333;
      border: 2px solid #333333;
      padding: 6px 12px;
      font-size: 0.9rem;
      font-weight: 700;
      border-radius: 4px;
      margin-bottom: 20px;
    }
    .si50-modal-step-list {
      list-style: none;
      padding: 0;
      margin: 0 0 20px 0;
    }
    .si50-modal-step-item {
      display: flex;
      gap: 12px;
      margin-bottom: 12px;
      align-items: flex-start;
    }
    .si50-modal-step-num {
      width: 24px;
      height: 24px;
      background: #333333;
      color: #FFFFFF;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 0.85rem;
      flex-shrink: 0;
    }
    .si50-modal-step-text {
      font-size: 0.95rem;
      color: #333333;
      line-height: 1.4;
    }
  </style>

  </main>

<?php
get_footer();
