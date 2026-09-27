<?php
/**
 * Template Name: About Us Template
 *
 * @package SecondInnings50
 */

get_header();
?>

<main id="primary" class="site-main content-area">

  <!-- HERO/HEADER TITLE SECTION -->
  <section class="about-hero-section" aria-label="<?php esc_attr_e( 'About SecondInnings50', 'secondinnings50' ); ?>">
    <div class="container text-center">
      <span class="section-tag"><?php esc_html_e( 'Our Purpose', 'secondinnings50' ); ?></span>
      <h1 class="section-title"><?php esc_html_e( 'Who We Are', 'secondinnings50' ); ?></h1>
      <p class="section-subtitle">
        <?php esc_html_e( 'Building a dignified haven of understanding, respect, and belonging for India\'s mature generation.', 'secondinnings50' ); ?>
      </p>
    </div>
  </section>

  <!-- STORYTELLING SECTION -->
  <section class="about-story-section" aria-label="<?php esc_attr_e( 'Our Story', 'secondinnings50' ); ?>">
    <div class="container grid grid-2col about-story-grid">
      <!-- Story Content -->
      <div class="about-story-content">
        <h2 class="about-headline"><?php esc_html_e( 'समझ, सम्मान और अपनापन', 'secondinnings50' ); ?></h2>
        <p class="story-text">
          <?php esc_html_e( 'Life is a journey of chapters, and entering your second innings should be one of the most exciting. SecondInnings50 was born out of a simple realization: as life stabilizes, our need for authentic connections, intellectual conversations, and warm friendships only grows.', 'secondinnings50' ); ?>
        </p>
        <p class="story-text">
          <?php esc_html_e( 'We do not believe in superficial matching or clinical solutions. Instead, we are building a vibrant, exclusive social club where urban Indian adults over 40 and 50 can meet, share a cup of tea (chai), discuss books, classic music, garden, and rediscover the joy of companionship in a safe, gated space.', 'secondinnings50' ); ?>
        </p>
        <div class="story-cta-group">
          <a href="<?php echo esc_url( home_url( '/join/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Begin Your Journey', 'secondinnings50' ); ?></a>
        </div>
      </div>
      
      <!-- Visual Element / Narrative Frame -->
      <div class="about-story-visual">
        <div class="story-visual-wrapper">
          <div class="decorative-frame"></div>
          <div class="story-quote-card">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="quote-svg-icon" aria-hidden="true">
              <path d="M9.5 9.5C9.5 7.01472 7.48528 5 5 5C2.51472 5 0.5 7.01472 0.5 9.5C0.5 11.9853 2.51472 14 5 14H6C6 16.5 4.5 18.5 2.5 19L3.5 20.5C6.5 19.5 9.5 16.5 9.5 12.5V9.5Z" fill="var(--color-gold)"/>
              <path d="M23.5 9.5C23.5 7.01472 21.4853 5 19 5C16.5147 5 14.5 7.01472 14.5 9.5C14.5 11.9853 16.5147 14 19 14H20C20 16.5 18.5 18.5 16.5 19L17.5 20.5C20.5 19.5 23.5 16.5 23.5 12.5V9.5Z" fill="var(--color-gold)"/>
            </svg>
            <p class="quote-text">
              <?php esc_html_e( '“True companionship is not just about age; it is about shared understanding, respect, and the comfort of feeling at home with someone.”', 'secondinnings50' ); ?>
            </p>
            <span class="quote-author"><?php esc_html_e( '— The SecondInnings50 Philosophy', 'secondinnings50' ); ?></span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- BRAND PILLARS SECTION -->
  <section class="about-pillars-section" aria-label="<?php esc_attr_e( 'Our Core Pillars', 'secondinnings50' ); ?>">
    <div class="container">
      <div class="section-header text-center">
        <span class="section-tag"><?php esc_html_e( 'Our Core Footprint', 'secondinnings50' ); ?></span>
        <h2 class="section-title"><?php esc_html_e( 'The Pillars of Our Community', 'secondinnings50' ); ?></h2>
        <p class="section-subtitle">
          <?php esc_html_e( 'Every interaction and feature at SecondInnings50 is built on these four fundamental promises.', 'secondinnings50' ); ?>
        </p>
      </div>

      <div class="grid grid-4col about-pillars-grid">
        <!-- Pillar 1: Safe & Secure -->
        <div class="card pillar-card">
          <div class="pillar-icon-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M9 12L11 14L15 10" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <h3 class="pillar-title"><?php esc_html_e( 'Safe & Secure', 'secondinnings50' ); ?></h3>
          <p class="pillar-desc"><?php esc_html_e( 'Your privacy is our priority. Vetted membership profiles and gated verification ensure security.', 'secondinnings50' ); ?></p>
        </div>

        <!-- Pillar 2: Trusted Community -->
        <div class="card pillar-card">
          <div class="pillar-icon-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <h3 class="pillar-title"><?php esc_html_e( 'Trusted Community', 'secondinnings50' ); ?></h3>
          <p class="pillar-desc"><?php esc_html_e( 'For 45+ individuals seeking companionship. No spam, no noise. Just genuine connections.', 'secondinnings50' ); ?></p>
        </div>

        <!-- Pillar 3: Respectful Environment -->
        <div class="card pillar-card">
          <div class="pillar-icon-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M12 22C12 22 20 18 20 12C20 9.5 18 7.5 15.5 7.5C14 7.5 12.5 8.5 12 10C11.5 8.5 10 7.5 8.5 7.5C6 7.5 4 9.5 4 12C4 18 12 22 12 22Z" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <h3 class="pillar-title"><?php esc_html_e( 'Respectful Environment', 'secondinnings50' ); ?></h3>
          <p class="pillar-desc"><?php esc_html_e( 'Kindness is our culture. Our moderators maintain respect, comfort, and positive engagement.', 'secondinnings50' ); ?></p>
        </div>

        <!-- Pillar 4: Meaningful Connections -->
        <div class="card pillar-card">
          <div class="pillar-icon-box">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
              <path d="M21 15C21 15.5304 20.7893 16.0391 20.4142 16.4142C20.0391 16.7893 19.5304 17 19 17H7L3 21V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V15Z" stroke="var(--color-terracotta)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <h3 class="pillar-title"><?php esc_html_e( 'Meaningful Connections', 'secondinnings50' ); ?></h3>
          <p class="pillar-desc"><?php esc_html_e( 'Real conversations, real bonds. Interest circles and chai chats build lifelong friendships.', 'secondinnings50' ); ?></p>
        </div>
      </div>
    </div>
  </section>

</main>

<?php
get_footer();
