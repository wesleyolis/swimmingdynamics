<?php
		  drupal_set_title($pref_head.' '.$type[arg(3)].' Rankings'." as of ".$last_ranking."<br/><small>".$heading.' '.Gender(arg(7)).' '.Age(arg(8)).' '.arg(10).'m '.Stroke(arg(9)).' '.Course(1,arg(6)).'</small>'.((arg(2)=='curr')?'':"<small><br>Athletes ages on ".arg(2).'</small>'));
		  $Where.= " and r.Stroke=".arg(9)." ";
		  $Where.= " and r.Distance=".arg(10)." ";
		  
   $headers[] = array('data' => t('#'), 'width' => '20px');
   $headers[] = array('data' => t('Time'), 'width' => '60px');
   $headers[] = array('data' => t('Athlete Name'), 'width' => '250px');
   $headers[] = array('data' => t('M/F'), 'width' => '30px');
   $headers[] = array('data' => t('Age'), 'width' => '30px');
   $headers[] = array('data' => t('Team'), 'width' => '80px');
   $headers[] = array('data' => t('Meet'));

   $query="Select SQL_CACHE r.MTEVENT,m.MName,a.Athlete, a.Last, a.First, a.Sex, ".$ath_age.", r.score, t.TCode, t.LSC, r.Age as sage";
   $query.=" FROM (((".$tm4db."result_".$season." as r left join ".$tm4db."athlete_".$season." as a on (r.ATHLETE=a.Athlete)) ".$filter_team." ) left join ".$tm4db."meet_".$season." as m on (r.Meet=m.Meet))";
   $query.=" WHERE r.nt=0 and ".$Where." order by r.Score";
 
  // $output.=$query;
   $result = db_query_pages($query,'score','ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6).'/'.arg(7).'/'.arg(8).'/'.arg(9).'/'.arg(10));

   while ($object = db_fetch_object($result['result']))
     {
	$link='athlete/'.arg(1).'/top_times/'.$object->Athlete;
	$rows[] = array($object->pos,get_time($object->score),l(t($object->Last.", ".$object->First), $link), $object->Sex, $object->age, $object->TCode."-".$object->LSC,l($object->MName,'meets/'.arg(1).'/event/results/'.$object->MTEVENT));
     }
   if (!$rows)
     $rows[] = array(array('data' => t('There are no athletes results found matching your criteria, click '.l(t('here'), 'ranking/'.arg(1).'/'.arg(2).'/'.arg(3).'/'.arg(4).'/'.arg(5).'/'.arg(6)).' to go back.'), 'colspan' => 7));
  // $output.=$result['query'];
   $output.= theme('table', $headers, $rows);

   $output.=$result['pager'];

?>