<?php 
        if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly	

	add_action( 'admin_head', 'wpc_admin_style');
	function wpc_admin_style(){
		wp_enqueue_style( 'wpc-jquery-ui', WPC_URL. '/css/jquery-ui.css' );		
		wp_enqueue_script( 'wpc', WPC_URL.'/js/wpc-admin.js', array('jquery'),'1.0.0',true );                
                
		wp_enqueue_script( 'validationEngine-en', WPC_URL.'/js/jquery.validationEngine-en.js');
		wp_enqueue_script( 'validationEngine', WPC_URL.'/js/jquery.validationEngine.js');
		wp_enqueue_style( 'validationEngine', WPC_URL.'/css/validationEngine.jquery.css');
		wp_enqueue_style( 'wpc',WPC_URL.'/css/wpc-admin.css' );
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'thickbox' );
                
                wp_enqueue_script( 'colorpicker-collage', WPC_URL.'/js/jscolor.js');
	}
	
	
	// Create menu
	add_action( 'admin_menu', 'wpcMenu' );
	function wpcMenu(){
            add_menu_page('Product Collage', 'Product Collage', 1, "wpc_product_collage_list", wpc_ourProductCollageCode);
            add_submenu_page(null, 'Update Product Collage', 'Update Product Collage', 1, "wpc_product_collage", wpc_ourProductCollage);
	}
        
        add_action('wpc_view_pagination','wpc_view_pagination',20,1);
	function wpc_view_pagination($total_pages=0){
		//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                
                if(@wpc_check_post_parameter($_REQUEST['paged']) > 0){
                    $paged = wpc_check_post_parameter($_REQUEST['paged']);
                }
                else{
                    $paged = 1;
                }
                
               // echo get_query_var('paged')."++++++++";
                
		$big = 999999999; // need an unlikely integer
                echo paginate_links( array(
                                        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                                        'format' => '?paged=%#%',
                                        'current' => max( 1, $paged ),
                                        'type'         => 'list',
                                        'total' => $total_pages
                                ) 
                        );
	}        
?>