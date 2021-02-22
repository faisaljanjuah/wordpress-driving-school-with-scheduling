<?php
/**
 * @package Simple-Forms
 */
/* 
Plugin Name: WP Simple Forms
Plugin URI: http://theagiletech.com/simple-forms
Description: This plugin is used to create simple forms with basic validation and filters to stop spams, it also has very simple but wonderful documentation to get started.
Version: 1.0.0
Author: Faisal Khan Janjua
Author URI: https://facebook.com/janjuahgk
License: GPLv2 or later
Text Domain: wp-simple-forms
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

if ( ! defined('ABSPATH') ){
    die;
}

if(!defined('WPSF_PLUGIN_PATH')) { define( 'WPSF_PLUGIN_PATH', plugin_dir_path(__FILE__) ); }
if(!defined('WPSF_PLUGIN_URL')) { define( 'WPSF_PLUGIN_URL', plugin_dir_url(__FILE__) ); }
if(!defined('WPSF_PLUGIN')) { define( 'WPSF_PLUGIN', plugin_basename(__FILE__) ); }

if( ! class_exists('WPSimpleForms') ){
    
    class WPSimpleForms {

        public function __construct() {
            require_once WPSF_PLUGIN_PATH .'inc/wpsf-plugin-activate.php';
            WPSFActivate::register();
            WPSFActivate::create_wpsf_table();
        }
 
        function wpsf_activate(){
            require_once WPSF_PLUGIN_PATH .'inc/wpsf-plugin-activate.php';
            WPSFActivate::activate();
        }
        
        function wpsf_deactivate(){
            require_once WPSF_PLUGIN_PATH .'inc/wpsf-plugin-deactivate.php';
            WPSFDeactivate::deactivate();
        }

        function wpsf_uninstall(){
            // Delete Custom post type
            // Delete all plugin data from DB
            require_once WPSF_PLUGIN_PATH .'inc/wpsf-plugin-uninstall.php';
            WPSFUninstall::uninstall();
        }

    }
    
    $pluginInstance = new WPSimpleForms();
    // $pluginInstance->register();

    // activation
    register_activation_hook( __FILE__ , array( $pluginInstance, 'wpsf_activate' ) );

    // deactivation
    register_deactivation_hook( __FILE__ , array( $pluginInstance, 'wpsf_deactivate' ) );

    // uninstall

}

