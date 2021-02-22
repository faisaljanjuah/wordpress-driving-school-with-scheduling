<?php get_header(); ?>

<div class="page-content archive-<?php the_ID(); ?>-content">
    <div class="container">
        <div class="row">
            <div <?php post_class('col-xs-12'); ?>>
                <?php
                    if(have_posts()):
                        while(have_posts()) : the_post(); ?>
                        <div class="post-row">
                            <div class="post-data">
                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <?php
                                if( has_post_thumbnail() ) { ?>
                                <img src="<?php the_post_thumbnail_url(); ?>" class="img-responsive visible-xs" />
                                <div class="post-thumb hidden-xs" style="background-image: url('<?php the_post_thumbnail_url(); ?>');"></div>
                                <?php	}
                                ?>
                                <?php the_excerpt(); ?>
                                <a href="<?php the_permalink(); ?>">Read More ></a>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <?php
                        endwhile;
                    endif;
                // my_numeric_posts_nav();
                // wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>