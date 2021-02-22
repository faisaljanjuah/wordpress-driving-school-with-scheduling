
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <?php
                            $abtArgs = array(
                                'type' => 'post',
                                'posts_per_page' => 1,
                                'category__in' => 9 // About Section
                            );
                            $abtLoop = new WP_Query($abtArgs);
                            if($abtLoop->have_posts()):
                                while($abtLoop->have_posts()): $abtLoop->the_post(); ?>
                                    <h4><?php the_title(); ?></h4>
                                    <?php the_content();
                                endwhile;
                            endif;
                            wp_reset_postdata();
                        ?>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-2">
                        <h4>Quick Links</h4>
                        <?php wp_nav_menu(array('theme_location' => 'footerMenu', 'container' => false, 'menu_class' => 'menuList' )); ?>
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-4">
                        <h4>Find us</h4>
                        <div id="footerMap" data-image="<?php echo get_template_directory_uri(); ?>/images/markeryellow.png"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="copyrights">
                            <p>Copyright &copy; <script>document.write(new Date().getFullYear());</script> All rights reserved | Developed by <a href="mailto:faisaljanjuah@gmail.com">Faisal Janjua</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div> <!-- End of .siteWrapper -->
<!-- footer end -->
<div class="eagle_popup">
    <div class="eagle_overlay"></div>
    <div class="eagle_popupOuter">
        <div class="eagle_popupMiddle">
            <div class="eagle_popupInner">
                <div class="eagle_popupContent">
                    <div class="appendMsg"></div>
                    <div class="eagle_confirmChoice">
                        <button type="button" class="btn mt-20 btn-wide btn-close">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    // var eagleDrivingSchool = {lat: 38.851040, lng: -77.319176};
    // var map = new google.maps.Map( document.getElementById('footerMap'), {
    //     zoom: 14,
    //     center: eagleDrivingSchool,
    //     zoomControl: false,
    //     mapTypeControl: false,
    //     disableDefaultUI: false
    // }
    // );
    // var marker = new google.maps.Marker({
    //     position: eagleDrivingSchool, 
    //     map: map,
    //     icon: jQuery('#footerMap').attr('data-image')
    // });
</script>
<?php wp_footer(); ?>
</body>
</html>