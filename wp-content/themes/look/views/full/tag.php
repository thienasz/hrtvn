<div class="woocommerce-breadcrumb">
    <?php
    if ( function_exists( 'look_breadcrumbs' ) ) {

        look_breadcrumbs();

    }?>
</div>
<div id="main-content" class="hasright-sidebar">
    <div class="row row-inner">
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <div class="blog-section">
                <?php if ( have_posts() ) : ?>

                    <?php /* The loop */ ?>
                    <?php while ( have_posts() ) : the_post(); ?>
                        <article class="<?php if(is_sticky()):?> sticky <?php endif; ?> blog-item post hentry" <?php look_schema_metadata( array( 'context' => 'entry' ) ); ?>>
                            <div class="article-info">
                                <?php if ( has_post_thumbnail() && ! post_password_required() && ! is_attachment() ) : ?>
                                    <div class="entry-thumbnail" <?php look_schema_metadata( array( 'context' => 'image' ) ); ?>>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail( 'full' );  ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div class="post-info">
                                    <div class="post-meta"><?php _e('By','look'); ?> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' )); ?>" <?php look_schema_metadata( array( 'context' => 'author' ) ); ?>><?php echo get_the_author(); ?></a> <?php _e('in','look'); ?> <?php the_category(',') ?></div>
                                    <h2 class="post-title" itemprop="name"><a href="<?php the_permalink(); ?>" itemprop="url"><?php the_title(); ?></a></h2>
                                    <div class="article-post-date" <?php look_schema_metadata( array( 'context' => 'entry_time' ) ); ?>>
                                        <?php look_get_posted(); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="article-description">
                                <?php the_content( wp_kses(__( 'Read more <span>&rarr;</span>', 'look' ),array('span')) );?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                    <?php if (function_exists("look_pagination")) {

                        look_pagination();
                    } ?>
                <?php endif; ?>

            </div>
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
