<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;

 add_option('wp_all_backup_enable_log',1);
 add_option('wp_all_backup_enable_autobackup',0);
 add_option('wp_all_backup_max_backups','');
 add_option('wp_all_backup_type','complete');
 add_option('wp_all_backup_exclude_dir',"|.git|.svn");
 add_option('wp_all_backup_backups_dir','wp_all_backup');

