<?php
$option = 'std';
require('../../../main_include.php');
require('../heading.php');
	       
	drupal_set_title('Apply Q/T Standard Menu - '.$heading);
	

	$headers[] = array('data' => t('Name'), 'width' => '100px');
	$headers[] = array('data' => t('Description'), 'width' => '150px');
	$headers[] = array('data' => t('Year'), 'width' => '40px');
	
	$results = db_query("Select SQL_CACHE Lcase(StdFile) as StdFile,Descript,Year From ".$db_name."stdname Order BY Year desc,StdFile");
	if(!mysql_error())
	while($object = mysql_fetch_object($results))
	$rows[] = array(l2($object->StdFile,'ath='.$_GET['ath'].'&std='.strtolower($object->StdFile),'type.php'),$object->Descript,$object->Year);
	
	$output.='Please select the standard times that you would like to apply.<br/>';
	$output.=theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);

	render_page();
	
?>