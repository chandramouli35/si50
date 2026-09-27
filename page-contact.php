<?php
/**
 * Template Name: Contact Page Template
 *
 * @package SecondInnings50
 */

get_header();
?>

<main id="main-content" class="site-main content-area" style="background-color: #F8F9FA; min-height: 80vh; padding: 40px 0; box-sizing: border-box;">
  <!-- Google Developer Editorial Workspace Layout -->
  <div class="si50-contact-container">
    
    <style>
      .si50-contact-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 60px 30px;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #333333;
        line-height: 1.8;
        background: #FFFFFF;
        border: 1px solid #E0E0E0;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        box-sizing: border-box;
      }
      .si50-contact-container h1, .si50-contact-container h2 {
        font-family: 'Playfair Display', Georgia, serif;
        color: #23282d;
        font-weight: 700;
      }
      .si50-contact-container h1 {
        font-size: 2.25rem;
        margin-top: 0;
        margin-bottom: 15px;
        border-bottom: 2px solid #E0E0E0;
        padding-bottom: 15px;
        text-align: center;
      }
      .si50-contact-subtitle {
        text-align: center;
        color: #666666;
        font-size: 1.1rem;
        margin-bottom: 40px;
      }
      .si50-form-group {
        margin-bottom: 25px;
      }
      .si50-form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 0.95rem;
        color: #23282d;
      }
      .si50-form-control {
        width: 100%;
        padding: 12px 16px;
        font-size: 1rem;
        font-family: inherit;
        border: 1.5px solid #23282d;
        border-radius: 4px;
        box-sizing: border-box;
        background-color: #FFFFFF;
        color: #333333;
        transition: border-color 0.2s ease;
      }
      .si50-form-control:focus {
        outline: none;
        border-color: #4A5568;
        box-shadow: 0 0 0 3px rgba(74, 85, 104, 0.1);
      }
      .si50-btn-submit {
        display: block;
        width: 100%;
        padding: 14px 20px;
        background-color: #23282d;
        color: #FFFFFF !important;
        font-size: 1rem;
        font-weight: 600;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.2s ease;
      }
      .si50-btn-submit:hover {
        background-color: #4A5568;
      }
      .si50-btn-submit:disabled {
        background-color: #A0AEC0;
        cursor: not-allowed;
      }
      .si50-error-message {
        color: #E53E3E;
        font-size: 0.85rem;
        margin-top: 5px;
        display: none;
      }

      /* Modal Styling */
      .si50-modal-overlay {
        display: none;
        justify-content: center;
        align-items: center;
        position: fixed;
        z-index: 99999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
      }
      .si50-modal-box {
        background: #FFFFFF;
        padding: 40px;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border: 1px solid #E0E0E0;
        box-sizing: border-box;
        animation: si50FadeIn 0.3s ease;
      }
      @keyframes si50FadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
      }
      .si50-modal-icon {
        margin-bottom: 20px;
      }
      .si50-modal-box h3 {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.5rem;
        margin-top: 0;
        margin-bottom: 10px;
        color: #23282d;
      }
      .si50-modal-box p {
        font-size: 1rem;
        color: #555555;
        margin-bottom: 25px;
        line-height: 1.6;
      }
      .si50-modal-close-btn {
        padding: 10px 24px;
        background-color: #23282d;
        color: #FFFFFF;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.95rem;
        transition: background-color 0.2s ease;
      }
      .si50-modal-close-btn:hover {
        background-color: #4A5568;
      }
      @media (max-width: 600px) {
        .si50-contact-container {
          padding: 30px 20px;
          border-radius: 0;
          border-left: none;
          border-right: none;
        }
        .si50-contact-container h1 {
          font-size: 1.75rem;
        }
      }
    </style>

    <h1><?php esc_html_e( 'Contact SecondInnings50 Vetting Support', 'secondinnings50' ); ?></h1>
    <p class="si50-contact-subtitle">
      <?php esc_html_e( 'Have questions about onboarding, verification, or community circles? Get in touch with our hosts.', 'secondinnings50' ); ?>
    </p>

    <!-- Native Interactive Form Layout -->
    <form id="si50-native-contact-form" method="post" aria-label="<?php esc_attr_e( 'Send support message form', 'secondinnings50' ); ?>">
      <?php wp_nonce_field( 'si50_auth_nonce', 'security' ); ?>
      
      <!-- Full Name -->
      <div class="si50-form-group">
        <label for="fullname"><?php esc_html_e( 'Full Name', 'secondinnings50' ); ?> <span style="color: #E53E3E;">*</span></label>
        <input type="text" id="fullname" name="fullname" class="si50-form-control" placeholder="<?php esc_attr_e( 'Enter your full name', 'secondinnings50' ); ?>" required aria-required="true">
        <div class="si50-error-message" id="fullname-error"></div>
      </div>

      <!-- WhatsApp Mobile Number -->
      <div class="si50-form-group">
        <label for="phone"><?php esc_html_e( 'WhatsApp Mobile Number', 'secondinnings50' ); ?> <span style="color: #E53E3E;">*</span></label>
        <input type="tel" id="phone" name="phone" class="si50-form-control" placeholder="<?php esc_attr_e( 'Enter your 10-digit mobile number', 'secondinnings50' ); ?>" required aria-required="true">
        <div class="si50-error-message" id="phone-error"></div>
      </div>

      <!-- Email Address -->
      <div class="si50-form-group">
        <label for="email"><?php esc_html_e( 'Email Address', 'secondinnings50' ); ?> <span style="color: #E53E3E;">*</span></label>
        <input type="email" id="email" name="email" class="si50-form-control" placeholder="<?php esc_attr_e( 'name@example.com', 'secondinnings50' ); ?>" required aria-required="true">
        <div class="si50-error-message" id="email-error"></div>
      </div>

      <!-- Nature of Inquiry -->
      <div class="si50-form-group">
        <label for="inquiry_nature"><?php esc_html_e( 'Nature of Inquiry', 'secondinnings50' ); ?> <span style="color: #E53E3E;">*</span></label>
        <select id="inquiry_nature" name="inquiry_nature" class="si50-form-control" required aria-required="true">
          <option value="General Support"><?php esc_html_e( 'General Support', 'secondinnings50' ); ?></option>
          <option value="Verification Host Call Scheduling"><?php esc_html_e( 'Verification Host Call Scheduling', 'secondinnings50' ); ?></option>
          <option value="Travel Partnership Inquiries"><?php esc_html_e( 'Travel Partnership Inquiries', 'secondinnings50' ); ?></option>
          <option value="Feedback"><?php esc_html_e( 'Feedback', 'secondinnings50' ); ?></option>
        </select>
        <div class="si50-error-message" id="inquiry_nature-error"></div>
      </div>

      <!-- Message -->
      <div class="si50-form-group">
        <label for="message"><?php esc_html_e( 'Your Message', 'secondinnings50' ); ?> <span style="color: #E53E3E;">*</span></label>
        <textarea id="message" name="message" class="si50-form-control" rows="6" placeholder="<?php esc_attr_e( 'How can our hosts help you today?', 'secondinnings50' ); ?>" required aria-required="true"></textarea>
        <div class="si50-error-message" id="message-error"></div>
      </div>

      <!-- Sharp Action Button -->
      <div style="margin-top: 30px;">
        <button type="submit" id="si50-submit-btn" class="si50-btn-submit">
          <?php esc_html_e( 'Send Message', 'secondinnings50' ); ?>
        </button>
      </div>
    </form>

  </div>

  <!-- Smooth Success Modal Overlay -->
  <div id="si50-contact-modal" class="si50-modal-overlay">
    <div class="si50-modal-box">
      <div class="si50-modal-icon">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12" cy="12" r="10" fill="#16A34A"/>
          <path d="M8 12L11 15L16 9" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <h3><?php esc_html_e( 'Inquiry Submitted', 'secondinnings50' ); ?></h3>
      <!-- Exact success alert string -->
      <p><?php esc_html_e( 'Thank you. Your inquiry has been securely routed to our verification hosts. We will contact you shortly.', 'secondinnings50' ); ?></p>
      <button type="button" id="si50-close-modal" class="si50-modal-close-btn"><?php esc_html_e( 'Close', 'secondinnings50' ); ?></button>
    </div>
  </div>
</main>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById("si50-native-contact-form");
  const modal = document.getElementById("si50-contact-modal");
  const closeModal = document.getElementById("si50-close-modal");
  const submitBtn = document.getElementById("si50-submit-btn");

  const ajaxUrl = "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>";

  if (!form || !modal || !closeModal || !submitBtn) return;

  // Form submit handler
  form.addEventListener("submit", function(e) {
    e.preventDefault();

    // Reset error states
    document.querySelectorAll(".si50-error-message").forEach(el => {
      el.style.display = "none";
      el.textContent = "";
    });

    let isValid = true;

    // Validate Full Name
    const fullname = document.getElementById("fullname");
    if (fullname.value.trim().length < 2) {
      showError("fullname-error", "<?php esc_attr_e( 'Please enter your full name.', 'secondinnings50' ); ?>");
      isValid = false;
    }

    // Validate Phone
    const phone = document.getElementById("phone");
    const phoneVal = phone.value.trim();
    if (phoneVal.length < 10) {
      showError("phone-error", "<?php esc_attr_e( 'Please enter a valid mobile number.', 'secondinnings50' ); ?>");
      isValid = false;
    }

    // Validate Email
    const email = document.getElementById("email");
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value.trim())) {
      showError("email-error", "<?php esc_attr_e( 'Please enter a valid email address.', 'secondinnings50' ); ?>");
      isValid = false;
    }

    // Validate Message
    const message = document.getElementById("message");
    if (message.value.trim().length < 10) {
      showError("message-error", "<?php esc_attr_e( 'Please provide a descriptive message of at least 10 characters.', 'secondinnings50' ); ?>");
      isValid = false;
    }

    if (!isValid) return;

    // Trigger submission
    submitBtn.disabled = true;
    submitBtn.textContent = "<?php esc_attr_e( 'Sending Message...', 'secondinnings50' ); ?>";

    const formData = new FormData(form);
    formData.append("action", "si50_submit_contact");

    fetch(ajaxUrl, {
      method: "POST",
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Reset form
        form.reset();
        // Show success modal
        modal.style.display = "flex";
      } else {
        alert(data.data.message || "<?php esc_attr_e( 'An error occurred. Please try again.', 'secondinnings50' ); ?>");
      }
      submitBtn.disabled = false;
      submitBtn.textContent = "<?php esc_attr_e( 'Send Message', 'secondinnings50' ); ?>";
    })
    .catch(error => {
      console.error("Submission error:", error);
      submitBtn.disabled = false;
      submitBtn.textContent = "<?php esc_attr_e( 'Send Message', 'secondinnings50' ); ?>";
      alert("<?php esc_attr_e( 'Network error. Please check your connection and try again.', 'secondinnings50' ); ?>");
    });
  });

  // Modal close handlers
  closeModal.addEventListener("click", function() {
    modal.style.display = "none";
  });

  window.addEventListener("click", function(e) {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });

  function showError(elementId, message) {
    const errorEl = document.getElementById(elementId);
    if (errorEl) {
      errorEl.textContent = message;
      errorEl.style.display = "block";
    }
  }
});
</script>

<?php
get_footer();
