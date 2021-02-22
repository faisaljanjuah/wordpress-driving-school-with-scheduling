<?php get_header(); ?>

        <div class="slider">
            <div class="mobileVideo" style="background-image: url('<?php echo get_template_directory_uri(); ?>/images/eagleFlying.gif);"></div>
            <div class="videoSlide">
                <video autoplay muted loop poster="<?php echo get_template_directory_uri(); ?>/images/eagleBanner.jpg">
                    <source src="<?php echo get_template_directory_uri(); ?>/files/eagleFlying.mp4" type="video/mp4">
                    Your browser does not support HTML5 video.
                </video>
                <!-- <audio autoplay style="opacity: 0; position: absolute; width: 0px; height: 0px; overflow: hidden; z-index: -100;">
                  <source src="<?php echo get_template_directory_uri(); ?>/files/eagleFlying.mp3" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio> -->
            </div>
            <div class="sliderContentWrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="sliderContent">
                                <div class="dtc">
                                    <div class="dib">
                                        <?php
                                            $custom_logo_id = get_theme_mod( 'custom_logo' );
                                            $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                                        ?>
                                        <a class="logo" href="<?php echo get_site_url(); ?>"><?php
                                            if ( has_custom_logo() ) {
                                                echo '<img src="'. esc_url( $logo[0] ) .'">';
                                            } else {
                                                echo '<h1>'. get_bloginfo( 'name' ) .'</h1>';
                                            }
                                        ?></a>
                                        <h1 class="logoText"><?php bloginfo('name'); ?></h1>
                                        <?php
                                            $wltArgs = array(
                                                'type' => 'post',
                                                'posts_per_page' => 1,
                                                'category__in' => 11 // Welcome Text
                                            );
                                            $wltLoop = new WP_Query($wltArgs);
                                            if($wltLoop->have_posts()):
                                                while($wltLoop->have_posts()): $wltLoop->the_post();
                                                    the_content();
                                                    the_title('<h3>', '</h3>');
                                                endwhile;
                                            endif;
                                            wp_reset_postdata();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $aboutMain = 9; ?>
        <div class="about">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <?php
                            $abtArgs = array(
                                'type' => 'post',
                                'posts_per_page' => 1,
                                'category__in' => $aboutMain // About Section
                            );
                            $abtLoop = new WP_Query($abtArgs);
                            if($abtLoop->have_posts()):
                                while($abtLoop->have_posts()): $abtLoop->the_post();
                                    if(has_post_thumbnail()){ ?>
                                        <div class="titleImage" style="background-image: url('<?php the_post_thumbnail_url(); ?>')"></div>
                                <?php } ?>
                                    <div class="about-box">
                                        <h2><?php the_title(); ?></h2>
                                        <?php 
                                            the_content();
                                            the_excerpt();
                                        ?>
                                    </div>
                                <?php 
                                endwhile;
                            endif;
                            wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="whoWeAre">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <div class="aboutSlider">
                            <?php
                                $gallery = 7;
                                $galArgs = array(
                                    'type' => 'post',
                                    'posts_per_page' => 6,
                                    'category__in' => $gallery // Gallery
                                );
                                $galLoop = new WP_Query($galArgs);
                                if($galLoop->have_posts()):
                                    while($galLoop->have_posts()): $galLoop->the_post();
                                        if(has_post_thumbnail()){ ?>
                                            <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>" />
                                    <?php
                                        }
                                    endwhile;
                                endif;
                                wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-5 col-lg-offset-1">
                        <?php
                            $abtArgs = array(
                                'type' => 'post',
                                'offset' => 1,
                                'posts_per_page' => 1,
                                'category__in' => $aboutMain // About Section
                            );
                            $abtLoop = new WP_Query($abtArgs);
                            if($abtLoop->have_posts()):
                                while($abtLoop->have_posts()): $abtLoop->the_post(); ?>
                                    <h2 class="section-title"><?php the_title(); ?></h2>
                                    <?php the_content(); ?>
                                <?php 
                                endwhile;
                            endif;
                            wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php $scheduleArea = 8; ?>
        <div class="scheduleArea">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6">
                    <?php
                        $shArgs = array(
                            'type' => 'post',
                            'posts_per_page' => 1,
                            'category__in' => $scheduleArea // Schedule Block 1st Post
                        );
                        $shLoop = new WP_Query($shArgs);
                        if($shLoop->have_posts()):
                            while($shLoop->have_posts()): $shLoop->the_post();
                            $shTitle = get_the_title();
                                if(has_post_thumbnail()){
                                    echo '<img src="'.get_the_post_thumbnail_url().'" alt="'.$shTitle.'" class="img-responsive" />';
                                }
                                echo '<h1 class="section-title">'.$shTitle.'</h1>';
                                the_content();
                            endwhile;
                        endif;
                        wp_reset_postdata();
                    ?>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6">
                        <div class="row">
                            <?php
                                $sh2Args = array(
                                    'offset' => 1,
                                    'type' => 'post',
                                    'posts_per_page' => 2,
                                    'category__in' => $scheduleArea // Schedule Block skip 1st post
                                );
                                $sh2Loop = new WP_Query($sh2Args);
                                if($sh2Loop->have_posts()):
                                    while($sh2Loop->have_posts()): $sh2Loop->the_post();
                                    $sh2Title = get_the_title(); ?>
                                        <div class="col-xs-12 col-sm-6">
                                            <div class="scheduleBlock">
                                                <?php 
                                                    if(has_post_thumbnail()){
                                                        echo '<img src="'.get_the_post_thumbnail_url().'" alt="'.$sh2Title.'" class="img-responsive" />';
                                                    }
                                                    echo '<h3>'.$sh2Title.'</h3>';
                                                    the_content(); ?>
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
            </div>
        </div>

        <?php $approach = 10; ?>
        <div class="ourApproach">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h1 class="section-title mb-30"><?php echo category_description($approach); ?></h1>
                    </div>
                </div>
                <div class="row">
                    <ul class="approachBlocks">
                        <?php
                            $apArgs = array(
                                'type' => 'post',
                                'posts_per_page' => 3,
                                'category__in' => $approach // Services
                            );
                            $apLoop = new WP_Query($apArgs);
                            if($apLoop->have_posts()):
                                while($apLoop->have_posts()): $apLoop->the_post(); ?>
                                    <li class="col-xs-12 col-sm-4">
                                        <div class="box-with-humber">
                                            <h3 class="text-black"><?php the_title(); ?></h3>
                                            <?php the_content(); ?>
                                        </div>
                                    </li>
                        <?php	endwhile;
                            endif;
                            wp_reset_postdata();
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <?php $services = 5; ?>
        <div class="ourServices">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h1 class="section-title text-center mb-60"><?php echo category_description($services); ?></h1>
                    </div>
                </div>
                <div class="row">
                    <?php
                        $SrArgs = array(
                            'type' => 'post',
                            'order' => 'ASC',
                            'posts_per_page' => 3,
                            'category__in' => $services // Services
                        );
                        $srLoop = new WP_Query($SrArgs);
                        if($srLoop->have_posts()):
                            while($srLoop->have_posts()): $srLoop->the_post(); ?>
                                <div class="col-xs-12 col-sm-6 col-md-4 srBlock">
                                    <div class="col-xs-3">
                                        <?php 
                                        if(has_post_thumbnail()){
                                            echo '<img src="'.get_the_post_thumbnail_url().'" alt="'.get_the_title().'" class="img-responsive" />';
                                        }
                                        ?>
                                    </div>
                                    <div class="col-xs-9 nopadding">
                                        <div class="srContents">
                                            <h3><?php the_title(); ?></h3>
                                            <div class="lead">
                                                <?php the_content(); ?>
                                            </div>
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                </div>
                    <?php	endwhile;
                        endif;
                        wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>

        <?php $testimonials = 6; ?>
        <div class="testimonials">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h1 class="section-title text-center mb-60"><?php echo category_description($testimonials); ?></h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                        <div class="testimonialsSlider">
                            <?php
                                $tArgs = array(
                                    'type' => 'post',
                                    'posts_per_page' => 4,
                                    'category__in' => $testimonials // Testimonials
                                );
                                $tsLoop = new WP_Query($tArgs);
                                if($tsLoop->have_posts()):
                                    while($tsLoop->have_posts()): $tsLoop->the_post(); ?>
                                        <div class="testimonialsSlide">
                                            <blockquote>
                                                <?php the_content(); ?>
                                            </blockquote>
                                            <p class="speakerName"><?php the_title(); ?></p>
                                        </div>
                            <?php	endwhile;
                                endif;
                                wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="contact">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h1 class="section-title text-center mb-60">Contact us</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <div class="contactForm">
                            <h2 class="mb-30">Contact Form</h2>
							<?php
								global $wpdb;
								$id = 3;
								$wpsfTable = $wpdb->prefix.'wpsf';
								$selectedRow = $wpdb->get_row( // get FORM from database
									$wpdb->prepare( "SELECT * FROM ".$wpsfTable." WHERE id=%d",$id ), ARRAY_A // ARRAY_A convert object into array for $inserted_row with isset() function
								);
								echo '<form action="'.home_url( $wp->request ).'/" method="post" class="wpsf-form">';
								echo '<input type="hidden" name="wpsf_admin_form" value="wpsf_admin_form" />';
								echo '<input type="hidden" name="wpsf_to" value="'.$selectedRow["mail_to"].'" />';
								echo '<input type="hidden" name="wpsf_from" value="'.$selectedRow["mail_from"].'" />';
								echo '<input type="hidden" name="wpsf_subject" value="'.$selectedRow["mail_subject"].'" />';
								echo '<input type="hidden" name="wpsf_blockwords" value="'.base64_decode($selectedRow["block_keywords"]).'" />';
								echo html_entity_decode(base64_decode($selectedRow['form']));
								echo '</form>';
							?>
							<div class="col-xs-12 msgResponse"></div>
						    <?php // echo do_shortcode('[contact-form-7 id="46" title="Quick Contact"]'); ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <div class="addressBlock">
                            <?php $walker = new Menu_With_Description; ?>
                            <?php wp_nav_menu( array( 'theme_location' => 'contactDetails', 'container' => false, 'menu_class' => 'contactMenu', 'walker' => $walker ) ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<script type="text/javascript">
    // var regId = 0;
    var templateUri = ['<?php echo get_template_directory_uri(); ?>'];
    var ajaxurl = templateUri[0]+'/sendmessage.php';
	function sendMessage(elm){
        var proceed = true;
        elm.closest("form").find('[required]').each(function(i){
            if(jQuery(this).val().length<1) {
                proceed = false;
                jQuery(this).addClass('error');
                jQuery('.msgResponse').html('<h4 style="color:#F00;">Please fill all required fields.</h4>');
            }
        });
        if(proceed){
            var data = elm.closest("form").serialize();
            elm.closest("form").find('.btn').addClass('loading');
            jQuery('.msgResponse').html('');
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: data,
                timeout: 20000,
                success: function (res){
                    if(res.length>0){
                        res = JSON.parse(res);
                        if(res['status'] == 'true'){
                            var msg = res['response'];
                            jQuery('.msgResponse').html('<h4 style="color:#333;">'+msg+'</h4>');
                        }
                        else if(res['status'] == 'false'){
                            var msg = res['response'];
                            jQuery('.msgResponse').html('<h4 style="color:#F00;">'+msg+'</h4>');
                        }
                        else {
                            jQuery('.msgResponse').html("<h4 style='color:#F00;'>Sorry! we can't serve your request at the moment.</h4>");
                        }
                    }
                },
                error: function(){
                    jQuery('.msgResponse').html('<h4 style="color:#333;">Sorry! server is not responding at the moment, Please try again.</h4>');
                },
                complete: function() {
                    jQuery('.wpsf-form .btn').removeClass('loading');
                }
            });
        }
	}
	jQuery(document).on('submit', '.wpsf-form', function(e){
        e.preventDefault();
		sendMessage(jQuery(this));
	});
    
	jQuery(document).on('click', '.wpsf-form .btn', function(e){
        e.preventDefault();
		sendMessage(jQuery(this));
	});
	jQuery(document).on('keyup', '.wpsf-form input', function(e){
		if (e.keyCode == 13) {
			sendMessage(jQuery(this));
		}
    });
</script>

<?php get_footer(); ?>