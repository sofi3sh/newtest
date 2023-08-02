<?php
  
	if (!defined('WP_UNINSTALL_PLUGIN')) {
		die;
	}
	 
	if( 1 == get_option("woolgap_clear_data") ){
			 
		global $wpdb;		
		//$wpdb->query("DELETE FROM {$wpdb->prefix}posts WHERE post_type='w_default_attributes';");
		 
	}
	 
