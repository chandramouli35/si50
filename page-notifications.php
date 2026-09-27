<?php
/**
 * Template Name: Connections Notifications Center
 *
 * @package SecondInnings50
 */

get_header();

// Guard access: User must be logged in and approved to manage connection requests
$current_user_id = get_current_user_id();
$is_logged_in    = is_user_logged_in();
$vetting_status  = $is_logged_in ? get_user_meta( $current_user_id, 'si50_vetting_status', true ) : 'pending_review';

if ( empty( $vetting_status ) ) {
	$vetting_status = 'pending_review';
}

$is_approved = ( 'approved' === $vetting_status );
?>

<main id="main-content" class="site-main content-area" style="background-color: var(--bg-warm); min-height: 80vh; padding: var(--spacing-xl) 0;">
  <div class="container" style="max-width: 900px;">

	<?php if ( ! $is_logged_in ) : ?>
		<!-- Non-Logged In State Warning -->
		<div class="card text-center" style="max-width: 600px; margin: 40px auto; padding: var(--spacing-xl); border: 2px solid #333333 !important; background-color: #FFFFFF !important; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: var(--radius-lg);">
			<span style="font-size: 3rem; display: block; margin-bottom: var(--spacing-md);">🔒</span>
			<h2 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-forest); margin-bottom: 12px;">
				<?php esc_html_e( 'Private Notifications Center', 'secondinnings50' ); ?>
			</h2>
			<p style="color: var(--color-charcoal-muted); margin-bottom: 24px; line-height: 1.6;">
				<?php esc_html_e( 'To protect the safety of our mature community, please log in to manage your connection handshakes and notifications.', 'secondinnings50' ); ?>
			</p>
			<div>
				<a href="#login" class="btn btn-primary si50-trigger-login" style="font-weight: 700; padding: 12px 24px;"><?php esc_html_e( 'Log In Securely', 'secondinnings50' ); ?></a>
			</div>
		</div>

	<?php elseif ( 'rejected' === $vetting_status ) : 
		$rejection_note = get_user_meta( $current_user_id, 'si50_rejection_note', true );
		if ( empty( $rejection_note ) ) {
			$rejection_note = esc_html__( 'No additional details provided. Please check that you supplied a valid WhatsApp contact number and real name.', 'secondinnings50' );
		}
		?>
		<!-- Application Declined State Notice -->
		<div class="card text-center" style="max-width: 600px; margin: 40px auto; padding: var(--spacing-xl); border: 2.5px solid var(--color-terracotta) !important; background-color: #FFFFFF !important; box-shadow: 0 10px 30px rgba(217, 83, 79, 0.15); border-radius: var(--radius-lg);">
			<span style="font-size: 3.5rem; display: block; margin-bottom: var(--spacing-md);">⚠️</span>
			<h2 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-terracotta-dark); margin-bottom: 15px;">
				<?php esc_html_e( 'Application Declined', 'secondinnings50' ); ?>
			</h2>
			<div style="background-color: #fdf3f2; border: 1.5px solid var(--color-terracotta); padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 20px; text-align: left;">
				<p style="color: var(--color-terracotta-dark); font-weight: 700; margin-bottom: 8px; font-size: var(--fs-md);">
					<?php esc_html_e( 'Feedback from our Coordination Team:', 'secondinnings50' ); ?>
				</p>
				<p style="color: var(--color-charcoal); font-size: var(--fs-sm); line-height: 1.5; margin: 0; font-style: italic;">
					"<?php echo esc_html( $rejection_note ); ?>"
				</p>
			</div>
			<p style="color: var(--color-charcoal-muted); font-size: var(--fs-xs); line-height: 1.5; margin-bottom: 20px;">
				<?php esc_html_e( 'Please correct your profile credentials to submit your application for verification welcome calls.', 'secondinnings50' ); ?>
			</p>
			<div>
				<a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>" class="btn btn-primary" style="font-weight: 700; padding: 12px 24px;"><?php esc_html_e( 'Update My Profile', 'secondinnings50' ); ?></a>
			</div>
		</div>

	<?php elseif ( ! $is_approved ) : ?>
		<!-- Pending Vetting Alert Warning Notice -->
		<div class="card text-center" style="max-width: 600px; margin: 40px auto; padding: var(--spacing-xl); border: 2.5px solid var(--color-gold) !important; background-color: #FFFFFF !important; box-shadow: 0 10px 30px rgba(197, 160, 89, 0.15); border-radius: var(--radius-lg);">
			<span style="font-size: 3.5rem; display: block; margin-bottom: var(--spacing-md);">⏳</span>
			<h2 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-forest); margin-bottom: 15px;">
				<?php esc_html_e( 'Verification Vetting in Progress', 'secondinnings50' ); ?>
			</h2>
			<div style="background-color: var(--color-gold-light); border: 1px solid var(--color-gold); padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 20px; text-align: left;">
				<p style="color: var(--color-forest-dark); font-weight: 700; margin-bottom: 8px; font-size: var(--fs-md);">
					<?php esc_html_e( 'Your membership application is securely received.', 'secondinnings50' ); ?>
				</p>
				<p style="color: var(--color-forest-dark); font-size: var(--fs-sm); line-height: 1.5; margin: 0;">
					<?php esc_html_e( 'Access to the notifications center will be unlocked following your verification welcome call. Our coordination team will contact you within 24 hours.', 'secondinnings50' ); ?>
				</p>
			</div>
			<p style="color: var(--color-charcoal-muted); font-size: var(--fs-xs); line-height: 1.5;">
				<?php esc_html_e( 'If you have any questions, contact Hello Support at hello@secondinnings50.in', 'secondinnings50' ); ?>
			</p>
		</div>

	<?php else : ?>
		<!-- Approved Notifications Layout -->
		<div style="margin-bottom: var(--spacing-md);">
			<span class="section-tag" style="display: inline-block; margin-bottom: 8px;"><?php esc_html_e( 'Handshake Tracker', 'secondinnings50' ); ?></span>
			<h1 style="font-family: var(--font-serif); font-size: 2.25em; font-weight: 800; color: var(--color-forest); margin: 0 0 8px 0; line-height: 1.2;">
				<?php esc_html_e( 'Connection Requests & Handshakes', 'secondinnings50' ); ?>
			</h1>
			<p style="color: var(--color-charcoal-muted); font-size: var(--fs-md); margin: 0;">
				<?php esc_html_e( 'Manage your companion connections, approve incoming handshakes, or track your sent requests.', 'secondinnings50' ); ?>
			</p>
		</div>

		<?php
		global $wpdb;
		$table_name = $wpdb->prefix . 'si50_connect_requests';
		$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'pending';
		if ( ! in_array( $active_tab, array( 'all', 'pending', 'approved', 'declined' ) ) ) {
			$active_tab = 'pending';
		}

		// Count variables for badges
		$count_pending = 0;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) === $table_name ) {
			$count_pending = $wpdb->get_var( $wpdb->prepare(
				"SELECT COUNT(*) FROM $table_name WHERE receiver_id = %d AND status = 'pending'",
				$current_user_id
			) );

			// Query items for active tab
			$query = "SELECT * FROM $table_name WHERE (sender_id = %d OR receiver_id = %d)";
			$params = array( $current_user_id, $current_user_id );

			if ( 'all' !== $active_tab ) {
				$query .= " AND status = %s";
				$params[] = $active_tab;
			}
			$query .= " ORDER BY timestamp DESC";
			$requests = $wpdb->get_results( $wpdb->prepare( $query, $params ) );
		} else {
			$requests = array();
		}
		?>

		<!-- Filtering Tabs Navigation -->
		<div class="notifications-tabs-wrapper" style="display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 2px solid #333333; padding-bottom: 12px; flex-wrap: wrap;">
			<a href="<?php echo esc_url( add_query_arg( 'tab', 'pending' ) ); ?>" class="btn" style="padding: 8px 16px; font-size: var(--fs-xs); font-weight: 700; text-decoration: none; border-radius: var(--radius-sm); border: 1.5px solid #333333; <?php echo ( 'pending' === $active_tab ) ? 'background-color: var(--color-forest) !important; color: #FFFFFF !important;' : 'background-color: #FFFFFF !important; color: #333333 !important;'; ?>">
				⏱ <?php esc_html_e( 'Pending / Awaiting', 'secondinnings50' ); ?>
				<?php if ( $count_pending > 0 ) : ?>
					<span style="background: #dc3545; color: #FFFFFF; font-size: 0.7rem; font-weight: 700; padding: 2px 6px; border-radius: 10px; margin-left: 4px;"><?php echo intval( $count_pending ); ?></span>
				<?php endif; ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( 'tab', 'approved' ) ); ?>" class="btn" style="padding: 8px 16px; font-size: var(--fs-xs); font-weight: 700; text-decoration: none; border-radius: var(--radius-sm); border: 1.5px solid #333333; <?php echo ( 'approved' === $active_tab ) ? 'background-color: var(--color-forest) !important; color: #FFFFFF !important;' : 'background-color: #FFFFFF !important; color: #333333 !important;'; ?>">
				🤝 <?php esc_html_e( 'Approved / Accepted', 'secondinnings50' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( 'tab', 'declined' ) ); ?>" class="btn" style="padding: 8px 16px; font-size: var(--fs-xs); font-weight: 700; text-decoration: none; border-radius: var(--radius-sm); border: 1.5px solid #333333; <?php echo ( 'declined' === $active_tab ) ? 'background-color: var(--color-forest) !important; color: #FFFFFF !important;' : 'background-color: #FFFFFF !important; color: #333333 !important;'; ?>">
				❌ <?php esc_html_e( 'Rejected / Declined', 'secondinnings50' ); ?>
			</a>
			<a href="<?php echo esc_url( add_query_arg( 'tab', 'all' ) ); ?>" class="btn" style="padding: 8px 16px; font-size: var(--fs-xs); font-weight: 700; text-decoration: none; border-radius: var(--radius-sm); border: 1.5px solid #333333; <?php echo ( 'all' === $active_tab ) ? 'background-color: var(--color-forest) !important; color: #FFFFFF !important;' : 'background-color: #FFFFFF !important; color: #333333 !important;'; ?>">
				📁 <?php esc_html_e( 'All Requests', 'secondinnings50' ); ?>
			</a>
		</div>

		<!-- Connections Grid -->
		<?php if ( empty( $requests ) ) : ?>
			<div class="card text-center" style="padding: 50px 20px; background-color: #FFFFFF !important; border: 2px dashed #333333 !important; border-radius: var(--radius-md);">
				<span style="font-size: 2.5rem; display: block; margin-bottom: 15px;">📥</span>
				<h3 style="font-family: var(--font-serif); font-size: var(--fs-lg); color: var(--color-forest); font-weight: 700; margin: 0 0 10px 0;">
					<?php esc_html_e( 'No Connection Handshakes Found', 'secondinnings50' ); ?>
				</h3>
				<p style="color: var(--color-charcoal-muted); font-size: var(--fs-sm); max-width: 450px; margin: 0 auto; line-height: 1.6;">
					<?php
					if ( 'pending' === $active_tab ) {
						esc_html_e( 'You do not have any pending connection requests waiting. Visit the Member Directory to send invites to potential companions.', 'secondinnings50' );
					} elseif ( 'approved' === $active_tab ) {
						esc_html_e( 'No approved connections yet. Accept received invites or wait for members to approve your requests to view their coordinates.', 'secondinnings50' );
					} else {
						esc_html_e( 'No requests found matching this status filter.', 'secondinnings50' );
					}
					?>
				</p>
				<div style="margin-top: 20px;">
					<a href="<?php echo esc_url( home_url( '/directory/' ) ); ?>" class="btn btn-primary" style="font-weight: 700; font-size: var(--fs-xs);"><?php esc_html_e( 'Go to Member Directory', 'secondinnings50' ); ?></a>
				</div>
			</div>
		<?php else : ?>
			<div class="notifications-list-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
				<?php foreach ( $requests as $req ) :
					$is_incoming = ( intval( $req->receiver_id ) === $current_user_id );
					$peer_id     = $is_incoming ? $req->sender_id : $req->receiver_id;
					$peer        = get_userdata( $peer_id );
					if ( ! $peer ) continue;

					$p_name       = get_user_meta( $peer_id, 'si50_fullname', true );
					if ( empty( $p_name ) ) {
						$p_name = $peer->display_name;
					}
					$p_gender     = get_user_meta( $peer_id, 'si50_gender', true );
					$p_age        = get_user_meta( $peer_id, 'si50_age_bracket', true );
					$p_city       = get_user_meta( $peer_id, 'si50_city_state', true );
					$p_occupation = get_user_meta( $peer_id, 'si50_occupation', true );
					$p_intro      = get_user_meta( $peer_id, 'si50_introduction', true );
					$p_interests  = (array) get_user_meta( $peer_id, 'si50_circles_interest', true );
					$p_looking    = (array) get_user_meta( $peer_id, 'si50_looking_for', true );
					$p_phone      = get_user_meta( $peer_id, 'si50_phone', true );
					$p_email      = esc_html( $peer->user_email );
					?>
					
					<div class="card connection-card" style="background-color: #FFFFFF !important; border: 2px solid #333333 !important; border-radius: var(--radius-lg); padding: var(--spacing-md); display: flex; flex-direction: column; justify-content: space-between; position: relative;">
						<div>
							<!-- Header badges -->
							<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 8px; flex-wrap: wrap;">
								<span style="font-size: var(--fs-xs); font-weight: 700; text-transform: uppercase; color: var(--color-terracotta); background-color: var(--color-gold-light); padding: 4px 8px; border-radius: var(--radius-sm); border: 1.5px solid var(--color-gold); white-space: nowrap;">
									🎂 <?php echo esc_html( $p_age ) . ' / ' . esc_html( $p_gender ); ?>
								</span>
								<span class="location-tag-truncated" title="📍 <?php echo esc_attr( $p_city ); ?>">
									📍 <?php echo esc_html( $p_city ); ?>
								</span>
							</div>

							<!-- Display Name & Occupation -->
							<h3 style="font-family: var(--font-serif); font-size: var(--fs-lg); font-weight: 800; color: var(--color-forest); margin: 0 0 4px 0;">
								<?php echo esc_html( $p_name ); ?>
							</h3>
							<p style="font-size: var(--fs-xs); color: var(--color-charcoal-muted); font-weight: 600; font-style: italic; margin-bottom: 12px;">
								💼 <?php echo esc_html( $p_occupation ); ?>
							</p>

							<!-- Bio Snippet -->
							<?php if ( ! empty( $p_intro ) ) : ?>
								<p style="font-size: var(--fs-sm); line-height: 1.5; color: var(--color-charcoal); margin-bottom: 15px; background-color: var(--bg-warm); padding: 10px; border-radius: var(--radius-sm); border-left: 3px solid var(--color-forest);">
									<?php echo esc_html( wp_trim_words( $p_intro, 25, '...' ) ); ?>
								</p>
							<?php endif; ?>

							<!-- Seeking Checkbox selections -->
							<?php if ( ! empty( $p_looking ) ) : ?>
								<div style="margin-bottom: 12px;">
									<span style="font-size: var(--fs-xs); font-weight: 700; color: var(--color-charcoal); display: block; margin-bottom: 6px;"><?php esc_html_e( 'Seeking:', 'secondinnings50' ); ?></span>
									<div style="display: flex; flex-wrap: wrap; gap: 6px;">
										<?php foreach ( $p_looking as $focus_item ) : ?>
											<span style="font-size: var(--fs-xs); font-weight: 600; color: var(--color-forest); background-color: rgba(27, 59, 43, 0.08); padding: 3px 8px; border-radius: 20px;"><?php echo esc_html( $focus_item ); ?></span>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>

							<!-- Interest Circles -->
							<?php if ( ! empty( $p_interests ) ) : ?>
								<div style="margin-bottom: 15px;">
									<span style="font-size: var(--fs-xs); font-weight: 700; color: var(--color-charcoal); display: block; margin-bottom: 6px;"><?php esc_html_e( 'Interests Circles:', 'secondinnings50' ); ?></span>
									<div style="display: flex; flex-wrap: wrap; gap: 6px;">
										<?php foreach ( $p_interests as $interest ) : ?>
											<span style="font-size: 0.75rem; font-weight: 600; color: var(--color-charcoal); border: 1px solid var(--color-border); background: #ffffff; padding: 2px 8px; border-radius: var(--radius-sm);"><?php echo esc_html( $interest ); ?></span>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<!-- State Actions Block -->
						<div style="border-top: 1.5px solid var(--color-border); padding-top: 15px; margin-top: 15px;">
							<?php if ( 'approved' === $req->status ) : ?>
								<!-- Approved: Reveal contacts & WhatsApp shortcuts -->
								<div style="background-color: #e7f4e8; border: 1.5px solid #1e7e34; padding: 12px; border-radius: var(--radius-md); margin-bottom: 12px; font-size: var(--fs-sm); color: #1e7e34;">
									<p style="margin: 0 0 6px 0; font-weight: 700;">🤝 <?php esc_html_e( 'You are Connected!', 'secondinnings50' ); ?></p>
									<p style="margin: 0 0 4px 0;">📧 <strong><?php esc_html_e( 'Email:', 'secondinnings50' ); ?></strong> <a href="mailto:<?php echo esc_attr( $p_email ); ?>" style="color: #1e7e34; font-weight: 600; text-decoration: underline;"><?php echo $p_email; ?></a></p>
									<p style="margin: 0;">📱 <strong><?php esc_html_e( 'WhatsApp:', 'secondinnings50' ); ?></strong> <a href="tel:<?php echo esc_attr( $p_phone ); ?>" style="color: #1e7e34; font-weight: 600; text-decoration: underline;"><?php echo esc_html( $p_phone ); ?></a></p>
								</div>
								
								<?php
								$clean_phone = preg_replace( '/[^0-9]/', '', $p_phone );
								if ( strlen( $clean_phone ) === 10 ) {
									$clean_phone = '91' . $clean_phone;
								}
								?>
								<a href="https://wa.me/<?php echo esc_attr( $clean_phone ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-block text-center" style="background-color: #25D366; color: #ffffff; font-weight: 700; width: 100%; border: none; display: flex; align-items: center; justify-content: center; gap: 8px; text-decoration: none; padding: 10px; border-radius: var(--radius-sm); font-size: var(--fs-xs);">
									<svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true" style="margin-top: 2px;"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.457L0 24zm6.59-4.846c1.666.988 3.31 1.493 5.405 1.494 5.276 0 9.57-4.287 9.573-9.564.001-2.556-1.002-4.959-2.816-6.78C16.924 2.5 14.55 1.5 12.008 1.5c-5.282 0-9.58 4.29-9.583 9.566-.001 2.01.522 3.823 1.517 5.485L2.94 20.897l6.707-1.743zM16.595 13.7c-.253-.127-1.5-.74-1.73-.824-.231-.084-.399-.127-.567.127-.168.252-.65.824-.796.993-.147.168-.294.19-.547.063-.253-.127-1.07-.394-2.04-1.259-.755-.674-1.266-1.506-1.414-1.759-.148-.252-.016-.39.11-.516.114-.112.253-.295.38-.442.127-.147.169-.253.253-.422.084-.168.042-.316-.021-.442-.063-.127-.567-1.36-.777-1.865-.205-.496-.41-.427-.567-.427-.147-.003-.315-.003-.483-.003-.168 0-.441.063-.672.316-.231.253-.882.863-.882 2.106 0 1.242.903 2.443 1.029 2.612.126.168 1.776 2.712 4.302 3.802.6.26 1.07.414 1.434.529.603.192 1.152.165 1.587.1.485-.072 1.5-.612 1.712-1.206.21-.595.21-1.106.147-1.206-.063-.1-.231-.143-.483-.27z"/></svg>
									<?php esc_html_e( 'Message on WhatsApp', 'secondinnings50' ); ?>
								</a>

							<?php elseif ( 'pending' === $req->status ) : ?>
								<?php if ( $is_incoming ) : ?>
									<!-- Incoming Request: Accept/Decline action buttons -->
									<div style="display: flex; gap: 10px;">
										<button class="btn btn-primary si50-btn-accept" data-sender-id="<?php echo esc_attr( $peer_id ); ?>" style="flex: 1; padding: 10px; font-weight: 700; font-size: var(--fs-xs);">
											<?php esc_html_e( 'Accept Request', 'secondinnings50' ); ?>
										</button>
										<button class="btn si50-btn-decline" data-sender-id="<?php echo esc_attr( $peer_id ); ?>" style="flex: 1; padding: 10px; font-weight: 600; font-size: var(--fs-xs); border: 1.5px solid #333333; color: var(--color-charcoal); background: #ffffff;">
											<?php esc_html_e( 'Decline', 'secondinnings50' ); ?>
										</button>
									</div>
								<?php else : ?>
									<!-- Outgoing Request: disabled Requested status -->
									<button class="btn btn-gold btn-block" disabled style="width: 100%; cursor: not-allowed; opacity: 0.7; font-weight: 700; border: 1.5px solid var(--color-gold) !important; background-color: var(--color-gold-light) !important; color: var(--color-forest-dark) !important;">
										⏱ <?php esc_html_e( 'Connection Requested', 'secondinnings50' ); ?>
									</button>
								<?php endif; ?>

							<?php else : ?>
								<!-- Rejected / Declined request state -->
								<button class="btn btn-block" disabled style="width: 100%; cursor: not-allowed; border: 1.5px solid #333333 !important; background-color: #f8f9fa !important; color: #6c757d !important; font-weight: 700;">
									❌ <?php esc_html_e( 'Connection Declined', 'secondinnings50' ); ?>
								</button>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	<?php endif; ?>

  </div>
</main>

<?php
get_footer();
