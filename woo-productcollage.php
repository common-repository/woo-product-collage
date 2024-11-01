<?php
/*
Plugin Name: Woo Product Collage
Plugin URI: http://www.thewpexperts.co.uk
Description: Product Collage is Wordpress Addon
Version: 2.0
Author: Thewpexperts
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('WPC_VERSION', '2.0');
define('WPC_URL', plugin_dir_url( __FILE__ ));
define('WPC_DIR', dirname( __FILE__ )); //an absolute path to this directory


define('WPC_USER_PRODUCT_COLLAGE', $wpdb->prefix."wpc_product_collage");

$wp_default_date_format = get_option( 'date_format' );
if(empty($wp_default_date_format)){
    $wp_default_date_format = "Y/m/d";
}

$wp_default_time_format = get_option( 'time_format' );
if(empty($wp_default_time_format)){
    $wp_default_time_format = "g:i A";
}

define('WPC_DEFAULT_DATE_FORMAT', $wp_default_date_format);
define('WPC_DEFAULT_DATE_FORMAT', $wp_default_time_format);

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    include_once("functions_manager.php");
    include_once("hooks_functions.php");
    include_once("admin_functions_manager.php");
    include_once("sql/install_table.php");

}