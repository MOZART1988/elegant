<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WPALLMenu {

       public function __construct() {
		add_action('init', array('WPALLMenu', 'init'));	
                add_action('admin_init',  array( $this,'wp_all_backup_admin_init'));
		add_filter('cron_schedules', array( $this,'wp_all_backup_cron_schedules'));
		add_action('wp_all_backup_event', array( $this,'wp_all_backup_event_process'));
                add_action('wp', array( $this,'wp_all_backup_scheduler_activation'));	
                
       }
       
    static function version () {
        return VERSION;
    }

    static function init() {        
      	        add_action( 'admin_menu', array('WPALLMenu', 'adminPage') );  
              
     }
   
    static function adminPage() {      
                add_menu_page('WP ALL Backup', 'WP ALL Backup', 'update_plugins', 'wpallbackup-listing', array('WPALLMenu','renderAdminPage'),WPALLBK_PLUGIN_URL.'/assets/images/wpallbk.png' );		
		add_submenu_page( 'wpallbackup-listing', 'Setting', 'Setting', 'manage_options', 'wpallbackup-settings', array('WPALLMenu','wpallbackupSettings'));
		add_submenu_page( 'wpallbackup-listing', 'Destination', 'Destination', 'manage_options', 'wpallbackup-destination', array('WPALLMenu','wpallbackupDestination'));
		add_submenu_page( 'wpallbackup-listing', 'Help', 'Help', 'manage_options', 'wpallbackup-help', array('WPALLMenu','wpallbackupHelp'));
		
    }  

   static function renderAdminPage() {      
        include('create-backup.php');     
    } 
   
    static function wpallbackupSettings() {  	  
        include('wpallbackup-settings.php');  
    } 
	
    static function wpallbackupDestination() {      
       include('wpallbackup-destination.php');  
    } 

    static function wpallbackupHelp() {      
        include('wpallbackup-help.php');     
    } 
    
function wp_all_backup_admin_init() {
    if(is_admin()){
         if(isset($_GET['action']) && current_user_can('manage_options')) {
             switch((string)$_GET['action']) {
               case 'create':				 
				$this->wp_all_backup_event_process();
				wp_redirect(get_bloginfo('url').'/wp-admin/admin.php?page=wpallbackup-listing&notification=create');     
				break;
                case 'removebackup':
				$index = (int)$_GET['index'];
				$options = get_option('wp_all_backup_backups');
				$newoptions = array();
				$count = 0;
				foreach($options as $option) {
					if($count != $index) {
						$newoptions[] = $option;
					}
					$count++;
				}
				
				@unlink($options[$index]['dir']);
                                update_option('wp_all_backup_backups', $newoptions);
				wp_redirect(get_bloginfo('url').'/wp-admin/admin.php?page=wpallbackup-listing&notification=delete');                        
				break;
                  case 'restorebackup':
                      $index = (int)$_GET['index'];
                      require_once( 'class-restore.php' );
                      $restore = new Wpbp_Restore();
                      $restore->start($index);
                      if(get_option('wp_all_backup_enable_log')==1){
                           $options = get_option('wp_all_backup_backups');
                           $path_info = wp_upload_dir();		
                           $logFileName=explode(".",$options[$index]['filename']);
		           $logfile = $path_info['basedir'].'/'.WPALLBK_BACKUPS_DIR.'/log/'.$logFileName[0].'.txt';
                           $message="\n\n Restore Backup at ". date("Y-m-d h:i:sa");
                           $this->write_log($logfile,$message);                            
                         }
                         wp_redirect(get_bloginfo('url').'/wp-admin/admin.php?page=wpallbackup-listing&notification=restore');                        
                      break;
             }
         }
    }
	register_setting('wp_all_backup_options', 'wp_all_backup_options', array( $this,'wp_all_backup_validate'));
	
}

function wp_all_backup_validate($input) {	
	return $input;
}

function wp_all_backup_event_process() {
    $details =$this->wp_all_backup_create_archive();
    $options = get_option('wp_all_backup_backups');
    $Destination="";
	$logMessageAttachment="";
    $logMessage="";
    if(!$options) {
		$options = array();
	}
        
        //FTP
        $filename = $details['filename'];
	include plugin_dir_path(__FILE__).'Destination/FTP/preflight.php';	
	$filename = $details['filename'];
	include plugin_dir_path(__FILE__).'Destination/FTP/sendaway.php';
	
	//Dropbox
	$dropb_autho=get_option('dropb_autho');
	if($dropb_autho=="yes")
	{
            include plugin_dir_path(__FILE__).'Destination/Dropbox/dropboxupload.php';	
            $Destination.=" Dropbox";
            $wp_upload_dir = wp_upload_dir();	
            $wp_upload_dir['basedir'] = str_replace('\\', '/', $wp_upload_dir['basedir']);	
            $localfile = trailingslashit($wp_upload_dir['basedir'].'/'.WPALLBK_BACKUPS_DIR.'/').$filename;
            $dropbox->UploadFile($localfile, $filename);
            $logMessage.="\n Upload Database Backup on Dropbox";
	 }
        
        //Email
	if(get_option('wp_all_backup_email_id'))
	{
            $to=get_option('wp_all_backup_email_id');
            $subject="Backup Created Successfully";
            $filename=$details['filename'];
            $filesze=$details['size'];
            $site_url= site_url(); 	 
            include('template/template_email_notification.php');

             $headers = array('Content-Type: text/html; charset=UTF-8');
             $wp_all_backup_email_id_file=get_option('wp_all_backup_email_attachment');          
             if($wp_all_backup_email_id_file=="yes" && get_option('wp_all_backup_enable_log')==1 && $details['size']<=209700000)
             {	  
                $attachments = $details['logfileDir'];          
                $logMessageAttachment=" with attached log file.";
             }
             else
                $attachments="";
             
            wp_mail( $to, $subject, $message, $headers, $attachments );
            $logMessage.="\n Send Backup Mail to:".$to;
            $logMessage.=$logMessageAttachment;
	}
        if(get_option('wp_all_backup_enable_log')==1){
                             $this->write_log($details['logfileDir'],$logMessage);                             
          }
          
   $Destination.=" Local";
   
   $options[] = array(
		'date' => mktime(),
		'filename' => $details['filename'],
		'url' => $details['url'],
		'dir' => $details['dir'],
                'logfile'=>$details['logfile'],
                'destination' =>$Destination,       
                'type' => $details['type'],   
		'size' => $details['size']
	);
   
   update_option('wp_all_backup_backups', $options);
}

function wp_all_backup_format_bytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

function wp_all_backup_create_archive() {
    $source_directory = $this->wp_all_backup_wp_config_path();
    $path_info = wp_upload_dir();
	$files_added=0;
	
	wp_mkdir_p($path_info['basedir'].'/'.WPALLBK_BACKUPS_DIR);
        wp_mkdir_p($path_info['basedir'].'/'.WPALLBK_BACKUPS_DIR.'/log');
	fclose(fopen($path_info['basedir'].'/'.WPALLBK_BACKUPS_DIR.'/index.php', 'w'));
        fclose(fopen($path_info['basedir'].'/'.WPALLBK_BACKUPS_DIR.'/log/index.php', 'w'));
        $FileName=Date("Y_m_d").'_'.Time("H:M:S").rand(9, 9999);
        $WPDBFileName=$FileName.'.zip';         
        $wp_all_backup_type=get_option('wp_all_backup_type');
        $logFile=$path_info['basedir'].'/'.WPALLBK_BACKUPS_DIR.'/log/'.$FileName.'.txt';
        $logFileURL=$path_info['baseurl'].'/'.WPALLBK_BACKUPS_DIR.'/log/'.$FileName.'.txt';
         $logMessage = "\n#--------------------------------------------------------\n"; 
         $logMessage = "\n NOTICE: Do NOT post to public sites or forums ";
         $logMessage .= "\n#--------------------------------------------------------\n";
         $logMessage .= "\n Backup File Name : ".$WPDBFileName;
         $logMessage .= "\n Backup File Path : ".$path_info['baseurl'].'/'.WPALLBK_BACKUPS_DIR.'/'.$WPDBFileName;
         $logMessage .= "\n Backup Type : ".$wp_all_backup_type;
         $logMessage .= "\n #--------------------------------------------------------\n";
             
			 //Start Number of backups to store on this server 
			$options = get_option('wp_all_backup_backups');
			$newoptions = array();
			$number_of_existing_backups=count($options);
			 error_log("number_of_existing_backups");
			 error_log($number_of_existing_backups);        
			$number_of_backups_from_user=get_option('wp_all_backup_max_backups');
                        error_log("number_of_backups_from_user");
                         error_log($number_of_backups_from_user);   
			 error_log("Delete old Backup:");
                         
        if(!empty($number_of_backups_from_user)){
        if(!($number_of_existing_backups < $number_of_backups_from_user))
		{
			  $diff=$number_of_existing_backups-$number_of_backups_from_user;
			for ($i = 0; $i <= $diff; $i++)
			{
				$index=$i;
				error_log($options[$index]['dir']);     
				@unlink($options[$index]['dir']);							
													
			}
				  for($i=($diff+1);$i <$number_of_existing_backups; $i++ )
				{
                    error_log($i);    
                    $index=$i;
                   
				$newoptions[] = $options[$index];
                         
			}               
                                
          	update_option('wp_all_backup_backups', $newoptions);
        }
		}
		//End Number of backups to store on this server 
       if ( class_exists( 'ZipArchive' ) )
		{
                        $logMessage .= "\n Zip method: ZipArchive \n";
			$zip = new ZipArchive;
			$zip->open($path_info['basedir'].'/'.WPALLBK_BACKUPS_DIR.'/'.$WPDBFileName, ZipArchive::CREATE);

                         if(get_option('wp_all_backup_type')=='Database' || get_option('wp_all_backup_type')=='complete'){
					
					$filename=$FileName.'.sql';
					$handle = fopen($this->wp_all_backup_wp_config_path().'/'.$filename,'w+');
					fwrite($handle, $this->wp_all_backup_create_mysql_backup($logFile));
					fclose($handle);
					$zip->addFile($this->wp_all_backup_wp_config_path().'/'.$filename,$filename);
				
			}
                    if(get_option('wp_all_backup_type')=='File' || get_option('wp_all_backup_type')=='complete'){
						$wp_all_backup_exclude_dir=get_option('wp_all_backup_exclude_dir');
						if(empty($wp_all_backup_exclude_dir))
						{
							 $excludes = WPALLBK_BACKUPS_DIR;
						}else{
							 $excludes = WPALLBK_BACKUPS_DIR.'|'.$wp_all_backup_exclude_dir;
						}	
                         $logMessage.="\n Exclude Rule : $excludes";
                        foreach ( $this->get_files() as $file ) {
				// Skip dot files, 
				if ( method_exists( $file, 'isDot' ) && $file->isDot() )
					continue;

				// Skip unreadable files
				if ( ! @realpath( $file->getPathname() ) || ! $file->isReadable() )
					continue;
                                  
			    // Excludes
			    if ( $excludes && preg_match( '(' . $excludes . ')', str_ireplace( trailingslashit( $this->get_root() ), '', self::conform_dir( $file->getPathname() ) ) ) )
			        continue;

			    if ( $file->isDir() )
					$zip->addEmptyDir( trailingslashit( str_ireplace( trailingslashit( $this->get_root() ), '', self::conform_dir( $file->getPathname() ) ) ) );

			    elseif ( $file->isFile() ){
					$zip->addFile( $file->getPathname(), str_ireplace( trailingslashit( $this->get_root() ), '', self::conform_dir( $file->getPathname() ) ) );
                             $logMessage .= "\n Added File: ".$file->getPathname();           
                            }

				if ( ++$files_added % 500 === 0 )
					if ( ! $zip->close() || ! $zip->open( $path_info['basedir'].'/'.WPALLBK_BACKUPS_DIR.'/'.$WPDBFileName, ZIPARCHIVE::CREATE ) )
						return;

			}
			}          
			$zip->close();         
				
                        @unlink($this->wp_all_backup_wp_config_path().'/'.$filename);
			@$filesize=filesize($path_info['basedir'].'/'.WPALLBK_BACKUPS_DIR.'/'.$WPDBFileName);
                        
                            $upload_path = array(
                             'filename' => ($WPDBFileName),
                             'dir' => ($path_info['basedir'].'/'.WPALLBK_BACKUPS_DIR.'/'.$WPDBFileName),
                             'url' => ($path_info['baseurl'].'/'.WPALLBK_BACKUPS_DIR.'/'.$WPDBFileName),
                             'size'=> ($filesize),
                             'type' => $wp_all_backup_type 
                     );
							
                            
                         if(get_option('wp_all_backup_enable_log')==1){
                                $this->write_log($logFile,$logMessage);
                                $upload_path['logfile']=$logFileURL;
                                $upload_path['logfileDir']=$logFile;
                         }else{
							  $upload_path['logfile']="";
						 }
                        return $upload_path;        
		}
		else
		{
    
         
			error_log("Class ZipArchive Not Present");
                        $logMessage .= "\n Zip method: pclzip \n";		
                      //  require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
		}
}

private function wp_all_backup_create_mysql_backup($logFile){
	global $wpdb;
	/*BEGIN : Prevent saving backup plugin settings in the database dump*/
	$options_backup = get_option('wp_all_backup_backups');	
	$settings_backup = get_option('wp_all_backup_options');
	delete_option('wp_all_backup_options');
	delete_option('wp_all_backup_backups');
	$logMessage = "\n#--------------------------------------------------------\n";
        $logMessage .= "\n Database Table Backup";
	$logMessage .= "\n#--------------------------------------------------------\n";	
	/*END : Prevent saving backup plugin settings in the database dump*/	
	$tables = $wpdb->get_col('SHOW TABLES');
	$output = '';
	foreach($tables as $table) {
		 $logMessage .= "\n $table";
		$result = $wpdb->get_results("SELECT * FROM {$table}", ARRAY_N);
		$row2 = $wpdb->get_row('SHOW CREATE TABLE '.$table, ARRAY_N); 
		$output .= "\n\n".$row2[1].";\n\n";
		$logMessage .= "(".count($result).")";
		for($i = 0; $i < count($result); $i++) {
			$row = $result[$i];
			$output .= 'INSERT INTO '.$table.' VALUES(';
			for($j=0; $j<count($result[0]); $j++) {
				$row[$j] = mysql_real_escape_string($row[$j]);
				$output .= (isset($row[$j])) ? '"'.$row[$j].'"'	: '""'; 
				if ($j < (count($result[0])-1)) {
					$output .= ',';
				}
			}
			$output .= ");\n";
		}
		$output .= "\n";
	}
	$wpdb->flush();
	$logMessage .= "\n#--------------------------------------------------------\n";
	/*BEGIN : Prevent saving backup plugin settings in the database dump*/
	add_option('wp_all_backup_backups', $options_backup);
	add_option('wp_all_backup_options', $settings_backup);
	/*END : Prevent saving backup plugin settings in the database dump*/
	 if(get_option('wp_all_backup_enable_log')==1){
                             $this->write_log($logFile,$logMessage);
                             $upload_path['logfile']=$logFile;
                         }else{
							  $upload_path['logfile']="";
						 }
	return $output;	
}

private function write_log( $logFile,$logMessage ) { 
	    // Actually write the log file
	    if ( is_writable( $logFile ) || ! file_exists( $logFile ) ) {

	    	if ( ! $handle = @fopen( $logFile, 'a' ) )
	    		return;

	    	if ( ! fwrite( $handle, $logMessage ) )
	    		return;

	    	fclose( $handle );

	    	return true;

	    }

	}
        
public function get_files() {

		if ( ! empty( $this->files ) )
			return $this->files;

		$this->files = array();

		// We only want to use the RecursiveDirectoryIterator if the FOLLOW_SYMLINKS flag is available
		if ( defined( 'RecursiveDirectoryIterator::FOLLOW_SYMLINKS' ) ) {

			$this->files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $this->get_root(), RecursiveDirectoryIterator::FOLLOW_SYMLINKS ), RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD );

			// Skip dot files if the SKIP_Dots flag is available
			if ( defined( 'RecursiveDirectoryIterator::SKIP_DOTS' ) )
				$this->files->setFlags( RecursiveDirectoryIterator::SKIP_DOTS + RecursiveDirectoryIterator::FOLLOW_SYMLINKS );


		// If RecursiveDirectoryIterator::FOLLOW_SYMLINKS isn't available then fallback to a less memory efficient method
		} else {

			$this->files = $this->get_files_fallback( $this->get_root() );

		}

		return $this->files;

	}
        
  public function get_root() {

		if ( empty( $this->root ) )
			$this->set_root( self::conform_dir( self::get_home_path() ) );

        return $this->root;

    }

 public function set_root( $path ) {

    	if ( empty( $path ) || ! is_string( $path ) || ! is_dir ( $path ) )
    		throw new Exception( 'Invalid root path <code>' . $path . '</code> must be a valid directory path' );

    	$this->root = self::conform_dir( $path );

    }    
 
public static function get_home_path() {

		$home_url = home_url();
		$site_url = site_url();

		$home_path = ABSPATH;

		// If site_url contains home_url and they differ then assume WordPress is installed in a sub directory
		if ( $home_url !== $site_url && strpos( $site_url, $home_url ) === 0 )
			$home_path = trailingslashit( substr( self::conform_dir( ABSPATH ), 0, strrpos( self::conform_dir( ABSPATH ), str_replace( $home_url, '', $site_url ) ) ) );

		return self::conform_dir( $home_path );

	}
   
 public static function conform_dir( $dir, $recursive = false ) {

		// Assume empty dir is root
		if ( ! $dir )
			$dir = '/';

		// Replace single forward slash (looks like double slash because we have to escape it)
		$dir = str_replace( '\\', '/', $dir );
		$dir = str_replace( '//', '/', $dir );

		// Remove the trailing slash
		if ( $dir !== '/' )
			$dir = untrailingslashit( $dir );

		// Carry on until completely normalized
		if ( ! $recursive && self::conform_dir( $dir, true ) != $dir )
			return self::conform_dir( $dir );

		return (string) $dir;

	}
        
public function wp_all_backup_cron_schedules($schedules) {
	$schedules['hourly'] = array(
		'interval' => 3600,
		'display' => 'hourly'
	);
        $schedules['twicedaily'] = array(
		'interval' => 43200,
		'display' => 'twicedaily'
	);
        $schedules['daily'] = array(
		'interval' => 86400,
		'display' => 'daily'
	);
        $schedules['weekly'] = array(
		'interval' => 604800,
		'display' => 'weekly'
	);
	$schedules['monthly'] = array(
		'interval' => 2635200,
		'display' => 'monthly'
	);
	return $schedules;
}

public function wp_all_backup_scheduler_activation() {
	$options= get_option('wp_all_backup_options');
	if ((!wp_next_scheduled('wp_all_backup_event')) && (isset($options['enable_autobackups']))) {
		wp_schedule_event(time(), $options['autobackup_frequency'], 'wp_all_backup_event');
	}
}

function wp_all_backup_wp_config_path() {
     $base = dirname(__FILE__);
    $path = false;
    if (@file_exists(dirname(dirname(dirname(dirname(dirname($base)))))."/wp-config.php")) {
       $path = dirname(dirname(dirname(dirname(dirname($base)))));
    } else {
		if (@file_exists(dirname(dirname(dirname($base)))."/wp-config.php")) {
			$path = dirname(dirname(dirname($base)));
		} else {
			$path = false;
		}
	}
    if ($path != false) {
        $path = str_replace("\\", "/", $path);
    }
    return $path;
}
	 
} // end WPALLMenu

$WPALLMenu= new WPALLMenu();?>