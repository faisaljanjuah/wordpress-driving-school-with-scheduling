<?php

$t_gpa = '';
$t_highSchoolName = '';
$scheduledDate = '';
$scheduledTime = '';

$data = [];
foreach($_POST as $key=>$value){
    $data[$key] = $value;
    
}
extract($data);


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

$wpsf_from = 'Eagle Driving School <info@eagledrivingschool.net>'; // hard Codeed :P
// $wpsf_to = '';
// $wpsf_from = '';
// $wpsf_subject = '';
$safeProceed = 'true';
$wpsf_blockwords = preg_split ("/,/", stripslashes($_POST['wpsf_blockwords']));
unset($_POST['wpsf_blockwords']); // remove block keywords fields from test
foreach ($_POST as $post){
	$post = strtolower($post);
    foreach($wpsf_blockwords as $blockWord){
        $blockWord = trim($blockWord); // trim extra space from keywords
        if(strpos($post, $blockWord) !== false){
            $safeProceed = 'false';
            break 2;
        }
    }
}

$s_age = $_POST['s_age'];
if( !($s_age == 'Above 18' || $s_age == 'Below 18')){
    $s_age = '';
}
// $mailBody = "<table border='0' style='text-align: left;'><tr><th style='vertical-align: top;'>Full Name:</th><td style='vertical-align: top;'>".$full_name."<br></td></tr><tr><th style='vertical-align: top;'>Learners Permit#</th><td style='vertical-align: top;'>".$t_learnerPermit."<br></td></tr><tr><th style='vertical-align: top;'>Issue Date:</th><td style='vertical-align: top;'>".$t_issueDate."<br></td></tr><tr><th style='vertical-align: top;'>Expiry Date:</th><td style='vertical-align: top;'>".$t_expirationDate."<br></td></tr><tr><th style='vertical-align: top;'>Date of Birth:</th><td style='vertical-align: top;'>".$t_Dob."<br></td></tr><tr><th style='vertical-align: top;'>Email ID:</th><td style='vertical-align: top;'>".$t_email."<br></td></tr><tr><th style='vertical-align: top;'>Address:</th><td style='vertical-align: top;'>".$t_Address."<br></td></tr><tr><th style='vertical-align: top;'>City Name:</th><td style='vertical-align: top;'>".$t_CityName."<br></td></tr><tr><th style='vertical-align: top;'>State:</th><td style='vertical-align: top;'>".$t_State."<br></td></tr><tr><th style='vertical-align: top;'>Zip Code:</th><td style='vertical-align: top;'>".$t_zipCode."<br></td></tr><tr><th style='vertical-align: top;'>Home Phone:</th><td style='vertical-align: top;'>".$t_homePhone."<br></td></tr><tr><th style='vertical-align: top;'>Cell Number:</th><td style='vertical-align: top;'>".$t_cellPhone."<br></td></tr><tr><th style='vertical-align: top;'>Age:</th><td style='vertical-align: top;'>".$s_age."<br></td></tr><tr><th style='vertical-align: top;'>High School Name:</th><td style='vertical-align: top;'>".$t_highSchoolName."<br></td></tr><tr><th style='vertical-align: top;'>GPA:</th><td style='vertical-align: top;'>".$t_gpa."<br></td></tr><tr><th style='vertical-align: top;'>When you want to Start:</th><td style='vertical-align: top;'>".$t_wdys."<br></td></tr><tr><th style='vertical-align: top;'>Schedule Date:</th><td style='vertical-align: top;'>".$scheduledDate."<br></td></tr><tr><th style='vertical-align: top;'>Schedule Time:</th><td style='vertical-align: top;'>".$scheduledTime."<br></td></tr></table>";
// //php mailer variables

// $wpsf_to = 'info@eagledrivingschool.net';
// $headers = 'From: '.$wpsf_from ."\r\n".
// 'Reply-To: ' . $t_email . "\r\n".
// "Content-type: text/html\r\n";
/* // // Here put your Validation and send mail
// $sent = wp_mail($wpsf_to, $wpsf_subject, $mailBody, $headers);
// if($sent) { */
if(true) {
    require_once('../../../wp-config.php');
    global $wpdb;
    $mails = $wpdb->prefix.'wpsf_mails';
    $query = "SELECT * FROM ".$mails." WHERE email='".encodeForm($t_email)."' AND cell_phone='".encodeForm($t_cellPhone)."'";
    $selectedReg = $wpdb->get_results ( $query );
    
    if(empty($selectedReg)){
        $wpdb->insert($mails, array(
            'full_name' => encodeForm($full_name),
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
            'schedule' => encodeForm($s_class),
            'sch_date' => encodeForm($scheduledDate),
            'sch_time' => encodeForm($scheduledTime)
        ));
        $result = array('status' => 'true', 'response' => "Thanks for registeration, we'll contact you shortly.");
        print_r(json_encode($result));
    }
    else {
        $newUpArr = array();
        foreach ($selectedReg as $k){
            foreach ($k as $keyu => $l) {
                $newUpArr[$keyu] = $l;
            }
        }
        $rowid = $newUpArr['id'];
        $wpdb->update( $mails, array( 
            'full_name' => '',
            ), array( "id" => $rowid ) 
        );
        $updated = $wpdb->update( $mails, array(
            'full_name' => encodeForm($full_name),
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
            'schedule' => encodeForm($s_class),
            'sch_date' => encodeForm($scheduledDate),
            'sch_time' => encodeForm($scheduledTime)
            ), array( "id" => $rowid )
        );
        
        if($updated > 0){
            $result = array('status' => 'true', 'response' => "Your registered information has been updated successfully.");
            print_r(json_encode($result));
        }
    }
}
else  {
    $result = array('status' => 'false', 'response' => "Sorry! Server is not responding at the moment, Please try again.");
    print_r(json_encode($result));
}