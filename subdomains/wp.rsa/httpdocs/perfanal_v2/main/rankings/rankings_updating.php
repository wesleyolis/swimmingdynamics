<?php
	header("Cache-Control: max-age=-1, no-store"); 
	require('ranking_config.php');

	//rankings are in the process of being updated.
	drupal_set_title($_GET['ss'].'-'.($_GET['ss']+1).' '.$pref_head." Updating Rankings in processing");
	$output.= '<br/>This will only take a couple of seconds. click the link below to go back to rankings.<br>';
	
	$output.= "<script language='javascript'>\n<!--\nsetTimeout(\"document.location='".url2('','index.php')."';\",3000);\n//-->\n</script>";

	render_page();
?>