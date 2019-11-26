<?php
    /*
    Plugin Name: CRUD Operation
    Plugin URI: logixbuilt.com
    Description: Crud operation plugin to perform insert, update, delete and view perform.
    Version:1.0
    Author:Sagar parmar
    Author URI:sagarparmar.com
    */

    global $wpdb;
    define('CRUD_PLUGIN_URI',plugin_dir_url(__FILE__));
    define('CRUD_PLUGIN_PATH',plugin_dir_path(__FILE__));

    register_activation_hook(__FILE__,'activate_crud_plugin_function');
    register_deactivation_hook(__FILE__,'deactivate_crud_plugin_function');

    function activate_crud_plugin_function()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix."crud";

        $sql = "CREATE TABLE $table_name (
            `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
            `title` varchar(225),
            `description` text,
            `gender` varchar(1),
            `sport` varchar(225),
            `image_id` int(11),
            `status` varchar(224) NOT NULL,
            `position` int(10) not null,
            PRIMARY KEY(id)
        )$charset_collate;";

        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    function deactivate_crud_plugin_function(){
        global $wpdb;
        $table_name = $wpdb->prefix."crud";
        $sql="DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
    }

    add_action('admin_menu','my_menu_pages');
    function my_menu_pages(){
        add_menu_page('CRUD','CRUD','manage_options','new-entry','my_menu_output');
        add_submenu_page('new-entry','CRUD Application','New Entry','manage_options','new-entry','my_menu_output');
        add_submenu_page('new-entry','CRUD Application','View Entries','manage_options','view-entries','my_submenu_output');
        add_submenu_page('new-entry','CRUD Application','View List','manage_options','view-list','my_submenu_list');
        wp_enqueue_style( 'responsive.bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );
        //wp_enqueue_style( 'bootstrap_datatable', 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css');
        //wp_enqueue_script( 'JQueryDataTable', 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js' );
        wp_enqueue_script( 'JQuery_code', 'https://code.jquery.com/jquery-3.3.1.js' );
        wp_enqueue_media();
        wp_enqueue_style( 'style', plugins_url('css/style.css',__FILE__) );
        wp_enqueue_script( 'myprefix_script', plugins_url( '/js/custom.js' , __FILE__ ), array('jquery'), '0.1' );
        wp_enqueue_script( 'myfooter_script', 'https://code.jquery.com/ui/1.12.0/jquery-ui.min.js', array('jquery'), '0.1', true );
        wp_enqueue_script( 'myfooter_bootstrap_script', 'https://getbootstrap.com/dist/js/bootstrap.min.js', array('jquery'), '0.1', true );
        wp_enqueue_script( 'myfooter_js_script', plugins_url( '/js/footer.js' , __FILE__ ) , array('jquery'), '0.1', true );
    }
    function my_submenu_list(){
        ob_start();
        include_once plugin_dir_path(__FILE__).'/operations/view_list.php';
        $template = ob_get_contents();
        ob_end_clean();
        echo $template;
    }

    function my_menu_output(){
        require_once(CRUD_PLUGIN_PATH.'/operations/new_entry.php');
    }
    function my_submenu_output(){
        require_once(CRUD_PLUGIN_PATH.'/operations/View_entry.php');
    }
    add_action('wp_ajax_myprefix_get_image', 'myprefix_get_image');
    function myprefix_get_image() {
        if(isset($_GET['id']) ){
            $image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'medium', false, array( 'id' => 'myprefix-preview-image' ) );
            $data = array(
                'image'    => $image,
            );
            wp_send_json_success( $data );
        } else {
            wp_send_json_error();
        }
    }


add_action('wp_ajax_myprefix_change_status','myprefix_change_status');
function myprefix_change_status(){
    global $wpdb;
    if(isset($_GET['id']) && isset($_GET['status'])){
        $s = "UPDATE ".$wpdb->prefix."crud SET status = '".$_GET['status']."' where id = '".$_GET['id']."'";
        if($wpdb->query($s)){
            $response = "Status successfully changed";
        }
        wp_send_json_success($response);
    }
    else{
        wp_send_json_error();
    }
}

add_action('wp_ajax_myprefix_update_position','myprefix_update_position');
function myprefix_update_position(){
    global $wpdb;
     $table_name = $wpdb->prefix."crud";
    if(isset($_GET['update'])){
        foreach($_GET['positions'] as $position){
            $index = $position[0];
            $newPosition =$position[1];
            $sql = "UPDATE $table_name SET position = '$newPosition' where id='$index'";
            $wpdb->query($sql);        
        }
        wp_send_json_success($sql);
    }else{
    wp_send_json_error();   
  }
}

add_action('wp_ajax_myprefix_save_information', 'myprefix_save_information');
function myprefix_save_information()
{
    if(isset($_GET['title'])&&isset($_GET['discription'])&&isset($_GET['gender'])&&isset($_GET['sport'])&&isset($_GET['image_id'])&&isset($_GET['status'])&&isset($_GET['position']))
    {
        global $wpdb;
        $table_name = $wpdb->prefix."crud";
        $sql = "INSERT INTO $table_name (`title`,`description`,`gender`,`sport`,`image_id`,`status`,`position`) VALUES (
            '".$_GET['title']."','".$_GET['discription']."','".$_GET['gender']."','".$_GET['sport']."','".$_GET['image_id']."','".$_GET['status']."','".$_GET['position']."'
        );";
        if($wpdb->query($sql)){        
            wp_send_json_success($sql);
        }
        wp_send_json_error();

    }
    else
    {
        wp_send_json_error();
    }
}

add_action('wp_ajax_myprefix_view_information', 'myprefix_view_information');
function myprefix_view_information()
{
    global $wpdb;
    $table_name = $wpdb->prefix."crud";
    $sql = "SELECT * FROM $table_name order by `position` ;";
    $row_count = "SELECT count(*) FROM $table_name ;";
    $num_row = $wpdb->get_var($row_count);
        $result = $wpdb->get_results($sql);
        foreach($result as $row){
            $table_list = $table_list."<tr id='$row->id' data-index='$row->id' data-position='$row->position' >
            <td><input type='checkbox' value='$row->id' id='record_$row->id'></td>
            <td>$row->id</td>
            <td>$row->title</td>
            <td>$row->description</td>
            <td>".get_gender($row->gender)."</td>
            <td>$row->sport</td>
            <td><img src='".wp_get_attachment_url($row->image_id)."' style='height:50px; width:70px;'></td>
            <td><select class='form-control form-control-sm' id='status_$row->id' onchange='changeStatus($row->id)'>
                    ".getStatus($row->status)."
                </select>
            </td>
            </tr>";
        }
        $data = array(
            "num_records" => $num_row,
            "list" => $table_list
        );

        
        wp_send_json_success($data);
    /*}else{
        wp_send_json_error();
    }*/
}



function getStatus($status)
{
    if($status == 'H')
    {
       return "<option value='H' selected>Hold</option>
        <option value='P'>Progress</option>
        <option value='C'>Complate</option>
        <option value='R'>Reject</option>";
    }else if($status == 'P'){
        return  "<option value='H'>Hold</option>
        <option value='P' selected>Progress</option>
        <option value='C'>Complate</option>
        <option value='R'>Reject</option>";
    }else if($status == 'C'){
       return "<option value='H'>Hold</option>
        <option value='P'>Progress</option>
        <option value='C' selected>Complate</option>
        <option value='R'>Reject</option>";
    }else if($status == 'R'){
        return  "<option value='H'>Hold</option>
        <option value='P'>Progress</option>
        <option value='C'>Complate</option>
        <option value='R' selected>Reject</option>";
    }
}

function get_gender($delimeter)
{
    if($delimeter == 'M'){return "Male";}
    else if($delimeter == 'F'){
        return "Female";
    }
}

//short code section
function display_crud_list($attr){
    $a = shortcode_atts(
        array(
            'name' => 'world'
        ),$attr
    );
    require_once(CRUD_PLUGIN_PATH.'/operations/frontview.php');
    return 'Hello '.$a['name'].'!';
    /*require_once(CRUD_PLUGIN_PATH.'/operations/frontview.php');*/
}
add_shortcode('crud_list','display_crud_list');


//require_once(CRUD_PLUGIN_PATH.'/operations/frontview.php');








/*
Note And References :
include( plugin_dir_url( __FILE__ ) . '/operations/frontview.php' );
https://github.com/iamersudip/Wordpress_CRUD_Plugin/blob/master/crud.php
https://github.com/iamersudip/Wordpress_CRUD_Plugin
https://torquemag.io/2017/06/custom-shortcode/

*/
?>