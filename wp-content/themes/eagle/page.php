<?php get_header(); ?>

<div class="page-content page-<?php the_ID(); ?>-content">
    <div class="container">
        <div class="row">
            <div <?php post_class('col-xs-12'); ?>>
                <?php
                if(have_posts()):
                    while(have_posts()) : the_post();
                        // the_title();
                        the_content();
                    endwhile;
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</div>

	
<?php get_footer(); ?>