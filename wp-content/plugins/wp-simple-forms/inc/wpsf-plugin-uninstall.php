<?php
/**
 * @package SimpleForms
 */

class WPSFUninstall {
    
    public static function uninstall(){
        flush_rewrite_rules();
    }
    
}