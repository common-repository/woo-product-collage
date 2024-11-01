<?php   
        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
        add_action("wpc_exist_bulk_action",wpc_exist_bulk_action);
        function wpc_exist_bulk_action(){
		
		if(empty(wpc_check_post_parameter($_REQUEST['action2'])))
			return;
			
		$action = isset( $_REQUEST['action2'] ) ? wpc_check_post_parameter($_REQUEST['action2']) : 'show';
		switch ($action) {			                          
                        case 'wpc_delete_collage_report':
				wpc_delete_collage_report();
				break;                        
		}
	}
        
        function wpc_delete_collage_report(){
		
		global $wpdb;
		$counter		= 0; 
		$tbl_name		=	WPC_USER_PRODUCT_COLLAGE;
                                
		for($i=0; $i<=count($_POST['numbers']); $i++)
		{
                    if(wpc_check_post_parameter($_POST['numbers'][$i])!="")
                    {
                        $id   			= wpc_check_post_parameter($_POST['numbers'][$i]);
                        $cond['id']		= $id;

                        $condMeta['booking_id']		= $id;

                        if(!empty($id))
                        {	
                            if($wpdb->delete($tbl_name,$cond)){                                    
                                $wpdb->delete($tbl_meta_name,$condMeta);                                    
                                $counter++;
                            }
                        }
                    }
		}
		if($counter){
			$msg 	= " $counter collage have been deleted successfully.";			
                        
                        $readmore_url = "admin.php?page=wpc_product_collage_list&msg=".$msg;                    
                        ?>
                        <script>document.location.href = '<?php echo $readmore_url; ?>';</script>
                        <?php                    
                        exit;
		}
	}
        
        
        function wpc_ourProductCollageCode(){
            global $wpdb;
            
            $tbl_collage = WPC_USER_PRODUCT_COLLAGE;
            
		
            do_action('wpc_exist_bulk_action');

            $data			 = array();
            $columns 	=	array(
                                            'id' =>  __('ID'),                                            
                                            'title'		  =>  __('Title'),
                                            'category_id' 	  	  =>  __('Category Selected'),  
                                            'product_id' =>  __('Products Selected'),
                                            'prod_layout' =>  __('Layout'),
                                            'color_scheme' =>  __('Color Scheme'),
                                            'date_added' =>  __('Order Date')
                                            );

            $columns 		=	apply_filters( 'new_user_columns', $columns );
            // count the number of users found in the query


            $search_name = wpc_check_post_parameter(addslashes($_REQUEST['search_name']));            

            $query_where	=	"where 1 ";
            if(!empty($search_name)){
                $query_where	.=	" and  (clg.title like '%s' || clg.color_scheme like '%s' ) ";
            }

            if(!empty($search_sponsorship)){
                //$query_where	.=	" and  (brk_list.sponsor_id = '".$search_sponsorship."' ) ";
            }

            $query_where 	=	apply_filters( 'new_user_query_where', $query_where );

            $query_order	=	"order by clg.id desc";
            $query_order 	=	apply_filters( 'new_user_order', $query_order );

            
            //$query_group_by = " group by clg.id";
            $query_group_by = "";
            
            if(!empty($search_name)){
                $search_name = '%'.$search_name.'%';
            }

            $query_total = "SELECT COUNT(*) FROM $tbl_collage as clg $query_where $query_group_by  $query_order";
            
            $query_total = $wpdb->prepare($query_total,$search_name,$search_name);   

            /* For Pagination */
            $total_record 	= $wpdb->get_var( $query_total );            
            $total_record 	= $total_record ? $total_record : 1;
            // grab the current page number and set to 1 if no page number is set
            $page = isset($_GET['paged']) ? $_GET['paged'] : 1;

            // how many users to show per page
            $record_per_page = 10;
            // calculate the total number of pages.
            $offset		 = $record_per_page * ($page - 1);
            $total_pages = ceil($total_record / $record_per_page);
            /* End Pagination */            

            $query			 =	"SELECT clg.* FROM $tbl_collage as clg $query_where $query_group_by  $query_order ";

            $query_limit	 =	" Limit $offset,$record_per_page";	
            $query_limit	 =	apply_filters( 'new_user_query_limit', $query_limit );
            $query			.=	$query_limit;
            
            //echo $query;
            
            
            
            $query = $wpdb->prepare($query,$search_name,$search_name);           
            

            $records		 = 	$wpdb->get_results($query,ARRAY_A);
            
            //print"<pre>";
            //print_r($records);
            //exit;
            
           
            
            foreach($records as $record):
                    $record_ids_array[]	=	$record['id'];
                    
                    $category_names = wpc_getMultipleCategoryName($record['category_id']);
                    $product_names = wpc_getMultipleProductName($record['product_id']);
                    $prod_layout = $record['prod_layout'];
                    
                    if(!empty($prod_layout)){
                        $prod_layout = "Layout ".$prod_layout;
                    }
                    $color_scheme = $record['color_scheme'];
                    
                    if(!empty($color_scheme)){
                        $color_scheme = $color_scheme."<div style='background-color:#".$color_scheme."; width:50px; height:10px;'></div>";
                    }
                    
                     
                    $date_added                         =       wpc_checkGlobalDateWithTime($record['date_added']);

                    

                    $record_array[] 		=   apply_filters( 'filter_new_user_data', array(
                                                                                    'id'		  =>	$record['id'],
                                                                                    'title'       => $record['title'],
                                                                                    'category_id' =>  $category_names,										         'product_id'=>  $product_names,
                                                                                    'prod_layout'=>  $prod_layout,
                                                                                    'total_amount'=>  $total_amount,
                                                                                    'color_scheme'=>  $color_scheme,
                                                                                    'date_added'=>  $date_added,
                                                                            ), 
                                                                            $record);
                    $counter++;		
            endforeach;
            
            
            //get all the sponsorship
            $args = array(
                            'post_type'=> 'fsga_sponsorships',
                            'post_status' => 'publish',
                            'order'    => 'ASC'
                          );

            $sponsorships_args = new WP_Query($args);  
            
            $msg = @wpc_check_post_parameter($_GET['msg']);
            
            require_once(WPC_DIR."/view/admin/our_product_collage.php");
        }
        
        function wpc_ourProductCollage(){
            
                global $wpdb;                
                $field_id	= (int)$_REQUEST['id'];
                
                
                
                if(@wpc_check_post_parameter($_REQUEST['hidden_collage_product']) == 'yes'){                    
                    
                    if($field_id > 0){

                        $category_id_str = @implode(",",@$_REQUEST['category_id']);
                        $product_id_str = @implode(",",@$_REQUEST['product_id']);
                        
                        
                        $results_payment = $wpdb->update( 
                                                WPC_USER_PRODUCT_COLLAGE, 
                                                array(
                                                        'title' => wpc_check_post_parameter($_REQUEST['title']),	// string
                                                        'category_id' => $category_id_str,
                                                        'product_id' => $product_id_str,
                                                        'prod_layout' => intval($_REQUEST['prod_layout']),
                                                        'color_scheme' => wpc_check_post_parameter($_REQUEST['color_scheme'])
                                                ), 
                                                array( 'ID' => $field_id ), 
                                                array( 
                                                        '%s',
                                                        '%s',
                                                        '%s',
                                                        '%d',
                                                        '%s'
                                                ), 
                                                array( '%d' ) 
                                        );
                    
                    }
                    else{                        

                        $date_added = time();                        
                        $category_id_str = @implode(",",@$_REQUEST['category_id']);
                        $product_id_str = @implode(",",@$_REQUEST['product_id']);
                        
                        
                        $results_payment = $wpdb->insert( 
                                                WPC_USER_PRODUCT_COLLAGE, 
                                                array(
                                                        'title' => wpc_check_post_parameter($_REQUEST['title']),	// string
                                                        'category_id' => $category_id_str,
                                                        'product_id' => $product_id_str,
                                                        'prod_layout' => intval($_REQUEST['prod_layout']),
                                                        'color_scheme' => wpc_check_post_parameter($_REQUEST['color_scheme']),
                                                        'date_added' => $date_added
                                                    
                                                ),                                                 
                                                array( 
                                                        '%s',
                                                        '%s',
                                                        '%s',
                                                        '%d',
                                                        '%s',
                                                        '%s'
                                                )
                                        );
                        
                    }
                    
                    if($field_id > 0){
                        $msg 	= 	"Your collage has been updated successfully.";
                    }
                    else{
                        $msg 	= 	"Your collage has been added successfully.";
                    }
                    
                    
                    
                    $readmore_url = "admin.php?page=wpc_product_collage_list&msg=".$msg;
                    //header("Location:".$readmore_url);
                    ?>
                    <script>document.location.href = '<?php echo $readmore_url; ?>';</script>
                    <?php                    
                    exit;
                }
                
		$category_id_db = "";
                $db_category_array = "";
                $db_product_array = "";
		if($field_id)
		{
                    
                    $query = $wpdb->prepare( "SELECT * FROM ".WPC_USER_PRODUCT_COLLAGE." WHERE id = '%d'", $field_id);
                    
                    $field_data		 = 	$wpdb->get_row($query,ARRAY_A);
                    
                    $category_id_db = @$field_data['category_id'];
                    $db_category_array = @explode(",",$category_id_db);
                    
                    
                    $product_id_db = @$field_data['product_id'];
                    $db_product_array = @explode(",",$product_id_db);
		}	
                
                //get all layouts
                $arr_layout_collage = wpc_listALLColorScheme();
                
                //get all the woocommerce categories
                
                $orderby = "title";
                $order = "ASC";
                $hide_empty = false;
                $args = array(                            
                            'orderby'    => $orderby,
                            'order'      => $order,
                            'hide_empty' => $hide_empty
                        );

                $product_categories = get_terms( 'product_cat', $args );
                
                
                
                        
                //$db_category_array = array(6,7);
                
                $orderby = "title";
                $order = "ASC";
                $query_args = array(
                                        'post_status' => 'publish', 
                                        'post_type' => 'product',
                                        'orderby'    => $orderby,
                                        'order'      => $order,
                                        'tax_query' => array(
                                            array(
                                                    'taxonomy' => 'product_cat',
                                                    'field' => 'term_id',
                                                    'terms' => $db_category_array,
                                                    'operator' => 'IN'
                                            )
                                        )
                                    );

                $loopArray = new WP_Query($query_args);
                $arr_posts = $loopArray->posts;

                $product_multi = "";
                $product_already_selected_multi = "";
                if(!empty($arr_posts)){
                    foreach($arr_posts as $post_info){
                        //echo $post_info->post_title;
                        
                        $selected = "";
                        if(@in_array($post_info->ID,$db_product_array)){
                            //$selected = "selected";
                            $product_already_selected_multi .= "<option ".$selected." value='".$post_info->ID."'>".$post_info->post_title."</option>";
                            continue;
                        }
                        
                        $product_multi .= "<option ".$selected." value='".$post_info->ID."'>".$post_info->post_title."</option>";
                    }                
                }
                //wp_reset_query();
		require_once(WPC_DIR."/view/admin/edit_product_collage.php");
        
        }
        

        
    add_action( 'wp_ajax_wpc_Fnproduct_accto_category_list', 'wpc_Fnproduct_accto_category_list' );
    add_action( 'wp_ajax_nopriv_wpc_Fnproduct_accto_category_list', 'wpc_Fnproduct_accto_category_list' );  
    
    function wpc_Fnproduct_accto_category_list(){
        global $wpdb;
        
        $str_selection = @wpc_check_post_parameter($_REQUEST['str_selection']);        
        $arr_selection = @explode(",",$str_selection);
        
        $str_selection_already = @wpc_check_post_parameter($_REQUEST['str_selection_already']);
        $arr_selection_already = @explode(",",$str_selection_already);
        
        $orderby = "title";
        $order = "ASC";
        $query_args = array(
                                'post_status' => 'publish', 
                                'post_type' => 'product',
                                'orderby'    => $orderby,
                                'order'      => $order,
                                'tax_query' => array(
                                    array(
                                            'taxonomy' => 'product_cat',
                                            'field' => 'term_id',
                                            'terms' => $arr_selection,
                                            'operator' => 'IN'
                                    )
                                )
                            );

        $loopAjaxArray = new WP_Query($query_args);
        
        
        $arr_posts = $loopAjaxArray->posts;

        $product_multi = "";
        
        $product_already_selected_multi = "";
        
        if(!empty($arr_posts)){
            foreach($arr_posts as $post_info){
                //echo $post_info->post_title;
                
                
                if(@in_array($post_info->ID,$arr_selection_already)){
                    $product_already_selected_multi .= "<option ".$selected." value='".$post_info->ID."'>".$post_info->post_title."</option>";
                    continue;
                }

                $selected = "";
                if(@in_array($post_info->ID,$db_product_array)){
                    $selected = "selected";
                }

                $product_multi .= "<option ".$selected." value='".$post_info->ID."'>".$post_info->post_title."</option>";
            }                
        }
        
        $json_array = array();
        
        $json_array['product_multi'] = $product_multi;
        $json_array['product_already_selected_multi'] = $product_already_selected_multi;
        
        echo json_encode($json_array);
        exit;
    }
        
?>