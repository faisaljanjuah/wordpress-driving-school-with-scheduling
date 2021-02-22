<?php
$data = [];
foreach($_POST as $key=>$value){
	$data[$key] = $value;
}
// print_r($data);

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


if (array_key_exists("verify",$data)) {
	if($data['verify'] == 'true') {
		$email = encodeForm(trim($data['email']));
		$phone = encodeForm(trim($data['phone']));
		require_once('../../../wp-config.php');
		global $wpdb;
		$mails = $wpdb->prefix.'wpsf_mails';
		$query = "SELECT id, sch_date, sch_time FROM ".$mails." WHERE email='".$email."' AND cell_phone='".$phone."'";
		$selectedReg = $wpdb->get_results ( $query );
		if(!empty($selectedReg)){
			$newArr = array();
			foreach ($selectedReg as $i){
				foreach ($i as $key => $j) {
					$newArr[$key] = $j;
				}
			}
			if(!empty($newArr['sch_date'])){
				$newArr['sch_date'] = decodeFrom($newArr['sch_date']);
			}
			if(!empty($newArr['sch_time'])){
				$newArr['sch_time'] = decodeFrom($newArr['sch_time']);
			}
			$newArr = array('status' => 'true') + $newArr;
			echo json_encode($newArr);
		}
		else {
			$result = array('status' => 'false');
			print_r(json_encode($result));
		}
		die();
	}
}

if (array_key_exists("check",$data)) {
	if($data['check'] == 'updatedate'){
		$newDate = $data['schDate'];
		$newTime = $data['schTime'];
		$rowid = $data['id'];
		if(!empty($newDate)){ $newDate = encodeForm($newDate); }
		if(!empty($newTime)){ $newTime = encodeForm($newTime); }
		require_once('../../../wp-config.php');
		global $wpdb;
		$mails = $wpdb->prefix.'wpsf_mails';
		$wpdb->update( $mails, array( 
				'sch_date' => '',
				'sch_time' => ''
			), array( "id" => $rowid ) 
		);
		$result = $wpdb->update( $mails, array( 
				'sch_date' => $newDate,
				'sch_time' => $newTime
			), array( "id" => $rowid ) 
		);
		$updatedQuery = "SELECT id, full_name, email, cell_phone, sch_date, sch_time FROM ".$mails." WHERE id=".$rowid;
		$updatedRow = $wpdb->get_results ( $updatedQuery );
		$newUpArr = array();
		foreach ($updatedRow as $k){
			foreach ($k as $keyu => $l) {
				$newUpArr[$keyu] = $l;
			}
		}
		$fullName = $newUpArr['full_name'];
		$cellPhone = $newUpArr['cell_phone'];
		$to_email = $newUpArr['email'];
		$schDate = $newUpArr['sch_date'];
		$schTime = $newUpArr['sch_time'];
		
		if(!empty($schDate)){ $schDate = decodeFrom($schDate); }
		if(!empty($schTime)){ $schTime = decodeFrom($schTime); }
				
		$wpsf_subject = 'Schedule Updated from Customer | Eagle Driving School';
		$wpsf_to = 'faisaljanjuah@gmail.com';
		$wpsf_from = 'Eagle Driving School <info@eagledrivingschool.net>'; // hard Codeed :P
		$headers = 'From: '.$wpsf_from ."\r\n".
			'Reply-To: ' . $to_email . "\r\n".
			"Content-type: text/html\r\n";
		
		$message = "<br><em>Schedule Update by <strong>".decodeFrom($fullName)."</strong></em><br><br><strong>Updated Details are:</strong><br><br><table style='border: 0; text-align: left;'><tr><th style='vertical-align: top;'>Name</th><td style='vertical-align: top;'>".decodeFrom($fullName)."</td></tr><tr><th style='vertical-align: top;'>Cell Number</th><td style='vertical-align: top;'>".decodeFrom($cellPhone)."</td></tr><tr><th style='vertical-align: top;'>Email</th><td style='vertical-align: top;'>".decodeFrom($to_email)."</td></tr><tr><th style='vertical-align: top;'>New Date</th><td style='vertical-align: top;'>".$schDate."</td></tr><tr><th style='vertical-align: top;'>New Time</th><td style='vertical-align: top;'>".$schTime."</td></tr></table>";
		$sent = wp_mail($wpsf_to, $wpsf_subject, $message, $headers);

		if ($sent > 0) { echo $sent; }
		else { echo $sent; }

	}
}