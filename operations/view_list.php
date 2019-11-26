<?php require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php'); ?>
<h1>view list</h1>
<?php 
    class Table_list extends WP_List_Table{      
        
        public function prepare_items(){
            $orderBy = isset($_GET['orderby']) ? trim($_GET['orderby']) : "";
            $order = isset($_GET['order']) ? trim($_GET['order']) : "";
            $search_term = isset($_POST['s'])? trim($_GET['order']) : "";
            $datas = $this->wp_list_table_data($orderBy,$order,$search_term);
            //pagination 
            $per_page = 6;
            $current_page = $this->get_pagenum();   //arguments for pagination
            $total_items = count($datas);
            $this->set_pagination_args(array(
                 "total_items" => $total_items,
                 "per_page" => $per_page,
            ));
            //array_slice(data,start_index,numbrers of indexs)
            $this->items = array_slice($datas,(($current_page-1)*$per_page),$per_page);//$datas;

            $columns = $this->get_columns();

            $hidden = $this->get_hidden_columns();  //hide columns
            $sortable = $this->get_sortable_columns();  //sort columns

            $this->_column_headers = array($columns,$hidden,$sortable);
        }
        public function wp_list_table_data($orderBy,$order,$search_term){
              global $wpdb;
              $table_name = $wpdb->prefix.'crud';
              $sql = $this->get_sort_query($orderBy,$order,$table_name,$search_term);
              $result = $wpdb->get_results($sql);
              $data = array();
              foreach($result as $row){ 
                  $data[] =
                      array(
                            'cb'=> '<input type="checkbox" />',                     //***************** */
                            "id"=>$row->id,
                            "title"=>$row->title,
                            "description"=>$row->description,
                            "gender"=>$this->get_gender($row->gender),
                            "sports"=>$row->sport,
                            "image"=>"<img src='".wp_get_attachment_image_url($row->image_id)."' height='70px' width='70px'></img>",
                            'status'=>$this->getStatus($row->status)
                        );
              }
            return $data;
        }
        function get_sort_query($orderBy,$order,$table_name,$search_term = ""){  
              if($orderBy == "title" && $order == "desc"){
                $sql = "SELECT * FROM $table_name ORDER BY `title` desc";
              }else if($orderBy == "title" && $order == "asc"){
                  $sql = "SELECT * FROM $table_name ORDER BY `title` asc";
              }else if($orderBy == "id" && $order == "desc"){
                $sql = "SELECT * FROM $table_name ORDER BY `id` desc";
              }else if($orderBy == "id" && $order == "asc"){
                $sql = "SELECT * FROM $table_name ORDER BY `id` asc";
              }else{
                $sql = "SELECT * FROM $table_name";
              }
              return $sql;
        }
        function get_gender($delimeter){
            if($delimeter == 'M'){return "Male";}
            else if($delimeter == 'F'){
            return "Female";
            }
        }

        function get_hidden_columns(){
            return array("");     // hide columns
        }
        function get_sortable_columns(){
            return array(
                "title" => array("title",false),
                "id" => array("id",false)
            ); 
        }
        public function get_columns(){
            $columns = array(
                'cb'=> '<input type="checkbox" />',
                "id" =>"ID",
                "title" => "Title",
                "description" => "Description",
                "gender" => "Gender",
                "sports" => "Sports",
                "image" => "Image",
                "status" => "Status"
            );
            return $columns;
        }


        function getStatus($status)
        {
            if($status == 'H')
            {
                return "Hold";
            }else if($status == 'P'){
                return  "Progress";
            }else if($status == 'C'){
                return "Complate";
            }else if($status == 'R'){
                return  "Reject";
            }
        }
        public function column_default($items,$columns_name){
            switch($columns_name){
                case 'cb':
                case 'id':
                case 'title':
                case 'description':
                case 'gender':
                case 'sports':
                case 'image':
                case 'status':
                    return $items[$columns_name];
                default:
                    return "no value";
            }
        }

        //actions
      /*  public function column_title($item){    
            $action = array(
                "edit" => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>',$_GET['page'],'list-edit',$item['id']),
                "delete" => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>',$_GET['page'],'list-delete',$item['id'])
            );
            return sprintf('%1$s %2$s',$item['title'],$this->row_actions($action));
        }*/
        public function column_status($item){
            $action = array(
               // "change" => sprintf('<select><option value="?page=%s&action=%s&status=H&id=%s">Hold</option></select>',$_GET['page'],'update-status',$item['id'])
               //<option value="H">Hold</option><option value="P">Progress</option><option value="C">Complete</option><option value="R">Reject</option>
                "change" => sprintf('<select id="status_%s" onchange="changeRowStatus(%s)">%s</select>',$item['id'],$item['id'],$this->getSelectStatus($item['status']))
            );
            return sprintf('%1$s %2$s',$item['status'],$this->row_actions($action));
        }

        
        function getSelectStatus($status)
        {
           
            
            if($status == 'Hold')
            {
                return '<option value="H" selected>Hold</option>
                <option value="P">Progress</option>
                <option value="C">Complate</option>
                <option value="R">Reject</option>';
            }else if($status == 'Progress'){
              
                return  '<option value="H">Hold</option>
                <option value="P" selected>Progress</option>
                <option value="C">Complate</option>
                <option value="R">Reject</option>';
            }else if($status == 'Complate'){
            return '<option value="H">Hold</option>
                <option value="P">Progress</option>
                <option value="C" selected>Complate</option>
                <option value="R">Reject</option>';
            }else if($status == 'Reject'){
                return  '<option value="H">Hold</option>
                <option value="P">Progress</option>
                <option value="C">Complate</option>
                <option value="R" selected>Reject</option>';
            }
        }
    }

    function show_data_table_list(){
        $table_list = new Table_List();
        $table_list->prepare_items();
        echo "<form method='post' name='frm_search_list' action='".$_SERVER['PHP_SELF']."?page=view-list'>";
        $table_list->search_box("Search","search_data_id");
        echo "</form>";
        $table_list->display();
    }

    show_data_table_list();
?>