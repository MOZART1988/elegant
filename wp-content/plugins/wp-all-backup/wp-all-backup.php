<?php
/*
Plugin Name: WP All Backup
Plugin URI: http://www.wpseeds.com/wp-all-backup/
Description: This plugin helps backup and restore your entire site at will, complete with FTP & Dropbox integration
Author: Prashant Walke
Version: 1.1
Author URI: https://walkeprashant.wordpress.com/about-me/
Text Domain: wpallbkp
Domain Path: /lang
License: GPLv2 
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

 if ( ! class_exists( 'WPAllBackup' ) ) :
final class WPAllBackup {
	
	public $version = '1.0';
	public $WPALLBK_prefix="wpab";
	
	protected static $_instance = null;

	public $query = null;

		public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0' );
	}

	public function __construct() {			
		// Define constants
		$this->define_constants();
                register_activation_hook( __FILE__, array($this,'installation') );             
	        add_action( 'plugins_loaded', array($this,'load_textdomain') );	
	     	$this->installation();
		// Include required files		
		$this->includes();
	}
	
            
	private function define_constants() {
                        define( 'WPALLBK_PLUGIN_FILE', __FILE__ );
                        define( 'WPALLBK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );  
                        $wp_all_backup_backups_dir=get_option('wp_all_backup_backups_dir');
                        if(!empty($wp_all_backup_backups_dir)){
                            define( 'WPALLBK_BACKUPS_DIR',get_option('wp_all_backup_backups_dir'));    	
                        }else{
                            define( 'WPALLBK_BACKUPS_DIR','wp_all_backup');    	
                        }
                        define( 'WPALLBK_VERSION', $this->version );
                        define( 'WPALLBK_PREFIX', $this->WPALLBK_prefix );
                        define( 'WPALLBK_TYPE','Free');
                        define( 'NOTIFIER_XML_FILE', 'http://wpseeds.com/notifier/wp-all-backup.xml' );	
                     }
		
	 function includes() {
	            include_once( 'includes/admin/class-admin-assets.php' );
                    include_once( 'includes/admin/class-admin-settings.php' );
	}  

	function installation(){
                    include('includes/installation.php');
	}

	function load_textdomain(){
		load_plugin_textdomain( 'wpallbkp',plugin_dir_path( __FILE__ ).'/lang' , 'wp-all-backup/lang' );
	}
}
endif;
function WPAllBackupFunction() {
	return WPAllBackup::instance();
}
$GLOBALS['WPAllBackup'] = WPAllBackupFunction();?>