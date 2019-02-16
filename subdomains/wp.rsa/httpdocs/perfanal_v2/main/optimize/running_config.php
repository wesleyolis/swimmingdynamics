<?php
require('../../main_include.php');
	// Options unic to a seasons
$options_url = $app_dir.'/running_options/'.$domain_reg.'/'.$config ['db_ident'].'_'.$_GET['version'].'_options.php';


require($options_url);
echo print_r($running_config);
 




?>