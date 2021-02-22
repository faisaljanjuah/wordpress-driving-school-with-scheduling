<div class="wrap">
    <h1 class="wp-heading-inline">Registrations</h1>
    <a href="admin.php?page=wp_simple_regs&action=wpsf_add_mail" class="page-title-action">Add New</a>
    <hr class="wp-header-end">

    <div class="page-content wpsf wpsf_forms_table">

        <?php

        if ( ! class_exists( 'WP_List_Table' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
        }

        if( ! class_exists('WPSF_List_Forms') ){
            class WPSF_List_Forms extends WP_List_Table {

                public function __construct() {
                    parent::__construct( array(
                        'singular' => 'registration',
                        'plural' => 'registrations',
                        'ajax' => false,
                    ) );
                }
                public function prepare_items() {
                    $data = $this->wp_simple_forms_list();
                    $per_page = 10;
                    $current_page = $this->get_pagenum();
                    $total_items = count($data);
                    $this->set_pagination_args(array(
                        'total_items'=>$total_items,
                        'per_page'=>$per_page
                    ));
                    $this->items = array_slice($data, ( ( $current_page - 1 ) * $per_page ), $per_page );
                    $allColumns = $this->get_columns();
                    $hidden = $this->get_hidden_columns();
                    $sortable = $this->get_sortable_columns();
                    $this->_column_headers = array($allColumns, $hidden, $sortable); // parameters must be in sequence like (data, visibility, sortable) etc etc
                }
                public function wp_simple_forms_list($orderby='', $order='', $searchtext=''){
                    global $wpdb;
                    $wpsfTable = $wpdb->prefix.'wpsf_mails';
                    $action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : "";
                    $formid = isset($_REQUEST['mail_id']) ? trim($_REQUEST['mail_id']) : "";
                    if(!empty($formid) && $action == 'wpsf_delete_mail') {
                        $wpdb->delete($wpsfTable, array("id"=>$formid));
                        $notice = '<div id="message" class="updated notice is-dismissible">';
                        $notice .= '<p>Registration has been <strong>deleted</strong> Successfully.</p>';
                        $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
                        $notice .= '</div>';
                        echo $notice;
                    }
                    $orderby = isset($_GET['orderby']) ? trim($_GET['orderby']) : "";
                    $order = isset($_GET['order']) ? trim($_GET['order']) : "ORDER BY ID DESC";
                    $searchtext = isset($_POST['s']) ? trim($_POST['s']) : "";

                    $allRegs = '';

                    if($orderby!=""){ $orderby = " ORDER BY $orderby "; } 
                    if( $order!="" ){ $order = " $order "; }
                    if( $searchtext!="" ){ 
                        $searchtext = " WHERE (full_name LIKE '%$searchtext%') OR (email LIKE '%$searchtext%') OR (cell_phone LIKE '%$searchtext%') ";
                    }
                    $query = "SELECT * FROM " . $wpsfTable.$searchtext.$orderby.$order;
                    $allRegs = $wpdb->get_results( $query );
                    $regs = array();
                    if(count($allRegs)>0){
                        foreach($allRegs as $index => $reg){
                            $regs[]=array(
                                "id"=>$reg->id,
                                "fullname"=>$reg->full_name,
                                "email"=>$reg->email,
                                "cell"=>$reg->cell_phone,
                                "schDate"=>$reg->sch_date,
                                "schTime"=>$reg->sch_time
                            );
                        }
                    }
                    return $regs;
                }
                public function get_columns() {
                    $columns = array(
                        'id' => 'ID',
                        'fullname' => 'Full Name',
                        'email' => 'Email',
                        'cell' => 'Cell Number',
                        'schDate' => 'Schedule Date',
                        'schTime' => 'Schedule Time',
                        'actions' => 'Actions'
                    );
                    return $columns;
                }
                public function column_default($item, $column_name) {
                    switch($column_name){
                        case 'id':
                        case 'fullname':
                        case 'email':
                        case 'cell':
                        case 'schDate':
                        case 'schTime':
                            return $item[$column_name];
                        case 'actions':
                            return '<a href="?page='.$_GET['page'].'&action=wpsf_edit_mail&mail_id='.$item['id'].'" class="list-action btn-edit">Edit</a><button type="button" data-page="'.$_GET['page'].'" data-action="wpsf_delete_mail" data-mail_id="'.$item['id'].'" class="list-action btn-delete">Delete</button>';
                        default:
                            return 'No Data Found!';
                    }
                }
                public function get_hidden_columns() {
                    return array('id');
                }
                public function get_sortable_columns() {
                    return array(
                        "fullname" => array( "fullname", false ) // true means default is asc, here desc is default
                    );
                }
                public function column_fullname($actionColumn){
                    $name_column = '<h4><a href="?page='.$_GET['page'].'&action=wpsf_edit_mail&mail_id='.$actionColumn['id'].'">'.base64_decode($actionColumn['fullname']).'</a></h4>';
                    return $name_column;
                }
                public function column_email($email){
                    $email = base64_decode($email['email']);
                    return $email;
                }
                public function column_cell($cell){
                    $cell = base64_decode($cell['cell']);
                    return $cell;
                }
                public function column_schDate($schDate){
                    $schDate = base64_decode($schDate['schDate']);
                    return $schDate;
                }
                public function column_schTime($schTime){
                    $schTime = base64_decode($schTime['schTime']);
                    return $schTime;
                }
            }
        }

        $WPSF_Table = new WPSF_List_Forms();
        $WPSF_Table->prepare_items();
        echo '<form method="post" name="search_wpsf_forms" action="'.$_SERVER['PHP_SELF'].'?page=wp_simple_regs">';
        $WPSF_Table->search_box('Search', 'search_wpsf_list_id');
        echo '</form>';
        $WPSF_Table->display();
        
        ?>
    </div>
</div>
