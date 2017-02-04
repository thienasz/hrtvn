<div class="woocommerce-breadcrumb">
    <?php
    if ( function_exists( 'look_breadcrumbs' ) ) {

        look_breadcrumbs();

    }?>
</div>
<div class="site-content container-fluid" role="main">
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-12">
            <?php
            // Start the Loop.
            while ( have_posts() ) : the_post();?>

            <article class="<?php if(is_sticky()):?> sticky <?php endif; ?> item-detail post hentry" <?php look_schema_metadata( array( 'context' => 'entry' ) ); ?>>
                <div class="article-info">
                    <div class="post-info">
                        <div class="post-meta">By <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' )); ?>" <?php look_schema_metadata( array( 'context' => 'author' ) ); ?>><?php echo get_the_author(); ?></a> in <?php the_category(',') ?></div>
                        <h1 class="post-title" itemprop="name"><?php the_title(); ?></h1>
                        <div class="article-post-date" <?php look_schema_metadata( array( 'context' => 'entry_time' ) ); ?>>
                            <?php look_get_posted(); ?>
                        </div>
                    </div>
                </div>
                
                <div class="article-description">
                    <?php the_content();?>
                </div>

                <div class="tags"><?php the_tags( '<strong>Tags: </strong>', ', ','' ); ?></div>
                
                <?php look_social_share(); ?>
            </article>

            <div class="relatedposts">
                <h3><?php _e('Related posts','look')?></h3>
                <div class="row">
                    <?php
                    $orig_post = $post;
                    global $post;
                    $tags = wp_get_post_tags($post->ID);

                    if ($tags) {
                        $tag_ids = array();
                        foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
                        $args=array(
                            'tag__in' => $tag_ids,
                            'post__not_in' => array($post->ID),
                            'posts_per_page'=>4, // Number of related posts to display.
                            'ignore_sticky_posts'=>1
                            );

                        $my_query = new wp_query( $args );

                        while( $my_query->have_posts() ) {
                            $my_query->the_post();
                            ?>
                            <div class="relatedthumb col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                <?php the_post_thumbnail(array(350,250)); ?><br />
                                <a href="<?php echo the_permalink();?>" class="title-link">
                                    <?php the_title(); ?>
                                </a>
                                <p>in <?php the_category(',') ?></p>
                            </div>

                            <?php }
                        }
                        $post = $orig_post;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                <?php
                // Author bio.
                if ( is_single() && get_the_author_meta( 'description' ) ) :
                    get_template_part( 'author-bio' );
                endif;
                ?>
                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) {
                    comments_template();
                }
                endwhile;
                ?>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
                <?php if ( is_active_sidebar( 'blog-right-sidebar' ) ) : ?>
                    <?php
                    if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('blog-right-sidebar') ) :
                    endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div><!-- #content -->

