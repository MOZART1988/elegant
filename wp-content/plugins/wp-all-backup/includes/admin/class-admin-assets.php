<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if ( ! class_exists( 'WPALLAdminAssets' ) ) :
    
class WPALLAdminAssets {

	public function __construct() {
		add_action( 'init', array( $this, 'admin_scripts' ) );		
	}

	 // Enqueue scripts
	public function admin_scripts() {		 
         if (isset($_GET['page'])) { 
			
            if ($_GET['page'] == "wpallbackup-listing" || $_GET['page'] == "wpallbackup-destination" || $_GET['page'] == "wpallbackup-help" || $_GET['page'] == "wpallbackup-settings") {  
				
		wp_enqueue_script('jquery');	
               
                wp_enqueue_style('wpallbkbootstrapcss',WPALLBK_PLUGIN_URL."assets/css/bootstrap.min.css" );
                wp_enqueue_style('wpallbkbootstrapcss');                    

                wp_enqueue_script('wpallbkbootstrapjs',WPALLBK_PLUGIN_URL."assets/js/bootstrap.min.js" );
                wp_enqueue_script('wpallbkbootstrapjs');
                
                wp_enqueue_script('wpallbkdataTables',WPALLBK_PLUGIN_URL."/assets/js/jquery.dataTables.js",array( 'jquery' ));
                wp_enqueue_script('wpallbkdataTables');
            
                wp_enqueue_style('wpallbkdataTablescss',WPALLBK_PLUGIN_URL."/assets/css/jquery.dataTables.css" );
                wp_enqueue_style('wpallbkdataTablescss');
               
                wp_enqueue_style('wpallbkadmincss',WPALLBK_PLUGIN_URL."/assets/css/wpdb_admin.css" );
                wp_enqueue_style('wpallbkadmincss');
            }
		  
	}



}
}

endif;

$obj= new WPALLAdminAssets();
?>