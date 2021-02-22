<?php

    global $wpdb;
    // Fields Starts Here
    $title = '';
    $shortcode = '';
    $form = '';
    $mail_to = '';
    $mail_from = '';
    $mail_subject = '';
    $mail_header = '';
    $mail_body = '';
    $mail_content_type = '';
    $block_keywords = '';
    
    // Necessary Variables
    $notice = '';
    $wpsfTable = $wpdb->prefix.'wpsf';
    
    function bringRecord($id){
        global $wpdb;
        $wpsfTable = $wpdb->prefix.'wpsf';
        $selectedRow = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM ".$wpsfTable." WHERE id=%d",$id ), ARRAY_A // ARRAY_A convert object into array for $inserted_row with isset() function
        ); 
        return $selectedRow;
    }

    $page_title = 'Update Form';
    $form_type = 'update_wpsf_form';
    $submit_btn_name = 'wpsf_submit_update_form';

    $action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : "";
    $form_id = isset($_REQUEST['form_id']) ? trim($_REQUEST['form_id']) : "";
    $form_action = $_SERVER['PHP_SELF'].'?page=wp_simple_forms&action=wpsf_edit';

    require_once WPSF_PLUGIN_PATH .'inc/validate.php';
    require_once WPSF_PLUGIN_PATH .'inc/process.php';

    if($action == 'wpsf_add'){
        $page_title = 'Add New Form';
        $form_type = 'add_wpsf_form';
        $submit_btn_name = 'wpsf_submit_new_form';
    }
    elseif(isset($_POST['wpsf_submit_new_form'])) {
        $proceed = 'true';
        $ctitle = validate('alphanum', $_POST['wpsf_title']);
        $cmailto = validate('email', $_POST['wpsf_mailTo']);
        $cmailfrom = validate('alphanumbr', $_POST['wpsf_mailFrom']);
        $csubject = validate('alphanum', $_POST['wpsf_subject']);
        if($ctitle == 'false'){ $proceed = 'false'; }
        if($cmailto == 'false'){ $proceed = 'false'; }
        if($cmailfrom == 'false'){ $proceed = 'false'; }
        if($csubject == 'false'){ $proceed = 'false'; }
        $cform = process_data($_POST['wpsf_form']);
        $cblockWords = process_data($_POST['wpsf_blockKeywords']);
        if($proceed) {
            $wpdb->insert($wpsfTable, array(
                "title"=> $_POST['wpsf_title'],
                "form"=> $cform,
                "mail_to"=> $_POST['wpsf_mailTo'],
                "mail_from"=> $_POST['wpsf_mailFrom'],
                "mail_subject"=> $_POST['wpsf_subject'],
                "block_keywords"=> $cblockWords
            ));
            $new_record = $wpdb->insert_id;
            $form_action .= '&form_id='.$new_record;
            $inserted_row = bringRecord($new_record); // select * Query
            extract($inserted_row); // Get array and Assign variables with same name
            $form = base64_decode($form);
            $block_keywords = base64_decode($block_keywords);
            // Message Printing
            if( $new_record > 0 ) {
                $notice = '<div id="message" class="updated notice is-dismissible">';
                $notice .= '<p>Form has been <strong>saved</strong> Successfully.</p>';
                $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
                $notice .= '</div>';
            }
            else {
                $notice = '<div id="message" class="error notice is-dismissible">';
                $notice .= '<p><strong>Failed</strong> to save form.</p>';
                $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
                $notice .= '</div>';
            }
        }
    }
    else {
        $edit_row = bringRecord($form_id); // select * Query
        extract($edit_row); // Get array and Assign variables with same name
        $form = base64_decode($form);
        $block_keywords = base64_decode($block_keywords);
        if(!empty($form_id)){
            $form_action .= '&form_id='.$form_id;
            if(isset($_POST['wpsf_submit_update_form'])){
                $proceed = 'true';
                $utitle = validate('alphanum', $_POST['wpsf_title']);
                $umailto = validate('email', $_POST['wpsf_mailTo']);
                $umailfrom = validate('alphanumbr', $_POST['wpsf_mailFrom']);
                $usubject = validate('alphanum', $_POST['wpsf_subject']);
                if($utitle == 'false'){ $proceed = 'false'; }
                if($umailto == 'false'){ $proceed = 'false'; }
                if($umailfrom == 'false'){ $proceed = 'false'; }
                if($usubject == 'false'){ $proceed = 'false'; }
                $uform = process_data($_POST['wpsf_form']);
                $ublockWords = process_data($_POST['wpsf_blockKeywords']);
                if($proceed){
                    $wpdb->update($wpsfTable, array(
                        "title"=> $_POST['wpsf_title'],
                        "form"=> $uform,
                        "mail_to"=> $_POST['wpsf_mailTo'],
                        "mail_from"=> $_POST['wpsf_mailFrom'],
                        "mail_subject"=> $_POST['wpsf_subject'],
                        "block_keywords"=> $ublockWords
                    ), array('id'=>$form_id));
                    $updatedRecord = bringRecord($form_id); // select * Query
                    extract($updatedRecord); // Get array and Assign variables with same name
                    $form = base64_decode($form);
                    $block_keywords = base64_decode($block_keywords);
                    if( $form_id > 0 ) {
                        $notice = '<div id="message" class="updated notice is-dismissible">';
                        $notice .= '<p>Form has been <strong>updated</strong> Successfully.</p>';
                        $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
                        $notice .= '</div>';
                    }
                }
            }
        }
        else {
            $notice = '<div id="message" class="error notice is-dismissible">';
            $notice .= '<p>No form selected! Please select <a href="'.$_SERVER['PHP_SELF'].'?page=wp_simple_forms">form from List</a> and then choose <strong>edit</strong> to make changes </p>';
            $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
            $notice .= '</div>';
        }

    }

    // // All prepared values
    // $title = (isset($title) && (trim($title)!="")) ?  $title : '';
    // // $shortcode = (isset($shortcode) && (trim($shortcode)!="")) ? str_replace('\"', '"', base64_decode($shortcode)) : '';
    // $form = (isset($form) && (trim($form)!="")) ?   str_replace('\"', '"', base64_decode($form)) : '';
    // $mail_to = (isset($mail_to) && (trim($mail_to)!="")) ?  $mail_to : '';
    // $mail_from = (isset($mail_from) && (trim($mail_from)!="")) ?  $mail_from : '';
    // $mail_subject = (isset($mail_subject) && (trim($mail_subject)!="")) ?  $mail_subject : '';
    // $mail_header = (isset($mail_header) && (trim($mail_header)!="")) ?  $mail_header : '';
    // $mail_body = (isset($mail_body) && (trim($mail_body)!="")) ? str_replace('\"', '"', base64_decode($mail_body)) : '';
    // $block_keywords = (isset($block_keywords) && (trim($block_keywords)!="")) ?  $block_keywords : '';

?>
<div class="wrap">

    <h1 class="wp-heading-inline"><?php echo $page_title; ?></h1>
    <hr class="wp-header-end">

    <?php echo $notice; ?>

    <div class="page-content wpsf wpsf_form">

        <form method="post" name="<?php echo $form_type; ?>" action="<?php echo $form_action; ?>">
            <p>
                <label>Title</label>
                <input type="text" name="wpsf_title" required value="<?php echo $title; ?>" />
            </p>
            <p>
                <label>Form</label>
                <textarea name="wpsf_form" required><?php echo $form; ?></textarea>
            </p>
            <p>
                <label>Mail-to</label>
                <input type="text" name="wpsf_mailTo" value="<?php echo $mail_to; ?>" />
            </p>
            <p>
                <label>Mail-From</label>
                <input type="text" name="wpsf_mailFrom" value="<?php echo $mail_from; ?>" />
            </p>
            <p>
                <label>Email Subject</label>
                <input type="text" name="wpsf_subject" value="<?php echo $mail_subject; ?>" />
            </p>
            <p>
                <label>Block Keywords</label>
                <input type="text" name="wpsf_blockKeywords" value="<?php echo $block_keywords; ?>" />
            </p>

            <p><button class="wpsf_btn wpsf_primary" type="submit" name="<?php echo $submit_btn_name; ?>">Save Form</button></p>
        </form>

    </div>
</div>
