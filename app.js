/**
 * SecondInnings50 - Premium Homepage Interactivity Script
 */

document.addEventListener("DOMContentLoaded", () => {
  initMobileMenu();
  initScrollReveal();
  initInteractiveCards();
  initFormValidation();
  initJoinFormValidation();
  initContactFormValidation();
  initProfileFormValidation();
  initAnchorLinks();

  // Style select fields dynamically on value change to mimic placeholder color states
  const selects = document.querySelectorAll("select");
  selects.forEach(select => {
    const handleSelectColor = () => {
      if (select.value) {
        select.classList.add("value-selected");
      } else {
        select.classList.remove("value-selected");
      }
    };
    handleSelectColor();
    select.addEventListener("change", handleSelectColor);
  });

  // Init auth and directory systems
  initLoginModal();
  initDirectoryActions();
  initProfileDropdown();
  initProfilePreferencesFormValidation();
  initOfferingModals();
  initRazorpayPayment();
  initSocialSharing();
});

/**
 * Mobile Navigation Menu controller
 * Properly manages ARIA states for accessibility
 */
function initMobileMenu() {
  const toggleBtn = document.querySelector(".mobile-menu-toggle");
  const mobileNav = document.getElementById("mobile-menu");
  const body = document.body;

  if (!toggleBtn || !mobileNav) return;

  toggleBtn.addEventListener("click", () => {
    const isExpanded = toggleBtn.getAttribute("aria-expanded") === "true";
    
    // Toggle state
    toggleBtn.setAttribute("aria-expanded", !isExpanded);
    mobileNav.setAttribute("aria-hidden", isExpanded);
    mobileNav.classList.toggle("open");
    
    // Prevent body scrolling when mobile menu is open
    if (!isExpanded) {
      body.style.overflow = "hidden";
    } else {
      body.style.overflow = "";
    }
  });

  // Close menu when a link is clicked
  const mobileLinks = mobileNav.querySelectorAll(".mobile-nav-link");
  mobileLinks.forEach(link => {
    link.addEventListener("click", () => {
      toggleBtn.setAttribute("aria-expanded", "false");
      mobileNav.setAttribute("aria-hidden", "true");
      mobileNav.classList.remove("open");
      body.style.overflow = "";
    });
  });
}

/**
 * Scroll Reveal animations using IntersectionObserver
 * Dynamically fades and slides up sections as they enter the screen
 */
function initScrollReveal() {
  // Add base fade-in style class in JS so standard CSS loads fine without blocking JS-less browsers
  const revealElements = document.querySelectorAll(
    ".hero-content, .hero-visual, .empathy-card, .offering-card, .safety-wrapper, .cta-card"
  );

  const observerOptions = {
    root: null,
    rootMargin: "0px",
    threshold: 0.12
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("revealed");
        observer.unobserve(entry.target); // only animate once
      }
    });
  }, observerOptions);

  // Apply initial hidden styles and observe
  revealElements.forEach(el => {
    el.style.opacity = "0";
    el.style.transform = "translateY(24px)";
    el.style.transition = "opacity 0.8s cubic-bezier(0.25, 1, 0.5, 1), transform 0.8s cubic-bezier(0.25, 1, 0.5, 1)";
    observer.observe(el);
  });
}

// Inject keyframe stylesheet details for helper class (or simply use styles.css)
const style = document.createElement('style');
style.innerHTML = `
  .revealed {
    opacity: 1 !important;
    transform: translateY(0) !important;
  }
`;
document.head.appendChild(style);

/**
 * Key Offering Interactive Cards
 */
function initInteractiveCards() {
  const cards = document.querySelectorAll(".offering-card");
  
  cards.forEach(card => {
    card.addEventListener("click", () => {
      const feature = card.getAttribute("data-feature");
      console.log(`User clicked to explore: ${feature}`);
      
      // Add a subtle click/pulse scale animation
      card.style.transform = "scale(0.98)";
      setTimeout(() => {
        card.style.transform = "";
      }, 150);
    });

    // Handle Keyboard 'Enter' key for Accessibility
    card.addEventListener("keydown", (e) => {
      if (e.key === "Enter") {
        card.click();
      }
    });
  });
}

/**
/**
 * Simulated OTP Verification Overlay (4-digit code)
 */
function triggerOtpVerification(phoneNumber, onSuccess, onCancel) {
  const overlay = document.createElement("div");
  overlay.className = "si50-modal-overlay";
  overlay.style.display = "flex";
  overlay.style.zIndex = "100000";
  
  const modalBox = document.createElement("div");
  modalBox.className = "si50-modal-box";
  modalBox.style.padding = "30px";
  modalBox.innerHTML = `
    <h3 style="margin-top:0;">Phone Verification</h3>
    <p>We've sent a 4-digit secure code to <strong>${phoneNumber}</strong>.</p>
    <div style="margin-bottom: 20px;">
      <input type="text" id="si50-otp-input" class="form-control text-center" maxlength="4" placeholder="••••" style="font-size: 24px; letter-spacing: 10px; width: 140px; margin: 0 auto; display: block;" autocomplete="one-time-code" />
      <span id="si50-otp-error" style="color: #E53E3E; font-size: 14px; display: none; margin-top: 8px;">Invalid code. Try 1234.</span>
    </div>
    <div style="display: flex; gap: 10px; justify-content: center;">
      <button id="si50-otp-verify-btn" class="btn btn-primary" style="padding: 10px 20px;">Verify Code</button>
      <button id="si50-otp-cancel-btn" class="btn" style="background-color: #E2E8F0; color: #333333; padding: 10px 20px;">Cancel</button>
    </div>
  `;
  
  overlay.appendChild(modalBox);
  document.body.appendChild(overlay);
  
  const verifyBtn = overlay.querySelector("#si50-otp-verify-btn");
  const cancelBtn = overlay.querySelector("#si50-otp-cancel-btn");
  const otpInput = overlay.querySelector("#si50-otp-input");
  const errorMsg = overlay.querySelector("#si50-otp-error");
  
  otpInput.focus();
  
  const verifyAction = () => {
    // Simulated OTP accepted value is 1234
    if (otpInput.value === "1234") {
      document.body.removeChild(overlay);
      if (typeof onSuccess === "function") onSuccess();
    } else {
      errorMsg.style.display = "block";
      otpInput.style.borderColor = "#E53E3E";
    }
  };
  
  verifyBtn.addEventListener("click", verifyAction);
  
  otpInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
      e.preventDefault();
      verifyAction();
    }
  });
  
  cancelBtn.addEventListener("click", () => {
    document.body.removeChild(overlay);
    if (typeof onCancel === "function") onCancel();
  });
}

/**
 * Lead Generation Form validation and handling
 */
function initFormValidation() {
  const form = document.getElementById("invitation-form");
  const successMsg = document.getElementById("form-success-msg");
  const nameInput = document.getElementById("user-name");
  const emailInput = document.getElementById("user-email");
  const phoneInput = document.getElementById("user-phone");
  const genderInput = document.getElementById("user-gender");
  const submittedNameSpan = document.getElementById("submitted-name");

  if (!form || !successMsg || !nameInput || !emailInput || !phoneInput || !genderInput) return;

  const ajaxUrl = typeof si50_ajax !== "undefined" ? si50_ajax.ajax_url : "/wp-admin/admin-ajax.php";

  form.addEventListener("submit", (e) => {
    e.preventDefault(); // Stop page reload

    // Reset error states
    let isValid = true;
    clearError(nameInput, "name-error");
    clearError(emailInput, "email-error");
    clearError(phoneInput, "phone-error");
    clearError(genderInput, "gender-error");

    const nameVal = nameInput.value.trim();
    const emailVal = emailInput.value.trim();
    const phoneVal = phoneInput.value.trim();
    const genderVal = genderInput.value;

    // 1. Name validation
    if (nameVal.length < 3) {
      showError(nameInput, "name-error", "Please enter your full name (minimum 3 characters).");
      isValid = false;
    } else if (!/^[a-zA-Z\s]+$/.test(nameVal)) {
      showError(nameInput, "name-error", "Please use letters and spaces only.");
      isValid = false;
    }

    // 2. Email validation
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(emailVal)) {
      showError(emailInput, "email-error", "Please enter a valid email address.");
      isValid = false;
    }

    // 3. Phone validation
    if (phoneVal.length < 8) {
      showError(phoneInput, "phone-error", "Please enter a valid phone number.");
      isValid = false;
    }

    // 4. Gender validation
    if (!genderVal) {
      showError(genderInput, "gender-error", "Please select your gender.");
      isValid = false;
    }

    if (isValid) {
      const submitButton = form.querySelector("button[type='submit']");
      const originalText = submitButton.innerText;

      // Trigger the simulated OTP verification modal
      triggerOtpVerification(phoneVal, () => {
        submitButton.disabled = true;
        submitButton.innerText = "Requesting Access...";

        const formData = new FormData();
        formData.append('action', 'si50_submit_lead');
        formData.append('name', nameVal);
        formData.append('email', emailVal);
        formData.append('phone', phoneVal);
        formData.append('gender', genderVal);

        fetch(ajaxUrl, {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            form.style.display = "none";
            submittedNameSpan.innerText = nameVal;
            successMsg.style.display = "block";
            successMsg.scrollIntoView({ behavior: "smooth", block: "center" });
          } else {
            showError(emailInput, "email-error", data.data.message || "An error occurred. Please try again.");
            submitButton.disabled = false;
            submitButton.innerText = originalText;
          }
        })
        .catch(error => {
          console.error("Error:", error);
          // Fallback for static mock testing
          form.style.display = "none";
          submittedNameSpan.innerText = nameVal;
          successMsg.style.display = "block";
          successMsg.scrollIntoView({ behavior: "smooth", block: "center" });
        });
      }, () => {
        // Cancelled verification
        submitButton.disabled = false;
        submitButton.innerText = originalText;
      });
    }
  });
}

// Global Form Validation Helpers
function showError(inputElement, errorId, message) {
  inputElement.style.borderColor = "#C05C3E";
  inputElement.setAttribute("aria-invalid", "true");
  const errorSpan = document.getElementById(errorId);
  if (errorSpan) {
    errorSpan.innerText = message;
  }
}

function clearError(inputElement, errorId) {
  inputElement.style.borderColor = "";
  inputElement.removeAttribute("aria-invalid");
  const errorSpan = document.getElementById(errorId);
  if (errorSpan) {
    errorSpan.innerText = "";
  }
}

/**
 * Join Application Form Validation
 */
function initJoinFormValidation() {
  const form = document.getElementById("join-application-form");
  const successMsg = document.getElementById("join-success-msg");
  
  if (!form || !successMsg) return;

  const nameInput = document.getElementById("join-name");
  const emailInput = document.getElementById("join-email");
  const phoneInput = document.getElementById("join-phone");
  const cityInput = document.getElementById("join-city");
  const ageInput = document.getElementById("join-age");
  const maritalInput = document.getElementById("join-marital");
  const occupationInput = document.getElementById("join-occupation");
  const messageInput = document.getElementById("join-message");
  const emergencyInput = document.getElementById("join-emergency");
  const passwordInput = document.getElementById("join-password");
  const passwordConfirmInput = document.getElementById("join-password-confirm");

  // Payment UI Toggles
  const paymentModeSection = document.getElementById("payment-mode-section");
  const qrCodeSection = document.getElementById("qr-code-upload-section");
  const genderGrid = form.querySelector(".gender-radio-grid");
  const genderRadios = form.querySelectorAll("input[name='gender']");
  const paymentModeRadios = form.querySelectorAll("input[name='payment_mode']");
  
  if (genderRadios) {
    genderRadios.forEach(radio => {
      radio.addEventListener("change", (e) => {
        if (e.target.value === 'Male') {
          if (paymentModeSection) paymentModeSection.style.display = 'block';
        } else {
          if (paymentModeSection) paymentModeSection.style.display = 'none';
        }
      });
    });
  }

  if (paymentModeRadios) {
    paymentModeRadios.forEach(radio => {
      radio.addEventListener("change", (e) => {
        if (e.target.value === 'QR_Code') {
          if (qrCodeSection) qrCodeSection.style.display = 'block';
        } else {
          if (qrCodeSection) qrCodeSection.style.display = 'none';
        }
      });
    });
  }

  const ajaxUrl = typeof si50_ajax !== "undefined" ? si50_ajax.ajax_url : "/wp-admin/admin-ajax.php";

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    let isValid = true;
    clearError(nameInput, "join-name-error");
    clearError(emailInput, "join-email-error");
    clearError(phoneInput, "join-phone-error");
    clearError(cityInput, "join-city-error");
    clearError(ageInput, "join-age-error");
    if(maritalInput) clearError(maritalInput, "join-marital-error");
    if(occupationInput) clearError(occupationInput, "join-occupation-error");
    if(genderGrid) clearError(genderGrid, "join-gender-error");
    clearError(messageInput, "join-message-error");
    if (emergencyInput) {
      clearError(emergencyInput, "join-emergency-error");
    }
    if (passwordInput) {
      clearError(passwordInput, "join-password-error");
    }
    if (passwordConfirmInput) {
      clearError(passwordConfirmInput, "join-password-confirm-error");
    }

    const nameVal = nameInput.value.trim();
    const emailVal = emailInput.value.trim();
    const phoneVal = phoneInput.value.trim();
    const cityVal = cityInput.value.trim();
    const ageVal = ageInput.value;
    const maritalVal = maritalInput ? maritalInput.value : "";
    const occupationVal = occupationInput ? occupationInput.value.trim() : "";
    const selectedGender = form.querySelector("input[name='gender']:checked");
    const messageVal = messageInput.value.trim();
    const emergencyVal = emergencyInput ? emergencyInput.value.trim() : "";
    const passwordVal = passwordInput ? passwordInput.value : "";
    const passwordConfirmVal = passwordConfirmInput ? passwordConfirmInput.value : "";

    // 1. Name
    if (nameVal.length < 3) {
      showError(nameInput, "join-name-error", "Please enter your full name (minimum 3 characters).");
      isValid = false;
    } else if (!/^[a-zA-Z\s]+$/.test(nameVal)) {
      showError(nameInput, "join-name-error", "Please use letters and spaces only.");
      isValid = false;
    }

    // 2. Email
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(emailVal)) {
      showError(emailInput, "join-email-error", "Please enter a valid email address.");
      isValid = false;
    }

    // 3. Phone
    if (phoneVal.length < 8) {
      showError(phoneInput, "join-phone-error", "Please enter a valid phone number.");
      isValid = false;
    }

    // 4. City
    if (cityVal.length < 2) {
      showError(cityInput, "join-city-error", "Please enter your city/location.");
      isValid = false;
    }

    // 5. Age
    if (!ageVal) {
      showError(ageInput, "join-age-error", "Please enter your age.");
      isValid = false;
    }

    if (!selectedGender) {
      if(genderGrid) showError(genderGrid, "join-gender-error", "Please select your gender identity.");
      isValid = false;
    }

    if (!maritalVal) {
      if(maritalInput) showError(maritalInput, "join-marital-error", "Please select marital status.");
      isValid = false;
    }

    if (occupationVal.length < 2) {
      if(occupationInput) showError(occupationInput, "join-occupation-error", "Please enter your occupation.");
      isValid = false;
    }

    // Emergency Contact check removed for now

    // Selfie Verification File check
    const selfieInput = document.getElementById("join-selfie");
    if (selfieInput) {
      clearError(selfieInput, "join-selfie-error");
      const selfieFile = selfieInput.files[0];
      if (!selfieFile) {
        showError(selfieInput, "join-selfie-error", "Please upload a verification selfie photo.");
        isValid = false;
      } else {
        const allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        const fileExt = selfieFile.name.split('.').pop().toLowerCase();
        if (!allowedExts.includes(fileExt)) {
          showError(selfieInput, "join-selfie-error", "Allowed file formats: JPG, PNG, GIF, WEBP.");
          isValid = false;
        }
      }
    }

    // Payment Screenshot check (if QR code selected)
    const selectedGender = form.querySelector("input[name='gender']:checked");
    const selectedPaymentMode = form.querySelector("input[name='payment_mode']:checked");
    if (selectedGender && selectedGender.value === 'Male' && selectedPaymentMode && selectedPaymentMode.value === 'QR_Code') {
      const screenshotInput = document.getElementById("join-payment-screenshot");
      if (screenshotInput) {
        clearError(screenshotInput, "join-payment-screenshot-error");
        const screenshotFile = screenshotInput.files[0];
        if (!screenshotFile) {
          showError(screenshotInput, "join-payment-screenshot-error", "Please upload your payment screenshot.");
          isValid = false;
        }
      }
    }

    // 7. Message
    if (messageVal.length < 10) {
      showError(messageInput, "join-message-error", "Please write a brief introduction (minimum 10 characters).");
      isValid = false;
    }

    // 8. Password
    if (passwordInput) {
      if (passwordVal.length < 6) {
        showError(passwordInput, "join-password-error", "Password must be at least 6 characters.");
        isValid = false;
      }
      if (passwordVal !== passwordConfirmVal) {
        showError(passwordConfirmInput, "join-password-confirm-error", "Passwords do not match.");
        isValid = false;
      }
    }

    // 9. Consents
    const consentTerms = document.getElementById("join-consent-terms");
    const consentWhatsapp = document.getElementById("join-consent-whatsapp");
    
    if (consentTerms) {
      clearError(consentTerms, "join-consent-terms-error");
      if (!consentTerms.checked) {
        showError(consentTerms, "join-consent-terms-error", "You must agree to the Terms & Conditions.");
        isValid = false;
      }
    }
    
    if (consentWhatsapp) {
      clearError(consentWhatsapp, "join-consent-whatsapp-error");
      if (!consentWhatsapp.checked) {
        showError(consentWhatsapp, "join-consent-whatsapp-error", "You must opt-in to WhatsApp communication.");
        isValid = false;
      }
    }

    if (!isValid) {
      const firstError = form.querySelector('[aria-invalid="true"]');
      if (firstError) {
        firstError.scrollIntoView({ behavior: "smooth", block: "center" });
        firstError.focus();
      }
    }

    if (isValid) {
      const submitButton = form.querySelector("button[type='submit']");
      const originalText = submitButton.innerText;

      // Mobile OTP Verification is disabled for first version
      submitButton.disabled = true;
      submitButton.innerText = "Submitting Application...";

      const formData = new FormData(form);
      formData.append('action', 'si50_ajax_register');
      if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
        formData.append('security', si50_ajax.nonce);
      }

      fetch(ajaxUrl, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          if (data.data.payu && data.data.payu_params) {
            // Immediate submission for PayU without confusing popup/message
            const payuForm = document.createElement('form');
            payuForm.method = 'POST';
            payuForm.action = data.data.payu_url;
            
            for (const key in data.data.payu_params) {
                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = key;
                hiddenField.value = data.data.payu_params[key];
                payuForm.appendChild(hiddenField);
            }
            
            document.body.appendChild(payuForm);
            // Hide everything and show a simple redirect text (not a popup)
            form.style.display = "none";
            successMsg.style.display = "block";
            successMsg.innerHTML = '<h3 class="success-title">Redirecting to Secure Payment...</h3><p class="success-desc">Please wait while we take you to the payment gateway.</p>';
            
            payuForm.submit();
          } else {
            // Normal success for QR code or Females
            form.style.display = "none";
            successMsg.style.display = "block";
            successMsg.scrollIntoView({ behavior: "smooth", block: "center" });
            if (data.data.redirect) {
              setTimeout(() => {
                window.location.href = data.data.redirect;
              }, 2000);
            }
          }
        } else {
          showError(emailInput, "join-email-error", data.data.message || "An error occurred. Please try again.");
          submitButton.disabled = false;
          submitButton.innerText = originalText;
        }
      })
      .catch(error => {
        console.error("Error:", error);
        submitButton.disabled = false;
        submitButton.innerText = originalText;
      });
    }
  });
}

/**
 * Contact Support Form Validation
 */
function initContactFormValidation() {
  const form = document.getElementById("contact-support-form");
  const successMsg = document.getElementById("contact-success-msg");

  if (!form || !successMsg) return;

  const nameInput = document.getElementById("contact-name");
  const emailInput = document.getElementById("contact-email");
  const phoneInput = document.getElementById("contact-phone");
  const messageInput = document.getElementById("contact-message");

  const ajaxUrl = typeof si50_ajax !== "undefined" ? si50_ajax.ajax_url : "/wp-admin/admin-ajax.php";

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    let isValid = true;
    clearError(nameInput, "contact-name-error");
    clearError(emailInput, "contact-email-error");
    clearError(messageInput, "contact-message-error");

    const nameVal = nameInput.value.trim();
    const emailVal = emailInput.value.trim();
    const messageVal = messageInput.value.trim();

    // 1. Name
    if (nameVal.length < 3) {
      showError(nameInput, "contact-name-error", "Please enter your name (minimum 3 characters).");
      isValid = false;
    }

    // 2. Email
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(emailVal)) {
      showError(emailInput, "contact-email-error", "Please enter a valid email address.");
      isValid = false;
    }

    // 3. Message
    if (messageVal.length < 5) {
      showError(messageInput, "contact-message-error", "Please write your message.");
      isValid = false;
    }

    if (isValid) {
      const submitButton = form.querySelector("button[type='submit']");
      submitButton.disabled = true;
      submitButton.innerText = "Sending Message...";

      const formData = new FormData(form);
      formData.append('action', 'si50_submit_contact');

      fetch(ajaxUrl, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          form.style.display = "none";
          successMsg.style.display = "block";
          successMsg.scrollIntoView({ behavior: "smooth", block: "center" });
        } else {
          showError(emailInput, "contact-email-error", data.data.message || "An error occurred. Please try again.");
          submitButton.disabled = false;
          submitButton.innerText = "Send Message";
        }
      })
      .catch(error => {
        console.error("Error:", error);
        // Fallback for static mock testing
        form.style.display = "none";
        successMsg.style.display = "block";
        successMsg.scrollIntoView({ behavior: "smooth", block: "center" });
      });
    }
  });
}

/**
 * Anchor links resolver
 * Redirects or smooth scrolls to sections properly on front-page vs sub-pages
 */
function initAnchorLinks() {
  const isHomePage = document.body.classList.contains("home") || 
                     document.body.classList.contains("front-page") ||
                     window.location.pathname === "/" ||
                     window.location.pathname.endsWith("/index.php");

  const homeUrl = document.body.dataset.homeUrl || "/";

  // Handle all anchor link clicks
  document.querySelectorAll('a[href*="#"]').forEach(link => {
    const href = link.getAttribute("href");
    if (!href) return;
    
    // Extract the hash from href
    let hash = "";
    if (href.startsWith("#")) {
      hash = href;
    } else {
      const hashIndex = href.indexOf("#");
      if (hashIndex !== -1) {
        hash = href.substring(hashIndex);
      }
    }

    if (!hash || hash === "#") return;

    link.addEventListener("click", (e) => {
      // Determine if we should navigate or scroll
      if (!isHomePage) {
        // If not on homepage and this is a relative hash link, redirect to home page + hash
        if (href.startsWith("#") || href.indexOf(window.location.pathname + "#") > -1) {
          e.preventDefault();
          window.location.href = homeUrl + hash;
        }
      } else {
        // If on homepage, smooth scroll to the target section
        const targetSection = document.querySelector(hash);
        if (targetSection) {
          e.preventDefault();
          
          // Close mobile menu if open
          const mobileNav = document.getElementById("mobile-menu");
          const toggleBtn = document.querySelector(".mobile-menu-toggle");
          if (mobileNav && mobileNav.classList.contains("open")) {
            mobileNav.classList.remove("open");
            mobileNav.setAttribute("aria-hidden", "true");
            if (toggleBtn) {
              toggleBtn.setAttribute("aria-expanded", "false");
            }
            document.body.style.overflow = "";
          }

          // Smooth scroll to the element
          targetSection.scrollIntoView({ behavior: "smooth", block: "start" });
          
          // Update URL hash without jumping the page
          history.pushState(null, null, hash);
        }
      }
    });
  });
}

/**
 * Triggers the premium simulated OTP verification modal overlay.
 * Uses event-driven transitions and auto-focus inputs.
 */
function triggerOtpVerification(phoneNumber, onVerified, onCancelled) {
  // Soft OTP Bypass for Staging/Testing
  if (typeof si50_ajax !== "undefined" && si50_ajax.otp_bypass) {
    console.log("OTP Verification Bypassed (Staging Mode Active). Proceeding directly to verification completion.");
    onVerified();
    return;
  }

  const ajaxUrl = typeof si50_ajax !== "undefined" ? si50_ajax.ajax_url : "/wp-admin/admin-ajax.php";

  // Check if backdrop already exists, if not create it
  let backdrop = document.getElementById("si50-otp-backdrop");
  if (!backdrop) {
    backdrop = document.createElement("div");
    backdrop.id = "si50-otp-backdrop";
    backdrop.className = "si50-otp-backdrop";
    backdrop.setAttribute("role", "dialog");
    backdrop.setAttribute("aria-modal", "true");
    backdrop.setAttribute("aria-labelledby", "si50-otp-title");
    
    backdrop.innerHTML = `
      <div class="si50-otp-modal">
        <div class="si50-otp-header">
          <span class="si50-otp-icon" aria-hidden="true">🔒</span>
          <h2 class="si50-otp-title" id="si50-otp-title">Mobile Vetting Code</h2>
          <p class="si50-otp-desc">To protect our community safety, we have sent a 4-Digit Mobile OTP to confirm ownership of your number:</p>
          <div class="si50-otp-phone-display" id="si50-otp-phone-display"></div>
        </div>
        
        <div class="si50-otp-error-msg" id="si50-otp-error-msg">Please enter a valid 4-digit code.</div>
        
        <div class="si50-otp-input-group">
          <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric" aria-label="First digit" required>
          <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric" aria-label="Second digit" required disabled>
          <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric" aria-label="Third digit" required disabled>
          <input type="text" class="otp-digit" maxlength="1" pattern="[0-9]" inputmode="numeric" aria-label="Fourth digit" required disabled>
        </div>
        
        <p style="font-size: 13px; margin-top:-5px; margin-bottom: 12px; color: #1B3B2B; font-weight:600;">
          💬 A real-time verification text has been routed to your WhatsApp.
        </p>

        <div class="si50-otp-timer" id="si50-otp-timer">Resend OTP in <span id="otp-timer-sec">30</span>s</div>
        
        <div class="si50-otp-actions">
          <button type="button" class="btn btn-primary si50-otp-btn-verify" id="si50-otp-btn-verify">Verify & Submit</button>
          <button type="button" class="si50-otp-btn-cancel" id="si50-otp-btn-cancel">Cancel & Edit Details</button>
        </div>
      </div>
    `;
    
    document.body.appendChild(backdrop);
    setupOtpInputInteractions(backdrop);
  }
  
  // Set Phone Number Display
  const phoneDisplay = backdrop.querySelector("#si50-otp-phone-display");
  if (phoneDisplay) {
    phoneDisplay.innerText = phoneNumber;
  }
  
  const errorMsg = backdrop.querySelector("#si50-otp-error-msg");
  if (errorMsg) {
    errorMsg.style.display = "none";
  }

  // Reset inputs
  const digits = backdrop.querySelectorAll(".otp-digit");
  digits.forEach((inp, idx) => {
    inp.value = "";
    if (idx === 0) {
      inp.disabled = false;
    } else {
      inp.disabled = true;
    }
  });

  // AJAX call to send real OTP code via the production-engine
  const sendOtpRequest = () => {
    const formData = new FormData();
    formData.append('action', 'si50_send_otp');
    formData.append('phone', phoneNumber);
    if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
      formData.append('security', si50_ajax.nonce);
    }
    fetch(ajaxUrl, {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (!data.success) {
        alert(data.data.message || 'Failed to dispatch verification code.');
      }
    })
    .catch(err => console.error('OTP Send Error:', err));
  };
  
  sendOtpRequest();

  // Start Resend Timer
  let timeLeft = 30;
  const timerSec = backdrop.querySelector("#otp-timer-sec");
  const timerDiv = backdrop.querySelector("#si50-otp-timer");
  if (timerSec) {
    timerSec.innerText = timeLeft;
  }
  if (timerDiv) {
    timerDiv.innerHTML = `Resend OTP in <span id="otp-timer-sec">${timeLeft}</span>s`;
  }
  
  clearInterval(window.otpTimerInterval);
  window.otpTimerInterval = setInterval(() => {
    timeLeft--;
    const tSec = backdrop.querySelector("#otp-timer-sec");
    if (tSec) tSec.innerText = timeLeft;
    
    if (timeLeft <= 0) {
      clearInterval(window.otpTimerInterval);
      if (timerDiv) {
        timerDiv.innerHTML = `<a class="si50-otp-resend-link" id="si50-otp-resend" style="color:var(--color-terracotta); font-weight:700; text-decoration:underline; cursor:pointer;">Resend OTP code</a>`;
        backdrop.querySelector("#si50-otp-resend").addEventListener("click", () => {
          triggerOtpVerification(phoneNumber, onVerified, onCancelled);
        });
      }
    }
  }, 1000);

  // Bind Buttons
  const verifyBtn = backdrop.querySelector("#si50-otp-btn-verify");
  const cancelBtn = backdrop.querySelector("#si50-otp-btn-cancel");
  
  // Clean old event listeners
  const newVerifyBtn = verifyBtn.cloneNode(true);
  const newCancelBtn = cancelBtn.cloneNode(true);
  verifyBtn.parentNode.replaceChild(newVerifyBtn, verifyBtn);
  cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);

  // Show modal
  setTimeout(() => {
    backdrop.classList.add("active");
    backdrop.querySelectorAll(".otp-digit")[0].focus();
  }, 50);

  newVerifyBtn.addEventListener("click", () => {
    let otpVal = "";
    digits.forEach(inp => otpVal += inp.value);
    
    if (otpVal.length < 4 || !/^\d{4}$/.test(otpVal)) {
      if (errorMsg) {
        errorMsg.innerText = "Please enter a valid 4-digit code.";
        errorMsg.style.display = "block";
      }
      return;
    }
    
    if (errorMsg) {
      errorMsg.style.display = "none";
    }
    newVerifyBtn.disabled = true;
    newVerifyBtn.innerHTML = `<span class="otp-spinner"></span>Verifying OTP...`;
    
    // Call backend verify handler
    const verifyData = new FormData();
    verifyData.append('action', 'si50_verify_otp');
    verifyData.append('phone', phoneNumber);
    verifyData.append('otp', otpVal);
    if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
      verifyData.append('security', si50_ajax.nonce);
    }

    fetch(ajaxUrl, {
      method: 'POST',
      body: verifyData
    })
    .then(res => res.json())
    .then(data => {
      newVerifyBtn.disabled = false;
      newVerifyBtn.innerText = "Verify & Submit";
      if (data.success) {
        backdrop.classList.remove("active");
        clearInterval(window.otpTimerInterval);
        onVerified();
      } else {
        if (errorMsg) {
          errorMsg.innerText = data.data.message || "Invalid code. Please try again.";
          errorMsg.style.display = "block";
        }
      }
    })
    .catch(err => {
      console.error('OTP Verification Error:', err);
      newVerifyBtn.disabled = false;
      newVerifyBtn.innerText = "Verify & Submit";
      if (errorMsg) {
        errorMsg.innerText = "Network connection failed. Please try again.";
        errorMsg.style.display = "block";
      }
    });
  });

  newCancelBtn.addEventListener("click", () => {
    backdrop.classList.remove("active");
    clearInterval(window.otpTimerInterval);
    onCancelled();
  });
}

/**
 * Handles tab transitions and backspaces for multi-input OTP grids
 */
function setupOtpInputInteractions(backdrop) {
  const digits = backdrop.querySelectorAll(".otp-digit");
  
  digits.forEach((inp, idx) => {
    // Typing input digit behavior
    inp.addEventListener("input", (e) => {
      const val = inp.value;
      if (val.length === 1 && idx < 3) {
        digits[idx + 1].disabled = false;
        digits[idx + 1].focus();
      }
    });

    // Keydown backspace behavior
    inp.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && inp.value === "" && idx > 0) {
        digits[idx - 1].focus();
        digits[idx].disabled = true;
      }
    });
    
    // Support numeric digits only
    inp.addEventListener("keypress", (e) => {
      if (e.which < 48 || e.which > 57) {
        e.preventDefault();
      }
    });
  });
}

/**
 * 8. Login Modal Trigger and Submission Handling
 */
function initLoginModal() {
  const triggers = document.querySelectorAll(".si50-trigger-login");
  const backdrop = document.getElementById("login-modal-backdrop");
  const closeBtn = document.getElementById("close-login-modal");
  const form = document.getElementById("si50-login-form");

  if (!backdrop) return;

  // Show Modal
  triggers.forEach(trigger => {
    trigger.addEventListener("click", (e) => {
      e.preventDefault();
      backdrop.classList.add("active");
      backdrop.setAttribute("aria-hidden", "false");
      const usernameInp = document.getElementById("login-username");
      if (usernameInp) usernameInp.focus();
    });
  });

  // Close Modal
  const closeModal = () => {
    backdrop.classList.remove("active");
    backdrop.setAttribute("aria-hidden", "true");
  };

  if (closeBtn) {
    closeBtn.addEventListener("click", closeModal);
  }

  backdrop.addEventListener("click", (e) => {
    if (e.target === backdrop) {
      closeModal();
    }
  });

  // Handle ESC key to close
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && backdrop.classList.contains("active")) {
      closeModal();
    }
  });

  // Form Submission
  if (form) {
    const ajaxUrl = typeof si50_ajax !== "undefined" ? si50_ajax.ajax_url : "/wp-admin/admin-ajax.php";
    const usernameInput = document.getElementById("login-username");
    const passwordInput = document.getElementById("login-password");
    const generalError = document.getElementById("login-general-error");
    const submitBtn = form.querySelector("button[type='submit']");

    form.addEventListener("submit", (e) => {
      e.preventDefault();
      if (!usernameInput || !passwordInput) return;

      clearError(usernameInput, "login-username-error");
      clearError(passwordInput, "login-password-error");
      if (generalError) {
        generalError.style.display = "none";
        generalError.innerText = "";
      }

      let isValid = true;
      if (!usernameInput.value.trim()) {
        showError(usernameInput, "login-username-error", "Please enter your username/email.");
        isValid = false;
      }
      if (!passwordInput.value) {
        showError(passwordInput, "login-password-error", "Please enter your password.");
        isValid = false;
      }

      if (isValid) {
        submitBtn.disabled = true;
        const originalText = submitBtn.innerText;
        submitBtn.innerText = "Logging In...";

        const formData = new FormData();
        formData.append("action", "si50_ajax_login");
        formData.append("username", usernameInput.value.trim());
        formData.append("password", passwordInput.value);
        if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
          formData.append("security", si50_ajax.nonce);
        }

        fetch(ajaxUrl, {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            submitBtn.innerText = "Success! Redirecting...";
            if (data.data.redirect) {
              window.location.href = data.data.redirect;
            } else {
              window.location.reload();
            }
          } else {
            submitBtn.disabled = false;
            submitBtn.innerText = originalText;
            if (generalError) {
              generalError.innerText = data.data.message || "Invalid credentials.";
              generalError.style.display = "block";
            }
          }
        })
        .catch(err => {
          console.error("Login Error:", err);
          submitBtn.disabled = false;
          submitBtn.innerText = originalText;
          if (generalError) {
            generalError.innerText = "Network connection failed. Please try again.";
            generalError.style.display = "block";
          }
        });
      }
    });
  }
}

/**
 * 9. Handles connect handshake requests AJAX in the Directory grid
 */
function initDirectoryActions() {
  const ajaxUrl = typeof si50_ajax !== "undefined" ? si50_ajax.ajax_url : "/wp-admin/admin-ajax.php";

  // Interest Submission Buttons (Admin Matchmaking)
  const interestBtns = document.querySelectorAll(".si50-btn-interest");
  interestBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      const receiverId = btn.getAttribute("data-receiver-id");
      if (!receiverId) return;

      btn.disabled = true;
      const originalText = btn.innerText;
      btn.innerText = "Submitting Interest...";

      const formData = new FormData();
      formData.append("action", "si50_submit_interest");
      formData.append("receiver_id", receiverId);

      fetch(ajaxUrl, {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          btn.innerText = "⏱ Interest Submitted";
          btn.style.opacity = "0.7";
          btn.style.cursor = "not-allowed";
        } else {
          btn.disabled = false;
          btn.innerText = originalText;
          alert(data.data.message || "Failed to submit interest.");
        }
      })
      .catch(err => {
        console.error("Interest Request Error:", err);
        btn.disabled = false;
        btn.innerText = originalText;
        alert("Failed to submit interest request. Please try again.");
      });
    });
  });

  // Accept Requests Buttons
  const acceptBtns = document.querySelectorAll(".si50-btn-accept");
  acceptBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      const senderId = btn.getAttribute("data-sender-id");
      if (!senderId) return;

      btn.disabled = true;
      btn.innerText = "Accepting...";

      const formData = new FormData();
      formData.append("action", "si50_respond_connect");
      formData.append("sender_id", senderId);
      formData.append("response", "approved");

      fetch(ajaxUrl, {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          window.location.reload();
        } else {
          btn.disabled = false;
          btn.innerText = "Accept Request";
          alert(data.data.message || "Failed to accept request.");
        }
      })
      .catch(err => {
        console.error("Accept Error:", err);
        btn.disabled = false;
        btn.innerText = "Accept Request";
      });
    });
  });

  // Decline Requests Buttons
  const declineBtns = document.querySelectorAll(".si50-btn-decline");
  declineBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      const senderId = btn.getAttribute("data-sender-id");
      if (!senderId) return;

      if (!confirm("Are you sure you want to decline this request?")) return;

      btn.disabled = true;
      btn.innerText = "Declining...";

      const formData = new FormData();
      formData.append("action", "si50_respond_connect");
      formData.append("sender_id", senderId);
      formData.append("response", "declined");

      fetch(ajaxUrl, {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          window.location.reload();
        } else {
          btn.disabled = false;
          btn.innerText = "Decline";
          alert(data.data.message || "Failed to decline request.");
        }
      })
      .catch(err => {
        console.error("Decline Error:", err);
        btn.disabled = false;
        btn.innerText = "Decline";
      });
    });
  });

  // Report Buttons
  const reportBtns = document.querySelectorAll(".si50-btn-report-member");
  reportBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      const reportedId = btn.getAttribute("data-reported-id");
      const reportedName = btn.getAttribute("data-reported-name");
      if (!reportedId) return;

      const reason = prompt(`Please specify a professional warning reason or feedback for reporting ${reportedName}'s profile:`);
      if (reason === null) return; // Cancelled
      if (reason.trim().length < 5) {
        alert("Please enter a valid reporting reason (minimum 5 characters).");
        return;
      }

      btn.disabled = true;
      const originalText = btn.innerText;
      btn.innerText = "Reporting...";

      const formData = new FormData();
      formData.append("action", "si50_report_member");
      formData.append("reported_user_id", reportedId);
      formData.append("reason", reason);
      if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
        formData.append("security", si50_ajax.nonce);
      }

      fetch(ajaxUrl, {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert(data.data.message);
          window.location.reload();
        } else {
          btn.disabled = false;
          btn.innerText = originalText;
          alert(data.data.message || "Failed to submit report.");
        }
      })
      .catch(err => {
        console.error("Report Error:", err);
        btn.disabled = false;
        btn.innerText = originalText;
        alert("Failed to report profile. Please try again.");
      });
    });
  });

  // Block Buttons
  const blockBtns = document.querySelectorAll(".si50-btn-block-member");
  blockBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      const blockedId = btn.getAttribute("data-blocked-id");
      const blockedName = btn.getAttribute("data-blocked-name");
      if (!blockedId) return;

      if (!confirm(`Are you sure you want to block ${blockedName}? You will no longer view their profile in the directory.`)) return;

      btn.disabled = true;
      const originalText = btn.innerText;
      btn.innerText = "Blocking...";

      const formData = new FormData();
      formData.append("action", "si50_block_member");
      formData.append("blocked_user_id", blockedId);
      if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
        formData.append("security", si50_ajax.nonce);
      }

      fetch(ajaxUrl, {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert(data.data.message);
          window.location.reload();
        } else {
          btn.disabled = false;
          btn.innerText = originalText;
          alert(data.data.message || "Failed to block member.");
        }
      })
      .catch(err => {
        console.error("Block Error:", err);
        btn.disabled = false;
        btn.innerText = originalText;
        alert("Failed to block profile. Please try again.");
      });
    });
  });
}

/**
 * Member Profile Update Form Validation
 */
/**
 * Member Profile Info Form Validation
 */
function initProfileFormValidation() {
  const form = document.getElementById("si50-profile-info-form");
  if (!form) return;

  const nameInput = document.getElementById("profile-name");
  const dobInput = document.getElementById("profile-dob");
  const ageInput = document.getElementById("profile-age");
  const maritalInput = document.getElementById("profile-marital");
  const occupationInput = document.getElementById("profile-occupation");
  const phoneInput = document.getElementById("profile-phone");
  const cityInput = document.getElementById("profile-city");
  const emergencyInput = document.getElementById("profile-emergency");
  const messageInput = document.getElementById("profile-message");
  const passwordInput = document.getElementById("profile-password");
  const passwordConfirmInput = document.getElementById("profile-password-confirm");
  const locationPrefInput = document.getElementById("profile-location-pref");
  const statusMsg = document.getElementById("profile-status-msg");
  const submitBtn = document.getElementById("profile-submit-btn");

  const ajaxUrl = typeof si50_ajax !== "undefined" ? si50_ajax.ajax_url : "/wp-admin/admin-ajax.php";

  // Forgot password click handler inside Info tab
  const forgotBtn = document.getElementById("profile-forgot-password-btn");
  if (forgotBtn) {
    forgotBtn.addEventListener("click", () => {
      forgotBtn.disabled = true;
      const originalText = forgotBtn.innerText;
      forgotBtn.innerText = "Sending Reset Email...";
      
      const formData = new FormData();
      formData.append("action", "si50_ajax_forgot_password");
      if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
        formData.append("security", si50_ajax.nonce);
      }
      
      fetch(ajaxUrl, {
        method: "POST",
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert(data.data.message);
        } else {
          alert(data.data.message || "Failed to send reset link.");
        }
        forgotBtn.disabled = false;
        forgotBtn.innerText = originalText;
      })
      .catch(err => {
        console.error(err);
        forgotBtn.disabled = false;
        forgotBtn.innerText = originalText;
        alert("Failed to send reset link. Please try again.");
      });
    });
  }

	// Delete Account click handler
	const deleteBtn = document.getElementById("profile-delete-account-btn");
	if (deleteBtn) {
	  deleteBtn.addEventListener("click", () => {
		const confirmDelete = confirm("Are you absolutely sure you want to permanently delete your account? This action cannot be undone.");
		if (!confirmDelete) return;

		const confirmSecond = prompt('Type "DELETE" to confirm account deletion:');
		if (confirmSecond !== "DELETE") {
		  alert("Account deletion cancelled.");
		  return;
		}

		deleteBtn.disabled = true;
		const originalText = deleteBtn.innerText;
		deleteBtn.innerText = "Deleting Account...";
		
		const formData = new FormData();
		formData.append("action", "si50_ajax_delete_account");
		if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
		  formData.append("security", si50_ajax.nonce);
		}
		
		fetch(ajaxUrl, {
		  method: "POST",
		  body: formData
		})
		.then(res => res.json())
		.then(data => {
		  if (data.success) {
			alert(data.data.message || "Account successfully deleted.");
			window.location.href = "/";
		  } else {
			alert(data.data.message || "Failed to delete account.");
			deleteBtn.disabled = false;
			deleteBtn.innerText = originalText;
		  }
		})
		.catch(err => {
		  console.error(err);
		  deleteBtn.disabled = false;
		  deleteBtn.innerText = originalText;
		  alert("Failed to delete account. Please try again.");
		});
	  });
	}

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    let isValid = true;
    
    // Clear previous errors
    clearError(nameInput, "profile-name-error");
    clearError(dobInput, "profile-dob-error");
    clearError(ageInput, "profile-age-error");
    clearError(maritalInput, "profile-marital-error");
    clearError(occupationInput, "profile-occupation-error");
    clearError(phoneInput, "profile-phone-error");
    clearError(cityInput, "profile-city-error");
    clearError(emergencyInput, "profile-emergency-error");
    clearError(messageInput, "profile-message-error");
    if (passwordInput) clearError(passwordInput, "profile-password-error");
    if (passwordConfirmInput) clearError(passwordConfirmInput, "profile-password-confirm-error");
    if (locationPrefInput) clearError(locationPrefInput, "profile-location-pref-error");
    const visibilityInput = document.getElementById("profile-visibility");
    if (visibilityInput) clearError(visibilityInput, "profile-visibility-error");
    
    if (statusMsg) {
      statusMsg.style.display = "none";
      statusMsg.innerText = "";
      statusMsg.style.color = "";
    }

    const nameVal = nameInput.value.trim();
    const dobVal = dobInput.value;
    const ageVal = ageInput.value;
    const maritalVal = maritalInput.value;
    const occupationVal = occupationInput.value.trim();
    const phoneVal = phoneInput.value.trim();
    const cityVal = cityInput.value.trim();
    const emergencyVal = emergencyInput.value.trim();
    const messageVal = messageInput.value.trim();
    const locationPrefVal = locationPrefInput ? locationPrefInput.value : "";
    const passwordVal = passwordInput ? passwordInput.value : "";
    const passwordConfirmVal = passwordConfirmInput ? passwordConfirmInput.value : "";

    // 1. Name
    if (nameVal.length < 3) {
      showError(nameInput, "profile-name-error", "Please enter your full name (minimum 3 characters).");
      isValid = false;
    } else if (!/^[a-zA-Z\s]+$/.test(nameVal)) {
      showError(nameInput, "profile-name-error", "Please use letters and spaces only.");
      isValid = false;
    }

    // 2. DOB
    if (!dobVal) {
      showError(dobInput, "profile-dob-error", "Please enter your date of birth.");
      isValid = false;
    }

    // 3. Age
    if (!ageVal) {
      showError(ageInput, "profile-age-error", "Please select your age range.");
      isValid = false;
    }

    // 4. Marital
    if (!maritalVal) {
      showError(maritalInput, "profile-marital-error", "Please select marital status.");
      isValid = false;
    }

    // 5. Occupation
    if (occupationVal.length < 2) {
      showError(occupationInput, "profile-occupation-error", "Please enter your occupation.");
      isValid = false;
    }

    // 6. Phone
    if (phoneVal.length < 8) {
      showError(phoneInput, "profile-phone-error", "Please enter a valid phone number.");
      isValid = false;
    }

    // 7. City
    if (cityVal.length < 2) {
      showError(cityInput, "profile-city-error", "Please enter your city/location.");
      isValid = false;
    }

    // Emergency check removed for now

    // 8.5. Location Preference
    if (locationPrefInput && !locationPrefVal) {
      showError(locationPrefInput, "profile-location-pref-error", "Please select location match preference.");
      isValid = false;
    }

    // 8.6. Profile Visibility
    if (visibilityInput && !visibilityInput.value) {
      showError(visibilityInput, "profile-visibility-error", "Please select profile visibility setting.");
      isValid = false;
    }

    // 9. Intro Message
    if (messageVal.length < 10) {
      showError(messageInput, "profile-message-error", "Please write a brief biography (minimum 10 characters).");
      isValid = false;
    }

    // 10. Passwords
    if (passwordVal) {
      if (passwordVal.length < 6) {
        showError(passwordInput, "profile-password-error", "Password must be at least 6 characters.");
        isValid = false;
      }
      if (passwordVal !== passwordConfirmVal) {
        showError(passwordConfirmInput, "profile-password-confirm-error", "Passwords do not match.");
        isValid = false;
      }
    }

    if (isValid) {
      submitBtn.disabled = true;
      const originalText = submitBtn.innerText;
      submitBtn.innerText = "Saving Changes...";

      const formData = new FormData(form);
      formData.append('action', 'si50_ajax_update_profile_info');
      if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
        formData.append('security', si50_ajax.nonce);
      }

      fetch(ajaxUrl, {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          if (statusMsg) {
            statusMsg.style.display = "block";
            statusMsg.style.color = "#1e7e34";
            statusMsg.innerText = data.data.message;
          }
          submitBtn.innerText = "Saved!";
          
          setTimeout(() => {
            window.location.reload();
          }, 1500);
        } else {
          if (statusMsg) {
            statusMsg.style.display = "block";
            statusMsg.style.color = "var(--color-terracotta)";
            statusMsg.innerText = data.data.message || "An error occurred while saving.";
          }
          submitBtn.disabled = false;
          submitBtn.innerText = originalText;
        }
      })
      .catch(err => {
        console.error("Profile update error:", err);
        if (statusMsg) {
          statusMsg.style.display = "block";
          statusMsg.style.color = "var(--color-terracotta)";
          statusMsg.innerText = "Network error. Please try again.";
        }
        submitBtn.disabled = false;
        submitBtn.innerText = originalText;
      });
    } else {
      const firstError = form.querySelector(".error-msg:not(:empty)");
      if (firstError) {
        firstError.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    }
  });
}

/**
 * Member Preferences Form Validation
 */
function initProfilePreferencesFormValidation() {
  const form = document.getElementById("si50-profile-preferences-form");
  if (!form) return;

  const statusMsg = document.getElementById("preferences-status-msg");
  const submitBtn = document.getElementById("preferences-submit-btn");
  const ajaxUrl = typeof si50_ajax !== "undefined" ? si50_ajax.ajax_url : "/wp-admin/admin-ajax.php";

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    if (statusMsg) {
      statusMsg.style.display = "none";
      statusMsg.innerText = "";
    }

    submitBtn.disabled = true;
    const originalText = submitBtn.innerText;
    submitBtn.innerText = "Saving Preferences...";

    const formData = new FormData(form);
    formData.append('action', 'si50_ajax_update_profile_preferences');
    if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
      formData.append('security', si50_ajax.nonce);
    }

    fetch(ajaxUrl, {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        if (statusMsg) {
          statusMsg.style.display = "block";
          statusMsg.style.color = "#1e7e34";
          statusMsg.innerText = data.data.message;
        }
        submitBtn.innerText = "Saved!";
        setTimeout(() => {
          submitBtn.disabled = false;
          submitBtn.innerText = originalText;
          statusMsg.style.display = "none";
        }, 1500);
      } else {
        if (statusMsg) {
          statusMsg.style.display = "block";
          statusMsg.style.color = "var(--color-terracotta)";
          statusMsg.innerText = data.data.message || "An error occurred.";
        }
        submitBtn.disabled = false;
        submitBtn.innerText = originalText;
      }
    })
    .catch(err => {
      console.error(err);
      if (statusMsg) {
        statusMsg.style.display = "block";
        statusMsg.style.color = "var(--color-terracotta)";
        statusMsg.innerText = "Network error. Please try again.";
      }
      submitBtn.disabled = false;
      submitBtn.innerText = originalText;
    });
  });
}

/**
 * LinkedIn-style Header Profile Dropdown menu toggler
 */
function initProfileDropdown() {
  const container = document.querySelector(".profile-dropdown-container");
  if (!container) return;

  const btn = container.querySelector(".profile-avatar-btn");
  const menu = container.querySelector(".profile-dropdown-menu");

  if (!btn || !menu) return;

  btn.addEventListener("click", (e) => {
    e.stopPropagation();
    const isExpanded = btn.getAttribute("aria-expanded") === "true";
    btn.setAttribute("aria-expanded", !isExpanded);
    if (!isExpanded) {
      menu.style.display = "block";
    } else {
      menu.style.display = "none";
    }
  });

  document.addEventListener("click", (e) => {
    if (!container.contains(e.target)) {
      btn.setAttribute("aria-expanded", "false");
      menu.style.display = "none";
    }
  });
}

/**
 * Interactive "Learn More" Component Routing Engine (Task 4)
 * Displays structured modal with steps and schedules for home page offerings.
 */
function initOfferingModals() {
  const modal = document.getElementById("si50-offering-modal");
  const closeBtn = document.getElementById("si50-offering-modal-close");
  const contentArea = document.getElementById("si50-offering-modal-content-area");
  
  if (!modal || !closeBtn || !contentArea) return;

  const offeringDetails = {
    chai: {
      title: "Virtual Chai Chats",
      schedule: "📅 Daily at 4:00 PM – 5:00 PM IST",
      desc: "Daily, lighthearted virtual discussion circles. Join a table of 5-6 peers, sip your afternoon tea, and chat about books, travel memories, retro music, or life philosophies in a structured, welcoming environment."
    },
    meetups: {
      title: "Local Meetups",
      schedule: "📅 Alternating Saturday Mornings (8:30 AM – 10:30 AM)",
      desc: "Safe, carefully curated offline get-togethers in your city. From morning heritage walks and museum visits to calm Sunday brunches, feel the warmth of face-to-face connections in small, trusted groups."
    },
    hobbies: {
      title: "Hobby Circles",
      schedule: "📅 Ongoing Activity Boards & Monthly Show-and-Tell",
      desc: "Find partners for kitchen gardening, exchanging recipes, learning watercolor painting, discussing stock market investing, or starting a community library together."
    },
    learning: {
      title: "Lifelong Learning",
      schedule: "📅 Every Tuesday and Thursday (6:00 PM – 7:30 PM IST)",
      desc: "Age is just a number when it comes to curiosity. Attend interactive webinars and masterclasses covering digital banking safety, smartphone photography, yoga, health wellness, and creative writing."
    }
  };

  const cards = document.querySelectorAll(".offering-card");
  cards.forEach(card => {
    const learnMoreTrigger = card.querySelector(".offering-footer");
    const triggerElement = learnMoreTrigger || card;
    
    triggerElement.style.cursor = "pointer";
    triggerElement.addEventListener("click", (e) => {
      e.preventDefault();
      const featureKey = card.getAttribute("data-feature");
      const details = offeringDetails[featureKey];
      if (!details) return;

      contentArea.innerHTML = `
        <h2 id="si50-offering-modal-title" style="font-family: Georgia, serif; font-size: 1.8rem; color: #1b3b2b; margin-top: 0; margin-bottom: 10px; border-bottom: 2px solid #333333; padding-bottom: 10px;">${details.title}</h2>
        <div class="si50-modal-schedule-badge">${details.schedule}</div>
        <p style="font-size: 1rem; line-height: 1.5; color: #333333; margin-bottom: 20px;">${details.desc}</p>
        <div style="text-align: right; margin-top: 20px;">
          <button type="button" class="btn btn-primary" id="si50-offering-modal-ok" style="border: 2px solid #333333; background: #ffffff; color: #333333; padding: 10px 24px; font-weight: 700; cursor: pointer; border-radius: 4px; transition: background 0.2s;">Great, Got It</button>
        </div>
      `;

      const okBtn = document.getElementById("si50-offering-modal-ok");
      if (okBtn) {
        okBtn.addEventListener("mouseover", () => okBtn.style.background = "#f1f3f4");
        okBtn.addEventListener("mouseout", () => okBtn.style.background = "#ffffff");
        okBtn.addEventListener("click", closeModal);
      }

      openModal();
    });
  });

  function openModal() {
    modal.classList.add("show");
    document.body.style.overflow = "hidden";
  }

  function closeModal() {
    modal.classList.remove("show");
    document.body.style.overflow = "";
  }

  closeBtn.addEventListener("click", closeModal);
  modal.addEventListener("click", (e) => {
    if (e.target === modal) closeModal();
  });
}

/**
 * 10. Razorpay Client-Side Payment Flow Trigger
 */
function initRazorpayPayment() {
  const triggerBtn = document.getElementById("si50-razorpay-trigger-btn");
  if (!triggerBtn) return;

  const ajaxUrl = typeof si50_ajax !== "undefined" ? si50_ajax.ajax_url : "/wp-admin/admin-ajax.php";

  triggerBtn.addEventListener("click", (e) => {
    e.preventDefault();

    // Dynamically load Razorpay SDK script if not loaded
    if (typeof Razorpay === "undefined") {
      triggerBtn.disabled = true;
      triggerBtn.innerText = "Loading Payment System...";

      const script = document.createElement("script");
      script.src = "https://checkout.razorpay.com/v1/checkout.js";
      script.onload = () => {
        triggerBtn.disabled = false;
        triggerBtn.innerText = "Pay Verification Contribution (Razorpay / UPI)";
        // Re-trigger click
        triggerBtn.click();
      };
      script.onerror = () => {
        triggerBtn.disabled = false;
        triggerBtn.innerText = "Pay Verification Contribution (Razorpay / UPI)";
        alert("Failed to load Razorpay payment library. Please check your internet connection.");
      };
      document.head.appendChild(script);
      return;
    }

    triggerBtn.disabled = true;
    const originalText = triggerBtn.innerText;
    triggerBtn.innerText = "Opening Secure Checkout...";

    const formData = new FormData();
    formData.append("action", "si50_razorpay_create_order");
    if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
      formData.append("security", si50_ajax.nonce);
    }

    fetch(ajaxUrl, {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      triggerBtn.disabled = false;
      triggerBtn.innerText = originalText;

      if (data.success) {
        const payload = data.data;
        const options = {
          key: payload.key_id,
          amount: payload.amount,
          currency: payload.currency,
          name: payload.name,
          description: payload.description,
          order_id: payload.order_id,
          handler: function (response) {
            triggerBtn.disabled = true;
            triggerBtn.innerText = "Verifying Contribution Transaction...";

            const verifyData = new FormData();
            verifyData.append("action", "si50_verify_razorpay_payment");
            verifyData.append("razorpay_payment_id", response.razorpay_payment_id);
            verifyData.append("razorpay_order_id", response.razorpay_order_id);
            verifyData.append("razorpay_signature", response.razorpay_signature);
            if (typeof si50_ajax !== "undefined" && si50_ajax.nonce) {
              verifyData.append("security", si50_ajax.nonce);
            }

            fetch(ajaxUrl, {
              method: "POST",
              body: verifyData
            })
            .then(vRes => vRes.json())
            .then(vData => {
              if (vData.success) {
                alert(vData.data.message);
                window.location.href = vData.data.redirect;
              } else {
                triggerBtn.disabled = false;
                triggerBtn.innerText = originalText;
                alert(vData.data.message || "Signature verification check failed.");
                window.location.href = "/payment/failure/";
              }
            })
            .catch(vErr => {
              console.error(vErr);
              triggerBtn.disabled = false;
              triggerBtn.innerText = originalText;
              alert("Verification network failure. Please contact SecondInnings50 Support.");
              window.location.href = "/payment/failure/";
            });
          },
          prefill: payload.prefill,
          theme: {
            color: "#1B3B2B" // Forest Green
          }
        };

        const rzp = new Razorpay(options);
        rzp.open();
      } else {
        alert(data.data.message || "Failed to initiate transaction.");
      }
    })
    .catch(err => {
      console.error(err);
      triggerBtn.disabled = false;
      triggerBtn.innerText = originalText;
      alert("Failed to communicate with payment processor. Please try again.");
    });
  });
}

/**
 * Clipboard Copy Sharing Helper
 */
function initSocialSharing() {
  const copyBtns = document.querySelectorAll(".si50-copy-share-link");
  copyBtns.forEach(btn => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const shareUrl = btn.getAttribute("data-share-url");
      const shareTitle = btn.getAttribute("data-share-title") || "SecondInnings50 Companion Circle";
      const text = `Check out this SecondInnings50 community invitation: ${shareTitle}! Join us here: ${shareUrl}`;
      
      navigator.clipboard.writeText(text).then(() => {
        const originalText = btn.innerHTML;
        btn.innerHTML = "✓ Copied Invite!";
        btn.style.backgroundColor = "#e7f4e8";
        btn.style.color = "#1e7e34";
        btn.style.borderColor = "#1e7e34";
        setTimeout(() => {
          btn.innerHTML = originalText;
          btn.style.backgroundColor = "";
          btn.style.color = "";
          btn.style.borderColor = "";
        }, 2000);
      }).catch(err => {
        console.error("Clipboard copy failed:", err);
        alert("Failed to copy link automatically. Please copy the URL from your browser address bar.");
      });
    });
  });
}



