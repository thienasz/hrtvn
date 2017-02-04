<div class="woocommerce-breadcrumb">
    <?php
    if ( function_exists( 'look_breadcrumbs' ) ) {

        look_breadcrumbs();

    }?>
</div>
<?php /* The loop */ ?>
<div id="main-content" class="container-fluid hasright-sidebar">
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php if(is_sticky()): ?> <?php post_class('sticky'); ?> <?php else: ?> <?php post_class(); ?> <?php endif; ?> >
                    <div class="entry-content static-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
            <?php if ( is_active_sidebar( 'blog-right-sidebar' ) ) : ?>
                <?php
                if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('blog-right-sidebar') ) :
                endif; ?>
            <?php endif; ?>

        </div>
    </div>
</div>