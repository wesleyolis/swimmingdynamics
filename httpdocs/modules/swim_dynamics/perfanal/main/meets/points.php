<?php
if(arg(3) !='female' & arg(3) !='male' & arg(3) !='mixed')
	       {
		  $query = "select SQL_CACHE m.MName, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke, e.Course from ".$tm4db."mtevent_".$season." e, ".$tm4db."meet_".$season." m WHERE e.Meet=%d and e.Meet=m.Meet";
		  $result = db_query($query,arg(3));
		  $object = db_fetch_object($result);
		  drupal_set_title($object->MName.' Meet Results'.' '.arg(1).'-'.(arg(1)+1).' - Indi Points');//."&nbsp;&nbsp;&nbsp;Points Rankings<br><br>");
		  setseasons_breadcrumb(array(l('Meets','meets/'.arg(1))));
		  $output.="<div class='tabs'><ul class='tabs primary'>";
		  $output.="<li>".l('Info','meets/'.arg(1).'/meets_info/'.arg(3))."</li>";
		  $output.="<li>".l('Events','meets/'.arg(1).'/events/'.arg(3))."</li>";
		  $output.="<li  class='active'>".l('Individual Points','meets/'.arg(1).'/points/'.arg(3))."</li>";
		  $output.="<li>".l('Team Points','meets/'.arg(1).'/team_points/'.arg(3))."</li>";
		  $output.="<li>".l('Fina Points','meets/'.arg(1).'/fina_points/'.arg(3))."</li>";
		  $output.="</ul></div>";

		  //$output.= "<br><p class='title' align=\'center\'>Points Rankings</p><br>";

		  $headers [] = array('data'=>t('Female'),'width'=>'100px');
		  $headers [] = array('data'=>t('Male'),'width'=>'100px');
		  $headers [] = array('data'=>t('Mixed'),'width'=>'100px');

		  $query = "SELECt Distinct Lo_Hi from ".$tm4db."mtevent_".$season." Where I_R='I' and  Meet=%d  union select '99' as Lo_Hi order by Lo_Hi desc";
		  $result = db_query($query,arg(3));
		  //$rows[] = array(l('All Groups','meets/'.arg(1).'/points/female/'.arg(3)),l('All Groups','meets/'.arg(1).'/points/male/'.arg(3)),l('All Groups','meets/'.arg(1).'/points/mixed/'.arg(3)));
		  while ($object = db_fetch_object($result))
		    {
		       $rows[] = array(l(Age($object->Lo_Hi),'meets/'.arg(1).'/points/female/'.arg(3).'/'.$object->Lo_Hi),l(Age($object->Lo_Hi),'meets/'.arg(1).'/points/male/'.arg(3).'/'.$object->Lo_Hi),l(Age($object->Lo_Hi),'meets/'.arg(1).'/points/mixed/'.arg(3).'/'.$object->Lo_Hi));
		    }
		  $output.= theme('table', $headers, $rows);
	       }
	     else
	       {
		  $query = "select m.MName, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke, e.Course from ".$tm4db."mtevent_".$season." e, ".$tm4db."meet_".$season." m WHERE e.Meet=".arg(4)." and e.Meet=m.Meet";
		  $result = db_query($query);
		  $object = db_fetch_object($result);
		  drupal_set_title($object->MName.' Meet Results'.' '.arg(1).'-'.(arg(1)+1).' - Indi Points');
		  switch(arg(3))
		    {
		     case 'female': $Gen= "Female";
		       break;
		     case 'male': $Gen="Male";
		       break;
		     case 'mixed': $Gen="Mixed";
		       break;
		    }
		  $output.= "<br><p class='title' align=\'center\'>".$Gen." Points Rankings</p><br>";

		  $Where = ' ';
		  switch(arg(3))
		    {
		     case 'female': $Where.= " and a.Sex='F'";
		       break;
		     case 'male': $Where.= " and a.Sex='M'";
		       break;
		    }
		  if(arg(5) !=NULL)
		    {
		       $Where.= " and r.Age >=".floor(arg(5)/100)." and r.Age <=".(arg(5)%100);
		    }

		  //Call render functon
		  $Where = "WHERE r.Meet=".arg(4).' '.$Where;
		  require('indi_points.php');

	       }
?>