<?php
/**
 * Template Name: Join Onboarding Application
 *
 * @package SecondInnings50
 */

get_header();
?>

  <!-- EMBEDDED STYLES FOR PREMIUM ONBOARDING FORM -->
  <style>
    .join-section {
      padding: var(--spacing-xl) 0;
      background-color: var(--bg-warm);
    }
    .join-container {
      max-width: 850px;
      margin: 0 auto;
    }
    .join-header {
      margin-bottom: var(--spacing-xl);
    }
    .join-card {
      background-color: var(--bg-white);
      border: 1px solid var(--color-border);
      border-radius: var(--radius-lg);
      padding: var(--spacing-xl);
      box-shadow: 0 15px 35px var(--color-shadow);
    }
    fieldset.form-section-group {
      border: 1px solid var(--color-border);
      border-radius: var(--radius-md);
      padding: var(--spacing-lg) var(--spacing-md);
      margin-bottom: var(--spacing-lg);
      background-color: var(--bg-white);
    }
    fieldset.form-section-group legend {
      font-family: var(--font-serif);
      font-size: var(--fs-md);
      font-weight: 700;
      color: var(--color-forest);
      padding: 0 12px;
      margin-left: 10px;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    .form-group-full {
      margin-bottom: var(--spacing-md);
    }
    .form-group-full:last-child {
      margin-bottom: 0;
    }
    .women-incentive-banner {
      background-color: var(--color-gold-light);
      border: 1.5px solid var(--color-gold);
      border-radius: var(--radius-md);
      padding: var(--spacing-md);
      margin-bottom: var(--spacing-lg);
      display: flex;
      gap: 15px;
      align-items: flex-start;
      text-align: left !important;
    }
    .women-incentive-banner .banner-icon {
      font-size: 1.75rem;
      color: var(--color-gold-hover);
      line-height: 1;
      flex-shrink: 0;
      margin-top: -2px;
    }
    .women-incentive-banner .banner-content {
      font-size: var(--fs-sm);
      color: var(--color-forest-dark);
      line-height: 1.6;
      flex: 1;
      min-width: 0;
      text-align: left !important;
    }
    .women-incentive-banner .banner-content p {
      margin: 0 0 8px 0;
      text-align: left !important;
    }
    .women-incentive-banner .banner-content p:last-child {
      margin-bottom: 0;
    }
    .women-incentive-banner .banner-title {
      font-weight: 700;
      display: block;
      margin-bottom: 6px;
      color: var(--color-terracotta-dark);
      text-align: left !important;
    }
    .trust-signoff-box {
      background-color: var(--color-forest-light);
      border: 1px dashed var(--color-forest);
      border-radius: var(--radius-md);
      padding: var(--spacing-md);
      margin-bottom: var(--spacing-lg);
      font-size: var(--fs-sm);
      color: var(--color-forest-dark);
      line-height: 1.5;
      text-align: center;
    }
    .trust-signoff-box strong {
      color: var(--color-terracotta-dark);
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
      font-size: var(--fs-sm);
      font-weight: 700;
      color: var(--color-forest);
      margin-bottom: 8px;
      display: block;
    }

    /* CRITICAL UI/UX ACCESSIBILITY STYLING FIXES (45-60 Active Adults) */
    .form-control, .select-control, .textarea-control {
      background-color: #FFFFFF !important;
      border: 1.5px solid var(--color-charcoal) !important;
      color: var(--color-charcoal) !important;
      font-weight: 600 !important; /* clear legible font weight */
      opacity: 1 !important;
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
      padding-left: 0 !important; /* remove absolute padding offset */
      gap: 12px !important; /* space between indicator box and text */
      width: 100% !important;
      min-height: 24px;
      cursor: pointer;
      user-select: none;
    }
    .checkmark, .radiomark {
      position: relative !important; /* change to relative so it naturally sits in flex flow */
      display: inline-block !important;
      flex-shrink: 0 !important;
      height: 22px !important;
      width: 22px !important;
      border: 1.5px solid var(--color-charcoal) !important;
      background-color: #FFFFFF !important;
      margin-top: 2px !important; /* align with first line of text */
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
    /* Indicator tick/dot inside checkmark and radiomark when checked */
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
      flex: 1 !important; /* force text wrapper to occupy remaining space and wrap cleanly */
      word-break: break-word !important;
      color: var(--color-charcoal) !important;
      font-weight: 600 !important;
      padding-left: 0 !important;
      line-height: 1.5 !important;
    }
    
    @media (max-width: 768px) {
      .join-card {
        padding: var(--spacing-md);
      }
      .women-incentive-banner {
        padding: 12px 14px;
        gap: 8px;
      }
      .women-incentive-banner .banner-icon {
        font-size: 1.5rem;
      }
    }
    @media (max-width: 576px) {
      /* Responsive grids handled by auto-fill */
    }
  </style>

<main id="primary" class="site-main content-area">

  <section class="join-section" aria-label="<?php esc_attr_e( 'Request invitation to join SecondInnings50', 'secondinnings50' ); ?>">
    <div class="container">
      <div class="join-container">
        
        <!-- Header Text -->
        <div class="join-header text-center">
          <span class="section-tag"><?php esc_html_e( 'Invitation Only Onboarding', 'secondinnings50' ); ?></span>
          <h1 class="section-title"><?php esc_html_e( 'Secure Senior Onboarding Circle & Onboarding Form', 'secondinnings50' ); ?></h1>
          <p class="section-subtitle">
            <?php esc_html_e( 'Welcome to SecondInnings50. To protect our community of active adults aged 45–60 and maintain a safe, respectful environment, membership is by invitation only. Please complete your registration details below.', 'secondinnings50' ); ?>
          </p>
        </div>

        <!-- Application Form Card -->
        <div class="card join-card">

          <!-- Women Member Strategy UI Block -->
          <div class="women-incentive-banner" role="note" aria-label="<?php esc_attr_e( 'Membership Tier & Onboarding Policy', 'secondinnings50' ); ?>">
            <span class="banner-icon" aria-hidden="true">✨</span>
            <div class="banner-content">
              <span class="banner-title"><?php esc_html_e( 'Membership Fees & Policy', 'secondinnings50' ); ?></span>
              <p style="margin-bottom: 8px;"><strong><?php esc_html_e( 'One-Time Membership Fee:', 'secondinnings50' ); ?></strong> <?php esc_html_e( '₹699 only.', 'secondinnings50' ); ?></p>
              <p style="margin-bottom: 8px;">👩 <strong><?php esc_html_e( 'Female Membership:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'FREE.', 'secondinnings50' ); ?></p>
              <p style="color: var(--color-terracotta); font-weight: 700;">✅ <?php esc_html_e( 'No Hidden Charges. Fully Refundable if no matches found.', 'secondinnings50' ); ?></p>
            </div>
          </div>

          <form class="join-form" id="join-application-form" method="post" enctype="multipart/form-data" novalidate aria-label="<?php esc_attr_e( 'Join Onboarding Application Form', 'secondinnings50' ); ?>">
            <?php wp_nonce_field( 'si50_auth_nonce', 'security' ); ?>
            
            <!-- GROUP 1: PERSONAL IDENTITY -->
            <fieldset class="form-section-group">
              <legend><?php esc_html_e( 'Personal Identity', 'secondinnings50' ); ?></legend>
              
              <div class="form-row-2col">
                <div class="form-group">
                  <span class="form-group-section-title"><?php esc_html_e( 'Gender Identity', 'secondinnings50' ); ?> <span class="required">*</span></span>
                  <div class="gender-radio-grid">
                    <label class="radio-container">
                      <input type="radio" name="gender" value="Male" required aria-required="true">
                      <span class="radiomark"></span>
                      <span class="radio-label-text"><?php esc_html_e( 'Male', 'secondinnings50' ); ?></span>
                    </label>
                    <label class="radio-container">
                      <input type="radio" name="gender" value="Female">
                      <span class="radiomark"></span>
                      <span class="radio-label-text"><?php esc_html_e( 'Female', 'secondinnings50' ); ?></span>
                    </label>
                    <label class="radio-container">
                      <input type="radio" name="gender" value="Prefer Not to Say">
                      <span class="radiomark"></span>
                      <span class="radio-label-text"><?php esc_html_e( 'Prefer Not to Say', 'secondinnings50' ); ?></span>
                    </label>
                  </div>
                  <span class="error-msg" id="join-gender-error" aria-live="polite"></span>
                </div>

                <div class="form-group">
                  <label for="join-name" class="form-label"><?php esc_html_e( 'Full Name', 'secondinnings50' ); ?> <span class="required">*</span></label>
                  <input type="text" id="join-name" name="fullname" class="form-control" placeholder="<?php esc_attr_e( 'Your Full Name', 'secondinnings50' ); ?>" required aria-required="true">
                  <span class="error-msg" id="join-name-error" aria-live="polite"></span>
                </div>
              </div>

              <!-- Strategic Gender Policy Highlight Notice -->
              <div class="form-group-full" style="margin-top: 8px; margin-bottom: var(--spacing-md);">
                <div style="background-color: var(--color-gold-light); border: 1.5px solid var(--color-gold); border-radius: var(--radius-sm); padding: 12px 16px; font-size: var(--fs-xs); color: var(--color-forest-dark); line-height: 1.5; text-align: left;">
                  🎉 <strong><?php esc_html_e( 'Launch Phase Special:', 'secondinnings50' ); ?></strong> <?php esc_html_e( 'Complimentary premium verification & lifetime basic membership for the first 100 women members. Secure vetting applied.', 'secondinnings50' ); ?>
                </div>
              </div>

              <div class="form-row-2col">
                <div class="form-group">
                  <label for="join-age" class="form-label"><?php esc_html_e( 'Age', 'secondinnings50' ); ?> <span class="required">*</span></label>
                  <input type="text" inputmode="numeric" pattern="\d*" id="join-age" name="age" class="form-control" placeholder="<?php esc_attr_e( 'e.g. 53', 'secondinnings50' ); ?>" required aria-required="true" min="40" max="100">
                  <span class="error-msg" id="join-age-error" aria-live="polite"></span>
                </div>

                <div class="form-group">
                  <label for="join-marital" class="form-label"><?php esc_html_e( 'Marital Status', 'secondinnings50' ); ?> <span class="required">*</span></label>
                  <select id="join-marital" name="marital_status" class="form-control select-control" required aria-required="true">
                    <option value="" disabled selected><?php esc_html_e( 'Select Marital Status', 'secondinnings50' ); ?></option>
                    <option value="Single"><?php esc_html_e( 'Single', 'secondinnings50' ); ?></option>
                    <option value="Separated"><?php esc_html_e( 'Separated', 'secondinnings50' ); ?></option>
                    <option value="Divorce in Process / Awaiting Divorce"><?php esc_html_e( 'Divorce in Process / Awaiting Divorce', 'secondinnings50' ); ?></option>
                    <option value="Divorced"><?php esc_html_e( 'Divorced', 'secondinnings50' ); ?></option>
                    <option value="Widow/Widower"><?php esc_html_e( 'Widow/Widower', 'secondinnings50' ); ?></option>
                  </select>
                  <span class="error-msg" id="join-marital-error" aria-live="polite"></span>
                </div>
              </div>

              <div class="form-group-full" style="margin-bottom: var(--spacing-md);">
                <label for="join-occupation" class="form-label"><?php esc_html_e( 'Occupation / Profession', 'secondinnings50' ); ?> <span class="required">*</span></label>
                <input type="text" id="join-occupation" name="occupation" class="form-control" placeholder="<?php esc_attr_e( 'e.g. Retired Teacher', 'secondinnings50' ); ?>" required aria-required="true">
                <span class="error-msg" id="join-occupation-error" aria-live="polite"></span>
              </div>
            </fieldset>

            <!-- GROUP 2: SECURE CONTACT -->
            <fieldset class="form-section-group">
              <legend><?php esc_html_e( 'Secure Contact & Location', 'secondinnings50' ); ?></legend>
              
              <div class="form-row-2col">
                <div class="form-group">
                  <label for="join-email" class="form-label"><?php esc_html_e( 'Email Address', 'secondinnings50' ); ?> <span class="required">*</span></label>
                  <input type="email" id="join-email" name="email" class="form-control" placeholder="<?php esc_attr_e( 'e.g. anand@email.com', 'secondinnings50' ); ?>" required aria-required="true">
                  <span class="error-msg" id="join-email-error" aria-live="polite"></span>
                </div>
                
                <div class="form-group">
                  <label for="join-phone" class="form-label"><?php esc_html_e( 'WhatsApp Mobile Number', 'secondinnings50' ); ?> <span class="required">*</span></label>
                  <input type="tel" id="join-phone" name="phone" class="form-control" placeholder="<?php esc_attr_e( 'e.g. 9876543210', 'secondinnings50' ); ?>" required aria-required="true">
                  <span class="error-msg" id="join-phone-error" aria-live="polite"></span>
                  <!-- OTP Verification UI Slot (Disabled for v1) -->
                </div>
              </div>

              <div class="form-group-full">
                <label for="join-city" class="form-label"><?php esc_html_e( 'City & State', 'secondinnings50' ); ?> <span class="required">*</span></label>
                <input type="text" id="join-city" name="city_state" class="form-control" placeholder="<?php esc_attr_e( 'e.g. Pune, Maharashtra', 'secondinnings50' ); ?>" required aria-required="true">
                <span class="error-msg" id="join-city-error" aria-live="polite"></span>
              </div>

              <div class="form-group-full" style="margin-top: 15px;">
                <label for="join-location-pref" class="form-label"><?php esc_html_e( 'Location Match Preference', 'secondinnings50' ); ?> <span class="required">*</span></label>
                <select id="join-location-pref" name="location_preference" class="form-control select-control" required aria-required="true" style="border: 1.5px solid #333333 !important; background-color: #FFFFFF !important;">
                  <option value="" disabled selected><?php esc_html_e( 'Select location preference', 'secondinnings50' ); ?></option>
                  <option value="Same City"><?php esc_html_e( 'Same City', 'secondinnings50' ); ?></option>
                  <option value="Same State"><?php esc_html_e( 'Same State', 'secondinnings50' ); ?></option>
                  <option value="Anywhere in India"><?php esc_html_e( 'Anywhere in India', 'secondinnings50' ); ?></option>
                  <option value="Open to Relocation"><?php esc_html_e( 'Open to Relocation', 'secondinnings50' ); ?></option>
                </select>
                <span class="error-msg" id="join-location-pref-error" aria-live="polite"></span>
              </div>
              
              <div class="form-row-2col" style="margin-top: 16px;">
                <div class="form-group">
                  <label for="join-password" class="form-label"><?php esc_html_e( 'Choose Password', 'secondinnings50' ); ?> <span class="required">*</span></label>
                  <input type="password" id="join-password" name="password" class="form-control" placeholder="<?php esc_attr_e( 'Min 6 characters', 'secondinnings50' ); ?>" required aria-required="true" style="border: 1.5px solid #333333 !important; background-color: #FFFFFF !important;">
                  <span class="error-msg" id="join-password-error" aria-live="polite" style="color: var(--color-terracotta); font-size: var(--fs-xs); display: block; margin-top: 4px;"></span>
                </div>
                <div class="form-group">
                  <label for="join-password-confirm" class="form-label"><?php esc_html_e( 'Confirm Password', 'secondinnings50' ); ?> <span class="required">*</span></label>
                  <input type="password" id="join-password-confirm" name="password_confirm" class="form-control" placeholder="<?php esc_attr_e( 'Re-enter password', 'secondinnings50' ); ?>" required aria-required="true" style="border: 1.5px solid #333333 !important; background-color: #FFFFFF !important;">
                  <span class="error-msg" id="join-password-confirm-error" aria-live="polite" style="color: var(--color-terracotta); font-size: var(--fs-xs); display: block; margin-top: 4px;"></span>
                </div>
              </div>
            </fieldset>

            <!-- GROUP 3: MATCHING ENGINE & INTEREST FLAGS -->
            <fieldset class="form-section-group">
              <legend><?php esc_html_e( 'Companionship & Circles Matcher', 'secondinnings50' ); ?></legend>
              
              <div class="form-group-full" style="margin-bottom: 20px;">
                <span class="form-group-section-title"><?php esc_html_e( 'What are you looking for? (Select all that apply)', 'secondinnings50' ); ?> <span class="required">*</span></span>
                <div class="looking-for-checkbox-grid">
                  <label class="checkbox-container">
                    <input type="checkbox" name="looking_for[]" value="Friendship">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Friendship / New Friends', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="looking_for[]" value="Conversation Companion">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Conversation Companion', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="looking_for[]" value="Activity Companion">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Activity & Interest Companion', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="looking_for[]" value="Long-Term Companionship">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Long-Term Companionship', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="looking_for[]" value="Shared Life Partnership">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Shared Life Partnership', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="looking_for[]" value="Meaningful Companionship">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Meaningful Companionship', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="looking_for[]" value="Life Partner Exploration">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Life Partner Exploration', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="looking_for[]" value="Open to New Beginnings">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Open to a New Chapter in Life', 'secondinnings50' ); ?></span>
                  </label>
                </div>
              </div>

              <div class="form-group-full" style="margin-bottom: 20px;">
                <label for="join-connection-intent" class="form-label"><?php esc_html_e( 'What Kind of Connection Am I Looking For?', 'secondinnings50' ); ?> <span class="required">*</span></label>
                <textarea id="join-connection-intent" name="connection_intent" class="form-control textarea-control" rows="6" placeholder="<?php esc_attr_e( 'Freely describe what you are looking for (e.g., Friendship, Phone Friend, Travel Companion, Meaningful Companionship, Long-Term Connection, Marriage / Life Partner, etc.). You can write in English or Hindi.', 'secondinnings50' ); ?>" required aria-required="true"></textarea>
              </div>

              <div class="form-group-full" style="margin-top: 15px; margin-bottom: 15px;">
                <span class="form-group-section-title"><?php esc_html_e( 'Hobby & Interest Circles Registry (Select all that interest you)', 'secondinnings50' ); ?></span>
                <div class="checkbox-group-grid checkbox-3col" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 10px; margin-top: 6px;">
                  <label class="checkbox-container">
                    <input type="checkbox" name="hobbies_interests[]" value="Social Outings">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Social Outings & Meetups', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="hobbies_interests[]" value="Gardening">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Gardening', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="hobbies_interests[]" value="Books & Poetry">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Books & Poetry', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="hobbies_interests[]" value="Music">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Music', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="hobbies_interests[]" value="Spirituality">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Spirituality', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="hobbies_interests[]" value="Morning Walks">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Morning Walks', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="hobbies_interests[]" value="Health & Wellness">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Health & Wellness', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="hobbies_interests[]" value="Business & Finance">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Business & Finance', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="hobbies_interests[]" value="Cooking">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Cooking', 'secondinnings50' ); ?></span>
                  </label>
                </div>
              </div>

              <div class="form-group-full">
                <span class="form-group-section-title"><?php esc_html_e( 'Circles Onboarding Interest', 'secondinnings50' ); ?></span>
                <div class="circles-checkbox-grid">
                  <label class="checkbox-container">
                    <input type="checkbox" name="circles_interest[]" value="WhatsApp Group Interest">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'WhatsApp Group Interest', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="circles_interest[]" value="Local Meetup Interest">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Local Meetup Interest', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="circles_interest[]" value="Activity Meetup Interest">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Activity Meetup Interest', 'secondinnings50' ); ?></span>
                  </label>
                </div>
              </div>
            </fieldset>

            <!-- GROUP 4: TRAVEL MATRIX SELECTION (Bypassed/Hidden for positioning alignment) -->
            <fieldset class="form-section-group" style="display: none;">
              <legend><?php esc_html_e( 'Travel Profile & Matrix', 'secondinnings50' ); ?></legend>
              
              <div class="form-group-full" style="margin-bottom: 20px;">
                <span class="form-group-section-title"><?php esc_html_e( 'Teerth Yatra & Leisure Destinations (Select interested)', 'secondinnings50' ); ?></span>
                <div class="travel-destinations-grid">
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Ayodhya">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Ayodhya Dham', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Haridwar & Rishikesh">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Haridwar & Rishikesh', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Vaishno Devi">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Vaishno Devi', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Amritsar">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Amritsar (Golden Temple)', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Khatu Shyam Ji">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Khatu Shyam Ji', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Salasar Balaji">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Salasar Balaji', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Varanasi">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Varanasi Kashi', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Ujjain Mahakal">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Ujjain Mahakal', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Tirupati Balaji">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Tirupati Balaji', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Jagannath Puri">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Jagannath Puri', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Jaipur & Udaipur">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Jaipur & Udaipur', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Shimla & Manali">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Shimla & Manali', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Kashmir">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Kashmir Valley', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Kerala">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Kerala Backwaters', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="travel_destinations[]" value="Goa">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Goa Beaches', 'secondinnings50' ); ?></span>
                  </label>
                  <div class="other-destination-wrapper" style="grid-column: 1 / -1; display: flex; align-items: center; flex-wrap: wrap; gap: 10px; width: 100%;">
                    <label class="checkbox-container" style="width: auto !important; display: inline-flex !important; align-items: center; margin-bottom: 0;">
                      <input type="checkbox" id="join-travel-dest-other-cb" name="travel_destinations[]" value="Other">
                      <span class="checkmark"></span>
                      <span class="checkbox-label-text" style="flex: 0 0 auto !important; margin-right: 5px;"><?php esc_html_e( 'Other:', 'secondinnings50' ); ?></span>
                    </label>
                    <input type="text" id="join-travel-dest-other-text" name="travel_destinations_other" class="form-control" placeholder="<?php esc_attr_e( 'Enter other destination details', 'secondinnings50' ); ?>" style="flex: 1 1 200px !important; min-width: 150px; height: 36px; font-size: 14px; padding: 6px 12px; border: 1.5px solid #333333 !important; background-color: #FFFFFF !important;">
                  </div>
                </div>
              </div>

              <div class="form-row-2col">
                <div class="form-group">
                  <span class="form-group-section-title"><?php esc_html_e( 'Preferred Travel Style', 'secondinnings50' ); ?></span>
                  <div class="travel-styles-grid">
                    <label class="checkbox-container">
                      <input type="checkbox" name="travel_styles[]" value="Spiritual Travel">
                      <span class="checkmark"></span>
                      <span class="checkbox-label-text"><?php esc_html_e( 'Spiritual Travel', 'secondinnings50' ); ?></span>
                    </label>
                    <label class="checkbox-container">
                      <input type="checkbox" name="travel_styles[]" value="Heritage & Culture">
                      <span class="checkmark"></span>
                      <span class="checkbox-label-text"><?php esc_html_e( 'Heritage & Culture', 'secondinnings50' ); ?></span>
                    </label>
                    <label class="checkbox-container">
                      <input type="checkbox" name="travel_styles[]" value="Nature & Mountains">
                      <span class="checkmark"></span>
                      <span class="checkbox-label-text"><?php esc_html_e( 'Nature & Mountains', 'secondinnings50' ); ?></span>
                    </label>
                    <label class="checkbox-container">
                      <input type="checkbox" name="travel_styles[]" value="Leisure Holidays">
                      <span class="checkmark"></span>
                      <span class="checkbox-label-text"><?php esc_html_e( 'Leisure Holidays', 'secondinnings50' ); ?></span>
                    </label>
                    <label class="checkbox-container">
                      <input type="checkbox" name="travel_styles[]" value="Weekend Getaways">
                      <span class="checkmark"></span>
                      <span class="checkbox-label-text"><?php esc_html_e( 'Weekend Getaways', 'secondinnings50' ); ?></span>
                    </label>
                  </div>
                </div>

                <div class="form-group">
                  <span class="form-group-section-title"><?php esc_html_e( 'Companion Style Preference', 'secondinnings50' ); ?></span>
                  <div class="form-group-full">
                    <label class="radio-container" style="margin-bottom: 6px;">
                      <input type="radio" name="companion_styles" value="Group Travel Only" checked>
                      <span class="radiomark"></span>
                      <span class="radio-label-text"><?php esc_html_e( 'Group Travel Only', 'secondinnings50' ); ?></span>
                    </label>
                    <label class="radio-container" style="margin-bottom: 6px;">
                      <input type="radio" name="companion_styles" value="Women-Only Group Travel">
                      <span class="radiomark"></span>
                      <span class="radio-label-text"><?php esc_html_e( 'Women-Only Group Travel', 'secondinnings50' ); ?></span>
                    </label>
                    <label class="radio-container" style="margin-bottom: 6px;">
                      <input type="radio" name="companion_styles" value="Men-Only Group Travel">
                      <span class="radiomark"></span>
                      <span class="radio-label-text"><?php esc_html_e( 'Men-Only Group Travel', 'secondinnings50' ); ?></span>
                    </label>
                    <label class="radio-container" style="margin-bottom: 6px;">
                      <input type="radio" name="companion_styles" value="Mixed Group Travel">
                      <span class="radiomark"></span>
                      <span class="radio-label-text"><?php esc_html_e( 'Mixed Group Travel', 'secondinnings50' ); ?></span>
                    </label>
                    <label class="radio-container">
                      <input type="radio" name="companion_styles" value="Open to Suggestions">
                      <span class="radiomark"></span>
                      <span class="radio-label-text"><?php esc_html_e( 'Open to Suggestions', 'secondinnings50' ); ?></span>
                    </label>
                  </div>
                </div>
              </div>
            </fieldset>

            <!-- NEW GROUP 5: SAFETY & PEACE OF MIND (Emergency / Family Contact) -->
            <fieldset class="form-section-group">
              <legend><?php esc_html_e( 'Safety & Peace of Mind', 'secondinnings50' ); ?></legend>
              <!-- Emergency Contact removed for now -->
              <div class="form-group-full" style="margin-top: 16px;">
                <span class="form-group-section-title"><?php esc_html_e( 'Special Segments & Incentives (Optional - Select if applicable)', 'secondinnings50' ); ?></span>
                <div class="checkbox-group-grid checkbox-2col" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px; margin-top: 6px;">
                  <label class="checkbox-container">
                    <input type="checkbox" name="special_incentives[]" value="Single Mother">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Single Mother', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="special_incentives[]" value="Widowed Woman">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Widowed Woman', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="special_incentives[]" value="Widower">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Widower', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="checkbox-container">
                    <input type="checkbox" name="special_incentives[]" value="Living Alone">
                    <span class="checkmark"></span>
                    <span class="checkbox-label-text"><?php esc_html_e( 'Living Alone / Independent', 'secondinnings50' ); ?></span>
                  </label>
                </div>
              </div>
              <div class="form-group-full" style="margin-top: 16px;">
                <label for="join-selfie" class="form-label"><?php esc_html_e( 'Verification Selfie (Up-close Photo)', 'secondinnings50' ); ?> <span class="required">*</span></label>
                <input type="file" id="join-selfie" name="verification_selfie" accept="image/*" class="form-control" required aria-required="true" style="padding: 8px; border: 1.5px solid var(--color-charcoal) !important;">
                <span class="error-msg" id="join-selfie-error" aria-live="polite"></span>
                <span style="font-size: var(--fs-xs); color: var(--color-charcoal-muted); display: block; margin-top: 6px;">
                  <?php esc_html_e( 'Please upload a clear, up-close selfie photo to verify identity. Photos are kept private, secure, and used strictly for onboarding authentication.', 'secondinnings50' ); ?>
                </span>
              </div>
              <div class="form-group-full" style="margin-top: 16px;">
                <label for="join-voice" class="form-label"><?php esc_html_e( 'Voice Introduction (Optional)', 'secondinnings50' ); ?></label>
                <input type="file" id="join-voice" name="voice_intro" accept="audio/*" class="form-control" style="padding: 8px; border: 1.5px solid var(--color-charcoal) !important;">
                <span class="error-msg" id="join-voice-error" aria-live="polite"></span>
                <span style="font-size: var(--fs-xs); color: var(--color-charcoal-muted); display: block; margin-top: 6px;">
                  <?php esc_html_e( 'Upload a short voice recording (MP3, WAV, etc.) introducing yourself. Hearing your voice creates trust and comfort faster than text alone.', 'secondinnings50' ); ?>
                </span>
              </div>
            </fieldset>

            <!-- GROUP 6: INTRODUCTION / ABOUT ME -->
            <fieldset class="form-section-group">
              <legend><?php esc_html_e( 'Introduce Yourself / About Me', 'secondinnings50' ); ?></legend>
              <div class="form-group-full">
                <label for="join-message" class="form-label"><?php esc_html_e( 'Introduce Yourself', 'secondinnings50' ); ?> <span class="required">*</span></label>
                <textarea id="join-message" name="introduction" class="form-control textarea-control" rows="15" placeholder="<?php esc_attr_e( 'Share a detailed message about yourself, your interests, your background, and your life journey...', 'secondinnings50' ); ?>" required aria-required="true"></textarea>
                <span style="font-size: var(--fs-xs); color: var(--color-charcoal-muted); display: block; margin-top: 6px;">
                  <?php esc_html_e( 'You can write up to 1000+ words to comfortably share your thoughts.', 'secondinnings50' ); ?>
                </span>
                <span class="error-msg" id="join-message-error" aria-live="polite"></span>
              </div>
            </fieldset>

            <!-- Trust Sign-off Disclaimer Block -->
            <div class="trust-signoff-box" role="contentinfo" aria-label="<?php esc_attr_e( 'Verification disclaimer', 'secondinnings50' ); ?>">
              <p style="margin-bottom: 10px;">
                🤝 <strong><?php esc_html_e( 'Genuine Intentions Declaration:', 'secondinnings50' ); ?></strong> 
                <?php esc_html_e( 'By registering, you declare that you are joining this platform with genuine intentions to find meaningful companionship and friendship.', 'secondinnings50' ); ?>
              </p>
              <p style="margin-bottom: 10px;">
                🔒 <strong><?php esc_html_e( 'Safety Verification Check:', 'secondinnings50' ); ?></strong> 
                <?php esc_html_e( 'To protect the safety and dignity of our community, all applications undergo secure admin review. You will receive a friendly welcome call or WhatsApp message from our coordination team before your profile is activated.', 'secondinnings50' ); ?>
              </p>
              <p style="margin-bottom: 10px;">
                🛡️ <strong><?php esc_html_e( 'Confidentiality & Privacy Notice:', 'secondinnings50' ); ?></strong> 
                <?php esc_html_e( 'Your personal information, including uploaded photos, mobile number, WhatsApp number, email address, and physical address, will remain strictly confidential. They will only be used for verification purposes and will never be shared without your consent.', 'secondinnings50' ); ?>
              </p>
              <p style="margin-bottom: 10px;">
                ⚠️ <strong><?php esc_html_e( 'Membership Approval Notice:', 'secondinnings50' ); ?></strong> 
                <?php esc_html_e( 'Registration does not guarantee membership. Every profile will be reviewed and approved by the Admin before becoming an active member.', 'secondinnings50' ); ?>
              </p>
              <p style="margin-bottom: 10px;">
                🚫 <strong><?php esc_html_e( 'False Information Warning:', 'secondinnings50' ); ?></strong> 
                <?php esc_html_e( 'Providing false or misleading information may result in rejection or permanent removal from the platform without any refund.', 'secondinnings50' ); ?>
              </p>
              <p style="margin-bottom: 0;">
                🛑 <strong><?php esc_html_e( 'Zero Tolerance Policy:', 'secondinnings50' ); ?></strong> 
                <?php esc_html_e( 'Any abusive behavior, harassment, or misuse of the platform will not be tolerated and may lead to immediate and permanent removal from the community.', 'secondinnings50' ); ?>
              </p>
            </div>

            <!-- NEW GROUP: PAYMENT MODE (Visible for Males Only) -->
            <fieldset class="form-section-group" id="payment-mode-section" style="display: none;">
              <legend><?php esc_html_e( 'Payment Mode (₹699 Only)', 'secondinnings50' ); ?></legend>
              <div class="form-group-full">
                <span class="form-group-section-title"><?php esc_html_e( 'Select Payment Method', 'secondinnings50' ); ?> <span class="required">*</span></span>
                <div class="gender-radio-grid">
                  <label class="radio-container">
                    <input type="radio" name="payment_mode" value="PayU" checked>
                    <span class="radiomark"></span>
                    <span class="radio-label-text"><?php esc_html_e( 'PayU (Credit/Debit/Netbanking/UPI)', 'secondinnings50' ); ?></span>
                  </label>
                  <label class="radio-container">
                    <input type="radio" name="payment_mode" value="QR_Code">
                    <span class="radiomark"></span>
                    <span class="radio-label-text"><?php esc_html_e( 'UPI QR Code (Manual)', 'secondinnings50' ); ?></span>
                  </label>
                </div>
              </div>
              <div id="qr-code-upload-section" style="display: none; margin-top: 15px; padding: 15px; border: 1px dashed var(--color-charcoal); border-radius: var(--radius-md); background-color: var(--color-forest-light); text-align: center;">
                <p style="font-size: var(--fs-sm); margin-bottom: 10px;"><strong>Scan QR Code to Pay</strong></p>
                <!-- QR Code Image placeholder -->
                <div style="margin: 15px auto; max-width: 250px;">
                  <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/payment-qr.jpeg" alt="UPI QR Code" style="width: 100%; border-radius: 8px; border: 1px solid #ccc;">
                </div>
                <p style="font-size: var(--fs-sm); margin-bottom: 10px;"><strong>UPI ID:</strong> bathedenindia-3@okicici</p>
                <p style="font-size: var(--fs-xs); margin-bottom: 15px; color: var(--color-forest-dark);">Please pay ₹699 using any UPI app (GPay, PhonePe, Paytm), and then upload a screenshot of the successful payment below.</p>
                <div style="text-align: left;">
                  <label for="join-payment-screenshot" class="form-label"><?php esc_html_e( 'Upload Payment Screenshot', 'secondinnings50' ); ?> <span class="required">*</span></label>
                  <input type="file" id="join-payment-screenshot" name="payment_screenshot" accept="image/*" class="form-control" style="padding: 8px; border: 1.5px solid var(--color-charcoal) !important;">
                </div>
                <span class="error-msg" id="join-payment-screenshot-error" aria-live="polite"></span>
              </div>
            </fieldset>

            <!-- Consent & Communication Preferences -->
            <fieldset class="form-section-group" style="margin-top: 15px; border-color: var(--color-terracotta-light);">
              <legend style="color: var(--color-terracotta);"><?php esc_html_e( 'Mandatory Consent', 'secondinnings50' ); ?></legend>
              <div class="form-group-full">
                <label class="checkbox-container">
                  <input type="checkbox" id="join-consent-terms" name="consent_terms" required aria-required="true">
                  <span class="checkmark"></span>
                  <span class="checkbox-label-text">
                    <?php esc_html_e( 'I agree to the Privacy Policy and Terms & Conditions of SecondInnings50.in. I consent to the collection and processing of my information for profile creation, matching, communication, and account management purposes.', 'secondinnings50' ); ?>
                  </span>
                </label>
                <span class="error-msg" id="join-consent-terms-error" aria-live="polite" style="display:none; color: var(--color-terracotta); font-size: var(--fs-xs); margin-top: 4px;"></span>
              </div>
              <div class="form-group-full" style="margin-top: 12px;">
                <label class="checkbox-container">
                  <input type="checkbox" id="join-consent-whatsapp" name="consent_whatsapp" required aria-required="true">
                  <span class="checkmark"></span>
                  <span class="checkbox-label-text">
                    <?php esc_html_e( 'I agree to receive account-related updates, match notifications, and important service messages from SecondInnings50.in via WhatsApp, SMS, email, or phone.', 'secondinnings50' ); ?>
                  </span>
                </label>
                <span class="error-msg" id="join-consent-whatsapp-error" aria-live="polite" style="display:none; color: var(--color-terracotta); font-size: var(--fs-xs); margin-top: 4px;"></span>
              </div>
            </fieldset>

            <!-- Form Action Submit -->
            <div class="form-action text-center">
              <button type="submit" class="btn btn-primary btn-block btn-join">
                <?php esc_html_e( 'Submit Onboarding Request / नई शुरुआत, बेहतर साथ! 🌾', 'secondinnings50' ); ?>
              </button>
            </div>

          </form>

          <!-- Success Message Container -->
          <div id="join-success-msg" class="success-message text-center" style="display: none;" aria-live="polite">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" style="margin-bottom: 1rem; display: inline-block;">
              <title><?php esc_html_e( 'Onboarding Application Submitted Successfully Checkmark', 'secondinnings50' ); ?></title>
              <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill="var(--color-gold)"/>
              <path d="M8 12L11 15L16 9" stroke="var(--color-forest-dark)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 class="success-title"><?php esc_html_e( 'Application Vetted Successfully!', 'secondinnings50' ); ?></h3>
            <p class="success-desc">
              <?php esc_html_e( 'Thank you for registering with SecondInnings. Your registration has been received successfully. Our team will review and verify your profile. We will contact you shortly regarding the next steps.', 'secondinnings50' ); ?>
            </p>
          </div>

        </div>

      </div>
    </div>
  </section>

</main>

<?php
get_footer();