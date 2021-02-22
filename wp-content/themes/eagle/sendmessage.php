<?php

$data = [];
foreach($_POST as $key=>$value){
    $data[$key] = $value;
}
extract($data);

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

if($safeProceed == 'true'){
	
	$mailBody = "<br><em>You received this email from your website.</em><br><table border='0' style='text-align: left;'><tr><th style='vertical-align: top;'>Name</th><td style='vertical-align: top;'>".$fname." ".$lname."</td></tr><tr><th style='vertical-align: top;'>Cell Phone</th><td style='vertical-align: top;'>".$cellPhone."</td></tr><tr><th style='vertical-align: top;'>Email</th><td style='vertical-align: top;'>".$email."</td></tr><tr><th style='vertical-align: top;'>Subject</th><td style='vertical-align: top;'>".$subject."</td></tr><tr><th style='vertical-align: top;'>Message</th><td style='vertical-align: top;'>".$message."</td></tr></table>";
    
    $wpsf_to = 'faisaljanjuah@gmail.com';
    $headers = 'From: '.$wpsf_from ."\r\n".
    'Reply-To: ' . $email . "\r\n".
    "Content-type: text/html\r\n";
    // Here put your Validation and send mail
    $sent = wp_mail($wpsf_to, $wpsf_subject, $mailBody, $headers);

    if($sent){
        $result = array('status' => 'true', 'response' => "Your message has been successfully.");
        print_r(json_encode($result));
    }

}
else  {
    $result = array('status' => 'false', 'response' => "Sorry! Server is not responding at the moment, Please try again.");
    print_r(json_encode($result));
}
