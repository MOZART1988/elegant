 <?php
  if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}?>
<div class="panel panel-success">
  <div class="panel-heading">
      <div class="panel-title"><h3><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> <?php  _e( 'Help', 'wpallbkp' );?></h3></div>

  </div>
  <div class="panel-body">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php  _e( 'Support', 'wpallbkp' );?></a></li>
    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><?php  _e( 'FAQ', 'wpallbkp' );?></a></li>
    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab"><?php  _e( 'Feature', 'wpallbkp' );?></a></li>    
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="home">
	<iframe src="http://www.wpseeds.com/support/" style="width: 90%;height: 550px;border: 4px solid #ffffff;"></iframe>
	</div>
    <div role="tabpanel" class="tab-pane" id="profile">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <span class=" glyphicon glyphicon-question-sign" aria-hidden="true"></span><?php  _e( 'Create New Backup', 'wpallbkp' );?>
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
        <?php  _e( 'WP All Backup menu will appear in Dashboard->WP ALL Backup. Click on sub menu WP ALL Backup and configure your settings and click on Create New Backup button', 'wpallbkp' );?>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
         <span class=" glyphicon glyphicon-question-sign" aria-hidden="true"></span><?php  _e( 'Store database backup on safe place- dropbox,FTP etc.', 'wpallbkp' );?>
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
          <p> <?php  _e( '(WP ALL Bacup=>Destinations)', 'wpallbkp' );?></p>
          <p><?php  _e( 'This page show all the destinations available for the plugin.
              A destination is a remote system like Dropbox or FTP. 
              Simply enter the destination information. 
               When the WP ALL Backup runs the archive file will be sent to the destination and also stored locally.
               Leave these blank for local backups.', 'wpallbkp' );?></p>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          <span class=" glyphicon glyphicon-question-sign" aria-hidden="true"></span><?php  _e( 'Email Notification', 'wpallbkp' );?>
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
           <p> <?php  _e( '(WP ALL Bacup=>Destinations=>Email Notification)', 'wpallbkp' );?></p>
        <p><?php  _e( 'Send Email Notification after backup', 'wpallbkp' );?><br/>
        <?php  _e( 'Enter email id for notification.  Leave blank if you don\'t want use this feature. 
            If you want attach log file on notification email then select yes option (Attach log file )', 'wpallbkp' );?></p>         
      </div>
    </div>
  </div>
            
            <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseTwo">
         <span class=" glyphicon glyphicon-question-sign" aria-hidden="true"></span><?php  _e( 'Configure the Settings', 'wpallbkp' );?>
        </a>
      </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
      <div class="panel-body">
          <p> <?php  _e( '(WP ALL Bacup=>Setting)', 'wpallbkp' );?></p>
          <h4> <?php  _e( 'Exclude Folders and files', 'wpallbkp' );?></h4>
          <p><?php  _e( 'Go to WP ALL Bacup > Setting > Exclude setting in the admin dashboard.
              If you would like to exclude certain files or folders from being backed up,
              such as a theme you don’t use or a file you added for testing purposes, 
              but don’t need anymore, you can enter them into the Exclude Folders and files field.
              The files or folders you enter here will be skipped over for each backup you make.', 'wpallbkp' );?></p>
            <p> <?php  _e( 'Enter new exclude rules as a pipe (|) separated list', 'wpallbkp' );?></p>
            <p> <?php  _e( 'E.g. wp-content/backupwordpress-728d36f682-backups|\wp-snapshots|\.svn|\.git', 'wpallbkp' );?></p>
          
            <h4> <?php  _e( 'Number of backups ', 'wpallbkp' );?></h4>
          <p><?php  _e( 'Go to WP ALL Bacup > Setting > Number of backups in the admin dashboard.
              Enter Number of backups to store on this server.', 'wpallbkp' );?></p>
           <p> <?php  _e( 'Past this limit older backups will be deleted automatically.', 'wpallbkp' );?></p>
            <p> <?php  _e( 'The disk that your backup is saved on doesn’t have enough free space? 
                Backup disk is almost full?  Low disk space for backup? Backup failed due to lack of space? 
                As you may set up a schedule to automatically do backup daily or weekly, and the size of disk space is limited, 
                so your backup disk will run out of space quickly or someday. It is a real pain to manually delete old backups.
                Don’t worry about it. 
                WP ALL Bakup makes it easy to delete old backup files automatically.', 'wpallbkp' );?></p>
            
            <h4> <?php  _e( 'Backup Type ', 'wpallbkp' );?></h4>
          <p><?php  _e( 'Go to WP ALL Bacup > Setting > Backup Type  in the admin dashboard.
              Select Backup Type.', 'wpallbkp' );?></p>
           <p> <?php  _e( 'You can select option like Complet, Only Database Backup, Only File Backup option', 'wpallbkp' );?></p>
           
           <h4> <?php  _e( 'Schedule Settings  ', 'wpallbkp' );?></h4>
          <p><?php  _e( 'Go to WP ALL Bacup > Setting > Schedule Settings  in the admin dashboard.
              Select Auto Backup Frequency.', 'wpallbkp' );?></p>
           <p> <?php  _e( 'You’ll also be able to schedule regular backups so they run automatically without any extra configuration on your part.', 'wpallbkp' );?></p>
           
            <h4> <?php  _e( 'Log Setting', 'wpallbkp' );?></h4>
          <p><?php  _e( 'Go to WP ALL Bacup > Setting > Log Setting   in the admin dashboard.
               Enable/Disable Backup Log .', 'wpallbkp' );?></p>
           <p> <?php  _e( 'When you  Enable Backup Log then it create log file which iclude all backup log information so that you can also get information about backup file containt. You can send log file on your email', 'wpallbkp' );?></p>
           
      </div>
    </div>
  </div>
            
            
            
</div>
	</div>
    <div role="tabpanel" class="tab-pane" id="messages">
        <hr>
        <a href="http://www.wpseeds.com/wp-all-backup/" target="_blank" class="btn btn-success btn" role="button"><?php  _e( 'WP ALL Backup Documentation', 'wpallbkp' );?></a>
        <div class="row">
             <div class="col-xs-6 col-md-6">
        <div class="list-group">
            <hr>
  <a href="#" class="list-group-item active">
    <?php  _e( 'WP ALL Backup Plus Features', 'wpallbkp' );?>
  </a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Create Database Backup easily on single click.', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Autobackup Backup automatically on a repeating schedule Integration', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Manual backup', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Multisite compatible', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Backup entire site', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Exclude specific folders and files', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Downloadable log files', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Simple one-click restore', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Set number of backups to store', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Automatically remove oldest backup', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'FTP integration', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Dropbox integration', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Email Notification', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Select Backup Type: Only Database,Only Files, Complete Backup', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Support', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Updates', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Clone', 'wpallbkp' );?></a>
  <a href="#" class="list-group-item"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> <?php  _e( 'Move Site', 'wpallbkp' );?></a>
  <a href="http://www.wpseeds.com/product/wp-all-backup/" target="_blank" class="list-group-item"><span class="label label-success"><span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span> <?php  _e( 'Buy', 'wpallbkp' );?></span></a>
</div>
  </div>
    </div>
   
  </div>
  
  </div>
   <div class="panel-footer"><h4>Get Flat 25% off on <a target="_blank" href="http://www.wpseeds.com/product/wp-all-backup/">WP All Backup Plus Plugin.</a> Use Coupon code 'WPSEEDS25'</h4></div></div>
</div>