<?php	

require('../../main_include.php');

drupal_set_title('Seasonal database information available');
$output.='<br>Select one of the available seasons below to acess older information.<br><br>';

 foreach($config['seas'] as $season=>$val)
 $output.= ll($season.' - '.($season+1),$page_url.$page_file.'?db='.$config['db_ident'].'&ss='.$season).'<br>';

 
render_page();

?>
	
	