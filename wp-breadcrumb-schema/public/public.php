<?php
if(!defined('ABSPATH')){
    exit;
}

class public_main extends common{
    
    public function __construct(){
        add_action('template_redirect',array($this, 'wp_breadcrumb_schema_find_page'));
    }
    
    function wp_breadcrumb_schema_find_page(){
        //modify this condition to show schema on diffrent pages.
        if('page'== get_post_type() AND is_singular()){
            add_action('wp_footer',array($this,'wp_breadcrumb_schema_print'));
        }
    }
    
    function wp_breadcrumb_schema_print(){
        global $post;
        if($this->wpbcs_get_saved_wpbcs_data($post->ID)!=false){
            if($this->wpbcs_get_saved_wpbcs_data($post->ID)->status == "on"){
                $all_data = $this->wpbcs_prepare_the_data($post->ID);
                $this->wpbcs_prepare_the_schema($all_data);
            }
        }   
    }
    
    function wpbcs_prepare_the_data($wpbcs_post_id){
        if(!isset($data_store)){
            global $data_store;
        }
        if($this->wpbcs_get_saved_wpbcs_data($wpbcs_post_id) != false){
            $the_id = $this->wpbcs_get_saved_wpbcs_data($wpbcs_post_id)->parent_id;
            $data_store[] = $this->wpbcs_get_saved_wpbcs_data($wpbcs_post_id);
            $this->wpbcs_prepare_the_data($the_id);
        }    
      return $data_store;
    }
    
    function wpbcs_prepare_the_schema($all_data){
        $schema_head = '<script type="application/ld+json">{"@context": "https://schema.org","@type": "BreadcrumbList","itemListElement": [';
        $schema_footer= ']}</script>';
        $i = 1;
        foreach(array_reverse($all_data ) as $single_data){
            $schema_body_array[$i]  = '{"@type": "ListItem",';
            $schema_body_array[$i] .= '"position": '.$i.',';
            $schema_body_array[$i] .= '"name":"'.$single_data->parent_title.'",';
           // echo $single_data->parent_title;
            $schema_body_array[$i] .= '"item":"'.get_permalink($single_data->parent_id).'"}';
            $i++;
        }
        $schema_string = null;
        foreach($schema_body_array as $schema){
            $schema_string .= $schema.',';
        }
        $schema_string = rtrim($schema_string,',');
        $breadcrumbschema = $schema_head . $schema_string . $schema_footer;
        echo $breadcrumbschema;
    }
    
    
}


