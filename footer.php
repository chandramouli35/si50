  <!-- FOOTER -->
  <footer class="main-footer" aria-label="<?php esc_attr_e( 'Footer Navigation', 'secondinnings50' ); ?>">
    <div class="container footer-grid">
      <div class="footer-brand">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="<?php esc_attr_e( 'SecondInnings50 Home', 'secondinnings50' ); ?>">
          <span class="logo-text">SecondInnings<span class="logo-accent">50</span></span>
        </a>
        <p class="footer-desc">
          <?php esc_html_e( "Building India's finest community space for the 40+ and 45+ generation. Designed for comfort, dignity, and authentic friendships.", 'secondinnings50' ); ?>
        </p>
      </div>
      
      <?php
      $is_home       = is_front_page() || is_home();
      $about_url     = $is_home ? '#about' : esc_url( home_url( '/#about' ) );
      $offerings_url = $is_home ? '#offerings' : esc_url( home_url( '/#offerings' ) );
      $safety_url    = $is_home ? '#safety' : esc_url( home_url( '/#safety' ) );
      ?>
      <div class="footer-links-group">
        <div class="footer-col">
          <h4 class="footer-heading"><?php esc_html_e( 'Platform', 'secondinnings50' ); ?></h4>
          <ul>
            <li><a href="<?php echo $about_url; ?>" class="footer-link"><?php esc_html_e( 'Our Purpose', 'secondinnings50' ); ?></a></li>
            <li><a href="<?php echo $offerings_url; ?>" class="footer-link"><?php esc_html_e( 'How it Works', 'secondinnings50' ); ?></a></li>
            <li><a href="<?php echo $safety_url; ?>" class="footer-link"><?php esc_html_e( 'Trust &amp; Safety', 'secondinnings50' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="footer-link"><?php esc_html_e( 'Contact Us', 'secondinnings50' ); ?></a></li>
          </ul>
        </div>
        
        <div class="footer-col">
          <h4 class="footer-heading"><?php esc_html_e( 'Community', 'secondinnings50' ); ?></h4>
          <ul>
            <li><a href="<?php echo $offerings_url; ?>" class="footer-link"><?php esc_html_e( 'Chai Chats', 'secondinnings50' ); ?></a></li>
            <li><a href="<?php echo $offerings_url; ?>" class="footer-link"><?php esc_html_e( 'Local Meetups', 'secondinnings50' ); ?></a></li>
            <li><a href="<?php echo $offerings_url; ?>" class="footer-link"><?php esc_html_e( 'Hobby Circles', 'secondinnings50' ); ?></a></li>
          </ul>
        </div>
        
        <div class="footer-col">
          <h4 class="footer-heading"><?php esc_html_e( 'Legal &amp; Support', 'secondinnings50' ); ?></h4>
          <ul>
            <li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" class="footer-link"><?php esc_html_e( 'Privacy Policy', 'secondinnings50' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/terms-and-conditions/' ) ); ?>" class="footer-link"><?php esc_html_e( 'Terms & Conditions', 'secondinnings50' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/refund-policy/' ) ); ?>" class="footer-link"><?php esc_html_e( 'Refund Policy', 'secondinnings50' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="footer-link"><?php esc_html_e( 'Contact Us', 'secondinnings50' ); ?></a></li>
            <li><a href="mailto:support@secondinnings50.in" class="footer-link"><?php esc_html_e( 'Grievance Email', 'secondinnings50' ); ?></a></li>
          </ul>
        </div>
      </div>
    </div>
    
    <div class="container footer-bottom">
      <p class="copyright-text">
        &copy; 2026 All Rights Reserved SecondInnings 50
      </p>
      <div class="footer-socials">
        <a href="#" aria-label="Facebook">
          <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
        </a>
        <a href="https://www.instagram.com/secondinnings50.in?igsh=dm5sMmw0cGJrY3Iy" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
          <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
        </a>
        <a href="#" aria-label="LinkedIn">
          <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
        </a>
      </div>
    </div>
  </footer>

  <?php wp_footer(); ?>
</body>
</html>
