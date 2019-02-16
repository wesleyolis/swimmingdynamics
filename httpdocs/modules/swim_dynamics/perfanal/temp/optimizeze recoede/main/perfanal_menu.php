<?php

function perfanal_help($section)
{
   switch($section)
     {
      case 'admin/modules#description':
	  {
	     return t('Performance Analysis based on Team Manager 4');
	  }
	break;
     }
}

function perfanal_perm()
{
   return array('site admin', 'setting admin','tmdb admin','teams','subscripton','everyone');
}

function perfanal_menu($may_cache)
{
if (!$may_cache)
	{
		$items[] = array('path' => 'admin/settings/perfanal',
		'title' => t('Performance Analysis'),
		'description'=>'TM4 Swimming administation setup, filters, records, rankings extra',
		'callback' => 'perfanal_site_admin_filter_settings',
		'access' => user_access('site admin'),
		'type' =>MENU_NORMAL_ITEM,
		'weight' => 10);
		
		$items[] = array('path' => 'admin/settings/perfanal/',
		'title' => t('Filters'),
		'callback' => 'perfanal_site_admin_filter_settings',
		'access' => user_access('site admin'),
		'type' =>MENU_DEFAULT_LOCAL_TASK,
		'weight' => 0);
	
		$items[] = array('path' => 'admin/settings/perfanal/add',
		'title' => t('ADD'),
		'callback' => 'drupal_get_form',
		'callback arguments' => array('perfanal_site_admin_settings'),
		'access' => user_access('site admin'),
		'type' =>MENU_LOCAL_TASK,
		'weight' => 1);
		
		
		
		
	}
	
	 return $items;
}

include 'perfanal_settings.php';

?>