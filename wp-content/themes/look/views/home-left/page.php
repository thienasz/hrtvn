<div class="woocommerce-breadcrumb">
    <?php
    if ( function_exists( 'look_breadcrumbs' ) ) {

        look_breadcrumbs();

    }?>
</div>
<?php /* The loop */ ?>
<div id="main-content" class="container-fluid">
	<div class="row row-inner">
		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content static-content">
					<?php the_content(); ?>
                    <?php
                    wp_link_pages( array(
                        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'look' ) . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                        'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'look' ) . ' </span>%',
                        'separator'   => '<span class="screen-reader-text">, </span>',
                    ) );
                    ?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</div>