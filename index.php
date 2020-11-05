<?php /* 
Plugin Name: Woocommerce Tiles Price Calculator
Description: Allows products to be sold as tiles with customizable width and height.
Author: Njegos Vukadin
Author Email: vukadinsu@gmail.com
Version: 1.0.0
*/

if( !defined( 'ABSPATH' ) ) exit;

define( 'WTPC_FILE', __FILE__ );
define( 'WTPC_DIR', dirname( __FILE__ ) );
define( 'WTPC_VERSION', '1.0.0' );

include  WTPC_DIR.'/lib/class.WTPC_Helpers.php';
include  WTPC_DIR.'/lib/class.WTPC_Plugin.php';

new WTPC_Plugin();