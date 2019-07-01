<?php
/*
 Plugin Name: WP Breadcrumb schema
 Plugin URI:  https://github.com/Pijushgupta
 Description: Add Breadcrumb Schema to Pages
 Version:     1.0.0
 Author:      Made with ❤ by Pijush Gupta
 Author URI:  https://github.com/Pijushgupta
 Text Domain: wp-breadcrumb-schema
 License:     GPL-2.0+
 License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*No access directly*/
if(!defined('ABSPATH')){
    exit;
}

//Paths
if(!defined('WPBCS')){
    define('WPBCS', plugin_dir_path(__FILE__));
    define('WPBCSADMIN',WPBCS . "/admin");
    define('WPBCSPUBLIC',WPBCS . "/public");
    define('WPBCSCOM',WPBCS . "/common");
}

//Loading common file 
$commonfile = WPBCSCOM . '/common.php';
$publicfile = WPBCSPUBLIC . '/public.php';
if(file_exists($commonfile) && file_exists($publicfile)){
    include_once $commonfile;
    include_once $publicfile;
}
 

//Required for current_user_can function to work properly
if(!function_exists('wp_get_current_user')){
     include_once (ABSPATH . "wp-includes/pluggable.php");
}

//checking if the current user is either admin or editor load admin class(s)/method(s)
if(current_user_can('editor') || current_user_can('administrator')){
    $adminfile = WPBCSADMIN . '/admin.php';
    if(file_exists($adminfile)){
        include_once $adminfile;
        if(class_exists('admin_main')){
            $wpbcs_admin = new admin_main();
        }
    }
}

//loading classes for frontend  
if(class_exists('public_main')){
    $wpbcs_public = new public_main();
}







    

