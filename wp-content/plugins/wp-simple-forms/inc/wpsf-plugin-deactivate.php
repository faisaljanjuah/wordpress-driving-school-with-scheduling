<?php
/**
 * @package SimpleForms
 */

class WPSFDeactivate {
    
    public static function deactivate(){
        flush_rewrite_rules();
    }
    
}