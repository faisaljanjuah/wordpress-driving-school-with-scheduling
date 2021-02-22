<?php get_header(); ?>

<div class="page-content page-<?php the_ID(); ?>-content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<form action="/" method="post" class="reschedule-form">
					<h4>If you are already registered then you can create or update your class schedule.</h4>
					<div class="row scheduleClass">
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="eRadio mt-10 mb-20">
								<input type="radio" checked name="regCheck" value="true" id="alreadyReg"/><label for="alreadyReg">I'm already registered</label>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="eRadio mt-10 mb-20">
								<input type="radio" name="regCheck" value="false" id="notReg"/><label for="notReg">I'm not registered</label>
							</div>
						</div>
						<div class="hideBtn">
							<div class="col-xs-12">
								<div class="eRadio mt-10 mb-20">
									<a href="<?php echo home_url(); ?>/registration/" class="btn btn-wide">Register Now</a>
								</div>
							</div>
						</div>
					</div>
					<div class="registrationInfo">
						<div class="row form-group">
							<div class="col-xs-12 col-sm-6 col-md-4 mb-20">
								<label class="text-black" for="s_fullName">Full Name <span>*</span></label>
								<input type="text" name="sFullName" value="" required size="40" class="form-control alphabetsOnly" id="s_fullName" />
							</div>
							<div class="col-xs-12 col-sm-6 col-md-4 mb-20">
								<label class="text-black" for="s_email">Registered Email ID <span>*</span></label>
								<input type="email" name="s_emailID" value="" required size="40" class="form-control validateEmail" id="s_email" />
							</div>
							<div class="col-xs-12 col-sm-12 col-md-4 mb-20">
								<label class="text-black" for="s_phone">Registered Cell Phone <span>*</span></label>
								<input type="text" name="s_phone" value="" required size="40" class="form-control numbersOnly" id="s_phone" />
							</div>
							<div class="col-xs-12 col-sm-12 col-md-4 mb-20">
								<button type="button" class="btn btn-wide btn-verify">Verify</button>
							</div>
							<div class="col-xs-12 verifyResponse"></div>
						</div>
					</div>
					<div class="scheduleFields">
						<div class="row form-group ">
							<div class="col-xs-12">
								<h4>Please choose desired date and time for your schedule.</h4>
								<input type="hidden" name="update_date" value="updatedate" />
							</div>
							<div class="col-xs-12 col-sm-6 mb-20">
								<label class="text-black" for="schDate">Date <span>*</span></label>
								<input name="scheduledDate" disabled type="text" data-field="date" data-format="dd-MMM-yyyy" readonly class="form-control scheduledDate off_prev" id="schDate"/>
							</div>
							<div class="col-xs-12 col-sm-6 mb-20">
								<label class="text-black" for="schTime">Time <span>*</span></label>
								<input name="scheduledTime" disabled type="text" data-field="time" data-format="hh:mm AA" readonly class="form-control" id="schTime"/>
							</div>
						</div>
						<div class="row form-group">
							<div class="col-xs-12 mb-20">
								<button type="button" disabled class="btn btn-wide wpcf7-form-control wpcf7-submit">Update schedule</button>
							</div>
							<div class="col-xs-12 updateResponse"></div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
global $wpdb;
$wpsfOffTable = $wpdb->prefix.'wpsf_off_dates';
echo '<script type="text/javascript" language="javascript">var dates = [];';
$offDates = array();
foreach( $wpdb->get_results("SELECT dates FROM $wpsfOffTable") as $key => $row) {
	echo 'dates.push("'.$row->dates.'");';
}
echo '</script>';
?>

<script type="text/javascript">
	var regId = 0;
	var templateUri = ['<?php echo get_template_directory_uri(); ?>'];
	var ajaxurl = templateUri[0]+'/updateSchedule.php';
	function verifyUser(elm){
		var safeverify = true;
		elm.closest('.registrationInfo').find('[required]').each(function(i){
            if(jQuery(this).val().length<1) {
                safeverify = false;
                jQuery(this).addClass('error');
                jQuery('.verifyResponse').html('<h4 style="color:#F00;">Please fill all required fields.</h4>');
            }
		});
		if(elm.closest('.registrationInfo').find('input').hasClass('error')){
			safeverify = false;
		}
		if(safeverify){
			var verify = jQuery('input[name="regCheck"]:checked').val();
			var name = jQuery('#s_fullName').val();
			var email = jQuery('#s_email').val();
			var phone = jQuery('#s_phone').val();
			jQuery('.btn-verify').addClass('loading');
			jQuery('.verifyResponse').html('');
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				timeout: 20000,
				data: {verify:verify, name:name, email:email, phone:phone },
				success: function (res){
					if(res.length>0){
						res = JSON.parse(res);
						if(res['status'] == 'true'){
							jQuery('#notReg').attr('disabled', 'disabled').parent().addClass('disabled');
							jQuery('.registrationInfo').addClass('disabled');
							jQuery('.registrationInfo input').attr('disabled', 'disabled');
							jQuery('.scheduleFields input, .scheduleFields button').removeAttr('disabled');
							jQuery('.registrationInfo button').text('Verified');
							jQuery('#schDate').val(res['sch_date']);
							jQuery('#schTime').val(res['sch_time']);
							jQuery('.scheduleFields').slideDown();
							regId = res['id'];
						}
						else {
							jQuery('.verifyResponse').html("<h4>Sorry! this registeration doesn't exist.</h4>");
						}
					}
				},
				error: function(){
					jQuery('.verifyResponse').html("<h4>Sorry! Server is not responding at the moment, Please try again.</h4>");
				},
				complete: function() {
					jQuery('.btn-verify').removeClass('loading');
				}
			});
		}
	}
	jQuery(document).on('click', '.page-my-schedule .btn-verify', function(){
		verifyUser(jQuery(this));
	});
	jQuery(document).on('keyup', '.registrationInfo input', function(e){
		if (e.keyCode == 13) {
			verifyUser(jQuery(this));
		}
	});

	function updateSchedule(){
		// update Record and notify Admin via Email
		var newDate = jQuery('#schDate').val();
		var newTime = jQuery('#schTime').val();
		var updatecheck = jQuery('input[name="update_date"]').val();
		jQuery('.scheduleFields .btn').addClass('loading');
		jQuery('.updateResponse').html('');
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: {id:regId, schDate:newDate, schTime:newTime, check:updatecheck },
			timeout: 20000,
			success: function (res){
				if(res.length > 0){
					jQuery('.updateResponse').html("<h4>Your schedule has been update successfully.</h4>");
				}
				else { jQuery('.updateResponse').html("<h4>Sorry! your schedule is not updated, try again later.</h4>"); }
			},
			error: function(res){
				jQuery('.updateResponse').html("<h4>Sorry! Server is not responding at the moment, Please try again.</h4>");
			},
			complete: function() {
				jQuery('.scheduleFields .btn').removeClass('loading');
			}
		});
	}
	jQuery(document).on('click', '.scheduleFields .btn', function(){
		updateSchedule();
	});
	jQuery(document).on('keyup', '.scheduleFields input', function(e){
		if (e.keyCode == 13) {
			updateSchedule();
		}
	});

</script>

<?php get_footer(); ?>