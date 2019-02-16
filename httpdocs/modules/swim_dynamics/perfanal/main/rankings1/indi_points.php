<?php

 $headers[] = array('data' => t('#'), 'width' => '20px');
   $headers[] = array('data' => t('Score'), 'width' => '60px');
   $headers[] = array('data' => t('Athlete Name'), 'width' => '250px');
   $headers[] = array('data' => t('M/F'), 'width' => '30px');
   $headers[] = array('data' => t('Age'), 'width' => '40px');
   $headers[] = array('data' => t('Team'), 'width' => '80px');

   $query = "select SQL_CACHE a.Athlete, a.Last, a.First, a.Sex, a.age, round((sum(r.POINTS)/10),1) as Sum_Points, t.TCode, t.LSC from ";
   $query.=" (".$tm4db."result_".$season." as r left join ".$tm4db."athlete_".$season." as a on (r.ATHLETE= a.Athlete)) ".$filter_team."";
   $query.=" Where r.points!=0 and".$Where." and r.I_R='I' GROUP BY  r.ATHLETE having Sum(r.POINTS)>0 order by Sum_Points DESC,a.last";
 
   $result = db_query_pages($query,'','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7));
   //$result = db_query($query);
   //$output.=$query;
   $pos=$result['pos'];
   $pre_pos=0;
   while ($object = db_fetch_object($result['result']))//db_fetch_object($result))
     {
	$link='athlete/'.arg(1).'/top_times/'.$object->Athlete;
	$rows[] = array(($pre_pos==$object->Sum_Points)?'-':$pos,($pre_pos=$object->Sum_Points),( ($object->Last==NULL)?'Athlete not found':l(t($object->Last.", ".$object->First), $link)), $object->Sex, $object->age, $object->TCode."-".$object->LSC);
	$pos++;
     }
   if (!$rows)
     {
	$rows[] = array(array('data' => t('There are no athletes results found matching your criteria, click '.l(t('here'), 'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4)).' to go back.'), 'colspan' => 6));
     }
   $output.= theme('table', $headers, $rows);
   $output.=$result['pager'];
   
   ?>