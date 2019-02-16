<?php

function perfanal_site_admin_filer_add()
{
$form ['filter'] = array('#type'=>'textfield','#size'=>40, '#max_length' => 40,
	'#default_value' => '', '#title' => t('DB Filter'),
	'#description' => t('Specify database name matching sceem'));
	
$form ['name'] = array('#type'=>'textfield','#size'=>40, '#max_length' => 40,
	'#default_value' => '', '#title' => t('Name'),
	'#description' => t('The friendly Display name used in menu\'s'));

$form ['enabled'] = array('#type'=>'radios',
	'#default_value' => '', '#title' => t('DB Filter'),
	'#description' => t('Specify database name matching sceem'));
}

function perfanal_site_admin_filter_settings()
{
	
	drupal_set_title("Performance Anaylsis Database Filter's Setup.");
	
	$head[]= array('data'=>'Display Name','width'=>'150px');
	$head[]=array('data'=>'Filter','width'=>'150px');
	$head[]=array('data'=>'Status','width'=>'80px');
	$head[]=array('data'=>'');
	
	$rs=db_query('Select * from {perfanal_db}');
	while($obj=db_fetch_object($rs))
	{
	$rows[]=array($obj->display_name,$obj->filter,($obj->enabled?'Enabled':'Disabled'),'Enable : Edit : Delete');	
	}
	
	$out.= theme('table',$head,$rows);
	
	/*
	$tm4db = variable_get('perfanal_database', 'perfanal');
	$form['perfanal_database'] = array('#type' => 'textfield', '#size' => 40, '#max_length' => 40,
	'#default_value' => variable_get('perfanal_database', 'perfanal'), '#title' => t('Database'),
	'#description' => t('Specify the name of the database to be used.'));*/
return $out;//system_settings_form($form);
}
?>