<?php

require('../../main_include.php'); 


		  $query = "select SQL_CACHE m.MName, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke, e.Course from ".$db_name."mtevent as e inner join ".$db_name."meet as m on (e.Meet=m.Meet) WHERE e.Meet=".inj_int($_GET['m']);
		  $result = db_query($query);
		  if(!mysql_error())
		  {
			  $object = mysql_fetch_object($result);
			  drupal_set_title($object->MName.' Meet Indi Points Results Ranking'.' '.$_GET['ss'].'-'.($_GET['ss']+1).' - Selection');//."&nbsp;&nbsp;&nbsp;Points Rankings<br><br>");
		  
		  setseasons_breadcrumb(array(l2('Meets','m='.$_GET['m'],'index.php'),l2('Events','&m='.$_GET['m'].'&age=All&gen=All','events.php')));
		  
		  $menu_option = 'points';
		  $display_link=0;
		  require('meets_menu.php');

		  $output.='<div style=\'padding:0.5em 0em 0.5em 0em;\'>Please make a Gender and Age group selection.</div>';
		  
		  
		  //$output.= "<br><p class='title' align=\'center\'>Points Rankings</p><br>";

		  $headers [] = array('data'=>t('Female'),'style'=>'width:5em;');
		  $headers [] = array('data'=>t('Male'),'style'=>'width:5em');
		  $headers [] = array('data'=>t('Mixed'),'style'=>'width:5em;');

		  $query = "SELECt Distinct Lo_Hi from ".$db_name."mtevent Where I_R='I' and  Meet=".inj_int($_GET['m'])."  union select '99' as Lo_Hi order by Lo_Hi desc";
		
		  $result = db_query($query);
		  if(!mysql_error())
		  while ($object = mysql_fetch_object($result))
		    {
		       $rows[] = array(l2(Age($object->Lo_Hi),'m='.$_GET['m'].'&gen=F&age='.$object->Lo_Hi,'indi_points.php'),l2(Age($object->Lo_Hi),'m='.$_GET['m'].'&gen=M&age='.$object->Lo_Hi,'indi_points.php'),l2(Age($object->Lo_Hi),'m='.$_GET['m'].'&gen=X&age='.$object->Lo_Hi,'indi_points.php'));
		    }
		    $output.= theme_table($headers, $rows,array('id'=>'hyper','class'=>'hyper'),null);
		  }
	       render_page();
?>