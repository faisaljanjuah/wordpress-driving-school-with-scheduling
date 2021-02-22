<div class="wrap">
    <!-- <h2><?php // echo esc_html(get_admin_page_title()); ?></h2> -->
    <h1 class="wp-heading-inline">Off Days</h1>
    <a href="admin.php?page=wp_off_dates&action=add_off_days" class="page-title-action">Add Off Day</a>
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
                        'singular' => 'date',
                        'plural' => 'dates',
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
                    $wpsfTable = $wpdb->prefix.'wpsf_off_dates';

                    $action = isset($_REQUEST['action']) ? trim($_REQUEST['action']) : "";
                    $formid = isset($_REQUEST['date_id']) ? trim($_REQUEST['date_id']) : "";
                    
                    if(!empty($formid) && $action == 'wpsf_delete') {
                        $wpdb->delete($wpsfTable, array("id"=>$formid));
                        $notice = '<div id="message" class="updated notice is-dismissible">';
                        $notice .= '<p>Date has been <strong>deleted</strong> Successfully.</p>';
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
                        $searchtext = " WHERE dates like '%$searchtext%' "; 
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
                                "dates"=>$post->dates
                                // "shortcode"=>$post->shortcode
                            );
                        }
                    }
                    return $posts;
                }
                
                public function get_columns() {
                    $columns = array(
                        // 'cb' => '<input type="checkbox" />',
                        // 'id' => 'ID',
                        'dates' => 'Dates',
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
                        case 'dates':
                        // case 'shortcode':
                            return $item[$column_name];
                        case 'actions':
                            return '<a href="?page='.$_GET['page'].'&action=edit_off_day&date_id='.$item['id'].'" class="list-action btn-edit">Edit</a><button type="button" data-page="'.$_GET['page'].'" data-action="wpsf_delete" data-date_id="'.$item['id'].'" class="list-action btn-delete">Delete</button>';
                        default:
                            return 'No Data Found!';
                    }
                }

                public function get_hidden_columns() {
                    return array('id');
                    // return array();
                }
                public function get_sortable_columns() {
                    return array(
                        "dates" => array( "dates", false ) // true means default is asc, here desc is default
                    );
                }

                public function column_dates($actionColumn){
                    $date_column = '<h4><a href="?page='.$_GET['page'].'&action=edit_off_day&date_id='.$actionColumn['id'].'">'.$actionColumn['dates'].'</a></h4>';
                    return $date_column;
                }
            }
        }

        $WPSF_Table = new WPSF_List_Forms();
        $WPSF_Table->prepare_items();
        echo '<form method="post" name="search_wpsf_offDate" action="'.$_SERVER['PHP_SELF'].'?page=wp_off_dates">';
        $WPSF_Table->search_box('Search', 'search_wpsf_list_id');
        echo '</form>';

        $WPSF_Table->display();
        
        ?>

    </div>

</div>
