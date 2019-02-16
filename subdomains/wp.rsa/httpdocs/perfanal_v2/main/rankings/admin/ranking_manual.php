<?php
header("Cache-Control: max-age=-1, no-store"); 
require('../../../main_include.php');



$version = inj_int($_GET['version']);
$config['version'] = $version;
$options_url = $app_dir.'/running_options/'.$domain_reg.'/'.$config ['db_ident'].'_'.$seas_reg.'_'.$version.'_options.php';
if(file_exists ($options_url))
{

		
	require($options_url);

}

$ranking_manual_update = 'manual';


$db_name = $config['db_name'].'.'.$config['db_prefix'].'_'.$version.'_';

$get_seas = $_GET['ss'];

echo 'Season:'.$get_seas;

require('automatic_update.php');
render_page();


?>