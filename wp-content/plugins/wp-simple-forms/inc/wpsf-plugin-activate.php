<?php
/**
 * @package SimpleForms
 */

class WPSFActivate {

    public static function activate(){
        flush_rewrite_rules();
    }

    public static function register(){
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue' ) ); // adds files on Admin side
        // add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) ); // adds files on client side
        
        // Plugin Menu on admin side
        add_action( 'admin_menu', array( __CLASS__, 'add_admin_page' ) );

        // Plugin Activation Links
        add_filter( "plugin_action_links_".WPSF_PLUGIN, array(__CLASS__, 'pluginActivationLinks') );
    }
    public static function enqueue(){
        wp_enqueue_style('SimpleFormsStyles', WPSF_PLUGIN_URL.'assets/css/wp-simple-forms.css');
        wp_enqueue_style('datePickerStyles', WPSF_PLUGIN_URL.'assets/css/dateTimePicker.css');
        wp_enqueue_script('jQlibrary', WPSF_PLUGIN_URL.'assets/js/jquery-3.3.1.min.js');
        wp_enqueue_script('datePickerScripts', WPSF_PLUGIN_URL.'assets/js/DateTimePicker.min.js');
        wp_enqueue_script('SimpleFormsScripts', WPSF_PLUGIN_URL.'assets/js/wp-simple-forms.js');
    }
    public static function add_admin_page(){
        add_menu_page('Registrations', 'Registrations', 'manage_options', 'wp_simple_regs', array(__CLASS__, 'mails_index'), 'dashicons-email-alt', 20 );
        add_menu_page('Off Dates', 'Off Dates', 'manage_options', 'wp_off_dates', array(__CLASS__, 'date_index'), 'dashicons-dismiss', 21 );
        add_menu_page('WP Simple Forms', 'WP Forms', 'manage_options', 'wp_simple_forms', array(__CLASS__, 'admin_index'), 'dashicons-format-aside', 22 );
    }
    public static function pluginActivationLinks($links){
        $pluginlinks = '<a href="admin.php?page=wp_simple_forms">Settings</a>'; // must be admin.php to active require menu link
            array_unshift( $links, $pluginlinks ); // insert in the beginning
        // $pluginlinks = '<a href="buy_simpleforms.php?page=wp_simple_forms"><strong style="display: inline; color: #17CC17;">Buy Pro</strong></a>';
        //     array_push( $links, $pluginlinks ); // insert in the End
        return $links;
    }

    public static function date_index(){
        $action = isset($_GET['action']) ? trim($_GET['action']) : "";
        if ( $action=="add_off_days" || $action=="edit_off_day" ){ // if action is "add_off_days" or "edit_off_days" go on manage form page
            $form_id = isset($_GET['form_id']) ? trim($_GET['form_id']) : "";
            ob_start();
            require_once WPSF_PLUGIN_PATH .'pages/manage_dates.php';
            $pageContent = ob_get_contents();
            ob_end_clean();
            echo $pageContent;
        }
        else { // else list all forms
            ob_start();
            require_once WPSF_PLUGIN_PATH .'pages/listDates.php';
            $pageContent = ob_get_contents();
            ob_end_clean();
            echo $pageContent;
        }
    }

    public static function admin_index(){
        $action = isset($_GET['action']) ? trim($_GET['action']) : "";
        if ( $action=="wpsf_add" || $action=="wpsf_edit" ){ // if action is "wpsf_add" or "wpsf_edit" go on manage form page
            $form_id = isset($_GET['form_id']) ? trim($_GET['form_id']) : "";
            ob_start();
            require_once WPSF_PLUGIN_PATH .'pages/manage_wpsf.php';
            $pageContent = ob_get_contents();
            ob_end_clean();
            echo $pageContent;
        }
        else { // else list all forms
            ob_start();
            require_once WPSF_PLUGIN_PATH .'pages/index.php';
            $pageContent = ob_get_contents();
            ob_end_clean();
            echo $pageContent;
        }
    }

    public static function mails_index(){
        $action = isset($_GET['action']) ? trim($_GET['action']) : "";
        if ( $action=="wpsf_add_mail" || $action=="wpsf_edit_mail" ){ // if action is "wpsf_add_mail" or "wpsf_edit_mail" go on manage form page
            $form_id = isset($_GET['mail_id']) ? trim($_GET['mail_id']) : "";
            ob_start();
            require_once WPSF_PLUGIN_PATH .'pages/manage_mails.php';
            $pageContent = ob_get_contents();
            ob_end_clean();
            echo $pageContent;
        }
        else { // else list all forms
            ob_start();
            require_once WPSF_PLUGIN_PATH .'pages/listMails.php';
            $pageContent = ob_get_contents();
            ob_end_clean();
            echo $pageContent;
        }
    }

    public static function create_wpsf_table(){
        global $wpdb;
        $table_name = $wpdb->prefix.'wpsf';
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $createTable = "CREATE TABLE IF NOT EXISTS $table_name (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                title varchar(255),
                form text,
                mail_to varchar(255),
                mail_from varchar(255),
                mail_subject varchar(255),
                block_keywords varchar(255),
                PRIMARY KEY (id)
                ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $createTable );
        }

        $table_name = $wpdb->prefix.'wpsf_off_dates';
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $createTable = "CREATE TABLE IF NOT EXISTS $table_name (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                dates varchar(255),
                PRIMARY KEY (id)
                ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $createTable );
        }

        $table_name = $wpdb->prefix.'wpsf_mails';
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = $wpdb->get_charset_collate();
            $createTable = "CREATE TABLE IF NOT EXISTS $table_name (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                full_name varchar(255),
                permit_no varchar(255),
                permit_issue_date varchar(255),
                permit_expiry varchar(255),
                date_of_birth varchar(255),
                email varchar(255),
                student_address varchar(255),
                city varchar(255),
                state_name varchar(255),
                zip varchar(255),
                home_phone varchar(255),
                cell_phone varchar(255),
                age varchar(255),
                school_name varchar(255),
                gpa varchar(255),
                when2start varchar(255),
                schedule varchar(255),
                sch_date varchar(255),
                sch_time varchar(255),
                PRIMARY KEY (id)
                ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $createTable );
        }
    }   

}