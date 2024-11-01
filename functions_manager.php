<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function wpc_extractParseFromDB($val){
    return sanitize_text_field($val);
}

function wpc_check_post_parameter($val){
    return sanitize_text_field($val);
}


function wpc_listALLColorScheme(){
    $arr_scheme = array();
    
    for($i=0;$i<4;$i++){
        $arr_scheme[$i]['layout_id'] = ($i + 1);
        $arr_scheme[$i]['title'] = "Layout ".($i + 1);
        $arr_scheme[$i]['url'] = WPC_URL."images/layout".($i + 1).".jpg";
    }
    
    return $arr_scheme;
}

function wpc_checkGlobalDateWithTime($date){
    return @date(WPC_DEFAULT_DATE_FORMAT,$date);
}


function wpc_getMultipleProductName($product_id){
    global $wpdb;
    
    $arr_product_id = @explode(",",$product_id);
    
    $product_name = "";
    $num_record = 0;
    foreach($arr_product_id as $single_product_id){
        
        $num_record++;
        
        if($num_record == count($arr_product_id)){
            
            $product_name .= get_the_title($single_product_id);
        }
        else{
            $product_name .= get_the_title($single_product_id).", ";
        }
    }
    
    return $product_name;
}

function wpc_getMultipleCategoryName($category_id){
    global $wpdb;
    
    $arr_category_id = @explode(",",$category_id);
    
    
    
    $cat_name = "";
    $num_record = 0;
    foreach($arr_category_id as $single_category_id){
        
        $num_record++;
        
        if($num_record == count($arr_category_id)){
            if($term = get_term_by( 'id', $single_category_id, 'product_cat' )){
                $cat_name .= $term->name;
            }
        }
        else{
            
            if($term = get_term_by( 'id', $single_category_id, 'product_cat' )){
                $cat_name .= $term->name.", ";
            }
        }
    }
    
    return $cat_name;
}

function wpc_newCollageData(){
    return true;
}

function wpc_pcollage_api_code(){
    global $wpdb;
    
    $collage_id = wpc_extractParseFromDB(intval(str_replace("pcollage_widget_container_","",@$_REQUEST['collage_id'])));
    
    $query = $wpdb->prepare( "SELECT * FROM ".WPC_USER_PRODUCT_COLLAGE." WHERE ID = '%d' LIMIT 0,1", $collage_id);
    
    $json_array['html'] = $query;
    
    $field_data          = 	$wpdb->get_row($query,ARRAY_A);

    $category_id = @$field_data['category_id'];
    $product_id = @$field_data['product_id'];

    $product_id_array = @explode(",",$field_data['product_id']);

    $prod_layout = @$field_data['prod_layout'];

    $limit_product = 4;
    if($prod_layout == 2){
        $limit_product = 1;
    }
    elseif($prod_layout == 3){
        $limit_product = 3;
    }
    elseif($prod_layout == 4){
        $limit_product = 3;
    }
    elseif($prod_layout == 5){
        $limit_product = 3;
    }

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $limit_product,
        'post__in' => $product_id_array,
        'orderby' => 'rand'
    );

    $loopProduct = new WP_Query( $args );

    $total_count = $loopProduct->post_count; 

    if($total_count == 1){
        $prod_layout = 2;
    }
    elseif($total_count == 2){
        $prod_layout = 1;
    }

    $arr_products = $loopProduct->posts;
    $color_scheme = $field_data['color_scheme'];


    $json_array = array();
    $html_layout = "";
    if(!empty($field_data)){
        $html_layout = "<div class='collage_layout_".$prod_layout."' >";
        $html_layout .= "<div class='collage_main_name'>";

        if(!empty($arr_products)){
            $count = 0;
            foreach ($arr_products as $products){

                $product_id = $products->ID;            
                $url_image = wp_get_attachment_url( get_post_thumbnail_id($product_id) );

                if(empty($url_image)){                
                    $url_image = wc_placeholder_img_src();
                }

                $read_more_link = get_permalink($product_id);            
                $count++;

                if($prod_layout != 4 && $prod_layout != 5 ){           
                    $html_layout .= "<div class='collage_product_cls part_collage_".$count."'><a href='".$read_more_link."'><img src='".$url_image."' width='200'></a><div class='prduct_name_collage'><a href='".$read_more_link."'>".esc_html($products->post_title)."</a></div></div>";  
                }
                else{
                    if($count == 1){
                        $html_layout .= "<div class='fourth_layout'>";
                    }

                    $html_layout .= "<div class='collage_product_cls part_collage_".$count."'><a href='".$read_more_link."'><img src='".$url_image."' width='200'></a><div class='prduct_name_collage'><a href='".$read_more_link."'>".esc_html($products->post_title)."</a></div></div>";  
                    if($count == 2){
                        $html_layout .= "</div>";
                    }
                }
            }
        }
        $html_layout .= "</div></div>";
    }

    $html_layout .= "<style>";

    //css for layout 1 starts
    $html_layout .= ".collage_layout_1{ float:left; max-width:500px; text-align:center}";
    $html_layout .= ".collage_layout_1 .collage_name{ width:100%; float:left; margin-top:10px; margin-bottom:10px; }";
    $html_layout .= ".collage_layout_1 .collage_product_cls{ width:49%; float:left; border:2px solid #".$color_scheme."; min-height:300px;  }";
    $html_layout .= ".collage_layout_1 img{ text-align:center; width:100%; margin-bottom:5px; max-height:200px; }";
    $html_layout .= ".collage_layout_1 .prduct_name_collage{ width:100%; float:left; text-align:center; margin-top:5px; margin-bottom:5px; color: #".$color_scheme."; } .collage_layout_1 .prduct_name_collage a{ color: #".$color_scheme."; text-decoration:none; }";
    $html_layout .= ".collage_layout_1 .part_collage_1{ border-right:0px !important;   }";
    $html_layout .= ".collage_layout_1 .part_collage_3{ margin-top:-2px }";
    $html_layout .= ".collage_layout_1 .part_collage_4{ border-left:0px !important; border-top:0px !important; min-height:300px; }";
    $html_layout .= ".collage_layout_1 .collage_name{ width:100%; float:left; margin-top:10px; margin-bottom:10px; }";
    //css for layout 1 ends

    //css for layout 2 starts
    $html_layout .= ".collage_layout_2{ float:left; max-width:500px; min-width:250px; text-align:center}";
    $html_layout .= ".collage_layout_2 .collage_name{ width:100%; float:left; margin-top:10px; margin-bottom:10px; }";
    $html_layout .= ".collage_layout_2 .collage_product_cls{ width:100%; float:left; border:2px solid #".$color_scheme."; min-height:300px;  }";
    $html_layout .= ".collage_layout_2 img{ text-align:center; width:100%; margin-bottom:5px; max-height:200px; }";
    $html_layout .= ".collage_layout_2 .prduct_name_collage{ width:100%; float:left; text-align:center; margin-top:5px; margin-bottom:5px; color: #".$color_scheme."; } .collage_layout_2 .prduct_name_collage a{ color: #".$color_scheme."; text-decoration:none; }";
    //css for layout 2 ends

    //css for layout 3 starts
    $html_layout .= ".collage_layout_3{ float:left; max-width:500px; min-width:250px; text-align:center}";
    $html_layout .= ".collage_layout_3 .collage_name{ width:100%; float:left; margin-top:10px; margin-bottom:10px; }";
    $html_layout .= ".collage_layout_3 .collage_product_cls{ width:49%; float:left; border:2px solid #".$color_scheme."; min-height:300px;  }";
    $html_layout .= ".collage_layout_3 img{ text-align:center; width:100%; margin-bottom:5px; max-height:200px; }";
    $html_layout .= ".collage_layout_3 .part_collage_1{ border-bottom:0px !important; border-right:0px !important;  }";
    $html_layout .= ".collage_layout_3 .part_collage_2{ border-bottom:0px !important;}";
    $html_layout .= ".collage_layout_3 .part_collage_3{ width:98.4%;  }";
    $html_layout .= ".collage_layout_3 .prduct_name_collage{ width:100%; float:left; text-align:center; margin-top:5px; margin-bottom:5px; color: #".$color_scheme."; } .collage_layout_3 .prduct_name_collage a{ color: #".$color_scheme."; text-decoration:none; }";
    //css for layout 3 ends

    //css for layout 4 starts
    $html_layout .= ".collage_layout_4{ float:left; max-width:500px; min-width:250px; text-align:center}";
    $html_layout .= ".collage_layout_4 .collage_name{ width:100%; float:left; margin-top:10px; margin-bottom:10px; }";
    $html_layout .= ".collage_layout_4 .collage_main_name{ width:100%; float:left; margin-top:10px; margin-bottom:10px; }";
    $html_layout .= ".collage_layout_4 .collage_product_cls{ height:100%; width:50%; float:left; border:2px solid #".$color_scheme."; min-height:300px;  }";
    $html_layout .= ".collage_layout_4 img{ text-align:center; width:100%; margin-bottom:5px; max-height:200px; }";
    $html_layout .= ".collage_layout_4 .part_collage_1{ border-bottom:0px !important; border-right:0px !important; width:100%  }";
    $html_layout .= ".collage_layout_4 .part_collage_2{  clear:both; width:100%; border-right:0px; }";
    $html_layout .= ".collage_layout_4 .part_collage_3{ height:100%; width:50%;  float:right; height:402px; padding-top:40%  }";
    $html_layout .= ".collage_layout_4 .fourth_layout { width:49%; float:left  }";
    $html_layout .= ".collage_layout_4 .prduct_name_collage{ width:100%; float:left; text-align:center; margin-top:5px; margin-bottom:5px; color: #".$color_scheme."; } .collage_layout_4 .prduct_name_collage a{ color: #".$color_scheme."; text-decoration:none; }";
    //css for layout 4 ends

    //css for layout 4 starts
    $html_layout .= ".collage_layout_5{ float:left; max-width:500px; min-width:250px; text-align:center}";
    $html_layout .= ".collage_layout_5 .collage_name{ width:100%; float:left; margin-top:10px; margin-bottom:10px; }";
    $html_layout .= ".collage_layout_5 .collage_main_name{ width:100%; float:left; margin-top:10px; margin-bottom:10px; }";
    $html_layout .= ".collage_layout_5 .collage_product_cls{ height:100%; width:50%; float:left; border:2px solid #".$color_scheme."; min-height:300px;  }";
    $html_layout .= ".collage_layout_5 img{ text-align:center; width:100%; margin-bottom:5px; max-height:200px; }";
    $html_layout .= ".collage_layout_5 .part_collage_1{ border-bottom:0px !important; border-right:0px !important; width:100%  }";
    $html_layout .= ".collage_layout_5 .part_collage_2{  clear:both; width:100%; border-right:0px; }";
    $html_layout .= ".collage_layout_5 .part_collage_3{ height:100%; width:50%;  float:right; height:402px; padding-top:40%  }";
    $html_layout .= ".collage_layout_5 .fourth_layout { width:49%; float:left  }";
    $html_layout .= ".collage_layout_5 .prduct_name_collage{ width:100%; float:left; text-align:center; margin-top:5px; margin-bottom:5px; color: #".$color_scheme."; } .collage_layout_5 .prduct_name_collage a{ color: #".$color_scheme."; text-decoration:none; }";
    //css for layout 4 ends

    $html_layout .= "@media only screen and (max-width:800px){";


    $html_layout .= ".collage_layout_1 img{ width:100% }";
    $html_layout .= ".collage_layout_3 img{ width:100%}";
    $html_layout .= ".collage_layout_4 img{ width:100%}";
    $html_layout .= ".collage_layout_5 img{ width:100%}";


    $html_layout .= ".collage_layout_4 .part_collage_3{ height:450px; width:49%;  padding-top:50%  }";
    $html_layout .= ".collage_layout_5 .part_collage_3{ height:450px; width:49%;  padding-top:50%  }";

    $html_layout .= ".collage_layout_1{ width:100%; max-width:100% }";
    $html_layout .= ".collage_layout_2{ width:100%; max-width:100% }";
    $html_layout .= ".collage_layout_3{ width:100%; max-width:100% }";
    $html_layout .= ".collage_layout_4{ width:100%; max-width:100% }";
    $html_layout .= ".collage_layout_5{ width:100%; max-width:100% }";

    $html_layout .= "}";
    $html_layout .= "</style>";

    $json_array['html'] = $html_layout;

    echo "callback('".json_encode($json_array,JSON_HEX_APOS)."')";
    exit;
}

function wpc_ajax_auth_init_for_collage(){    
    add_action( 'wp_ajax_newCollageData', 'wpc_newCollageData' );
    add_action( 'wp_ajax_nopriv_newCollageData', 'wpc_newCollageData' );    
    
    
    add_action( 'wp_ajax_wpc_pcollage_api_code', 'wpc_pcollage_api_code' );
    add_action( 'wp_ajax_wpc_pcollage_api_code', 'wpc_pcollage_api_code' );
}
add_action('init', 'wpc_ajax_auth_init_for_collage');
?>