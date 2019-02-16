<?php

	header("Cache-Control: max-age=-1, no-store"); 
	require('../../../main_include.php');
	
	 $running_config['ranking_last_update']=null;
	  $running_config['ranking_updating']=true;
	   require('../../../running_options.php');
	   
	   $output.=l2('Back to Version Admin Menu','','../../optimize/version.php');
	
	render_page();



?>