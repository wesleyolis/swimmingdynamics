<?php
	header("Cache-Control: max-age=-1, no-store"); 
	require('../../main_include.php');
	
	if($_GET['activate_version']!=null)
	{
		$options_url=$app_dir.'/running_options/'.$domain_reg.'/'.$config ['db_ident'].'_version_options.php';
		if(file_exists ($options_url))
		{
			$file_time =  filemtime($options_url);
		}
		else
		{
			if(!chdir($app_dir.'/running_options/'.$domain_reg))
			{
				$path = dirname('/'.$domain_reg); 
				if(mkdir( $path, 0777))
				echo 'Created dir';
				else
				echo'Dir Creation Failed';
			}				
		}
		file_put_contents($options_url, '<?php $config[\'version\']=\''.$_GET['activate_version'].'\'; ?>');
		
		//read active version in case it failed to write out
		if(file_exists ($options_url))
		require($options_url);
		else
		$output.= 'Failed to activae season:'.$_GET['activate_version'];
	}
	
	
	drupal_set_title('Version Selection Menu');
	
	/*
	$dblink = mysql_pconnect($config['db_url'], $config['db_user'], $config['db_pass']);
		if (!$dblink)
		{
			die('Connected Failed<br/>');
			break;
		}
		else
		{
			$db_selected = mysql_select_db($config['db_name'], $dblink);
				if (!$db_selected) {
				    die ('Can\'t use '.$db.': ' . mysql_error());
				}
				$result = mysql_query("SHOW TABLES");
				
		}
		*/
	//	echo "show tables from ".$config['db_name']." like '".$config['db_prefix'].'_'.$seas.'_'."%'";
	$results = db_query("show tables from ".$config['db_name']." like '".$config['db_prefix']."\___\_athlete%'");
	$pos = strlen($config['db_prefix'])+1;
	if(!mysql_error())
		while($object = mysql_fetch_array($results))
		{
			$version = substr($object[0],$pos,2);
			// check if optermizations has been run in the season firsts
			$options_url = $app_dir.'/running_options/'.$domain_reg.'/'.$config ['db_ident'].'_'.$version.'_options.php';
			if(file_exists ($options_url))
			$link_active = ($version == $config['version'])?'Currently Activated':l2('Activate','activate_version='.$version,'version.php');
			else
			$link_active = 'Run Optimization first!';
			
			$rows[] = Array($version,l2('Optermize Database','version='.$version,'optermize.php'),$link_active);
			
		}
			
	$output.= theme_table(null, $rows,null,null);
			
	
	render_page();
?>
