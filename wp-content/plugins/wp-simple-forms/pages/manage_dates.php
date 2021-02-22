<?php

    global $wpdb;
    // Fields Starts Here
    $dates = '';
    
    // Necessary Variables
    $notice = '';
    $tableName = $wpdb->prefix.'wpsf_off_dates';
    
    // add_off_days
    // edit_off_day
    function bringRecord($id){
        global $wpdb;
        $tableName = $wpdb->prefix.'wpsf_off_dates';
        $selectedRow = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM ".$tableName." WHERE id=%d",$id ), ARRAY_A // ARRAY_A convert object into array for $inserted_row with isset() function
        ); 
        return $selectedRow;
    }

    $page_title = 'Update Off Date';
    $form_type = 'update_off_date';
    $submit_btn_name = 'wpsf_submit_update_off_date';

    $action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : "";
    $date_id = isset($_REQUEST['date_id']) ? trim($_REQUEST['date_id']) : "";
    $form_action = $_SERVER['PHP_SELF'].'?page=wp_off_dates&action=edit_off_day';

    require_once WPSF_PLUGIN_PATH .'inc/validate.php';

    if($action == 'add_off_days'){
        $page_title = 'Add Off Date';
        $form_type = 'add_off_date';
        $submit_btn_name = 'wpsf_submit_add_off_date';
    }
    elseif(isset($_POST['wpsf_submit_add_off_date'])) {
        $proceed = 'true';
        $coffdate = validate('date', $_POST['off_date']);
        if($coffdate == 'false'){ $proceed = 'false'; }

        if($proceed) {
            $wpdb->insert($tableName, array(
                "dates"=> $_POST['off_date']
            ));
            $new_record = $wpdb->insert_id;
            $form_action .= '&date_id='.$new_record;
            $inserted_row = bringRecord($new_record); // select * Query
            extract($inserted_row); // Get array and Assign variables with same name
            // Message Printing
            if( $new_record > 0 ) {
                $notice = '<div id="message" class="updated notice is-dismissible">';
                $notice .= '<p>Off Day has been <strong>added</strong> Successfully.</p>';
                $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
                $notice .= '</div>';
            }
            else {
                $notice = '<div id="message" class="error notice is-dismissible">';
                $notice .= '<p><strong>Failed</strong> to save Off Day.</p>';
                $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
                $notice .= '</div>';
            }
        }
    }
    else {
        $edit_row = bringRecord($date_id); // select * Query
        extract($edit_row); // Get array and Assign variables with same name
        
        if(!empty($date_id)){
            $form_action .= '&date_id='.$date_id;
            if(isset($_POST['wpsf_submit_update_off_date'])){
                $proceed = 'true';
                $uoffdate = validate('date', $_POST['off_date']);
                if($uoffdate == 'false'){ $proceed = 'false'; }
                if($proceed){
                    $wpdb->update($tableName, array(
                        "dates"=> $_POST['off_date']
                    ), array('id'=>$date_id));
                    $updatedRecord = bringRecord($date_id); // select * Query
                    extract($updatedRecord); // Get array and Assign variables with same name
                    if( $date_id > 0 ) {
                        $notice = '<div id="message" class="updated notice is-dismissible">';
                        $notice .= '<p>Off Day has been <strong>updated</strong> Successfully.</p>';
                        $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
                        $notice .= '</div>';
                    }
                }
            }
        }
        else {
            $notice = '<div id="message" class="error notice is-dismissible">';
            $notice .= '<p>No Date selected! Please select <a href="'.$_SERVER['PHP_SELF'].'?page=wp_off_dates">Date from List</a> and then choose <strong>edit</strong> to make changes </p>';
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
            <p>
                <label for="offDate">Select Off Date</label>
                <input id="offDate" type="text" name="off_date" data-field="date" data-format="dd-MMM-yyyy" readonly="readonly" required="required" value="<?php echo $dates; ?>" />

            </p>

            <p><button disabled class="wpsf_btn wpsf_primary" type="submit" name="<?php echo $submit_btn_name; ?>">Save Off Date</button></p>
        </form>

    </div>
</div>
