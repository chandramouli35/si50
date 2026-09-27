<?php
/**
 * Template Name: Members Directory
 *
 * @package SecondInnings50
 */

get_header();

// Guard access: User must be logged in and approved to see other members
$current_user_id = get_current_user_id();
$is_logged_in    = is_user_logged_in();
$vetting_status  = $is_logged_in ? get_user_meta( $current_user_id, 'si50_vetting_status', true ) : 'pending_review';

if ( empty( $vetting_status ) ) {
	$vetting_status = 'pending_review';
}

$is_approved = ( 'approved' === $vetting_status );
?>

<style>
  .si50-directory-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 30px;
    margin-top: 24px;
  }
  .directory-list-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
  @media (max-width: 960px) {
    .si50-directory-grid {
      grid-template-columns: 1fr;
    }
    .si50-filter-sidebar .card {
      position: static !important;
    }
  }
  @media (max-width: 680px) {
    .directory-list-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<main id="main-content" class="site-main content-area" style="background-color: var(--bg-warm); min-height: 80vh; padding: var(--spacing-xl) 0;">
  <div class="container">

	<?php if ( ! $is_logged_in ) : ?>
		<!-- Non-Logged In State Warning -->
		<div class="card text-center" style="max-width: 600px; margin: 40px auto; padding: var(--spacing-xl); border: 2px solid #333333 !important; background-color: #FFFFFF !important; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-radius: var(--radius-lg);">
			<span style="font-size: 3rem; display: block; margin-bottom: var(--spacing-md);">🔒</span>
			<h2 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-forest); margin-bottom: 12px;">
				<?php esc_html_e( 'Private Member Directory', 'secondinnings50' ); ?>
			</h2>
			<p style="color: var(--color-charcoal-muted); margin-bottom: 24px; line-height: 1.6;">
				<?php esc_html_e( 'To safeguard the security, dignity, and privacy of our senior community, the lookup directory is restricted to approved members. Please log in or submit an invitation onboarding application to proceed.', 'secondinnings50' ); ?>
			</p>
			<div style="display: flex; gap: 15px; justify-content: center;">
				<a href="#login" class="btn btn-primary si50-trigger-login" style="font-weight: 700; padding: 12px 24px;"><?php esc_html_e( 'Log In Securely', 'secondinnings50' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/join-application/' ) ); ?>" class="btn btn-gold" style="font-weight: 700; padding: 12px 24px;"><?php esc_html_e( 'Apply to Join', 'secondinnings50' ); ?></a>
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
				<?php esc_html_e( 'To re-submit or correct your onboarding information, please update your profile details using the link below.', 'secondinnings50' ); ?>
			</p>
			<div>
				<a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>" class="btn btn-primary" style="font-weight: 700; padding: 12px 24px; text-decoration: none; display: inline-block;"><?php esc_html_e( 'Update My Profile', 'secondinnings50' ); ?></a>
			</div>
		</div>

	<?php elseif ( 'suspended' === $vetting_status ) : ?>
		<!-- Suspended State Notice -->
		<div class="card text-center" style="max-width: 600px; margin: 40px auto; padding: var(--spacing-xl); border: 2.5px solid var(--color-terracotta) !important; background-color: #FFFFFF !important; box-shadow: 0 10px 30px rgba(217, 83, 79, 0.15); border-radius: var(--radius-lg);">
			<span style="font-size: 3.5rem; display: block; margin-bottom: var(--spacing-md);">🚫</span>
			<h2 style="font-family: var(--font-serif); font-weight: 700; color: var(--color-terracotta-dark); margin-bottom: 15px;">
				<?php esc_html_e( 'Account Suspended', 'secondinnings50' ); ?>
			</h2>
			<div style="background-color: #fdf3f2; border: 1.5px solid var(--color-terracotta); padding: 16px 20px; border-radius: var(--radius-md); margin-bottom: 20px; text-align: left;">
				<p style="color: var(--color-terracotta-dark); font-weight: 700; margin-bottom: 8px; font-size: var(--fs-md);">
					<?php esc_html_e( 'Account Suspension Notice:', 'secondinnings50' ); ?>
				</p>
				<p style="color: var(--color-charcoal); font-size: var(--fs-sm); line-height: 1.5; margin: 0;">
					<?php esc_html_e( 'Your account has been suspended due to reports of activity that violates our community guidelines or safety protocols. Please contact support at hello@secondinnings50.in if you believe this is in error.', 'secondinnings50' ); ?>
				</p>
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
					<?php esc_html_e( 'Access will be unlocked following your standard manual verification welcome call. Our coordination team will contact you within 24 hours to confirm authenticity and activate your profile.', 'secondinnings50' ); ?>
				</p>
			</div>
			<p style="color: var(--color-charcoal-muted); font-size: var(--fs-xs); line-height: 1.5;">
				<?php esc_html_e( 'If you have any questions or did not receive a verification call, please contact Hello Support at hello@secondinnings50.in', 'secondinnings50' ); ?>
			</p>
		</div>

	<?php else : ?>
		<!-- Approved Directory Layout -->
		<div style="margin-bottom: var(--spacing-lg);">
			<span class="section-tag" style="display: inline-block; margin-bottom: 8px;"><?php esc_html_e( 'Find Companion Circles', 'secondinnings50' ); ?></span>
			<h1 style="font-family: var(--font-serif); font-size: 2.25em; font-weight: 800; color: var(--color-forest); margin: 0 0 8px 0; line-height: 1.2;">
				<?php esc_html_e( 'Genuine Companionship After 40: Connect with Like-Minded Members', 'secondinnings50' ); ?>
			</h1>
			<p style="color: var(--color-charcoal-muted); font-size: var(--fs-md); max-width: 650px; margin: 0;">
				<?php esc_html_e( 'Discover active adults nearby. Phone numbers and email addresses are securely locked until both members approve a connect request handshake.', 'secondinnings50' ); ?>
			</p>
		</div>



		<!-- Split Sidebar & Grid Layout -->
		<div class="si50-directory-grid">
			
			<!-- Filter Sidebar Section -->
			<aside class="si50-filter-sidebar" aria-label="<?php esc_attr_e( 'Search Filters', 'secondinnings50' ); ?>">
				<div class="card" style="background-color: #FFFFFF !important; border: 1.5px solid #333333 !important; padding: var(--spacing-md); border-radius: var(--radius-md); position: sticky; top: 100px; box-shadow: 0 5px 15px rgba(0,0,0,0.02);">
					<h3 style="font-family: var(--font-serif); font-size: var(--fs-md); color: var(--color-forest); border-bottom: 1.5px solid var(--color-border); padding-bottom: 8px; margin: 0 0 16px 0; font-weight: 700;">
						🔍 <?php esc_html_e( 'Filter Members', 'secondinnings50' ); ?>
					</h3>
					
					<form method="get" id="si50-directory-filters-form">
						<!-- Search Input -->
						<div class="form-group" style="margin-bottom: 16px;">
							<label for="search-input" class="form-label" style="font-weight: 700; font-size: var(--fs-xs); display: block; margin-bottom: 6px; color: var(--color-charcoal);"><?php esc_html_e( 'Search Name / Keyword', 'secondinnings50' ); ?></label>
							<input type="text" id="search-input" name="search_query" value="<?php echo isset( $_GET['search_query'] ) ? esc_attr( $_GET['search_query'] ) : ''; ?>" class="form-control" placeholder="<?php esc_attr_e( 'e.g. Anand', 'secondinnings50' ); ?>" style="width: 100% !important; border: 1.5px solid #333333 !important; background-color: #FFFFFF !important; padding: 8px 12px !important; border-radius: var(--radius-sm) !important; font-size: var(--fs-sm) !important; color: #333333 !important;">
						</div>

						<!-- Gender Filter -->
						<div class="form-group" style="margin-bottom: 16px;">
							<label for="gender-filter" class="form-label" style="font-weight: 700; font-size: var(--fs-xs); display: block; margin-bottom: 6px; color: var(--color-charcoal);"><?php esc_html_e( 'Gender', 'secondinnings50' ); ?></label>
							<select id="gender-filter" name="gender" class="form-control select-control" style="width: 100% !important; border: 1.5px solid #333333 !important; background-color: #FFFFFF !important; padding: 8px 12px !important; border-radius: var(--radius-sm) !important; font-size: var(--fs-sm) !important; color: #333333 !important;">
								<option value=""><?php esc_html_e( 'All Genders', 'secondinnings50' ); ?></option>
								<option value="Female" <?php selected( isset( $_GET['gender'] ) ? $_GET['gender'] : '', 'Female' ); ?>><?php esc_html_e( 'Female', 'secondinnings50' ); ?></option>
								<option value="Male" <?php selected( isset( $_GET['gender'] ) ? $_GET['gender'] : '', 'Male' ); ?>><?php esc_html_e( 'Male', 'secondinnings50' ); ?></option>
							</select>
						</div>

						<!-- Age Bracket Filter -->
						<div class="form-group" style="margin-bottom: 16px;">
							<label for="age-filter" class="form-label" style="font-weight: 700; font-size: var(--fs-xs); display: block; margin-bottom: 6px; color: var(--color-charcoal);"><?php esc_html_e( 'Age Bracket', 'secondinnings50' ); ?></label>
							<select id="age-filter" name="age_bracket" class="form-control select-control" style="width: 100% !important; border: 1.5px solid #333333 !important; background-color: #FFFFFF !important; padding: 8px 12px !important; border-radius: var(--radius-sm) !important; font-size: var(--fs-sm) !important; color: #333333 !important;">
								<option value=""><?php esc_html_e( 'All Age Ranges', 'secondinnings50' ); ?></option>
								<option value="40-45" <?php selected( isset( $_GET['age_bracket'] ) ? $_GET['age_bracket'] : '', '40-45' ); ?>><?php esc_html_e( '40 to 45 Years', 'secondinnings50' ); ?></option>
								<option value="46-50" <?php selected( isset( $_GET['age_bracket'] ) ? $_GET['age_bracket'] : '', '46-50' ); ?>><?php esc_html_e( '46 to 50 Years', 'secondinnings50' ); ?></option>
								<option value="51-55" <?php selected( isset( $_GET['age_bracket'] ) ? $_GET['age_bracket'] : '', '51-55' ); ?>><?php esc_html_e( '51 to 55 Years', 'secondinnings50' ); ?></option>
								<option value="56-60" <?php selected( isset( $_GET['age_bracket'] ) ? $_GET['age_bracket'] : '', '56-60' ); ?>><?php esc_html_e( '56 to 60 Years', 'secondinnings50' ); ?></option>
								<option value="60+" <?php selected( isset( $_GET['age_bracket'] ) ? $_GET['age_bracket'] : '', '60+' ); ?>><?php esc_html_e( '60 Years & Above', 'secondinnings50' ); ?></option>
							</select>
						</div>

						<!-- City / Location Filter -->
						<div class="form-group" style="margin-bottom: 16px;">
							<label for="location-filter" class="form-label" style="font-weight: 700; font-size: var(--fs-xs); display: block; margin-bottom: 6px; color: var(--color-charcoal);"><?php esc_html_e( 'City / Location', 'secondinnings50' ); ?></label>
							<input type="text" id="location-filter" name="location" value="<?php echo isset( $_GET['location'] ) ? esc_attr( $_GET['location'] ) : ''; ?>" class="form-control" placeholder="<?php esc_attr_e( 'e.g. Pune', 'secondinnings50' ); ?>" style="width: 100% !important; border: 1.5px solid #333333 !important; background-color: #FFFFFF !important; padding: 8px 12px !important; border-radius: var(--radius-sm) !important; font-size: var(--fs-sm) !important; color: #333333 !important;">
						</div>

						<!-- Connection Focus Filter -->
						<div class="form-group" style="margin-bottom: 20px;">
							<label for="focus-filter" class="form-label" style="font-weight: 700; font-size: var(--fs-xs); display: block; margin-bottom: 6px; color: var(--color-charcoal);"><?php esc_html_e( 'Connection Focus', 'secondinnings50' ); ?></label>
							<select id="focus-filter" name="focus" class="form-control select-control" style="width: 100% !important; border: 1.5px solid #333333 !important; background-color: #FFFFFF !important; padding: 8px 12px !important; border-radius: var(--radius-sm) !important; font-size: var(--fs-sm) !important; color: #333333 !important;">
								<option value=""><?php esc_html_e( 'All Connections', 'secondinnings50' ); ?></option>
								<option value="Friendship" <?php selected( isset( $_GET['focus'] ) ? $_GET['focus'] : '', 'Friendship' ); ?>><?php esc_html_e( 'Friendship', 'secondinnings50' ); ?></option>
								<option value="Conversation Companion" <?php selected( isset( $_GET['focus'] ) ? $_GET['focus'] : '', 'Conversation Companion' ); ?>><?php esc_html_e( 'Conversation', 'secondinnings50' ); ?></option>
								<option value="Travel Companion" <?php selected( isset( $_GET['focus'] ) ? $_GET['focus'] : '', 'Travel Companion' ); ?>><?php esc_html_e( 'Travel Companion', 'secondinnings50' ); ?></option>
								<option value="Long-Term Companionship" <?php selected( isset( $_GET['focus'] ) ? $_GET['focus'] : '', 'Long-Term Companionship' ); ?>><?php esc_html_e( 'Long-Term Companionship', 'secondinnings50' ); ?></option>
							</select>
						</div>

						<div style="display: flex; flex-direction: column; gap: 10px;">
							<button type="submit" class="btn btn-primary btn-block" style="width: 100%; padding: 10px; font-weight: 700; font-size: var(--fs-xs);"><?php esc_html_e( 'Apply Filters', 'secondinnings50' ); ?></button>
							<a href="<?php echo esc_url( get_permalink() ); ?>" class="btn btn-block text-center" style="width: 100%; border: 1px solid var(--color-border); text-align: center; display: block; padding: 8px; font-size: var(--fs-xs); color: var(--color-charcoal); border-radius: var(--radius-sm); font-weight: 600; text-decoration: none; background: #fafafa;"><?php esc_html_e( 'Clear All', 'secondinnings50' ); ?></a>
						</div>
					</form>
				</div>
			</aside>

			<!-- Members List Grid Section -->
			<section class="si50-directory-list" aria-label="<?php esc_attr_e( 'Members List', 'secondinnings50' ); ?>">
				<?php
				// Build secure SQL user query
				$gender_filter   = isset( $_GET['gender'] ) ? sanitize_text_field( $_GET['gender'] ) : '';
				$age_filter      = isset( $_GET['age_bracket'] ) ? sanitize_text_field( $_GET['age_bracket'] ) : '';
				$location_filter = isset( $_GET['location'] ) ? sanitize_text_field( $_GET['location'] ) : '';
				$search_query    = isset( $_GET['search_query'] ) ? sanitize_text_field( $_GET['search_query'] ) : '';
				$focus_filter    = isset( $_GET['focus'] ) ? sanitize_text_field( $_GET['focus'] ) : '';

				$meta_query = array(
					'relation' => 'AND',
					array(
						'key'     => 'si50_vetting_status',
						'value'   => 'approved',
						'compare' => '='
					),
					array(
						'relation' => 'OR',
						array(
							'key'     => 'si50_compatibility_paused',
							'value'   => '1',
							'compare' => '!='
						),
						array(
							'key'     => 'si50_compatibility_paused',
							'compare' => 'NOT EXISTS'
						)
					),
					array(
						'relation' => 'OR',
						array(
							'key'     => 'si50_profile_visibility',
							'value'   => 'private',
							'compare' => '!='
						),
						array(
							'key'     => 'si50_profile_visibility',
							'compare' => 'NOT EXISTS'
						)
					)
				);

				if ( ! empty( $gender_filter ) ) {
					$meta_query[] = array(
						'key'     => 'si50_gender',
						'value'   => $gender_filter,
						'compare' => '='
					);
				}

				if ( ! empty( $age_filter ) ) {
					$meta_query[] = array(
						'key'     => 'si50_age_bracket',
						'value'   => $age_filter,
						'compare' => '='
					);
				}

				if ( ! empty( $location_filter ) ) {
					$meta_query[] = array(
						'key'     => 'si50_city_state',
						'value'   => $location_filter,
						'compare' => 'LIKE'
					);
				}

				// Fetch blocked and blocking user IDs to maintain strict isolation
				$blocked_ids = si50_get_blocked_user_ids( $current_user_id );
				$exclude_ids = array_merge( array( $current_user_id ), $blocked_ids );

				$args = array(
					'role__not_in' => array( 'administrator' ),
					'exclude'      => $exclude_ids,
					'meta_query'   => $meta_query
				);

				if ( ! empty( $search_query ) ) {
					$args['search'] = '*' . esc_attr( $search_query ) . '*';
					$args['search_columns'] = array( 'display_name', 'user_email', 'user_nicename' );
				}

				$members = get_users( $args );

				// Extra PHP-based precise filter for serialized array check
				if ( ! empty( $focus_filter ) && ! empty( $members ) ) {
					$filtered_members = array();
					foreach ( $members as $member ) {
						$looking_for = get_user_meta( $member->ID, 'si50_looking_for', true );
						if ( is_array( $looking_for ) && in_array( $focus_filter, $looking_for ) ) {
							$filtered_members[] = $member;
						}
					}
					$members = $filtered_members;
				}

				if ( empty( $members ) ) :
				?>
					<!-- Empty State Grid Screen -->
					<div class="card text-center" style="padding: 40px; background-color: #FFFFFF !important; border: 1.5px dashed var(--color-border) !important; border-radius: var(--radius-md);">
						<span style="font-size: 2.5rem; display: block; margin-bottom: 15px;">👥</span>
						<h3 style="font-family: var(--font-serif); font-size: var(--fs-lg); color: var(--color-forest); font-weight: 700; margin: 0 0 10px 0;">
							<?php esc_html_e( 'No Matching Members Found', 'secondinnings50' ); ?>
						</h3>
						<p style="color: var(--color-charcoal-muted); font-size: var(--fs-sm); max-width: 400px; margin: 0 auto;">
							<?php esc_html_e( 'Try refining your location search terms or clearing filters to locate other active adults.', 'secondinnings50' ); ?>
						</p>
					</div>
				<?php else : ?>
					
					<!-- Grid Layout Cards -->
					<div class="directory-list-grid">
						<?php foreach ( $members as $member ) : 
							$member_id      = $member->ID;
							$m_name         = esc_html( $member->display_name );
							$m_gender       = get_user_meta( $member_id, 'si50_gender', true );
							$m_age          = get_user_meta( $member_id, 'si50_age_bracket', true );
							$m_city         = get_user_meta( $member_id, 'si50_city_state', true );
							$m_occupation   = get_user_meta( $member_id, 'si50_occupation', true );
							$m_intro        = get_user_meta( $member_id, 'si50_introduction', true );
							$m_interests    = (array) get_user_meta( $member_id, 'si50_circles_interest', true );
							$m_looking_for  = (array) get_user_meta( $member_id, 'si50_looking_for', true );
							$m_connection_intent = get_user_meta( $member_id, 'si50_connection_intent', true );
							$m_voice_intro = get_user_meta( $member_id, 'si50_voice_intro', true );
							$m_phone        = get_user_meta( $member_id, 'si50_phone', true );
							$m_email        = esc_html( $member->user_email );

							// Check interest submission status
							$interest_req = si50_get_connection_request( $current_user_id, $member_id );
							$interest_status = $interest_req ? $interest_req->status : false;
							?>
							<div class="card si50-member-card" style="background-color: #FFFFFF !important; border: 1.5px solid var(--color-border) !important; border-radius: var(--radius-lg); padding: var(--spacing-md); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s ease, border-color 0.2s ease; position: relative;">
								
								<div>
									<!-- Card Header Status tags -->
									<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; gap: 8px; flex-wrap: wrap;">
										<span style="font-size: var(--fs-xs); font-weight: 700; text-transform: uppercase; color: var(--color-terracotta); background-color: var(--color-gold-light); padding: 4px 8px; border-radius: var(--radius-sm); border: 1px solid var(--color-gold); white-space: nowrap;">
											🎂 <?php echo esc_html( $m_age ) . ' / ' . esc_html( $m_gender ); ?>
										</span>
										<?php 
										$comp_score = si50_calculate_compatibility( $current_user_id, $member_id );
										?>
										<span style="font-size: var(--fs-xs); font-weight: 700; color: #1e7e34; background-color: #e7f4e8; padding: 4px 8px; border-radius: var(--radius-sm); border: 1px solid #1e7e34; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;" title="<?php esc_attr_e( 'Interest Compatibility Match Score', 'secondinnings50' ); ?>">
											🍀 <?php echo esc_html( $comp_score ); ?>% Match
										</span>
										<span class="location-tag-truncated" style="text-align: right;" title="📍 <?php echo esc_attr( $m_city ); ?>">
											📍 <?php echo esc_html( $m_city ); ?>
										</span>
									</div>

									<!-- Member Basic Identity Info -->
									<h2 style="font-family: var(--font-serif); font-size: var(--fs-lg); font-weight: 800; color: var(--color-forest); margin: 0 0 4px 0; display: flex; align-items: center; gap: 6px;">
										<?php echo esc_html( $m_name ); ?>
										<?php 
										$is_badge_verified = get_user_meta( $member_id, 'si50_verified_badge', true );
										if ( $is_badge_verified ) :
											?>
											<span class="si50-emerald-badge" title="<?php esc_attr_e( 'SecondInnings50 Verified Member Identity Badge', 'secondinnings50' ); ?>" style="display: inline-flex; align-items: center; color: #10b981; vertical-align: middle;">
												<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" style="display: inline-block; vertical-align: middle; color: #10B981; flex-shrink: 0;" aria-hidden="true">
													<title><?php esc_html_e( 'SecondInnings50 Emerald Verification Checkmark', 'secondinnings50' ); ?></title>
													<path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
												</svg>
											</span>
										<?php endif; ?>
									</h2>
									<p style="font-size: var(--fs-xs); color: var(--color-charcoal-muted); font-weight: 600; font-style: italic; margin-bottom: 12px;">
										💼 <?php echo esc_html( $m_occupation ); ?>
									</p>


										<?php if ( in_array( 'Conversation Companion', $m_looking_for ) ) : ?>
											<!-- Dignified, respectful Companionship Circle Badge -->
											<div style="margin-bottom: 12px; display: inline-flex; align-items: center; gap: 6px; background-color: var(--color-forest-light); border: 1.5px solid var(--color-forest); color: var(--color-forest-dark); padding: 4px 10px; border-radius: var(--radius-sm); font-size: var(--fs-xs); font-weight: 700;" title="<?php esc_attr_e( 'Open to Voice Conversations & Single Companionship', 'secondinnings50' ); ?>">
												<span>🌾</span>
												<span><?php esc_html_e( 'Companionship Circle', 'secondinnings50' ); ?></span>
											</div>
										<?php endif; ?>

										<!-- Voice Intro snippet -->
										<?php if ( ! empty( $m_voice_intro ) ) : ?>
											<div style="margin-bottom: 12px; padding: 10px; background-color: var(--bg-warm); border-radius: var(--radius-sm); border: 1px solid var(--color-gold);">
												<span style="font-size: var(--fs-xs); font-weight: 700; color: var(--color-forest); display: block; margin-bottom: 6px;">🎙️ <?php esc_html_e( 'Voice Introduction', 'secondinnings50' ); ?></span>
												<audio controls style="width: 100%; height: 32px;">
													<source src="<?php echo esc_url( $m_voice_intro ); ?>" type="audio/mpeg">
												</audio>
											</div>
										<?php endif; ?>

										<!-- Introduction Bio snippet -->
										<?php if ( ! empty( $m_intro ) ) : ?>
											<p style="font-size: var(--fs-sm); line-height: 1.5; color: var(--color-charcoal); margin-bottom: 15px; background-color: var(--bg-warm); padding: 10px; border-radius: var(--radius-sm); border-left: 3px solid var(--color-forest);">
												<?php echo nl2br( esc_html( wp_trim_words( $m_intro, 25, '...' ) ) ); ?>
											</p>
										<?php endif; ?>

										<!-- Connection Intent Detail -->
										<?php if ( ! empty( $m_connection_intent ) ) : ?>
											<p style="font-size: var(--fs-xs); line-height: 1.4; color: var(--color-charcoal-muted); margin-bottom: 12px;">
												<strong><?php esc_html_e( 'Looking for:', 'secondinnings50' ); ?></strong> <?php echo esc_html( wp_trim_words( $m_connection_intent, 20, '...' ) ); ?>
											</p>
										<?php endif; ?>

										<!-- Connection focuses Checklist tags -->
										<?php if ( ! empty( $m_looking_for ) ) : ?>
											<div style="margin-bottom: 12px;">
												<span style="font-size: var(--fs-xs); font-weight: 700; color: var(--color-charcoal); display: block; margin-bottom: 6px;"><?php esc_html_e( 'Seeking:', 'secondinnings50' ); ?></span>
												<div style="display: flex; flex-wrap: wrap; gap: 6px;">
													<?php foreach ( $m_looking_for as $focus_item ) : ?>
														<span style="font-size: var(--fs-xs); font-weight: 600; color: var(--color-forest); background-color: rgba(27, 59, 43, 0.08); padding: 3px 8px; border-radius: 20px;"><?php echo esc_html( $focus_item ); ?></span>
													<?php endforeach; ?>
												</div>
											</div>
										<?php endif; ?>

										<!-- Interest Badges -->
										<?php if ( ! empty( $m_interests ) ) : ?>
											<div style="margin-bottom: 15px;">
												<span style="font-size: var(--fs-xs); font-weight: 700; color: var(--color-charcoal); display: block; margin-bottom: 6px;"><?php esc_html_e( 'Interests Circles:', 'secondinnings50' ); ?></span>
												<div style="display: flex; flex-wrap: wrap; gap: 6px;">
													<?php foreach ( $m_interests as $interest ) : ?>
														<span style="font-size: 0.75rem; font-weight: 600; color: var(--color-charcoal); border: 1px solid var(--color-border); background: #ffffff; padding: 2px 8px; border-radius: var(--radius-sm);"><?php echo esc_html( $interest ); ?></span>
													<?php endforeach; ?>
												</div>
											</div>
										<?php endif; ?>

								</div>

								<!-- Matchmaking Interest Action -->
								<div style="border-top: 1.5px solid var(--color-border); padding-top: 12px; margin-top: 12px; display: flex; flex-direction: column; gap: 10px;">
									
									<?php if ( $interest_status ) : ?>
										<button class="btn btn-block" disabled style="width: 100%; cursor: not-allowed; font-weight: 700; border: 1.5px solid var(--color-gold) !important; background-color: var(--color-gold-light) !important; color: var(--color-forest-dark) !important; padding: 10px;">
											⏱ <?php esc_html_e( 'Interest Submitted', 'secondinnings50' ); ?>
										</button>
									<?php else : ?>
										<button class="btn btn-primary btn-block si50-btn-interest" data-receiver-id="<?php echo esc_attr( $member_id ); ?>" style="width: 100%; padding: 10px; font-weight: 700;">
											⭐ <?php esc_html_e( "I'm Interested", 'secondinnings50' ); ?>
										</button>
									<?php endif; ?>

									<!-- Report & Block Safety Actions -->
									<div style="display: flex; gap: 8px; margin-top: 6px; border-top: 1px dashed var(--color-border); padding-top: 10px;">
										<button type="button" class="si50-btn-report-member" data-reported-id="<?php echo esc_attr( $member_id ); ?>" data-reported-name="<?php echo esc_attr( $m_name ); ?>" style="flex: 1; font-size: 11px; font-weight: 700; color: var(--color-terracotta); background: none; border: 1px solid var(--color-terracotta); padding: 6px; cursor: pointer; border-radius: 4px; transition: all 0.2s; box-sizing: border-box;">
											⚠️ <?php esc_html_e( 'Report', 'secondinnings50' ); ?>
										</button>
										<button type="button" class="si50-btn-block-member" data-blocked-id="<?php echo esc_attr( $member_id ); ?>" data-blocked-name="<?php echo esc_attr( $m_name ); ?>" style="flex: 1; font-size: 11px; font-weight: 700; color: #666; background: none; border: 1px solid #ccc; padding: 6px; cursor: pointer; border-radius: 4px; transition: all 0.2s; box-sizing: border-box;">
											🚫 <?php esc_html_e( 'Block', 'secondinnings50' ); ?>
										</button>
									</div>

								</div>
							</div>
						<?php endforeach; ?>
					</div>

				<?php endif; ?>
			</section>

		</div>

	<?php endif; ?>

  </div>
</main>

<?php
get_footer();
