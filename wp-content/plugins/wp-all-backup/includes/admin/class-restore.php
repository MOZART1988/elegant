<?php

/**
 * Restore Database and file class
 *
 * @version 1.0
 */
class Wpbp_Restore {
	
	/**
	 * The path where the backup file is stored
	 *
	 * @string
	 * @access private
	 */
	private $path = '';

	/**
	 * The backup type, must be either complete, file or database
	 *
	 * @string
	 * @access private
	 */
	private $type = '';

	function __construct() {

	}

	public function start( $id ) {
               
                $options = get_option('wp_all_backup_backups'); 
		$this->type = $options[$id]['type'];
		$this->path = $options[$id]['dir'];
                 error_log("Restore Backup");
                 error_log($this->type);   
                 error_log($this->path);   
		$this->restore();

	}

	function restore() {

		if ( ! class_exists( 'PclZip' ) )
			require ABSPATH . 'wp-admin/includes/class-pclzip.php';

		if ( $this->type == 'Complete' || $this->type == 'complete' )
			$this->restore_complete();

		if ( $this->type == 'Database' || $this->type == 'database' )
			$this->restore_database();

		if ( $this->type == 'File' || $this->type == 'file' )
			$this->restore_files();

	}

	function restore_complete() {
                error_log("Inside restore_complete");
 
		$filename = basename( $this->path, '.zip' ) . '.sql';

		$file_path = ABSPATH . $filename;

		$this->restore_files();

		$this->restore_database( $file_path );

	}

	function restore_database( $file = null ) {
               error_log("Inside restore_database");

		global $wpdb;

		if ( ! $file ) {

			$archive = new PclZip( $this->path );

			$filename = basename( $this->path, '.zip' ) . '.sql';
                        $path_info = wp_upload_dir(); 
			$dir = $path_info['basedir'].'/'.WPALLBK_BACKUPS_DIR.'/';	
			$file_path = $dir .$filename;

			if ( ! $archive->extract( PCLZIP_OPT_PATH, $dir ) )
				wp_die( 'Unable to extract zip file. Please check that zlib php extension is enabled. <button onclick="history.go(-1);">Go Back</button>', 'ZIP Error' );

		} else {

			$file_path = $file;

		}

	

		$database_file =  $file_path ;
                $database_name=$this->wp_backup_get_config_db_name();
				$database_user=$this->wp_backup_get_config_data('DB_USER');				
				$datadase_password=$this->wp_backup_get_config_data('DB_PASSWORD');
				$database_host=$this->wp_backup_get_config_data('DB_HOST');
                                
                                ini_set("max_execution_time", "5000"); 
				ini_set("max_input_time",     "5000");
				ini_set('memory_limit', '1000M');
				set_time_limit(0);
				
                if((trim((string)$database_name) != '') && (trim((string)$database_user) != '') && (trim((string)$datadase_password) != '') && (trim((string)$database_host) != '') && ($conn = @mysql_connect((string)$database_host, (string)$database_user, (string)$datadase_password))) {
		/*BEGIN: Select the Database*/
		if(!mysql_select_db((string)$database_name, $conn)) {
			$sql = "CREATE DATABASE IF NOT EXISTS `".(string)$database_name."`";
			mysql_query($sql, $conn);
			mysql_select_db((string)$database_name, $conn);
		}
		/*END: Select the Database*/
		
		/*BEGIN: Remove All Tables from the Database*/
		$found_tables = null;
		if($result = mysql_query("SHOW TABLES FROM `{".(string)$database_name."}`", $conn)){
			while($row = mysql_fetch_row($result)){
				$found_tables[] = $row[0];
			}
			if (count($found_tables) > 0) {
				foreach($found_tables as $table_name){
					mysql_query("DROP TABLE `{".(string)$database_name."}`.{$table_name}", $conn);
				}
			}
		}
		/*END: Remove All Tables from the Database*/
		
		/*BEGIN: Restore Database Content*/
		if(isset($database_file))
		{
		
		$database_file=$database_file;
		$sql_file = @file_get_contents($database_file, true);
		
		$sql_queries = explode(";\n", $sql_file);
		
		
		for($i = 0; $i < count($sql_queries); $i++) {
			mysql_query($sql_queries[$i], $conn);
		}
		
		
		}
		}
                @unlink( $file_path );
		
	}

	function restore_files( $file = null ) {
                error_log("Inside restore_files");
		if ( ! $file)
			$archive = new PclZip( $this->path );
		else
			$archive = new PclZip( $file );

		if ( ! $archive->extract( PCLZIP_OPT_PATH, ABSPATH ) )
			wp_die( 'Unable to extract zip file. Please check that zlib php extension is enabled. <button onclick="history.go(-1);">Go Back</button>', 'ZIP Error' );
		
	}
        
        function wp_backup_get_config_db_name() {
	   $filepath=get_home_path().'/wp-config.php';
       	$config_file = @file_get_contents("$filepath", true);
	preg_match("/'DB_NAME',\s*'(.*)?'/", $config_file, $matches);
	return $matches[1];
}

function wp_backup_get_config_data($key) {
        $filepath=get_home_path().'/wp-config.php';
        $config_file = @file_get_contents("$filepath", true);
	switch($key) {
		case 'DB_NAME':
			preg_match("/'DB_NAME',\s*'(.*)?'/", $config_file, $matches);
			break;
		case 'DB_USER':
			preg_match("/'DB_USER',\s*'(.*)?'/", $config_file, $matches);
			break;
		case 'DB_PASSWORD':
			preg_match("/'DB_PASSWORD',\s*'(.*)?'/", $config_file, $matches);
			break;
		case 'DB_HOST':
			preg_match("/'DB_HOST',\s*'(.*)?'/", $config_file, $matches);
			break;
	}
	return $matches[1];
}

}