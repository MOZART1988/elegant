<?php 
include plugin_dir_path(__FILE__).'DropboxClient.php';
$dropbox = new DropboxClient(array(
'app_key' => "4zcm4evu9lqwp6r",
'app_secret' => "e62k5l63v7b7e3b",
'app_full_access' => false,
),'en');

handle_dropbox_auth($dropbox); // see below
// if there is no upload, show the form


// store_token, load_token, delete_token are SAMPLE functions! please replace with your own!



function store_token($token, $name)
{

file_put_contents(plugin_dir_path(__FILE__)."tokens/$name.token", serialize($token));
}


function load_token($name)
{
if(!file_exists(plugin_dir_path(__FILE__)."tokens/$name.token")) return null;
return @unserialize(@file_get_contents(plugin_dir_path(__FILE__)."tokens/$name.token"));
}

function delete_token($name)
{
@unlink(plugin_dir_path(__FILE__)."tokens/$name.token");
}




  
 function handle_dropbox_auth($dropbox)
{
// first try to load existing access token
$access_token = load_token("access");

if(!empty($access_token)) {

$dropbox->SetAccessToken($access_token);
}
elseif(!empty($_GET['auth_callback'])) // are we coming from dropbox's oauth page?
{

// then load our previosly created request token
$request_token = load_token($_GET['oauth_token']);


if(empty($request_token)) die('Request token not found!');
// get & store access token, the request token is not needed anymore
$access_token = $dropbox->GetAccessToken($request_token);
store_token($access_token, "access");
delete_token($_GET['oauth_token']);
}
// checks if access token is required


if($dropbox->IsAuthorized())
{
$dropb_autho="yes";
update_option('dropb_autho', $dropb_autho );
echo '<h3>'; _e( 'Dropbox Account Details', 'wpallbkp' );echo '</h3>';
$account_info = $dropbox->GetAccountInfo();
 $used = round(($account_info->quota_info->quota - ($account_info->quota_info->normal + $account_info->quota_info->shared)) / 1073741824, 1);
        $quota = round($account_info->quota_info->quota / 1073741824, 1);
        echo $account_info->display_name . ', ' .'you have'. ' ' .$used .'GB' .'of'. ' ' . $quota . 'GB (' . round(($used / $quota) * 100, 0) .'%) ' .'free';
    
     echo '</br><p>';
	  _e( 'Unlink Account for local backups', 'wpallbkp' );
	 echo '</p></br>';
     echo '<td><a href="'.get_bloginfo('url').'/wp-admin/admin.php?page=wpallbackup-destination&action=unlink" class="button-primary">'; _e( 'Unlink Account', 'wpallbkp' );  echo'<a/>';
      
             
   
      
}

else
{
$dropb_autho="no";
update_option('dropb_autho', $dropb_autho );
// redirect user to dropbox oauth page
$return_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."?page=wpallbackup-destination&auth_callback=1";
$auth_url = $dropbox->BuildAuthorizeUrl($return_url);
$request_token = $dropbox->GetRequestToken();
store_token($request_token, $request_token['t']);

    ?>
    <style>
    #adminmenuwrap {
        padding-bottom: 838px;
    }
</style>
    <h3><?php _e( 'Dropbox', 'wpallbkp' ); ?></h3>
    <p><?php _e( 'Define an Dropbox destination connection.', 'wpallbkp' ); ?></p>
    <p><?php _e( 'In order to use Dropbox destination you will need to authorized it with your Dropbox account', 'wpallbkp' ); ?></p>
    <p><?php _e( 'Please click the authorize button below and follow the instructions inside the pop up window', 'wpallbkp' ); ?></p>
    <p><?php _e( 'For local backup leave the setting as it is', 'wpallbkp' ); ?></p>
          <p>
    <form action="" method="get">
        <a href="<?php echo $auth_url?>"><input type="button" name="authorize" id="authorize" value="<?php _e( 'Authorize', 'wpallbkp' ); ?>"
               class="button-primary" /></a><br/>
         </form>
    </p>
    <?php
    
   // die();
//die("Authentication required".$auth_url);
}
}
 
?>