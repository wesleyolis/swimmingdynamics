<?php
$options_url=$app_dir.'/running_options/'.$domain_reg.'/'.$config ['db_ident'].'_'.$seas_reg.'_'.$config['version'].'_options.php';

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
	file_put_contents($options_url, '<?php $running_config = unserialize(\''.serialize($running_config).'\'); ?>');

?>