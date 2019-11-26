<?php
     global $wpdb;
     $table_name = $wpdb->prefix."crud";
    if(isset($_POST['update'])){
        foreach($_POST['positions'] as $position){
            $index = $position[0];
            $newPosition =$position[1];
            $sql = "UPDATE $table_name SET posiion = '$newPosition' where id='$index'";
           if($wpdb->query($sql)){
               exit('success');
           };   
        }
    }
?>