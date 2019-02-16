<?php

	     $C_session = get_cur_seasons();
	$items[] = array('path' => 'optimize',
			 'title' => t('Optimize SWIM DB'),
			 'callback' => 'perfanal_optimize',
			 'access' => user_access('admin performance analysis'),
			 'weight' => -1);
	$items[] = array('path' => 'updaterankings',
			 'title' => t('Update Rankings'),
			 'callback' => 'perfanal_updaterankings',
			 'access' => user_access('admin performance analysis'),
			 'weight' => -1);
	
	$items[] = array('path' => 'report_standard' ,
			 'title' => t('Nat.G  Standards Statics'),
			 'callback' => 'perfanal_report_standard',
			 'access' => user_access('aditional performance analysis'),
			 'weight' => 7);
			
	$items[] = array(
			 'path' => 'admin/settings/perfanal',
			 'title' => t('Perfanal'),
			 'description' => t('Swim TM4 Database, display settings configuration.'),
			 'callback' => 'drupal_get_form',
			 'callback arguments' => array('perfanal_admin_settings'),
			 'access' => user_access('admin performance analysis'),
			 'type' => MENU_NORMAL_ITEM, // optional
			 );
			 
	
    
?>