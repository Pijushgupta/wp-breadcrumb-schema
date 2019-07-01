<?php
if(!defined('ABSPATH')){
    exit;
}

class admin_main extends common {

    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'wpbcs_load_js_css'));
        add_action('add_meta_boxes', array($this, 'wpbcs_show_meta_box'));
        add_action('save_post', array($this, 'save_meta_box_value'));
    }

    //It will load css and js 
    function wpbcs_load_js_css() {
        wp_register_style('wpbcs-admin-css', plugin_dir_url(__FILE__) . 'css/wpbcs-admin.css', array(), '1.0', 'all');
        wp_register_script('wpbcs-admin-js', plugin_dir_url(__FILE__) . 'js/wpbcs-admin.js', array(), '1.0', false);

        wp_enqueue_style('wpbcs-admin-css');
        wp_enqueue_script('wpbcs-admin-js');
    }

    //It will show the "Meta Box" or fields on Pages    
    function wpbcs_show_meta_box() {

        add_meta_box('wpbcs_meta_box', 'Wordpress Breadcrumb Schema', array($this, 'get_meta_box'), 'page', 'normal', 'high');
    }
    
    //Genarating the meta box 
    function get_meta_box() {
        global $post;
        $checked = "";
        if ($this->wpbcs_get_saved_wpbcs_data($post->ID) != false) {
            if ($this->wpbcs_get_saved_wpbcs_data($post->ID)->status == "on") {
                $checked = "checked";
            }
        }
        ?>
        <div class="checkbox">
            <label><input name="endisable" type="checkbox"  <?php echo $checked; ?>>Enable</label>
        </div> 
        <hr>
        <div class="width30percent">
            <div class="form-group">
                <label for="sel1">Select Parent Page</label>
                <select name="bcparent" class="form-control" id="sel1">
        <?php
        // get the list of pages 
        $pages = get_pages(array('post_status' => 'publish', 'post_type' => 'page'));
        foreach ($pages as $page) {
            //checking if currently opped page and option page is same or not   
            if ($post->ID != $page->ID) {
                if ($this->wpbcs_get_saved_wpbcs_data($post->ID)->parent_id == $page->ID) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                ?>
                            <option <?php echo $selected; ?> value="<?php echo $page->ID; ?>"> <?php echo $page->post_title; ?> </option>


            <?php }
        } ?>
                </select>


            </div> 
        </div>

        <?php
    }

    // Saving/updating data to wp_postmeta table with the id of current post
    function save_meta_box_value($post_id) {
        if (filter_input(INPUT_POST, 'bcparent')) {
            update_post_meta($post_id, 'wpbcs', $this->prepare_json_data());
        }
    }

    //creatign a json string
    function prepare_json_data() {
        $randomobj->status = filter_input(INPUT_POST, 'endisable');
        $randomobj->parent_id = filter_input(INPUT_POST, 'bcparent');
        $randomobj->parent_title = get_the_title($randomobj->parent_id);
        $randomobj->parent_permalink = get_the_permalink($randomobj->parent_id, true);
        $parent_data = json_encode($randomobj);
        return $parent_data;
    }
    

}
