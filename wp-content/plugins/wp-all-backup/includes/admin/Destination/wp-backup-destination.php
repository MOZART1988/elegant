<h2><?php _e( 'Destination', 'wpallbkp' ); ?></h2>
		
<!-- Nav tabs -->
<ul class="nav nav-tabs" style="width: 640px;">
  <li><a href="#FTP" data-toggle="tab"><?php _e( 'FTP', 'wpallbkp' ); ?></a></li>
  <li><a href="#Dropbox" data-toggle="tab"><?php _e( 'Dropbox', 'wpallbkp' ); ?></a></li>
 
</ul>

<!-- Tab panes -->
<div class="tab-content">
  <div class="tab-pane active" id="FTP">
       <h2><?php _e( 'FTP/sFTP', 'wpallbkp' ); ?> </h2>
       <p><?php _e( 'FTP/sFTP Destination Define an FTP destination connection. You can define destination which use FTP.', 'wpallbkp' ); ?></p>
       <?php 
         // call FTP Details form
        include plugin_dir_path(__FILE__).'/FTP/ftp-form.php';
       ?>
  
  </div>
  
  <div class="tab-pane" id="Dropbox">
      <?php include plugin_dir_path(__FILE__).'/Dropbox/dropboxupload.php';?>
 </div>
 
</div>