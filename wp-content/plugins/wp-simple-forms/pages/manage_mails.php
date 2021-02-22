<?php

    global $wpdb;
    // Fields Starts Here
    $t_fullname = '';
    $t_learnerPermit = '';
    $t_issueDate = '';
    $t_expirationDate = '';
    $t_Dob = '';
    $t_email = '';
    $t_Address = '';
    $t_CityName = '';
    $t_State = '';
    $t_zipCode = '';
    $t_homePhone = '';
    $t_cellPhone = '';
    $s_age = '';
    $below18 = 'checked';
    $above18 = '';
    $t_highSchoolName = '';
    $t_gpa = '';
    $t_wdys = '';
    $schedule = '';
    $scheduleNow = 'checked';
    $scheduleLater = '';
    $scheduledDate = '';
    $scheduledTime = '';
    
    // Necessary Variables
    $notice = '';
    $wpsfTable = $wpdb->prefix.'wpsf_mails';
    
    function bringRecord($id){
        global $wpdb;
        $wpsfTable = $wpdb->prefix.'wpsf_mails';
        $selectedRow = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM ".$wpsfTable." WHERE id=%d",$id ), ARRAY_A // ARRAY_A convert object into array for $inserted_row with isset() function
        ); 
        return $selectedRow;
    }

    function encodeForm($form_data){
            // Step 1 "Convert into HTML and remove slashes then encode data"
            $formEncode = htmlentities($form_data); // 1
            $formEncode = stripslashes($formEncode); // 2
            $formEncode = base64_encode($formEncode); // 3
        return $formEncode;
    }
    function decodeFrom($form_data){
            // Step 2 "decode data then reconvert into html"
            $formDecode = base64_decode($form_data); // 3
            $formDecode = html_entity_decode($formDecode); // 1
        return $formDecode;
    }

    $page_title = 'Update Registrations';
    $form_type = 'update_eagle_reg';
    $submit_btn_name = 'submit_update_reg';
    $btnTxt = 'Save';

    $action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : "";
    $mail_id = isset($_REQUEST['mail_id']) ? trim($_REQUEST['mail_id']) : "";
    $form_action = $_SERVER['PHP_SELF'].'?page=wp_simple_regs&action=wpsf_edit_mail';

    require_once WPSF_PLUGIN_PATH .'inc/validate.php';
    require_once WPSF_PLUGIN_PATH .'inc/process.php';

    if($action == 'wpsf_add_mail'){
        $page_title = 'Add New Registrations';
        $form_type = 'add_eagle_reg';
        $submit_btn_name = 'submit_new_reg';
        $btnTxt = 'Register';
    }
    elseif(isset($_POST['submit_new_reg'])) {
        extract($_POST);
        $wpdb->insert($wpsfTable, array(
            'full_name' => encodeForm($t_fullname),
            'permit_no' => encodeForm($t_learnerPermit),
            'permit_issue_date' => encodeForm($t_issueDate),
            'permit_expiry' => encodeForm($t_expirationDate),
            'date_of_birth' => encodeForm($t_Dob),
            'email' => encodeForm($t_email),
            'student_address' => encodeForm($t_Address),
            'city' => encodeForm($t_CityName),
            'state_name' => encodeForm($t_State),
            'zip' => encodeForm($t_zipCode),
            'home_phone' => encodeForm($t_homePhone),
            'cell_phone' => encodeForm($t_cellPhone),
            'age' => encodeForm($s_age),
            'school_name' => encodeForm($t_highSchoolName),
            'gpa' => encodeForm($t_gpa),
            'when2start' => encodeForm($t_wdys),
            'schedule' => encodeForm($schedule),
            'sch_date' => encodeForm($scheduledDate),
            'sch_time' => encodeForm($scheduledTime)
        ));
        $new_record = $wpdb->insert_id;
        $form_action .= '&mail_id='.$new_record;
        $inserted_row = bringRecord($new_record); // select * Query


        extract($inserted_row); // Get array and Assign variables with same name
        // Message Printing
        if( $new_record > 0 ) {
            $notice = '<div id="message" class="updated notice is-dismissible">';
            $notice .= '<p>Registration has been <strong>saved</strong> Successfully.</p>';
            $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
            $notice .= '</div>';
        }
        else {
            $notice = '<div id="message" class="error notice is-dismissible">';
            $notice .= '<p><strong>Failed</strong> to save Registration.</p>';
            $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
            $notice .= '</div>';
        }
    }
    else {
        if(!empty($mail_id)){
            $edit_row = bringRecord($mail_id); // select * Query
            extract($edit_row); // Get array and Assign variables with same name
            $t_fullname = decodeFrom($full_name);
            $t_learnerPermit = decodeFrom($permit_no);
            $t_issueDate = decodeFrom($permit_issue_date);
            $t_expirationDate = decodeFrom($permit_expiry);
            $t_Dob = decodeFrom($date_of_birth);
            $t_email = decodeFrom($email);
            $t_Address = decodeFrom($student_address);
            $t_CityName = decodeFrom($city);
            $t_State = decodeFrom($state_name);
            $t_zipCode = decodeFrom($zip);
            $t_homePhone = decodeFrom($home_phone);
            $t_cellPhone = decodeFrom($cell_phone);
            $s_age = decodeFrom($age);
            $t_highSchoolName = decodeFrom($school_name);
            $t_gpa = decodeFrom($gpa);
            $t_wdys = decodeFrom($when2start);
            $schedule = decodeFrom($schedule);
            $scheduledDate = decodeFrom($sch_date);
            $scheduledTime = decodeFrom($sch_time);
            
            if($s_age == 'below18'){
                $below18 = 'checked';
                $above18 = '';
            }
            elseif($s_age == 'above18') {
                $below18 = '';
                $above18 = 'checked';
            }
            if($schedule == 'scheduleNow'){
                $scheduleNow = 'checked';
                $scheduleLater = '';
            }
            elseif($schedule == 'scheduleLater') {
                $scheduleNow = '';
                $scheduleLater = 'checked';
            }

            $form_action .= '&mail_id='.$mail_id;
            if(isset($_POST['submit_update_reg'])){

                $highSchool = ''; if(!empty($_POST['t_highSchoolName'])){ $highSchool = encodeForm($_POST['t_highSchoolName']); }
                $gpa = ''; if(!empty($_POST['t_gpa'])){ $gpa = encodeForm($_POST['t_gpa']); }
                
                $scheduledDate = ''; if(!empty($_POST['scheduledDate'])){ $scheduledDate = encodeForm($_POST['scheduledDate']); }
                $scheduledTime = ''; if(!empty($_POST['scheduledTime'])){ $scheduledTime = encodeForm($_POST['scheduledTime']); }

                $wpdb->update($wpsfTable, array(
                    'full_name' => encodeForm($_POST['t_fullname']),
                    'permit_no' => encodeForm($_POST['t_learnerPermit']),
                    'permit_issue_date' => encodeForm($_POST['t_issueDate']),
                    'permit_expiry' => encodeForm($_POST['t_expirationDate']),
                    'date_of_birth' => encodeForm($_POST['t_Dob']),
                    'email' => encodeForm($_POST['t_email']),
                    'student_address' => encodeForm($_POST['t_Address']),
                    'city' => encodeForm($_POST['t_CityName']),
                    'state_name' => encodeForm($_POST['t_State']),
                    'zip' => encodeForm($_POST['t_zipCode']),
                    'home_phone' => encodeForm($_POST['t_homePhone']),
                    'cell_phone' => encodeForm($_POST['t_cellPhone']),
                    'age' => encodeForm($_POST['s_age']),
                    'school_name' => $highSchool,
                    'gpa' => $gpa,
                    'when2start' => encodeForm($_POST['t_wdys']),
                    'schedule' => encodeForm($_POST['s_class']),
                    'sch_date' => $scheduledDate,
                    'sch_time' => $scheduledTime
                ), array('id'=>$mail_id));
                $updatedRecord = bringRecord($mail_id); // select * Query
                extract($updatedRecord); // Get array and Assign variables with same name

                $t_fullname = decodeFrom($full_name);
                $t_learnerPermit = decodeFrom($permit_no);
                $t_issueDate = decodeFrom($permit_issue_date);
                $t_expirationDate = decodeFrom($permit_expiry);
                $t_Dob = decodeFrom($date_of_birth);
                $t_email = decodeFrom($email);
                $t_Address = decodeFrom($student_address);
                $t_CityName = decodeFrom($city);
                $t_State = decodeFrom($state_name);
                $t_zipCode = decodeFrom($zip);
                $t_homePhone = decodeFrom($home_phone);
                $t_cellPhone = decodeFrom($cell_phone);
                $s_age = decodeFrom($age);
                $t_highSchoolName = decodeFrom($school_name);
                $t_gpa = decodeFrom($gpa);
                $t_wdys = decodeFrom($when2start);
                $schedule = decodeFrom($schedule);
                $scheduledDate = decodeFrom($sch_date);
                $scheduledTime = decodeFrom($sch_time);

                if($s_age == 'below18'){
                    $below18 = 'checked';
                    $above18 = '';
                }
                elseif($s_age == 'above18') {
                    $below18 = '';
                    $above18 = 'checked';
                }            
                if($schedule == 'scheduleNow'){
                    $scheduleNow = 'checked';
                    $scheduleLater = '';
                }
                elseif($schedule == 'scheduleLater') {
                    $scheduleNow = '';
                    $scheduleLater = 'checked';
                }

                if( $mail_id > 0 ) {
                    $notice = '<div id="message" class="updated notice is-dismissible">';
                    $notice .= '<p>Form has been <strong>updated</strong> Successfully.</p>';
                    $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
                    $notice .= '</div>';
                }
            }
        }
        else {
            $notice = '<div id="message" class="error notice is-dismissible">';
            $notice .= '<p>No Registrations selected! Please select <a href="'.$_SERVER['PHP_SELF'].'?page=wp_simple_regs">Registrations from List</a> and then choose <strong>edit</strong> to make changes </p>';
            $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
            $notice .= '</div>';
        }
    }

?>
<div id="dtBox"></div>
<div class="wrap">

    <h1 class="wp-heading-inline"><?php echo $page_title; ?></h1>
    <hr class="wp-header-end">

    <?php echo $notice; ?>

    <div class="page-content wpsf wpsf_form">

        <form method="post" name="<?php echo $form_type; ?>" action="<?php echo $form_action; ?>">

            <div class="row form-group">
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_fullName">Full Name <span>*</span></label>
                    <input type="text" name="t_fullname" value="<?php echo $t_fullname; ?>" class="validates-as-required form-control" id="t_fullName" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_learnerPermit">Learners Permit# <span>*</span></label>
                    <input type="text" name="t_learnerPermit" value="<?php echo $t_learnerPermit; ?>" class="validates-as-required form-control" id="t_learnerPermit" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_issueDate">Issue Date <span>*</span></label>
                    <input type="text" name="t_issueDate" value="<?php echo $t_issueDate; ?>" data-field="date" data-format="dd-MMM-yyyy" readonly class="validates-as-required form-control" id="t_issueDate" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_expirationDate">Expiration Date <span>*</span></label>
                    <input type="text" name="t_expirationDate" value="<?php echo $t_expirationDate; ?>" data-field="date" data-format="dd-MMM-yyyy" readonly class="validates-as-required form-control" id="t_expirationDate" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_Dob">Date of Birth <span>*</span></label>
                    <input type="text" name="t_Dob" value="<?php echo $t_Dob; ?>" data-field="date" data-format="dd-MMM-yyyy" readonly class="validates-as-required form-control" id="t_Dob" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_email">Email ID <span>*</span></label>
                    <input type="email" name="t_email" value="<?php echo $t_email; ?>" class="validates-as-required form-control" id="t_email" />
                </div>
                <div class="col-xs-12 mb-20">
                    <label class="text-black" for="t_Address">Address <span>*</span></label>
                    <input type="text" name="t_Address" value="<?php echo $t_Address; ?>" class="validates-as-required form-control" id="t_Address" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_CityName">City Name <span>*</span></label>
                    <input type="text" name="t_CityName" value="<?php echo $t_CityName; ?>" class="validates-as-required form-control" id="t_CityName" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_State">State <span>*</span></label>
                    <input type="text" name="t_State" value="<?php echo $t_State; ?>" class="validates-as-required form-control" id="t_State" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_zipCode">Zip Code <span>*</span></label>
                    <input type="text" name="t_zipCode" value="<?php echo $t_zipCode; ?>" class="validates-as-required form-control" id="t_zipCode" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_homePhone">Home Phone</label>
                    <input type="text" name="t_homePhone" value="<?php echo $t_homePhone; ?>" class="form-control" id="t_homePhone" aria-invalid="false" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_cellPhone">Cell Phone <span>*</span></label>
                    <input type="text" name="t_cellPhone" value="<?php echo $t_cellPhone; ?>" class="validates-as-required form-control" id="t_cellPhone" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_wdys">When Do you want to Start</label>
                    <input type="text" name="t_wdys" value="<?php echo $t_wdys; ?>" data-field="date" data-format="dd-MMM-yyyy" readonly class="form-control" id="t_wdys" aria-invalid="false" />
                </div>
            </div>
            <div class="row isAdult">
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="eRadio mt-10 mb-20">
                        <input type="radio" name="s_age" id="below18" value="below18" <?php echo $below18; ?> />
                        <label for="below18">I'm below 18 years old</label>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="eRadio mt-10 mb-20">
                        <input type="radio" name="s_age" id="above18" value="above18" <?php echo $above18; ?> />
                        <label for="above18">I'm above 18 years old</label>
                    </div>
                </div>
            </div>
            <div class="row teenFields">
                <div class="col-xs-12 col-sm-6 col-md-4 mb-20">
                    <label class="text-black" for="t_gpa">Your GPA <span>*</span></label>
                    <input type="text" name="t_gpa" value="<?php echo $t_gpa; ?>" class="validates-as-required form-control" id="t_gpa" />
                </div>
                <div class="col-xs-12 col-sm-6 col-md-8 mb-20">
                    <label class="text-black" for="t_highSchoolName">High School Name</label>
                    <input type="text" name="t_highSchoolName" value="<?php echo $t_highSchoolName; ?>" class="form-control" id="t_highSchoolName" aria-invalid="false" />
                </div>
            </div>
            <div class="row scheduleClass">
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="eRadio mt-10 mb-20">
                        <input type="radio" name="s_class" id="scheduleNow" value="scheduleNow" <?php echo $scheduleNow; ?> />
                        <label for="scheduleNow">Schedule my class now</label>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="eRadio mt-10 mb-20">
                        <input type="radio" name="s_class" id="scheduleLater" value="scheduleLater" <?php echo $scheduleLater; ?> />
                        <label for="scheduleLater">Schedule my class later</label>
                    </div>
                </div>
            </div>
            <div class="row scheduleFields form-group ">
                <div class="col-xs-12 col-sm-6 mb-20">
                    <label class="text-black" for="schDate">Date <span>*</span></label>
                    <input name="scheduledDate" type="text" value="<?php echo $scheduledDate; ?>" data-field="date" data-format="dd-MMM-yyyy" readonly class="form-control" id="schDate" />
                </div>
                <div class="col-xs-12 col-sm-6 mb-20">
                    <label class="text-black" for="schTime">Time <span>*</span></label>
                    <input name="scheduledTime" type="text" value="<?php echo $scheduledTime; ?>" data-field="time" data-format="hh:mm AA" readonly class="form-control" id="schTime" />
                </div>
            </div>
            <div class="row form-group">
                <div class="col-xs-12 mb-20">
                    <button class="wpsf_btn wpsf_primary" type="submit" name="<?php echo $submit_btn_name; ?>"><?php echo $btnTxt; ?></button>
                </div>
            </div>

        </form>

    </div>
</div>
