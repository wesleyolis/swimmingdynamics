<?php
$headers[] = array('data' => t('#'), 'width' => '20px');
   $headers[] = array('data' => t('Score'), 'width' => '60px');
   $headers[] = array('data' => t('Athlete Name'), 'width' => '250px');
   $headers[] = array('data' => t('M/F'), 'width' => '30px');
   $headers[] = array('data' => t('Age'), 'width' => '40px');
   $headers[] = array('data' => t('Team'), 'width' => '80px');

	/*$query = "select e.Lo_Hi,  a.Athlete, a.Last, a.First, a.Sex,r.age, (sum(r.POINTS)/10) as Sum_Points, t.TCode, t.LSC from ";
	$query.=" ((".$tm4db.".mtevent_".$season." as e left join ".$tm4db.".result_".$season." as r on (e.MtEvent=r.MTEVENT)) left join ".$tm4db.".athlete_".$season." as a on (r.ATHLETE= a.Athlete)) left join ".$tm4db.".team_".$season." as t on (r.TEAM=t.Team)";
	$query.=" ".$Where." and r.I_R='I' and a.Athlete>0 GROUP BY e.Lo_Hi, r.ATHLETE having Sum(r.POINTS)>0 order by e.Lo_Hi, Sum_Points DESC,a.last";
	*/
   $query = "select a.Athlete, a.Last, a.First, a.Sex,r.age, (sum(r.POINTS)/10) as Sum_Points, t.TCode, t.LSC from ";
   $query.=" ( ".$tm4db.".result_".$season." as r left join ".$tm4db.".athlete_".$season." as a on (r.ATHLETE= a.Athlete)) left join ".$tm4db.".team_".$season." as t on (r.TEAM=t.Team)";
   $query.=" ".$Where." and r.I_R='I' and a.Athlete>0 GROUP BY r.ATHLETE having Sum(r.POINTS)>0 order by Sum_Points DESC,a.last";

   $result = db_query($query);
   //$output.=$query;
   $output.= '<table><tr><td>';

   $pos = 0;
   $pos2 = -1;
   $point=NULL;
   //Grouping
   $Age=NULL;
   $First = false;
   while ($object = db_fetch_object($result))
     {
	if($Age != $object->Lo_Hi)
	  {
	     $Age = $object->Lo_Hi;
	     $pos = 0;
	     //Heading for Grouping
	     if($rows==NULL && $First)
	       $rows[] = array(array('data' => t('There are no results for this Age Group'), 'colspan' => 6));
	     $First=true;

	     if($rows !=NULL)
	       $output.= theme('table', $headers, $rows).'<br>';
	     $output.= "<br><p class='title' align=\'center\'><b>".Age($object->Lo_Hi).'</b></p><br>';
	     $rows = NULL;
	  }

	if($point != $object->Sum_Points)
	  {
	     $point = $object->Sum_Points;
	     $pos++;
	  }
	$link='athlete/'.arg(1).'/top_times/'.$object->Athlete;
	$rows[] = array((($pos ==$pos2)?'-':$pos),Round($object->Sum_Points,1),( ($object->Last==NULL)?'Athlete not found':l(t($object->Last.", ".$object->First), $link)), $object->Sex, $object->age, $object->TCode."-".$object->LSC);
	if($pos != pos2)
	  $pos2 = $pos;
     }
   if (!$rows)
     {
	$rows[] = array(array('data' => t('There are no results for this Age Group, click '.l(t('here'), 'meets/'.arg(1).'/points/'.arg(4)).' to go back.'), 'colspan' => 6));
     }
   $output.= theme('table', $headers, $rows);
   $output.= '</td></tr></table>';
?>