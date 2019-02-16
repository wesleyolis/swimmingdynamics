<?php
$headers[] = array('data' => t('#'), 'width' => '20px');
   $headers[] = array('data' => t('Score'), 'width' => '60px');
   $headers[] = array('data' => t('Team Name'), 'width' => '250px');
   $headers[] = array('data' => t('Code'), 'width' => '80px');
   $headers[] = array('data' => t('Athletes'), 'width' => '80px');

   $query="SELECT SQL_CACHE Round(SUM(r.POINTS)/10,1) as Sum_Points,t.TName,t.TCode, t.LSC , Count(DISTINCT If(r.I_R='I',r.Athlete,0)) AS Sum_Athletes";
   $query.=" FROM (".$tm4db."result_".$season." as r left join ".$tm4db."athlete_".$season." as a on (r.ATHLETE=a.Athlete)) left join ".$tm4db."team_".$season." as t on (r.TEAM=t.Team)";

   $query.=" Where r.points != 0 and ".$Where." Group by r.Team HAVING SUM(r.POINTS)>0 Order by Sum_Points Desc ".pages_limit(1);
//echo $query;
   $result = db_query($query);

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
	$rows[] = array((($pos ==$pos2)?'-':$pos),$object->Sum_Points,( ($object->TName==NULL)?'Team not found':$object->TName), $object->TCode."-".$object->LSC,$object->Sum_Athletes);
	if($pos != pos2)
	  $pos2 = $pos;
     }
   if (!$rows)
     {
	$rows[] = array(array('data' => t('There are no athletes results found matching your criteria, click '.l(t('here'), 'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5)).' to go back.'), 'colspan' => 5));
     }
     $output='Ages determined by result.';
   $output.=  theme('table', $headers, $rows);

?>