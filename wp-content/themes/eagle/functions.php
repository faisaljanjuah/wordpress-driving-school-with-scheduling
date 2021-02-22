<?php

if(! function_exists( 'eagleTheme_setup' )){
	function eagleTheme_setup(){
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1568, 9999 );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'mainMenu' => __( 'Main Menu', 'eagle' ),
				'footerMenu' => __( 'Footer Menu', 'eagle' ),
				'contactDetails' => __( 'Contact Details', 'eagle' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 190,
				'width'       => 190,
				'flex-width'  => false,
				'flex-height' => false,
			)
		);
	}
}
add_action( 'after_setup_theme', 'eagleTheme_setup' );

function eagle_filesInjection(){
	wp_enqueue_style('ThemeStyle', get_template_directory_uri().'/style.css', array(),'1.0.0','all');

	wp_enqueue_script('gmaps', 'https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyBUP1lTKTVLGnboU1PaU73gRS1DBTJ21LU', array(), '3', false);
	wp_enqueue_script('jqLib', get_template_directory_uri().'/js/jquery-3.3.1.min.js', array(), '3.3.1', true);
	wp_enqueue_script('DateTimePicker', get_template_directory_uri().'/js/DateTimePicker.min.js', array(), '0.1.39', true);
	wp_enqueue_script('SlickJS', get_template_directory_uri().'/js/slick.js', array(), '1.8.0', true);
	wp_enqueue_script('ScriptsJS', get_template_directory_uri().'/js/custom.js', array(), '1.0.0', true);
	wp_localize_script('scheduleReg', 'check_registeration', array(
		'ajax_url' => admin_url('admin-ajax.php')
	));
}
add_action('wp_enqueue_scripts','eagle_filesInjection');

function admin_js() {
	echo '<link type="text/css" rel="stylesheet" href="'.get_template_directory_uri().'/scss/admin.css" />';
	echo '<script type="text/javascript" src="'. get_template_directory_uri() . '/js/admin.js"></script>';
}
add_action('admin_footer', 'admin_js');

function eagle_logo_setup() {
	$defaults = array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array( 'site-title', 'site-description' ),
	);
	add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'eagle_logo_setup' );

// Remove p tags from category description
remove_filter('term_description','wpautop');

class Menu_With_Description extends Walker_Nav_Menu {
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $class_names = $value = '';
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';

        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';

        $item_output = $args->before;
        $item_output .= '<label>' . $item->description . '</label>';
        $item_output .= ! empty( $item->url ) ? '<a'. $attributes .'>' : '<p>';
        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
        $item_output .= ! empty( $item->url ) ? '</a>' : '</p>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }
}


// /////////////////////////////////////////////////////////////


// add_action('wp_ajax_cvf_send_message', array('CVF_Posts', 'cvf_send_message') );
// add_action('wp_ajax_nopriv_cvf_send_message', array('CVF_Posts', 'cvf_send_message') );
// add_filter('wp_mail_content_type', array('CVF_Posts', 'cvf_mail_content_type') );

// class CVF_Posts {
//     public static function cvf_send_message() {
//         if (isset($_POST['message'])) {
//             $to = get_option('admin_email');
//             $headers = 'From: ' . $_POST['name'] . ' <"' . $_POST['email'] . '">';
//             $subject = "carlofontanos.com | New Message from " . $_POST['name'];
//             ob_start();
//             echo '
//                 <h2>Message:</h2>' . 
//                 wpautop($_POST['message']) . '
//                 <br />
//                 --
//                 <p><a href = "' . home_url() . '">www.carlofontanos.com</a></p>
//             ';
//             $message = ob_get_contents();
//             ob_end_clean();
//             if($mail){
//                 echo 'success';
//             }
//         }
//         exit();
//     }
//     public static function cvf_mail_content_type() {
//         return "text/html";
//     }
// }