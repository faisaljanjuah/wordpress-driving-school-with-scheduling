<?php get_header(); ?>

<?php $faqs = 12; ?>
<div class="page-content page-<?php the_ID(); ?>-content">
    <div class="container">
        <div class="row">
            <?php
                $faqArgs = array(
                    'type' => 'post',
                    'category__in' => $faqs // About Section
                );
                $faqLoop = new WP_Query($faqArgs);
                if($faqLoop->have_posts()):
                    while($faqLoop->have_posts()): $faqLoop->the_post(); ?>
                        <div class="col-xs-12">
                            <div class="faq-single mb-20">
                                <label><?php the_title(); ?></label>
                                <div class="accordionContent">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    <?php                    
                    endwhile;
                endif;
                wp_reset_postdata();
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>