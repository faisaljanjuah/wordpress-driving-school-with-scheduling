<?php get_header(); ?>

<?php $aboutMain = 9; ?>
<div class="page-content page-<?php the_ID(); ?>-content">
    <div class="container">
        <div class="row">
            <?php
                $abtArgs = array(
                    'type' => 'post',
                    'category__in' => $aboutMain // About Section
                );
                $abtLoop = new WP_Query($abtArgs);
                if($abtLoop->have_posts()):
                    while($abtLoop->have_posts()): $abtLoop->the_post();
                    
                    if(has_post_thumbnail()){ ?>
                        <div class="col-xs-12 col-sm-6">
                            <?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <h2><?php the_title(); ?></h2>
                            <?php the_content(); ?>
                        </div>
                    <?php }
                    else { ?>
                        <div class="col-xs-12">
                            <h2><?php the_title(); ?></h2>
                            <?php the_content(); ?>
                        </div>
                    <?php
                    }
                    endwhile;
                endif;
                wp_reset_postdata();
            ?>
        </div>
    </div>
</div>




<?php get_footer(); ?>