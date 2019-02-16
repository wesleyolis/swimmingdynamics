<?php

require('../../main_include.php'); 
$display_link=0;

 $query = "select SQL_CACHE m.MName from ".$db_name."meet as m WHERE m.Meet=".inj_int($_GET['m']);
 
	  $result = db_query($query);
	  if(!mysql_error())
	  $object = mysql_fetch_object($result);
	  drupal_set_title($object->MName.' Meet Team Points Results Ranking'.' '.$_GET['ss'].'-'.($_GET['ss']+1).' - Selection');//."&nbsp;&nbsp;&nbsp;Points Rankings<br><br>");
		
		$menu_option = 'team';
		require('meets_menu.php');

	  $output.="<div>Please select a gender Catagory.</div>";	
	  $output.="<br>".l2('Mixed','m='.$_GET['m'].'&gen=X','team_points.php');
	  $output.="<br><br>".l2('Female','m='.$_GET['m'].'&gen=F','team_points.php');
	  $output.="<br><br>".l2('Male','m='.$_GET['m'].'&gen=M','team_points.php');
	  
	  render_page();

?>
