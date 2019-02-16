<?php

 if($_GET['sanc']!='ALL')
     {
	$results = db_query("Select SQL_CACHE * from ".$db_name."code Where abbr='".inj_str($_GET['sanc'])."' and type=3");
	if(!mysql_error())
	$object = mysql_fetch_array($results);
	$tindex[0] = $object['tindex'];
	$heading = (($object['_desc']=='')?$object['ABBR'].' ':$object['_desc']).' Meets Only, ';
     }
   else
     $heading = 'All Meets, ';

?>