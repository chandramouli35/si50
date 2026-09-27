<?php
/**
 * The main template file.
 * This is the default fallback template required by WordPress.
 *
 * @package SecondInnings50
 */

get_header();
?>

<main id="primary" class="site-main content-area">
  <div class="container" style="padding: var(--spacing-xl) 0;">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
    else :
        echo '<p>' . esc_html__( 'No posts found.', 'secondinnings50' ) . '</p>';
    endif;
    ?>
  </div>
</main>

<?php
get_footer();
