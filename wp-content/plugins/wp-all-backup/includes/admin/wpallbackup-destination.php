 <?php

  if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(isset($_POST['Submit'])){
 if (!isset($_POST['wpallbackup_update_setting']))
die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");
if (!wp_verify_nonce($_POST['wpallbackup_update_setting'],'wpallbackup-update-setting'))
die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");

     if(isset($_POST['wp_all_backup_email_id']))
		 {
		   
		   update_option('wp_all_backup_email_id',sanitize_text_field($_POST['wp_all_backup_email_id']));
		  }
		   if(isset($_POST['wp_all_backup_email_id']))
		 {
		   $email_attachment=sanitize_text_field($_POST['wp_all_backup_email_attachment']);
		   update_option('wp_all_backup_email_attachment',$email_attachment);
		  }
}
                 //Remove Dropbox tocken
                 if(isset($_GET['page']) && $_GET['page']=="wpallbackup-destination" && isset($_GET['action']) && $_GET['action']=="unlink") 
		   {
		     // Specify the target directory and add forward slash
                         $dir = plugin_dir_path(__FILE__)."Destination/Dropbox/tokens/"; 
           
                         // Open the directory
                         $dirHandle = opendir($dir); 
                         // Loop over all of the files in the folder
                        while ($file = readdir($dirHandle)) { 
                         // If $file is NOT a directory remove it
                          if(!is_dir($file)) { 
                                 unlink ("$dir"."$file"); // unlink() deletes the files
                            }
                       }
                          // Close the directory
                           closedir($dirHandle); 
                           wp_redirect(get_bloginfo('url').'/wp-admin/admin.php?page=wpallbackup-destination');

		   }
 ?><div class="panel panel-success">
  <div class="panel-heading">
    <div class="panel-title"><h3><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> <?php _e( 'Destination', 'wpallbkp' ); ?></h3></div>
  </div>
  <div class="panel-body">
  

 <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseI">
          <h2><?php _e( 'FTP/sFTP', 'wpallbkp' ); ?> </h2>
       
        </a>
      </h4>
    </div>
    <div id="collapseI" class="panel-collapse collapse in">
      <div class="panel-body">
      <p><?php _e( 'FTP/sFTP Destination Define an FTP destination connection. You can define destination which use FTP.', 'wpallbkp' ); ?></p>
      <?php	 include plugin_dir_path(__FILE__).'Destination/FTP/ftp-form.php';?>
	</div>		
        </div>
    </div>
     <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseII">
          <h2><?php _e( 'Email Notification', 'wpallbkp' ); ?></h2>
       
        </a>
      </h4>
    </div>
    <div id="collapseII" class="panel-collapse collapse in">
      <div class="panel-body">
      
     <?php echo '<form name="wp-email_form" method="post" action="" >';
		 
			$wp_all_backup_email_id="";
		        $wp_all_backup_email_id=get_option('wp_all_backup_email_id');
		        $wp_all_backup_email_attachment="";
		        $wp_all_backup_email_attachment=get_option('wp_all_backup_email_attachment');
		        echo '<p>';
		        echo '<span class="glyphicon glyphicon-envelope"></span>';
				 _e( 'Send Email Notification', 'wpallbkp' );
				echo '</br></p>';
				 _e( 'Email Id : ', 'wpallbkp' );
			     echo '<p>';
				echo '<input type="text" name="wp_all_backup_email_id" value="'.$wp_all_backup_email_id.'" placeholder="';
				 _e( 'Your Email Id', 'wpallbkp' );echo '">';				 
				echo '<p>';
				_e( 'Leave blank if you don\'t want use this feature', 'wpallbkp' );
				echo '</p>';
				echo '<p>';
				_e( 'Attach log file : ', 'wpallbkp' );				
				_e( 'Your Email Id', 'wpallbkp' );
				$selected_option=get_option( 'wp_all_backup_email_attachment' );
					
					if($selected_option=="yes") $selected_yes="selected=\"selected\"";
					else
					$selected_yes="";
					if($selected_option=="no") $selected_no="selected=\"selected\"";
					else
					$selected_no="";
                                 	echo '<select id="lead-theme" name="wp_all_backup_email_attachment">';
								    echo '<option value="none">';
									_e( 'Select', 'wpallbkp' );
									echo '</option>';								
									echo '<option  value="yes"'.$selected_yes.'>';
									_e( 'Yes', 'wpallbkp' );
									echo '</option>';
									echo '<option  value="no" '.$selected_no.'>';
										_e( 'No', 'wpallbkp' );
									echo '</option>';
									
								
							echo '</select></p>';
							

					echo '<p>';
					_e( 'If you want attache log file to email then select "yes" (File attached only when log file size <=25MB)', 'wpallbkp' );
					echo '</p>';
					
			echo '</p>';
			echo '<input name="wpallbackup_update_setting" type="hidden" value="'.wp_create_nonce('wpallbackup-update-setting').'" />';
			echo '<p class="submit">';
				echo '<input type="submit" name="Submit" class="button-primary" value="';
				_e( 'Save Settings', 'wpallbkp' ); echo'" />';
			echo '</p>';
			echo '</form>';?>
	</div>		
        </div>
    </div>
	
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseIII">
          <h2><?php _e( 'Dropbox', 'wpallbkp' ); ?> </h2>
       
        </a>
      </h4>
    </div>
    <div id="collapseIII" class="panel-collapse collapse in">
      <div class="panel-body">
      
     <?php include plugin_dir_path(__FILE__).'Destination/Dropbox/dropboxupload.php';?>
	</div>		
        </div>
    </div>
		
		<?php
		echo '</div>';
		?>
		
  </div>
   <div class="panel-footer"><h4>Get Flat 25% off on <a target="_blank" href="http://www.wpseeds.com/product/wp-all-backup/">WP All Backup Plus Plugin.</a> Use Coupon code 'WPSEEDS25'</h4></div></div>
</div>