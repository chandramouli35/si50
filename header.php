<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/hero.png" type="image/png">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-home-url="<?php echo esc_url( home_url( '/' ) ); ?>">
<?php wp_body_open(); ?>

  <!-- Skiplink for Web Accessibility -->
  <a href="#main-content" class="skip-link"><?php esc_html_e( 'Skip to main content', 'secondinnings50' ); ?></a>

  <!-- PREMIUM NAVIGATION HEADER -->
  <header class="main-header" aria-label="<?php esc_attr_e( 'Main Navigation', 'secondinnings50' ); ?>">
    <div class="header-container">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="<?php esc_attr_e( 'SecondInnings50 Home', 'secondinnings50' ); ?>">
        <?php
        if ( has_custom_logo() ) {
            the_custom_logo();
        } else {
            echo '<span class="logo-text">SecondInnings<span class="logo-accent">50</span></span>';
        }
        ?>
      </a>
      
      <!-- Desktop Navigation Menu -->
      <nav class="desktop-nav" aria-label="<?php esc_attr_e( 'Desktop navigation links', 'secondinnings50' ); ?>">
        <?php
        if ( has_nav_menu( 'menu-1' ) ) {
            wp_nav_menu( array(
                'theme_location' => 'menu-1',
                'container'      => false,
                'menu_class'     => 'desktop-nav-list',
            ) );
        } else {
            $is_home       = is_front_page() || is_home();
            $about_url     = $is_home ? '#about' : esc_url( home_url( '/#about' ) );
            $offerings_url = $is_home ? '#offerings' : esc_url( home_url( '/#offerings' ) );
            $safety_url    = $is_home ? '#safety' : esc_url( home_url( '/#safety' ) );
            $cta_url       = $is_home ? '#cta' : esc_url( home_url( '/#cta' ) );

            echo '<ul>';
            echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="nav-link' . ( $is_home ? ' active' : '' ) . '">' . esc_html__( 'Home', 'secondinnings50' ) . '</a></li>';
            echo '<li><a href="' . $about_url . '" class="nav-link">' . esc_html__( 'Our Purpose', 'secondinnings50' ) . '</a></li>';
            echo '<li><a href="' . $offerings_url . '" class="nav-link">' . esc_html__( 'Explore Circles', 'secondinnings50' ) . '</a></li>';
            echo '<li><a href="' . $safety_url . '" class="nav-link">' . esc_html__( 'Trust &amp; Safety', 'secondinnings50' ) . '</a></li>';
            echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '" class="nav-link">' . esc_html__( 'Contact Us', 'secondinnings50' ) . '</a></li>';
            
            // No additional menu items for logged-in users on desktop text navigation since they are handled by the icons header block.
            echo '</ul>';
        }
        ?>
      </nav>

      <div class="header-actions">
        <?php
        $is_home = is_front_page() || is_home();
        $cta_url = $is_home ? '#cta' : esc_url( home_url( '/#cta' ) );
        ?>
        <?php if ( is_user_logged_in() ) : ?>
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'si50_connect_requests';
            $bell_count = 0;
            if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) === $table_name ) {
                $bell_count = $wpdb->get_var( $wpdb->prepare(
                    "SELECT COUNT(*) FROM $table_name WHERE receiver_id = %d AND status = 'pending'",
                    get_current_user_id()
                ) );
            }
            $user_id = get_current_user_id();
            $userdata = get_userdata( $user_id );
            $m_name = get_user_meta( $user_id, 'si50_fullname', true );
            if ( empty( $m_name ) ) {
                $m_name = $userdata ? $userdata->display_name : '';
            }
            ?>
            <a href="<?php echo esc_url( home_url( '/notifications/' ) ); ?>" class="bell-nav-link" aria-label="<?php esc_attr_e( 'Notifications', 'secondinnings50' ); ?>" style="position: relative; display: inline-flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; border: 1.5px solid #333333; background: #FFFFFF; color: #333333; margin-right: 12px; cursor: pointer; text-decoration: none; vertical-align: middle;">
                <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" style="display: block;">
                    <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                </svg>
                <?php if ( $bell_count > 0 ) : ?>
                    <span class="bell-badge" style="position: absolute; top: -5px; right: -5px; background: #dc3545; color: #FFFFFF; font-size: 0.75rem; font-weight: 700; min-width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; padding: 2px; border: 1.5px solid #FFFFFF;"><?php echo intval( $bell_count ); ?></span>
                <?php endif; ?>
            </a>
            
            <div class="profile-dropdown-container" style="position: relative; display: inline-block; vertical-align: middle; margin-right: 12px;">
                <button class="profile-avatar-btn" aria-label="<?php esc_attr_e( 'User menu', 'secondinnings50' ); ?>" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; border: 1.5px solid #333333; background: #FFFFFF; color: #333333; cursor: pointer; padding: 0; position: relative;">
                    <span style="font-weight: 700; font-size: 1rem; text-transform: uppercase;">
                        <?php 
                        $first_char = mb_substr( $m_name, 0, 1, 'utf-8' ); 
                        echo esc_html( $first_char ? $first_char : 'U' ); 
                        ?>
                    </span>
                    <span style="position: absolute; bottom: 2px; right: 2px; width: 8px; height: 8px; background: #28a745; border-radius: 50%; border: 1px solid #FFFFFF;"></span>
                </button>
                <div class="profile-dropdown-menu" style="display: none; position: absolute; right: 0; top: 52px; width: 220px; background-color: #FFFFFF; border: 2px solid #333333; border-radius: var(--radius-md); box-shadow: 0 10px 25px rgba(0,0,0,0.1); z-index: 1000; padding: var(--spacing-xs) 0; text-align: left;">
                    <div style="padding: 10px 15px; border-bottom: 1.5px solid var(--color-border); font-size: var(--fs-xs); color: var(--color-charcoal-muted);">
                        <p style="font-weight: 700; color: var(--color-forest); margin: 0 0 2px 0;"><?php echo esc_html( $m_name ); ?></p>
                        <p style="margin: 0; font-size: 0.75rem; word-break: break-all;"><?php echo esc_html( $userdata ? $userdata->user_email : '' ); ?></p>
                    </div>
                    <a href="<?php echo esc_url( home_url( '/profile/?tab=info' ) ); ?>" style="display: block; padding: 10px 15px; text-decoration: none; color: var(--color-charcoal); font-weight: 600; font-size: var(--fs-xs); transition: background 0.2s;" onmouseover="this.style.background='var(--color-forest-light)';" onmouseout="this.style.background='none';"><?php esc_html_e( 'My Profile', 'secondinnings50' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/profile/?tab=preferences' ) ); ?>" style="display: block; padding: 10px 15px; text-decoration: none; color: var(--color-charcoal); font-weight: 600; font-size: var(--fs-xs); transition: background 0.2s;" onmouseover="this.style.background='var(--color-forest-light)';" onmouseout="this.style.background='none';"><?php esc_html_e( 'My Preferences', 'secondinnings50' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/profile/?tab=connections' ) ); ?>" style="display: block; padding: 10px 15px; text-decoration: none; color: var(--color-charcoal); font-weight: 600; font-size: var(--fs-xs); transition: background 0.2s;" onmouseover="this.style.background='var(--color-forest-light)';" onmouseout="this.style.background='none';"><?php esc_html_e( 'My Connections', 'secondinnings50' ); ?></a>
                    <div style="border-top: 1.5px solid var(--color-border); margin-top: 5px; padding-top: 5px;">
                        <a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" style="display: block; padding: 10px 15px; text-decoration: none; color: var(--color-terracotta); font-weight: 700; font-size: var(--fs-xs); transition: background 0.2s;" onmouseover="this.style.background='var(--color-terracotta-light)';" onmouseout="this.style.background='none';"><?php esc_html_e( 'Log Out', 'secondinnings50' ); ?></a>
                    </div>
                </div>
            </div>
            <a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>" class="btn btn-primary btn-nav"><?php esc_html_e( 'Directory', 'secondinnings50' ); ?></a>
        <?php else : ?>
            <a href="#login" class="btn btn-secondary btn-nav si50-trigger-login" style="margin-right: 8px;"><?php esc_html_e( 'Log In', 'secondinnings50' ); ?></a>
        <?php endif; ?>
        <!-- Hamburger Menu Button for Mobile -->
        <button class="mobile-menu-toggle" aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'secondinnings50' ); ?>" aria-expanded="false">
          <span class="bar"></span>
          <span class="bar"></span>
          <span class="bar"></span>
        </button>
      </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <nav class="mobile-nav" id="mobile-menu" aria-label="<?php esc_attr_e( 'Mobile navigation links', 'secondinnings50' ); ?>" aria-hidden="true">
      <?php
      if ( has_nav_menu( 'menu-1' ) ) {
          wp_nav_menu( array(
              'theme_location' => 'menu-1',
              'container'      => false,
              'menu_class'     => 'mobile-nav-list',
          ) );
      } else {
          $is_home       = is_front_page() || is_home();
          $about_url     = $is_home ? '#about' : esc_url( home_url( '/#about' ) );
          $offerings_url = $is_home ? '#offerings' : esc_url( home_url( '/#offerings' ) );
          $safety_url    = $is_home ? '#safety' : esc_url( home_url( '/#safety' ) );
          $cta_url       = $is_home ? '#cta' : esc_url( home_url( '/#cta' ) );

          echo '<ul>';
          echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="mobile-nav-link">' . esc_html__( 'Home', 'secondinnings50' ) . '</a></li>';
          echo '<li><a href="' . $about_url . '" class="mobile-nav-link">' . esc_html__( 'Our Purpose', 'secondinnings50' ) . '</a></li>';
          echo '<li><a href="' . $offerings_url . '" class="mobile-nav-link">' . esc_html__( 'Explore Circles', 'secondinnings50' ) . '</a></li>';
          echo '<li><a href="' . $safety_url . '" class="mobile-nav-link">' . esc_html__( 'Trust &amp; Safety', 'secondinnings50' ) . '</a></li>';
          echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '" class="mobile-nav-link">' . esc_html__( 'Contact Us', 'secondinnings50' ) . '</a></li>';
          
          if ( is_user_logged_in() ) {
              global $wpdb;
              $table_name = $wpdb->prefix . 'si50_connect_requests';
              $bell_count = 0;
              if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) === $table_name ) {
                  $bell_count = $wpdb->get_var( $wpdb->prepare(
                      "SELECT COUNT(*) FROM $table_name WHERE receiver_id = %d AND status = 'pending'",
                      get_current_user_id()
                  ) );
              }
              $bell_text = $bell_count > 0 ? sprintf( esc_html__( 'Notifications (%d)', 'secondinnings50' ), $bell_count ) : esc_html__( 'Notifications', 'secondinnings50' );
              echo '<li><a href="' . esc_url( home_url( '/notifications/' ) ) . '" class="mobile-nav-link">' . $bell_text . '</a></li>';
              echo '<li><a href="' . esc_url( home_url( '/profile/' ) ) . '" class="mobile-nav-link">' . esc_html__( 'My Profile', 'secondinnings50' ) . '</a></li>';
              echo '<li><a href="' . esc_url( home_url( '/directory/' ) ) . '" class="mobile-nav-link">' . esc_html__( 'Directory', 'secondinnings50' ) . '</a></li>';
              echo '<li><a href="' . esc_url( wp_logout_url( home_url( '/' ) ) ) . '" class="mobile-nav-link">' . esc_html__( 'Log Out', 'secondinnings50' ) . '</a></li>';
          } else {
              echo '<li><a href="#login" class="mobile-nav-link si50-trigger-login">' . esc_html__( 'Log In', 'secondinnings50' ) . '</a></li>';
          }
          echo '</ul>';
      }
      ?>
    </nav>
  </header>
