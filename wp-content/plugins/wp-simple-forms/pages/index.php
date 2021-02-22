<div class="wrap">
    <!-- <h2><?php // echo esc_html(get_admin_page_title()); ?></h2> -->
    <h1 class="wp-heading-inline">WP Simple Forms</h1>
    <a href="admin.php?page=wp_simple_forms&action=wpsf_add" class="page-title-action">Add New</a>
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
                        'singular' => 'form',
                        'plural' => 'forms',
                        'ajax' => false,
                    ) );
                }

                // function no_items() { // Custom Message if No data found in table
                //     _e( "You haven't created any form yet, <a href='admin.php?page=wp_simple_forms&action=wpsf_add'><strong>Click here</strong></a> to start!" );
                // }

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
                    $wpsfTable = $wpdb->prefix.'wpsf';

                    $action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : "";
                    $formid = isset($_REQUEST['form_id']) ? trim($_REQUEST['form_id']) : "";
                    
                    if(!empty($formid) && $action == 'wpsf_delete') {
                        $wpdb->delete($wpsfTable, array("id"=>$formid));
                        $notice = '<div id="message" class="updated notice is-dismissible">';
                        $notice .= '<p>Form has been <strong>deleted</strong> Successfully.</p>';
                        $notice .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
                        $notice .= '</div>';
                        echo $notice;
                    }

                    $orderby = isset($_GET['orderby']) ? trim($_GET['orderby']) : "";
                    $order = isset($_GET['order']) ? trim($_GET['order']) : "ORDER BY ID DESC";
                    $searchtext = isset($_POST['s']) ? trim($_POST['s']) : "";

                    $allPosts = '';

                    if($orderby!=""){ $orderby = " ORDER BY $orderby "; } 
                    if( $order!="" ){ $order = " $order "; }
                    if( $searchtext!="" ){ 
                        // $searchtext = " AND (post_title LIKE '%$searchtext%' OR post_content LIKE '%$searchtext%') "; 
                        $searchtext = " WHERE TITLE like '%$searchtext%' "; 
                    }
                    // $query = "SELECT * FROM ".$wpdb->posts." WHERE post_type='post' AND post_status='publish' ".$searchtext.$orderby.$order;
                    $query = "SELECT * FROM " . $wpsfTable.$searchtext.$orderby.$order;
                    // echo '<script>console.log("'.$query.'");</script>';
                    $allPosts = $wpdb->get_results( $query );
                    $posts = array();
                    if(count($allPosts)>0){
                        foreach($allPosts as $index => $post){
                            $posts[]=array(
                                "id"=>$post->id,
                                "title"=>$post->title
                                // "shortcode"=>$post->shortcode
                            );
                        }
                    }
                    return $posts;
                }

                public function get_columns() {
                    $columns = array(
                        // 'cb' => '<input type="checkbox" />',
                        'id' => 'ID',
                        'title' => 'Title',
                        // 'shortcode' => 'Shortcode',
                        'actions' => 'Actions'
                    );
                    return $columns;
                }
                // public function column_cb($item) {
                //     return sprintf('<input type="checkbox" name="forms[]" value="%s" />', $item['id']);
                // }
                public function column_default($item, $column_name) {
                    switch($column_name){
                        case 'id':
                        case 'title':
                        // case 'shortcode':
                            return $item[$column_name];
                        case 'actions':
                            return '<a href="?page='.$_GET['page'].'&action=wpsf_edit&form_id='.$item['id'].'" class="list-action btn-edit">Edit</a><button type="button" data-page="'.$_GET['page'].'" data-action="wpsf_delete" data-form_id="'.$item['id'].'" class="list-action btn-delete">Delete</button>';
                        default:
                            return 'No Data Found!';
                    }
                }

                public function get_hidden_columns() {
                    // return array('id');
                    return array();
                }
                public function get_sortable_columns() {
                    return array(
                        "title" => array( "title", false ) // true means default is asc, here desc is default
                        // "amount" => array( "amount", false ),
                        // "payment_method" => array( "payment_method", false )
                    );
                }

                public function column_title($actionColumn){
                    // // Creating Modify Buttons in specific Column
                    // $actions = array(
                    //     "edit"=>'<a href="?page='.$_GET['page'].'&action=wpsf_edit&form_id='.$actionColumn['id'].'">Edit</a>',
                    //     "delete"=>'<a href="?page='.$_GET['page'].'&action=wpsf_delete&form_id='.$actionColumn['id'].'">Delete</a>'
                    // );
                    // return $actionColumn['title'].' '.$this->row_actions($actions);
                    $title_column = '<h4><a href="?page='.$_GET['page'].'&action=wpsf_edit&form_id='.$actionColumn['id'].'">'.$actionColumn['title'].'</a></h4>';
                    return $title_column;
                }

                // public function column_shortcode($shortcodeColumn){
                //     // $shortcode_column = '<input type="text" onfocus="this.select();" readonly value="'.$shortcodeColumn['shortcode'].'" />';
                //     $shortcode_column = '<textarea onfocus="this.select();">'. str_replace('\"', '"', base64_decode($shortcodeColumn['shortcode'])).'</textarea>';
                //     return $shortcode_column;
                // }

                // public function get_bulk_actions() {
                //     $actions = array(
                //         'delete' => __( 'Delete', 'WPSF' ),
                //     );
                //     return $actions;
                // }
                // public function process_bulk_action() {
                //     if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'delete' ) ) {
                //         $delete_ids = esc_sql( $_POST['forms'] );
                //         foreach ( $delete_ids as $did ) { 
                //             global $wpdb;
                //             $wpdb->query($wpdb->prepare(
                //                 "DELETE FROM ".$wpdb->prefix.'wpsf'." WHERE id='".$did."'"));
                //         }
                //         wp_redirect( esc_url( add_query_arg() ) );
                //         exit;
                //     }
                // }
            }
        }

        $WPSF_Table = new WPSF_List_Forms();
        $WPSF_Table->prepare_items();
        echo '<form method="post" name="search_wpsf_forms" action="'.$_SERVER['PHP_SELF'].'?page=wp_simple_forms">';
        // $search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : false;
        // echo '<p class="search-box">';
        // echo '<label class="screen-reader-text" for="search_wpsf_list_id-search-input">Search:</label>';
        // echo '<input type="search" id="search_wpsf_list_id-search-input" name="s" value="'.$search.'">';
        // echo '<input type="submit" id="search-submit" class="button" value="Search">';
        // echo '</p>';
        $WPSF_Table->search_box('Search', 'search_wpsf_list_id');
        echo '</form>';

        $WPSF_Table->display();
        
        ?>

    </div>

</div>
