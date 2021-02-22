<?php get_header(); ?>

<div class="page-content page-<?php the_ID(); ?>-content">
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
	jQuery(document).on('keyup', '.wpsf-form input, .wpsf-form textarea', function(e){
		if (e.keyCode == 13) {
			sendMessage(jQuery(this));
		}
    });
</script>


<?php get_footer(); ?>