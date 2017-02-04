<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Magical Theme
 * @since The Look 1.0
 */

get_header(); ?>

<main id="main" class="site-main" role="main">

	<section class="error-404 not-found">
		<div id="content-wrapper">
			<h1>404</h1>
			<h3 class="page-title"><?php _e( 'Sorry! Page you are looking can&rsquo;t be found.', 'look' ); ?></h3>
			<p><?php _e('Go back to the ','look'); ?><a href="<?php echo esc_url( home_url( '/' ) ) ;?>" rel="home"><?php _e('homepage' ,'look' ); ?></a></p>
		</div>
	</section><!-- .error-404 -->

</main><!-- .site-main -->


<?php get_footer(); ?>
