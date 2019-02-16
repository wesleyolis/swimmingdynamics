<?php
 if(arg(3) !='female' & arg(3) !='male' & arg(3) !='mixed')
	       {
		  $query = "select SQL_CACHE m.MName, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke, e.Course from ".$tm4db."mtevent_".$season." e, ".$tm4db."meet_".$season." m WHERE e.Meet=%d and e.Meet=m.Meet";
		  $result = db_query($query,arg(3));
		  $object = db_fetch_object($result);
		  drupal_set_title($object->MName.' Meet Results'.' '.arg(1).'-'.(arg(1)+1).' - Team Points');//."&nbsp;&nbsp;&nbsp;Points Rankings<br><br>");

		  $output.="<div class='tabs'><ul class='tabs primary'>";
		  $output.="<li>".l('Info','meets/'.arg(1).'/meets_info/'.arg(3))."</li>";
		  $output.=" <li>".l('Events','meets/'.arg(1).'/events/'.arg(3))."</li>";
		  $output.="<li>".l('Individual Points','meets/'.arg(1).'/points/'.arg(3))."</li>";
		  $output.="<li  class='active'>".l('Team Points','meets/'.arg(1).'/team_points/'.arg(3))."</li>";
		  $output.="<li>".l('Fina Points','meets/'.arg(1).'/fina_points/'.arg(3))."</li>";
		  $output.="</ul></div>";

		  $output.="<br>".l('Mixed','meets/'.arg(1).'/team_points/mixed/'.arg(3));
		  $output.="<br><br>".l('Female','meets/'.arg(1).'/team_points/female/'.arg(3));
		  $output.="<br><br>".l('Male','meets/'.arg(1).'/team_points/male/'.arg(3));
	       }
	     else
	       {
		  $query = "select SQL_CACHE m.MName, e.MtEv, e.MtEvX, e.Lo_Hi, e.Sex as ESex, e.Distance, e.Stroke, e.Course from ".$tm4db."mtevent_".$season." e, ".$tm4db."meet_".$season." m WHERE e.Meet=%d and e.Meet=m.Meet";
		  $result = db_query($query,arg(4));
		  $object = db_fetch_object($result);
		  drupal_set_title($object->MName.' Meet Results'.' '.arg(1).'-'.(arg(1)+1).' - Team Points');
		  setseasons_breadcrumb(array(l('Meets','meets/'.arg(1))));
		  switch(arg(3))
		    {
		     case 'female': $Gen= "Female";
		       break;
		     case 'male': $Gen="Male";
		       break;
		     case 'mixed': $Gen="Mixed";
		       break;
		    }
		  $output.= "<br><p class='title' align=\'center\'>".$Gen." Team Rankings</p><br>";

		  $Where='';
		  switch(arg(3))
		    {
		     case 'female': $Where.= " and a.Sex='F'";
		       break;
		     case 'male': $Where.= " and a.Sex='M'";
		       break;
		    }

				/*Display team rankings*/

		  $headers[] = array('data' => t('#'), 'width' => '20px');
		  $headers[] = array('data' => t('Score'), 'width' => '60px');
		  $headers[] = array('data' => t('Team Name'), 'width' => '250px');
		  $headers[] = array('data' => t('Code'), 'width' => '80px');
		  $headers[] = array('data' => t('Athletes'), 'width' => '80px');

		  $query="SELECT SUM(r.POINTS)/10 as Sum_Points,t.TName,t.TCode, t.LSC , Count(DISTINCT If(r.I_R='I',r.Athlete,0)) AS Sum_Athletes";
		  $query.=" FROM (".$tm4db."result_".$season." as r  left join ".$tm4db."athlete_".$season." as a on (r.ATHLETE=a.Athlete)) left join ".$tm4db."team_".$season." as t on (r.TEAM=t.Team)";
		  $query.=" Where r.Meet=%d".$Where." Group by r.Team HAVING SUM(r.POINTS)>0 Order by Sum_Points Desc";

		  $result = db_query($query,arg(4));

		  $pos = 0;
		  $pos2 = -1;
		  $point=NULL;
		  while ($object = db_fetch_object($result))
		    {
		       if($point != $object->Sum_Points)
			 {
			    $point = $object->Sum_Points;
			    $pos++;
			 }
		       $rows[] = array((($pos ==$pos2)?'-':$pos),Round($object->Sum_Points,1),( ($object->TName==NULL)?'Team not found':$object->TName), $object->TCode."-".$object->LSC,$object->Sum_Athletes);
		       if($pos != pos2)
			 $pos2 = $pos;
		    }
		  if (!$rows)
		    {
		       $rows[] = array(array('data' => t('There are no results for this Gender, click '.l(t('here'), 'meets/'.arg(1).'/team_points/'.arg(4)).' to go back.'), 'colspan' => 5));
		    }
		  $output.= theme('table', $headers, $rows);
	       }
?>