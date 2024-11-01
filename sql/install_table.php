<?php 
global $wpc_productcollage_db_version;
$wpc_productcollage_db_version = '2.0';

function wpc_product_install() {
	global $wpdb;
	global $wpc_productcollage_db_version;

	//$table_name = $wpdb->prefix . 'wpc_product_collage';
        $table_name = WPC_USER_PRODUCT_COLLAGE;
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`title` varchar(255) NOT NULL,
                `category_id` text NOT NULL,
                `product_id` text NOT NULL,
                `prod_layout` varchar(255) NOT NULL,
                `color_scheme` varchar(255) NOT NULL,                
                `date_added` varchar(255) NOT NULL,
		PRIMARY KEY  (`id`)
	) $charset_collate;";       
        
        

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'wpc_productcollage_db_version', $wpc_productcollage_db_version );
}


function wpc_update_db_check() {
    global $wpc_productcollage_db_version;
    
    if ( get_site_option( 'wpc_productcollage_db_version' ) != $wpc_productcollage_db_version) {
        wpc_product_install();
    }
    
    
}
add_action( 'plugins_loaded', 'wpc_update_db_check' );

?>