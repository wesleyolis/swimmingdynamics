<?php

	set_time_limit(60*5);		//5 minutes script time out
	Ignore_User_Abort(True);	//continue even if user aborts
	
	header("Cache-Control: max-age=-1, no-store"); 
	require('../../../main_config.php');
	require('../../../main_functions.php');
	require('../../../main_durpal_functions.php');
	require('../../../render_page.php');
	
	
	
	
	foreach($sites as $domain_reg=>$data)
	{
		echo 'Site: '.$domain_reg.'<br/>';
		 foreach($data as $url_reg=>$conf)
		 {
			$config = $conf;
			$config ['db_ident'] = $url_reg;
			$seas=null;
			
			
				
			echo '<h3>>>>>>>>>>>> '.$url_reg.'<<<<<<<<<<<<<<<<<</h3><br/>';
		 	foreach($conf['seas'] as $seas_reg=>$s)
			{
				$config = array_merge($config,$s,$conf['settings'],$s['settings']);
				$config['seas_curr'] = $seas_reg;
				$running_config = $s['running_config'];
				echo '<br><br> <h5>------------------------- <a href=\'../../optimize/version.php?db='.$url_reg.'&ss='.$seas_reg.'\'>'.$seas_reg.'</a>----------------------------</h5><br/>';
			
					
				$options_url = $app_dir.'/running_options/'.$domain_reg.'/'.$config ['db_ident'].'_'.$seas_reg.'_version_options.php';
				//read the active seasons
				if(file_exists ($options_url))
				require($options_url);
				
				// Options unic to a seasons
				$options_url = $app_dir.'/running_options/'.$domain_reg.'/'.$config ['db_ident'].'_'.$seas_reg.'_'.$config['version'].'_options.php';		
				// check if optermizations has been run in the season firsts
				if(file_exists ($options_url))
				{
					$running_config = null;
					require($options_url);
					
					//update rankings  
					$version = $config['version'];
					echo 'version:'.$config['version'].'<br><br>';
					$options_url = $app_dir.'/running_options/'.$domain_reg.'/'.$config ['db_ident'].'_'.$config['version'].'_options.php';
					if(file_exists ($options_url))
					require($options_url);
					 
					$ranking_manual_update = 'auto';
					
					$db_name = $config['db_name'].'.'.$config['db_prefix'].'_'.$config['version'].'_';
					$get_seas = $seas_reg;
					require('automatic_update.php');
					
					echo $output;
					$output='';
					
				}
				else
				{
				echo '<br>Run Optimization first!';
	
				}
				echo $output;
				$output='';
				
				
			}
			
					 
		 
		 }
	}
		
			
	
	
?>