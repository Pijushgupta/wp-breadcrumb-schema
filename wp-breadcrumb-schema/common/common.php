<?php
if(!defined('ABSPATH')){
    exit;
}

class common {
    
    public function __construct() {
        
    }
  public function wpbcs_get_saved_wpbcs_data($post_id) {
        if (get_post_meta($post_id, 'wpbcs', true)) {
            
            $parent_data = json_decode(get_post_meta($post_id, 'wpbcs', true));
            if($parent_data !== NULL){
            return $parent_data;
            }
        }else{
        return false;
        }
    }
}

